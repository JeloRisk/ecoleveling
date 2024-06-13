<template>
    <div class="w-full h-auto">
        <canvas ref="chartCanvas" height="250px"></canvas>
    </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import Chart from "chart.js/auto";
import axios from "axios";
import { format, eachDayOfInterval, eachMonthOfInterval } from "date-fns";

export default {
    name: "Chart",
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
        selectedPeriod: {
            type: String,
            default: "monthly",
        },
    },
    setup(props) {
        const chartCanvas = ref(null);

        onMounted(() => {
            fetchDataAndDrawChart();
        });

        watch(
            () => [props.startDate, props.endDate, props.selectedPeriod],
            () => {
                fetchDataAndDrawChart();
            }
        );

        const fetchDataAndDrawChart = async () => {
            try {
                if (props.selectedPeriod == "yearly") {
                    let apiUrlWithParams = props.apiUrl;

                    if (props.roomId.trim() !== "") {
                        apiUrlWithParams += `?room_id=${props.roomId}`;
                    }

                    if (props.selectedPeriod == "yearly") {
                        apiUrlWithParams += `${
                            props.roomId.trim() !== "" ? "&" : "?"
                        }period=${props.selectedPeriod}`;
                    }

                    const response = await axios.get(apiUrlWithParams);
                    const { labels, data } = response.data;

                    // ttransform date labels
                    const startDate = new Date("2024-01");
                    const endDate = new Date("2024-12");
                    const allDates = eachMonthOfInterval({
                        start: startDate,
                        end: endDate,
                    });
                    const formattedLabels = allDates.map((date) =>
                        format(date, "MMMM")
                    );
                    const currentMonth = new Date().getMonth() + 1; // Get current month (0-indexed)

                    // i-fill na dagijay 0 missing dates with 0 usage minutes kase tay iot tay ket han nga makaproduce ti data nu nakapatay
                    const filledData = allDates.map((date) => {
                        const monthIndex = date.getMonth() + 1;
                        if (monthIndex <= currentMonth) {
                            const dateIndex = labels.indexOf(
                                format(date, "yyyy-MM")
                            );
                            return dateIndex !== -1 ? data[dateIndex] : 0;
                        }
                        return null;
                    });
                    console.log(filledData);

                    // remove dates with 0 usage minutes
                    const filteredLabels = [];
                    const filteredData = [];
                    for (let i = 0; i < formattedLabels.length; i++) {
                        if (filledData[i] !== 0) {
                            filteredLabels.push(formattedLabels[i]);
                            filteredData.push(filledData[i]);
                        }
                    }

                    // Destroy chart if it exists
                    if (chartCanvas.value && chartCanvas.value.chart) {
                        chartCanvas.value.chart.destroy();
                    }
                    const options = {
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                            },
                            y: {
                                grid: {
                                    display: false,
                                },
                            },
                        },
                    };
                    // draw
                    const ctx = chartCanvas.value.getContext("2d");
                    chartCanvas.value.chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            // labels: filteredLabels, // or formattedLabels
                            labels: formattedLabels, // or

                            datasets: [
                                {
                                    label: "With Ecoleveling",

                                    data: filledData,
                                    fill: false,

                                    borderColor: "rgb(65, 184, 131)",
                                    tension: 0.4,
                                    cubicInterpolationMode: "monotone",
                                    pointRadius: 0,
                                    pointHoverRadius: 10,
                                },
                            ],
                        },
                        options: options,
                    });
                } else if (props.selectedPeriod == "monthly") {
                    let apiUrlWithParams = props.apiUrl;

                    if (props.roomId.trim() !== "") {
                        apiUrlWithParams += `?room_id=${props.roomId}`;
                    }

                    if (props.startDate && props.endDate) {
                        apiUrlWithParams += `${
                            props.roomId.trim() !== "" ? "&" : "?"
                        }start_date=${props.startDate}&end_date=${
                            props.endDate
                        }`;
                    }

                    const response = await axios.get(apiUrlWithParams);
                    const { labels, data } = response.data;

                    // ttransform date labels
                    const startDate = new Date(props.startDate);
                    const endDate = new Date(props.endDate);
                    const allDates = eachDayOfInterval({
                        start: startDate,
                        end: endDate,
                    });
                    const formattedLabels = allDates.map((date) =>
                        format(date, "MMMM dd yyyy")
                    );

                    // i-fill na dagijay 0 missing dates with 0 usage minutes kase tay iot tay ket han nga makaproduce ti data nu nakapatay
                    const filledData = allDates.map((date) => {
                        const formattedDate = format(date, "yyyy-MM-dd");
                        const dateIndex = labels.indexOf(formattedDate);
                        return dateIndex !== -1 ? data[dateIndex] : 0;
                    });

                    // remove dates with 0 usage minutes
                    const filteredLabels = [];
                    const filteredData = [];
                    for (let i = 0; i < formattedLabels.length; i++) {
                        if (filledData[i] !== 0) {
                            filteredLabels.push(formattedLabels[i]);
                            filteredData.push(filledData[i]);
                        }
                    }

                    // Destroy ssschart if it exists
                    if (chartCanvas.value && chartCanvas.value.chart) {
                        chartCanvas.value.chart.destroy();
                    }
                    const options = {
                        responsive: true,
                        maintainAspectRatio: false,

                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                },
                            },
                            y: {
                                grid: {
                                    display: true,
                                },
                            },
                        },
                        plugins: {
                            tooltip: {
                                mode: "index",
                                intersect: false,
                            },
                        },
                    };
                    // draw
                    const ctx = chartCanvas.value.getContext("2d");
                    chartCanvas.value.chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: formattedLabels, // or formattedLabels
                            datasets: [
                                {
                                    label: "With Ecoleveling",
                                    data: filledData,
                                    // or filledData
                                    fill: true,
                                    // backgroundColor: 'rgba(65, 184, 131, 0.5)',
                                    backgroundColor: function (context) {
                                        var chart = context.chart;
                                        var gradient =
                                            chart.ctx.createLinearGradient(
                                                0,
                                                0,
                                                0,
                                                chart.height
                                            );
                                        gradient.addColorStop(
                                            0.25,
                                            "rgba(65, 184, 131, 1)"
                                        );
                                        gradient.addColorStop(
                                            0.75,
                                            "rgba(255, 255, 255, 1)"
                                        );
                                        return gradient;
                                    },
                                    borderColor: "rgb(65, 184, 131)",
                                    tension: 0.4,
                                    cubicInterpolationMode: "monotone",
                                    pointRadius: 0,
                                    pointHoverRadius: 10,
                                },

                                {
                                    label: "Unobserved Schedule(3-hour Lab)",
                                    data: Array(filledData.length).fill(0.09),
                                    fill: true,
                                    borderColor: "rgb(255, 99, 132)",
                                    tension: 0,
                                },
                            ],
                        },
                        options: options,
                    });
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        return {
            chartCanvas,
        };
    },
};
</script>

<style>
.canvas-container {
    width: 100%;
    height: auto;
}
</style>
