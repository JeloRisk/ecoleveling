<template>
    <div class="p-4 border border-gray-300 rounded">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-4"
        >
            <h2 class="text-2xl font-bold">Room Occupancy Chart</h2>
            <div class="flex">
                <input
                    type="date"
                    id="start-date"
                    v-model="startDate"
                    :min="minDate"
                    :max="maxDate"
                    @input="updateEndDate"
                    class="border border-gray-300 px-2 py-1 mr-2"
                />
                <div class="mx-2">-</div>
                <input
                    type="date"
                    id="end-date"
                    v-model="endDate"
                    :min="startDate"
                    :max="maxDate"
                    class="border border-gray-300 px-2 py-1 ml-2"
                />
            </div>
        </div>
        <Chart
            :apiUrl="apiUrl"
            :roomId="roomIdFromDetail"
            :startDate="startDate"
            :endDate="endDate"
            :selectedPeriod="selectedPeriod"
            @data-fetch-request="fetchData"
        />
    </div>
</template>

<script>
import Chart from "@/components/ChartsComponent.vue";

export default {
    name: "ChartForAll",
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
            minDate: "",
            maxDate: "",
            selectedPeriod: "yearly",
            dataAvailable: true,
        };
    },
    methods: {
        handleDataAvailable(value) {
            this.dataAvailable = value;
        },
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
                const response = await fetch(apiUrlWithParams);
                const { labels } = await response.json();
                if (labels.length > 0) {
                    this.minDate = labels[0];
                    this.maxDate = labels[labels.length - 1];
                    this.startDate = labels[0];
                    this.endDate = labels[labels.length - 1];
                }
                console.log(`${this.startDate}-${this.endDate}`);

                this.$emit("start-date", this.startDate);

                this.$emit("end-date", this.endDate);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        updateEndDate() {
            if (this.endDate < this.startDate) {
                this.endDate = this.startDate;
            }
        },
    },
    mounted() {
        this.fetchData();
    },
};
</script>

<style></style>
