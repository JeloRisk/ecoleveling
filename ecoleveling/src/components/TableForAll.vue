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
                default: '',
            },
        },
        data() {
            return {
                columns: ['Room', 'Date', 'Start Time', 'End Time'],
                rows: [],
            };
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            async fetchData() {
                try {
                    let apiUrlWithParams = this.apiUrl;
                    if (this.roomId.trim() !== '') {
                        apiUrlWithParams += `?room_id=${this.roomId}`;
                    }
                    const response = await axios.get(apiUrlWithParams);
                    this.rows = response.data.map(row => ({
                        roomNumber: row.roomNumber,
                        date: row.date,
                        startTime: row.startTime,
                        endTime: row.endTime,
                        usageMinutes: this.formatNumber(`${row.usageMinutes}`),
                    }));

                    this.$emit('data-available', this.rows.length > 0);
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            },

            formatNumber(num) {
                return parseFloat(num).toFixed(2)
            },
        },
    };
</script>

<style scoped>
/* Add your component-specific styling here */
</style>
