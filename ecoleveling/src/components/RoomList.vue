<template>
    <div class="bg-gray-100 p-6 ">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold">Room List</h2>
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
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                rooms: [],
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
