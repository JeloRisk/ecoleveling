<template>
    <div class="w-full h-auto">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script>
    import { ref, onMounted, watch } from 'vue';
    import Chart from 'chart.js/auto';
    import axios from 'axios';
    import { format, eachDayOfInterval } from 'date-fns';

    export default {
        name: 'Chart',
        props: {
            apiUrl: {
                type: String,
                required: true,
            },
            roomId: {
                type: String,
                default: '', // empty string by default means retrieve data for all rooms
            },
            startDate: {
                type: String,
                default: '',
            },
            endDate: {
                type: String,
                default: '',
            },
        },
        setup(props) {

            const chartCanvas = ref(null);

            onMounted(() => {
                fetchDataAndDrawChart();
            });

            watch(() => [props.startDate, props.endDate], () => {
                fetchDataAndDrawChart();
            });

            const fetchDataAndDrawChart = async () => {
                try {
                    let apiUrlWithParams = props.apiUrl;

                    if (props.roomId.trim() !== '') {
                        apiUrlWithParams += `?room_id=${props.roomId}`;
                    }

                    if (props.startDate && props.endDate) {
                        apiUrlWithParams += `${props.roomId.trim() !== '' ? '&' : '?'}start_date=${props.startDate}&end_date=${props.endDate}`;
                    }
                    console.log(apiUrlWithParams)

                    const response = await axios.get(apiUrlWithParams);
                    const { labels, data } = response.data;

                    // ttransform date labels
                    const startDate = new Date(props.startDate);
                    const endDate = new Date(props.endDate);
                    const allDates = eachDayOfInterval({ start: startDate, end: endDate });
                    const formattedLabels = allDates.map(date => format(date, 'MMMM d'));

                    // i-fill na dagijay 0 missing dates with 0 usage minutes kase tay iot tay ket han nga makaproduce ti data nu nakapatay
                    const filledData = allDates.map(date => {
                        const dateIndex = labels.indexOf(format(date, 'yyyy-MM-dd'));
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

                    // Destroy chart if it exists
                    if (chartCanvas.value && chartCanvas.value.chart) {
                        chartCanvas.value.chart.destroy();
                    }

                    // draw
                    const ctx = chartCanvas.value.getContext('2d');
                    chartCanvas.value.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: filteredLabels,
                            datasets: [{
                                label: 'Room Occupancy',
                                data: filteredData,
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                        },
                    });
                } catch (error) {
                    console.error('Error fetching data:', error);
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
