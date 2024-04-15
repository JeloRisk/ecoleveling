<template>

    <div
        v-if="room"
        class="container mx-auto"
    >


        <div class="bg-gradient-to-r from-green-800 to-teal-600 h-fit px-6 py-4 rounded-lg">
            <span class="font-bold text-3xl mb-2 text-white">
                Room {{ room.roomNumber }}
            </span>
            <!-- on/off button for status -->
            <div class="flex items-center">
                <button
                    @click="toggleStatus"
                    :class="{ 'bg-green-600': room.status === 'Active', 'bg-red-600': room.status === 'Inactive' }"
                    class="px-4 py-2 text-white rounded-md mr-4"
                >
                    {{ room.status === 'Active' ? 'On' : 'Off' }}
                </button>

                <!-- confirmation modal -->
                <transition name="fade">
                    <div
                        v-if="showModal"
                        class="fixed inset-0 z-50 flex items-center justify-center"
                    >
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="bg-white p-6 rounded-lg shadow-md relative z-10">
                            <p class="text-lg mb-4">Turning {{ room.status === 'Active' ? 'off' : 'on' }} this will cut
                                the power in Room {{ room.roomNumber }}</p>
                            <input
                                type="text"
                                v-model="confirmationText"
                                placeholder="Enter room number to confirm"
                                class="w-full px-4 py-2 border rounded-md mb-4"
                            >
                            <div class="flex justify-end">
                                <button
                                    @click.once="confirmStatusChange"
                                    :disabled="!isConfirmationValid || disableConfirmationButton"
                                    :class="{ 'bg-teal-600': isConfirmationValid, 'bg-gray-400': !isConfirmationValid }"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-md"
                                >Confirm</button>
                                <button
                                    @click="closeModal"
                                    class="px-4 py-2 ml-2 border rounded-md"
                                >Cancel</button>
                            </div>
                        </div>
                    </div>
                </transition>

            </div>
        </div>
        <div class="mx-auto mt-2 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <div class="font-semibold text-lg mb-2">Electricity Consumption Time Log</div>
                <div class="mb-4">
                    <span class="text-gray-700">Status:</span>
                    <span class="font-bold">{{ room.status }}</span>
                </div>
                <!-- <Chart
                    :apiUrl="apiUrl"
                    :roomId="`${room.id}`"
                /> -->
                <!-- <ChartForAll
                    :apiUrl="apiUrl"
                    :roomId="`${room.id}`"
                    :startDate="startDate"
                    :endDate="endDate"
                    @data-fetch-request="fetchData"
                /> -->
                <ChartForAll :roomIdFromDetail="`${room.id}`" />

                <!-- <DynamicTable
                    :columns="columns"
                    :rows="rows"
                /> -->

                <TableForAll
                    :apiUrl="`${apiUrl}`"
                    :roomId="`${room.id}`"
                />
            </div>
        </div>
    </div>
    <div
        v-else
        class="flex items-center justify-center h-screen"
    >
        <p class="text-gray-700 text-xl">Loading...</p>
    </div>
</template>

<script>
    import axios from 'axios';
    // import Chart from '@/components/ChartsComponent.vue';
    import ChartForAll from './ChartForAll.vue';
    import TableForAll from './TableForAll.vue';
    export default {
        components: {
            ChartForAll, TableForAll
        },
        props: ['id'],
        data() {
            return {
                room: null,
                showModal: false,
                confirmationText: '',    // the text entered by the user for confirmation
                apiUrl: 'http://localhost:8000/api/room-occupancy-log-books',
                disableConfirmationButton: false,


                // for dynamic table
                columns: ['Room', 'Date', 'Start Time', 'end Time', 'Usage Per Minutes'],
                rows: [],
            };
        },
        created() {
            this.fetchRoomDetails();
        },
        methods: {



            // fetch room details from the backend
            fetchRoomDetails() {
                axios.get(`http://localhost:8000/api/rooms/${this.id}`)
                    .then(response => {
                        this.room = response.data;
                        console.log(this.room.status);

                    })
                    .catch(error => {
                        console.error('Error fetching room details: ', error);
                    });
            },

            // update the room status on the backend
            updateRoomStatus() {
                axios.put(`http://localhost:8000/api/rooms/${this.room.id}`,
                    { status: this.room.status })
                    .then(response => {
                        console.log('Room status updated successfully ' + this.room.status);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error updating room status: ', error);
                    });
            },

            toggleStatus() {
                // opens the confirmation modal
                this.showModal = true;
            },

            // confirm if the input in the form is the same as the room number 
            // then change the room status to the opposite of the current status
            confirmStatusChange() {
                if (this.confirmationText === this.room.roomNumber) {
                    this.showModal = false
                    this.room.status = this.room.status === 'Active' ? 'Inactive' : 'Active';
                    this.disableConfirmationButton = true;
                    this.updateRoomStatus();
                }
            },
            closeModal() {
                this.showModal = false;
            }
        },
        computed: {
            // Check if the confirmation text matches the room number
            isConfirmationValid() {
                return this.confirmationText === this.room.roomNumber;
            }
        }
    };
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>