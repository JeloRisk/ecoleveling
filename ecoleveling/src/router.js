// import { createMemoryHistory, createRouter } from 'vue-router'

import Home from "./components/Home.vue";
import Rooms from "./components/RoomList.vue";
import ActivityLog from "./components/ActivityLog.vue";
import { createRouter, createWebHashHistory } from "vue-router";

import RoomDetails from "./components/RoomDetails.vue";

const routes = [
    { path: "/", component: Home },

    { path: "/rooms", component: Rooms },
    {
        path: "/room/:id",
        name: "RoomDetails",
        component: RoomDetails,
        props: true,
    },
    {
        path: "/activity-log",
        name: "ActivityLog",
        component: ActivityLog,
        props: (route) => ({ apiUrlWithParams: route.query.apiUrlWithParams }),
    },
];

// const router = createRouter({
//   history: createMemoryHistory(),
//   routes,
// })

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

// export default router;

export default router;
