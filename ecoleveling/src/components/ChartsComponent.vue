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
                default: '', // Empty string by default means retrieve data for all rooms
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

                    // Append room ID query parameter if it's provided and not empty
                    if (props.roomId.trim() !== '') {
                        apiUrlWithParams += `?room_id=${props.roomId}`;
                    }

                    // Append start and end date parameters if they're provided
                    if (props.startDate && props.endDate) {
                        apiUrlWithParams += `${props.roomId.trim() !== '' ? '&' : '?'}start_date=${props.startDate}&end_date=${props.endDate}`;
                    }

                    const response = await axios.get(apiUrlWithParams);
                    const { labels, data } = response.data;

                    // Transform date labels
                    const startDate = new Date(props.startDate);
                    const endDate = new Date(props.endDate);
                    const allDates = eachDayOfInterval({ start: startDate, end: endDate });
                    const formattedLabels = allDates.map(date => format(date, 'MMMM d'));

                    // Fill in missing dates with 0 usage minutes
                    const filledData = allDates.map(date => {
                        const dateIndex = labels.indexOf(format(date, 'yyyy-MM-dd'));
                        return dateIndex !== -1 ? data[dateIndex] : 0;
                    });

                    // Destroy existing chart if it exists
                    if (chartCanvas.value && chartCanvas.value.chart) {
                        chartCanvas.value.chart.destroy();
                    }

                    // Draw chart using Chart.js
                    const ctx = chartCanvas.value.getContext('2d');
                    chartCanvas.value.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: formattedLabels,
                            datasets: [{
                                label: 'Room Occupancy',
                                data: filledData,
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false, // Disable default aspect ratio
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
/* Add Tailwind CSS classes for chart resizing */
.canvas-container {
    width: 100%;
    height: auto;
}
</style>
