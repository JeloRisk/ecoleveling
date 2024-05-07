<template>
    <div>
        <div class="table-header">
            <h2>Activity Log</h2>
        </div>
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
</template>

<script>
import DynamicTable from "@/components/uiComponents/DynamicTable.vue";
import axios from "axios";
import { onMounted, ref, watch, watchEffect } from "vue";

export default {
    name: "ActivityLog",
    components: {
        DynamicTable,
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
            tablePagination: true,
        };
    },
    setup() {
        const rows = ref([]);
        console.log(rows);

        const columns = ["Room", "Date", "Start Time", "End Time", "kWh"];
        // onMounted(fetchData);
        onMounted(() => {
            fetchData();
        });

        const fetchData = async () => {
            try {
                let apiUrlWithParams =
                    "http://localhost:8000/api/room-occupancy-log-books";

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
                    usageMinutes: formatNumber(row.kWh),
                }));
                console.log(rows);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        const formatNumber = (num) => parseFloat(num).toFixed(10);
        return { columns, rows }; // Return reactive data
    },
};
</script>
