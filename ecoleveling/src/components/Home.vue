<template>
    <div>
        <!-- Greeting Message and Icon -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-2">Hello, Admin</h2>
            </div>
            <div>
                <!-- Add your icon here -->
                <!-- For example, if you are using Font Awesome -->
                <i class="fas fa-smile text-xl"></i>
            </div>
        </div>

        <!-- Room List -->
        <h2 class="ext-center text-2xl tfont-bold mb-4">List</h2>
        <div class="flex flex-wrap justify-between">
            <div
                v-for="room in rooms"
                :key="room.id"
                class="w-1/3 p-4"
            >
                <RouterLink :to="{ name: 'RoomDetails', params: { id: room.id } }">
                    <div class="bg-white shadow rounded-lg p-4">
                        <h3 class="text-lg font-bold mb-2">{{ room.roomNumber }}</h3>
                        <p class="mb-2">Status: {{ room.status }}</p>
                    </div>
                </RouterLink>
            </div>
        </div>
    </div>
    <ChartForAll />
</template>


<script>
    import axios from 'axios';
    import ChartForAll from '@/components/ChartForAll.vue';
    export default {
        components: {
            ChartForAll
        },
        data() {
            return {
                rooms: [],
            };
        },
        created() {
            this.fetchRooms();
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