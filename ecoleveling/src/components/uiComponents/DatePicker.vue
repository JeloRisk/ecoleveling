<template>
    <button
        id="dateRangeButton"
        data-dropdown-toggle="dateRangeDropdown"
        data-dropdown-ignore-click-outside-class="datepicker"
        data-dropdown-hide="datepicker"
        type="button"
        class="inline-flex items-center text-blue-700 font-medium hover:underline"
    >
        {{ buttonText }}
        <svg
            class="w-3 h-3 ms-2"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 10 6"
        >
            <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 4 4 4-4"
            />
        </svg>
    </button>

    <div
        id="dateRangeDropdown"
        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow"
    >
        <div
            class="p-3 flex flex-col justify-end"
            aria-labelledby="dateRangeButton "
        >
            <div class="flex flex-col">
                <span class="mx-2 text-gray-500">from</span>

                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                    >
                        <svg
                            class="w-4 h-4 text-gray-500"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"
                            />
                        </svg>
                    </div>
                    <input
                        v-model="startDate"
                        name="start"
                        type="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 placeholder-gray-400"
                        placeholder="Start Date"
                    />
                </div>
                <span class="mx-2 text-gray-500">to</span>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                    >
                        <svg
                            class="w-4 h-4 text-gray-500"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"
                            />
                        </svg>
                    </div>
                    <input
                        v-model="endDate"
                        name="end"
                        type="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 placeholder-gray-400"
                        placeholder="End Date"
                    />
                </div>
            </div>
            <button
                @click="dateselected"
                class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 justify-end"
            >
                Filter
            </button>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["dateselected"],
    data() {
        return {
            startDate: "",
            endDate: "",
            buttonText: "Select Date Range",
        };
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
        dateselected() {
            if (this.startDate.trim() !== "" && this.endDate.trim() !== "") {
                this.buttonText = `${this.startDate} - ${this.endDate}`;
            } else if (this.startDate.trim() !== "") {
                this.buttonText = this.startDate;
            } else if (this.endDate.trim() !== "") {
                this.buttonText = this.endDate;
            } else {
                this.buttonText = "Select Date Range";
            }
            this.$emit("dateselected", this.startDate, this.endDate);
        },
    },
};
</script>
