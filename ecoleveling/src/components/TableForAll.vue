<template>
    <div class="border border-gray-200 rounded-xl p-8 bg-white">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Activity Log</h2>
            <router-link
                :to="{ path: '/activity-log' }"
                class="text-[#117D2C] hover:underline text-sm"
            >
                See all
            </router-link>
        </div>
        <div v-if="rows.length > 0" class="">
            <TableSlice
                :columns="columns"
                :rows="rows"
                :tablePagination="tablePagination"
            />
        </div>
        <div v-else>
            <p class="text-gray-500">No data available.</p>
        </div>
    </div>
</template>

<script>
// import DynamicTable from "@/components/uiComponents/DynamicTable.vue";
import TableSlice from "./uiComponents/TableSlice.vue";
import axios from "axios";
import { onMounted, ref, watch, watchEffect } from "vue";

export default {
    name: "MyComponent",
    components: {
        // DynamicTable,
        TableSlice,
    },
    props: {
        apiUrl: {
            type: String,
            required: true,
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
    },
    data() {
        return {
            columns: ["Room", "Date", "Start Time", "End Time", "kWh"],
            rows: [],
            dataAvailable: true,
            tablePagination: false,
        };
    },
    setup(props) {
        const rows = ref([]);

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
                let apiUrlWithParams = props.apiUrl;
                if (props.roomId.trim() !== "") {
                    apiUrlWithParams += `?room_id=${props.roomId}`;
                }
                if (props.startDate && props.endDate) {
                    apiUrlWithParams += `${
                        props.roomId.trim() !== "" ? "&" : "?"
                    }start_date=${props.startDate}&end_date=${props.endDate}`;
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

        // Example usage:
        const totalMinutes = 62;
        const result = convertToHours(totalMinutes);
        console.log(result); // Output: "1 hour 2 minutes"

        const formatNumber = (num) => parseFloat(num).toFixed(10);
        return { columns, rows }; // Return reactive data
    },
};
</script>
