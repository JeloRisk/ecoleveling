<template>
    <div>
        <div class="border border-gray-200 rounded-xl p-8 bg-white">
            <h2 class="text-xl font-semibold">Activity Log</h2>

            <DatePickerVue
                v-if="isDatepicker"
                @dateselected="handleDateSelected"
            />
            <div v-if="rows.length > 0">
                <DynamicTable
                    :columns="columns"
                    :rows="rows"
                    :tablePagination="tablePagination"
                />
            </div>
            <div v-else>
                <p>No data available.</p>
            </div>
        </div>

        <!-- </div> -->
    </div>
</template>

<script>
import DynamicTable from "@/components/uiComponents/DynamicTable.vue";
import axios from "axios";
import { onMounted, ref, watch, watchEffect } from "vue";
import DatePickerVue from "./DatePicker.vue";
export default {
    name: "LogTable",
    components: {
        DynamicTable,
        DatePickerVue,
    },
    props: {
        apiUrl: {
            type: String,
            required: false,
        },
        roomId: {
            type: String,
            default: "", // empty string by default means retrieve data for all rooms
        },
        startDate: {
            type: String,
            default: "",
        },
        endDate: {
            type: String,
            default: "",
        },
        kWh: {
            type: String,
            default: "Hello",
        },
        datepicker: {
            type: Boolean,
            default: false,
        },
    },
    data(props) {
        return {
            columns: ["Room", "Date", "Start Time", "End Time", "kWh"],
            rows: [],
            isDatepicker: props.datepicker,
            dataAvailable: true,
            tablePagination: true,
            sDate: "",
            eDate: "",
        };
    },
    methods: {
        handleDateSelected(startDate, endDate) {
            console.log(startDate);
            this.sDate = startDate;
            this.eDate = endDate;

            this.dateselected2();
        },
        dateselected2() {
            this.$emit("dateselected2", this.sDate, this.eDate);
        },
    },
    setup(props) {
        const eDate = ref([]);
        const sDate = ref([]);
        const rows = ref([]);
        console.log(rows);

        const columns = [
            "Room",
            "Date",
            "Start Time",
            "End Time",
            "Total Time Usage",
            "kWh",
        ];

        onMounted(() => {
            fetchData();
        });

        watch(
            () => [props.startDate, props.endDate],
            () => {
                fetchData();
            }
        );
        const fetchData = async () => {
            try {
                let apiUrlWithParams =
                    "http://localhost:8000/api/room-occupancy-log-books";
                if (props.startDate && props.endDate) {
                    apiUrlWithParams += `?start_date=${props.startDate}&end_date=${props.endDate}`;
                }

                console.log(apiUrlWithParams);

                const response = await axios.get(apiUrlWithParams);
                const { labels, data } = response.data;

                if (rows.value.length != 0) {
                    rows.value = [];
                    console.log("Gello");
                }

                rows.value = response.data.map((row) => ({
                    roomNumber: row.roomNumber,
                    date: row.date,
                    startTime: row.startTime,
                    endTime: row.endTime,
                    usageMinutes: convertToHours(row.usageMinutes),
                    kWh: formatNumber(row.kWh),
                }));
                console.log(rows);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };
        const convertToHours = (totalMinutes) => {
            const hours = Math.floor(totalMinutes / 60);
            const remainingMinutes = totalMinutes % 60;
            const minutes = Math.floor(remainingMinutes);
            const seconds = Math.floor((remainingMinutes - minutes) * 60);

            const hourString =
                hours > 0 ? `${hours} ${hours === 1 ? "hour" : "hours"}` : "";
            const minuteString =
                minutes > 0
                    ? `${minutes} ${minutes === 1 ? "minute" : "minutes"}`
                    : "";
            const secondString =
                seconds > 0
                    ? `${seconds} ${seconds === 1 ? "second" : "seconds"}`
                    : "";

            const parts = [hourString, minuteString, secondString].filter(
                (part) => part !== ""
            );

            return parts.join(" ");
        };

        const totalMinutes = 62;
        const result = convertToHours(totalMinutes);
        console.log(result);
        const formatNumber = (num) => parseFloat(num).toFixed(10);

        return { columns, rows, sDate, eDate };
    },
};
</script>
