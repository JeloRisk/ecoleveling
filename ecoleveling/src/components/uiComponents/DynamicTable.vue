<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <!-- loop through columns and create table headers -->
                    <th
                        v-for="(column, index) in columns"
                        :key="index"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >{{ column }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="(row, index) in rows"
                    :key="index"
                    class="hover:bg-gray-50"
                >
                    <td
                        v-for="(value, key) in row"
                        :key="key"
                        class="px-6 py-4 whitespace-nowrap"
                    >{{ value }}</td>
                </tr>

                <!-- If rows are empty and data is available, display a message -->
                <tr v-if="!rows.length && dataAvailable">
                    <td
                        :colspan="columns.length"
                        class="px-6 py-4 text-center"
                    >No data available.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'DynamicTable',
        props: {
            // arr of column names for table headers
            columns: {
                type: Array,
                required: true,
            },
            // arr of row of objects
            rows: {
                type: Array,
                required: true,
            },
            // boolean indicating if data is available
            dataAvailable: {
                type: Boolean,
                default: true,
            },
        },
        // setup(props) {
        //     const fetchData = async () => { }

        // }
    };
</script>

<style scoped>
/* Add your component-specific styling here */
</style>
