<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="col-span-3 lg:col-span-3">
            <ChartWithTable class="h-full" />
        </div>

        <!-- Table -->
        <div class="h-full md:col-span-2 col-span-3">
            <TableForAll :apiUrl="`${apiUrl}`" class="h-full" />
        </div>

        <!-- Room list -->
        <div class="h-full md:col-span-1 col-span-3 ">
            <div class="space-y-6  h-full">
                <div class="border border-gray-200 rounded-xl p-8 bg-white  h-full">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Rooms</h2>
                        <RouterLink
                            to="/rooms"
                            class="text-[#117D2C] hover:underline text-sm"
                            >See all</RouterLink
                        >
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div
                            v-for="room in sortedRooms"
                            :key="room.id"
                            :class="{
                                'opacity-50': room.status === 'Disabled',
                                'pointer-events-none':
                                    room.status === 'Disabled',
                            }"
                            class="rounded-lg p-4 group hover:bg-green-300"
                        >
                            <RouterLink
                                :to="{
                                    name: 'RoomDetails',
                                    params: { id: room.id },
                                }"
                                class="flex items-center"
                            >
                                <!-- Room number box -->
                                <div
                                    class="bg-[#117D2C] text-white rounded-md p-2 mr-2 h-10 w-10 text-center"
                                >
                                    {{ room.roomNumber }}
                                </div>

                                <!-- ex -->
                                <div
                                    class="border-l border-gray-400 h-16 mx-2 group-hover:border-[#117D2C]"
                                ></div>

                                <!-- Room status -->
                                <div>
                                    <p
                                        :class="{
                                            // 'text-green-600':
                                            //     room.status === 'With Ecoleveling',
                                            // 'text-gray-300':
                                            //     room.status !== 'With Ecoleveling',
                                        }"
                                        class="group-hover:text-gray-700"
                                    >
                                        Status: {{ room.status }}
                                    </p>
                                </div>
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import ChartForAll from "@/components/ChartForAll.vue";
import ChartWithTable from "./ChartWithTable.vue";

import TableForAll from "@/components/TableForAll.vue";
export default {
    components: {
        ChartForAll,
        ChartWithTable,
        TableForAll,
    },
    data() {
        return {
            rooms: [],
            apiUrl: "http://localhost:8000/api/room-occupancy-log-books",
        };
    },
    created() {
        this.fetchRooms();
    },
    computed: {
        sortedRooms() {
            // Sort tay room
            return this.rooms.sort((a, b) => {
                if (a.status === "Active" && b.status !== "Active") return -1;
                if (a.status !== "Active" && b.status === "Active") return 1;
                if (a.status === "Inactive" && b.status === "Disabled")
                    return -1;
                if (a.status === "Disabled" && b.status === "Inactive")
                    return 1;
                return 0;
            });
        },
    },
    methods: {
        fetchRooms() {
            axios
                .get("http://localhost:8000/api/rooms/")
                .then((response) => {
                    this.rooms = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching rooms: ", error);
                });
        },
    },
};
</script>
