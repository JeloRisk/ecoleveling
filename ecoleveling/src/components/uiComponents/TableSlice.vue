<template>
    <div class="overflow-x-auto text-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="">
                <tr>
                    <th
                        v-for="(column, index) in columns"
                        :key="index"
                        :class="{
                            'text-right px-0': index === rowCount - 1,
                            'text-left px-0': index === 0,
                            'text-left px-6 py-3':
                                index > 0 && index < rowCount - 1,
                        }"
                        class="text-left font-medium uppercase tracking-wider"
                    >
                        {{ column }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="(row, rowIndex) in displayedRows"
                    :key="rowIndex"
                    class="hover:bg-gray-50"
                >
                    <td
                        v-for="(value, colIndex, index) in row"
                        :key="colIndex"
                        :class="{
                            'text-right px-0': index === rowCount - 1,
                            'text-left px-0': index === 0,
                            'text-left px-6 py-3':
                                index > 0 && index < rowCount - 1,
                        }"
                        class="whitespace-nowrap"
                    >
                        {{ value }}
                    </td>
                </tr>
                <tr v-if="displayedRows.length === 0 && dataAvailable">
                    <td :colspan="columns.length" class="px-6 py-4 text-center">
                        No data available.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "TableSlice",
    props: {
        columns: {
            type: Array,
            required: true,
        },
        rows: {
            type: Array,
            required: true,
        },
        dataAvailable: {
            type: Boolean,
            default: true,
        },
        tableSlice: {
            type: Boolean,
            default: true,
        },
        initialItemsPerPage: {
            type: Number,
            default: 5,
        },
        perPageOptions: {
            type: Array,
            default: () => [5, 10, 25, 50],
        },
    },
    computed: {
        displayedRows() {
            if (!this.tableSlice) return this.rows;

            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = Math.min(
                startIndex + this.itemsPerPage,
                this.rows.length
            );
            return this.rows.slice(startIndex, endIndex);
        },
        rowCount() {
            return this.columns.length;
        },
    },
    data() {
        return {
            currentPage: 1,
            itemsPerPage: this.initialItemsPerPage,
        };
    },
};
</script>

<style scoped></style>
