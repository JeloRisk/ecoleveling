<template>
    <div class="border border-gray-200 rounded-xl p-8 bg-white h-full">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-4"
        >
            <h2 class="text-2xl font-bold">Overview of Room 2's kWh Usage</h2>
            <div class="flex items-center">
                <div class="flex space-x-2">
                    <!-- <button
                        :class="{
                            'bg-green-500 text-white':
                                selectedPeriod === 'yearly',
                        }"
                        @click="selectedPeriod = 'yearly'"
                        class="px-4 py-2 rounded-md border border-gray-300 transition-colors duration-300 hover:bg-green-500 hover:text-white"
                    >
                        Year
                    </button> -->
                    <button
                        :class="{
                            'bg-green-500 text-white':
                                selectedPeriod === 'yearly',
                        }"
                        @click="selectedPeriod = 'yearly'"
                        class="px-4 py-2 rounded-md border border-gray-300 transition-colors duration-300 hover:bg-green-500 hover:text-white"
                    >
                        Month
                    </button>
                    <button
                        :class="{
                            'bg-green-500 text-white':
                                selectedPeriod === 'monthly',
                        }"
                        @click="selectedPeriod = 'monthly'"
                        class="px-4 py-2 rounded-md border border-gray-300 transition-colors duration-300 hover:bg-green-500 hover:text-white"
                    >
                        Day
                    </button>
                </div>

                <div v-if="selectedPeriod === 'monthly'" class="flex">
                    <input
                        type="date"
                        id="start-date"
                        v-model="startDate"
                        :max="maxDate"
                        class="border border-gray-300 px-2 py-1 mr-2 rounded-md"
                    />
                    <input
                        type="date"
                        id="end-date"
                        v-model="endDate"
                        :max="maxDate"
                        :min="startDate"
                        class="border border-gray-300 px-2 py-1 rounded-md"
                    />
                </div>
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
            selectedPeriod: "monthly", // Default selection
        };
    },
    computed: {
        periodGetter() {
            // console.log(this.selectedPeriod);
            // this.apiUrl = ""
            return this.selectedPeriod;
        },
        minStartDate() {
            return this.endDate ? this.endDate : null;
        },
    },
    methods: {
        async fetchData() {
            try {
                let apiUrlWithParams = this.apiUrl;
                if (this.roomId) {
                    apiUrlWithParams += `?room_id=${this.roomId}`;
                }

                if (this.selectedPeriod === "monthly") {
                    const today = new Date();
                    const firstDayOfMonth = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        1
                    );
                    this.startDate = firstDayOfMonth
                        .toISOString()
                        .substr(0, 10);
                    this.endDate = today.toISOString().substr(0, 10);
                }

                // if (this.startDate && this.endDate) {
                //     apiUrlWithParams += `&start_date=${this.startDate}&end_date=${this.endDate}`;
                // }
                // console.log(apiUrlWithParams)

                const response = await fetch(apiUrlWithParams);
                const { labels } = await response.json();
                if (labels.length > 0) {
                    this.maxDate = labels[labels.length - 1];
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        startDate: function (newStartDate) {
            if (
                newStartDate &&
                this.endDate &&
                new Date(newStartDate) > new Date(this.endDate)
            ) {
                this.endDate = newStartDate;
            }
        },
    },
};
</script>

<style>
/* Add any custom styles for your home component */
</style>
