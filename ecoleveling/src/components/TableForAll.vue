<template>
    <div>
        <div v-if="rows.length > 0">
            <DynamicTable
                :columns="columns"
                :rows="rows"
            />
        </div>
        <div v-else>
            <p>No data available.</p>
        </div>
    </div>
</template>
<script>
    import DynamicTable from '@/components/uiComponents/DynamicTable.vue';
    import axios from 'axios';
    import { onMounted, ref, watch, watchEffect } from 'vue';

    export default {
        name: 'MyComponent',
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
        data() {
            return {
                columns: ['Room', 'Date', 'Start Time', 'End Time'],
                rows: [],
                dataAvailable: true, 
            };
        },
        setup(props) {
            const rows = ref([]);
            console.log(rows)

            const columns = ['Room', 'Date', 'Start Time', 'End Time']
            // onMounted(fetchData);
            onMounted(() => {
                fetchData();
            });
            watch(() => [props.startDate, props.endDate], () => {
                fetchData();
            });

            const fetchData = async () => {
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

                    if (rows.value.length != 0) {
                        rows.value = []
                        console.log("Gello")
                    }

                    rows.value = response.data.map(row => ({
                        roomNumber: row.roomNumber,
                        date: row.date,
                        startTime: row.startTime,
                        endTime: row.endTime,
                        usageMinutes: formatNumber(row.usageMinutes), 
                    }));
                    console.log(rows)
                } catch (error) {
                    console.error('Error fetching data:', error);
                }



            }

            const formatNumber = (num) => parseFloat(num).toFixed(2);
            return { columns, rows }; // Return reactive data


        },
        // mounted() {
        //     this.fetchData();
        // },
        // watch: {
        //     // Watch for changes in sDate and eDate props
        //     sDate: 'fetchData',
        //     eDate: 'fetchData',
        // },
        // methods: {
        //     async fetchData() {
        //         try {
        //             let apiUrlWithParams = this.apiUrl;
        //             if (this.roomId.trim() !== '') {
        //                 apiUrlWithParams += `?room_id=${this.roomId}`;
        //                 if (this.sDate && this.eDate) {
        //                     apiUrlWithParams += `&start_date=${this.sDate}&end_date=${this.eDate}`;
        //                 }

        //             }

        //             else if (this.sDate && this.eDate) {
        //                 apiUrlWithParams += `?start_date=${this.sDate}&end_date=${this.eDate}`;
        //             }
        //             const response = await axios.get(apiUrlWithParams);
        //             this.rows = response.data.map(row => ({
        //                 roomNumber: row.roomNumber,
        //                 date: row.date,
        //                 startTime: row.startTime,
        //                 endTime: row.endTime,
        //                 usageMinutes: this.formatNumber(`${row.usageMinutes}`),
        //             }));
        //             // Update data availability
        //             this.dataAvailable = this.rows.length > 0;

        //             this.$emit('data-available', this.rows.length > 0);
        //         } catch (error) {
        //             console.error('Error fetching data:', error);
        //         }
        //     },

        //     formatNumber(num) {
        //         return parseFloat(num).toFixed(2)
        //     },
        // },
    };
</script>
