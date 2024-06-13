<template>
    <div>
        <LogsTable
            :datepicker="true"
            @dateselected2="handleDateSelected2"
            :startDate="startDate"
            :endDate="endDate"
        />
    </div>
</template>

<script>
import LogsTable from "./uiComponents/LogsTable.vue";
export default {
    name: "ActivityLog",
    components: {
        LogsTable,
    },
    data() {
        return {
            startDate: "",
            endDate: "",
        };
    },
    methods: {
        handleDateSelected2(startDate, endDate) {
            this.endDate = "";
            this.startDate = "";

            this.startDate = startDate;
            this.endDate = endDate;
            if (this.endDate.length === 0) {
                this.endDate = startDate;
            } else if (this.startDate.length === 0) {
                this.startDate = this.endDate;
            } else if (this.startDate.length != 0 && this.endDate.length != 0) {
                this.startDate = startDate;

                this.endDate = endDate;
            }
            console.log(this.startDate + "-" + this.endDate);
        },
    },
};
</script>
