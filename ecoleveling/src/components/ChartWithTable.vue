<template>
    <div class="p-4 border border-gray-300 rounded">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-4"
        >
            <h2 class="text-2xl font-bold">Room Occupancy Chart</h2>
            <div class="flex items-center">
                <select
                    v-model="selectedPeriod"
                    class="border border-gray-300 px-2 py-1 mr-2"
                >
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="monthly">Monthly</option>
                </select>
                <input
                    v-if="selectedPeriod === 'last_7_days'"
                    type="date"
                    id="start-date"
                    v-model="startDate"
                    :max="maxDate"
                    class="border border-gray-300 px-2 py-1 mr-2"
                />
                <input
                    v-if="selectedPeriod === 'last_7_days'"
                    type="date"
                    id="end-date"
                    v-model="endDate"
                    :max="maxDate"
                    class="border border-gray-300 px-2 py-1"
                />
            </div>
        </div>
        <hr class="border-t border-gray-300 my-4" />
        <Chart
            :apiUrl="apiUrl"
            :roomId="roomIdFromDetail"
            :startDate="startDate"
            :endDate="endDate"
            :selectedPeriod="periodGetter"
            @data-fetch-request="fetchData"
        />
    </div>
</template>

<script>
import Chart from "@/components/ChartsComponent.vue";

export default {
    name: "ChartWithTable",
    components: {
        Chart,
    },

    props: {
        roomIdFromDetail: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            apiUrl: "http://localhost:8000/api/get-chart-data",
            roomId: "",
            startDate: "",
            endDate: "",
            maxDate: "",
            selectedPeriod: "last_7_days", // Default selection
        };
    },
    computed: {
        periodGetter() {
            // console.log(this.selectedPeriod);
            // this.apiUrl = ""
            return this.selectedPeriod;
        },
    },
    methods: {
        async fetchData() {
            try {
                let apiUrlWithParams = this.apiUrl;
                if (this.roomId) {
                    apiUrlWithParams += `?room_id=${this.roomId}`;
                    if (this.startDate && this.endDate) {
                        apiUrlWithParams += `&start_date=${this.startDate}&end_date=${this.endDate}`;
                    }
                } else if (this.startDate && this.endDate) {
                    apiUrlWithParams += `?start_date=${this.startDate}&end_date=${this.endDate}`;
                }
                // console.log(apiUrlWithParams)

                const response = await fetch(apiUrlWithParams);
                const { labels } = await response.json();
                if (labels.length > 0) {
                    this.maxDate = labels[labels.length - 1];
                    this.startDate = labels[labels.length - 7]; // Automatically set start date to 7 days ago
                    this.endDate = labels[labels.length - 1];
                }
                console.log(`${this.startDate}-${this.endDate}`);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
    },
    mounted() {
        this.fetchData();
    },
};
</script>

<style>
/* Add any custom styles for your home component */
</style>
