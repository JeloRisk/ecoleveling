// import { createMemoryHistory, createRouter } from 'vue-router'

import Home from './components/Home.vue'
import About from './components/RoomList.vue'
import { createRouter, createWebHashHistory } from 'vue-router';

import RoomDetails from './components/RoomDetails.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/about', component: About },
  {
    path: '/room/:id',
    name: 'RoomDetails',
    component: RoomDetails,
    props: true
  }
]

// const router = createRouter({
//   history: createMemoryHistory(),
//   routes,
// })


const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

// export default router;


export default router