<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        v-for="(column, index) in columns"
                        :key="index"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        {{ column }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-[#585757]">
                <tr
                    v-for="(row, index) in displayedRows"
                    :key="index"
                    class="hover:bg-gray-50"
                >
                    <td
                        v-for="(value, key) in row"
                        :key="key"
                        class="px-6 py-4 whitespace-nowrap"
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
        <!--  Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div class="flex items-center">
                <span class="mr-2 text-gray-500">Show:</span>
                <select
                    v-model="itemsPerPage"
                    @change="changeItemsPerPage($event.target.value)"
                    class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
                    <option
                        v-for="option in perPageOptions"
                        :key="option"
                        :value="option"
                    >
                        {{ option }}
                    </option>
                </select>
            </div>
            <div class="flex items-center">
                <button
                    @click="previousPage"
                    :disabled="currentPage === 1 || !shouldShowPagination"
                    class="text-gray-500 font-semibold py-2 px-4 border border-gray-300 rounded shadow mr-2 disabled:opacity-50"
                >
                    &lt;
                </button>
                <input
                    type="number"
                    v-model="currentPage"
                    min="1"
                    :max="totalPages"
                    class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-opacity-50 w-10 px-2 py-1 mr-2 text-center"
                    style="-webkit-appearance: none; -moz-appearance: textfield"
                />
                of {{ totalPages }} Pages
                <button
                    @click="nextPage"
                    :disabled="
                        currentPage === totalPages || !shouldShowPagination
                    "
                    class="text-gray-500 font-semibold py-2 px-4 border border-gray-300 rounded shadow ml-2 disabled:opacity-50"
                >
                    &gt;
                </button>
            </div>
            <div>
                <span>{{ paginationSummary }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DynamicTable",
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
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = Math.min(
                startIndex + this.itemsPerPage,
                this.rows.length
            );
            return this.rows.slice(startIndex, endIndex);
        },
        shouldShowPagination() {
            return this.tableSlice && this.totalPages > 1;
        },
        totalPages() {
            return Math.ceil(this.rows.length / this.itemsPerPage);
        },
        paginationSummary() {
            const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
            // console.log(this.currentPage);

            const endItem = Math.min(
                startItem + this.itemsPerPage - 1,
                this.rows.length
            );
            console.log(startItem > this.rows.length);
            if (startItem > this.rows.length) {
                return `0 of ${this.rows.length} rows`;
            } else if (startItem < 0) {
                return `0 of ${this.rows.length} rows`;
            } else {
                return `${startItem}-${endItem} of ${this.rows.length} rows`;
            }
        },
    },
    data() {
        return {
            currentPage: 1,
            itemsPerPage: this.initialItemsPerPage,
        };
    },
    methods: {
        changePage(pageNumber) {
            this.currentPage = parseInt(pageNumber, 10);
        },
        changeItemsPerPage(value) {
            this.itemsPerPage = parseInt(value, 10);
            // reset
            this.currentPage = 1;
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
    },
};
</script>

<style scoped></style>
