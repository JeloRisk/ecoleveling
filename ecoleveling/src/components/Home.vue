<template>

    <!-- room list -->
    <div class="space-y-2">
        <div class="p-4 border border-gray-300 roundedbg-white">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Rooms</h2>
                <div class="flex items-center">
                    <i class="fas fa-th-large text-xl mr-2"></i>
                    <RouterLink
                        to="/rooms"
                        class="text-blue-500 hover:underline"
                    >View All Rooms</RouterLink>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div
                    v-for="room in sortedRooms"
                    :key="room.id"
                    :class="{ 'opacity-50': room.status === 'Disabled', 'pointer-events-none': room.status === 'Disabled' }"
                    class="bg-white rounded-lg shadow-md p-6"
                >

                    <!-- To link to room details page if the room is active or inactive -->
                    <RouterLink
                        :to="{ name: 'RoomDetails', params: { id: room.id } }"
                        v-if="room.status !== 'Disabled'"
                    >
                        <h3 class="text-lg font-bold mb-2">{{ room.roomNumber }}</h3>
                        <p
                            :class="{ 'text-green-600': room.status === 'Active', 'text-gray-600': room.status !== 'Active' }"
                            class="mb-4"
                        >Status: {{ room.status }}</p>
                    </RouterLink>

                    <div v-else>
                        <h3 class="text-lg font-bold mb-2">{{ room.roomNumber }}</h3>
                        <p class="text-gray-600 mb-4">Status: {{ room.status }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- chart component for all rooms -->
        <ChartForAll />

        <TableForAll :apiUrl="`${apiUrl}`" />
    </div>
</template>


<script>
    import axios from 'axios';
    import ChartForAll from '@/components/ChartForAll.vue';

    import TableForAll from '@/components/TableForAll.vue';
    export default {
        components: {
            ChartForAll, TableForAll
        },
        data() {
            return {
                rooms: [],
                apiUrl: 'http://localhost:8000/api/room-occupancy-log-books',

            };
        },
        created() {
            this.fetchRooms();
        },
        computed: {
            sortedRooms() {
                // Sort tay room
                return this.rooms.sort((a, b) => {
                    if (a.status === 'Active' && b.status !== 'Active') return -1;
                    if (a.status !== 'Active' && b.status === 'Active') return 1;
                    if (a.status === 'Inactive' && b.status === 'Disabled') return -1;
                    if (a.status === 'Disabled' && b.status === 'Inactive') return 1;
                    return 0;
                });
            },
        },
        methods: {
            fetchRooms() {
                axios.get('http://localhost:8000/api/rooms/')
                    .then(response => {
                        this.rooms = response.data;
                    })
                    .catch(error => {
                        console.error('Error fetching rooms: ', error);
                    });
            },
        },
    };
</script>