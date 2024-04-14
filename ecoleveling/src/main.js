// main.js or main.ts

import { createApp } from 'vue'
import App from './App.vue'
import router from './router.js'
import VueAxios from 'vue-axios'
import axios from 'axios'


import './assets/main.css'

import LineChart from './components/LineChart.vue'
// Import and register Chart.js components globally


const app = createApp(App);
app.component('line-chart', LineChart); 

app.use(router);
app.use(VueAxios, axios);
app.mount('#app');



// window.Vue = require('vue');

// import router from './router.js'
// import VueAxios from 'vue-axios'
// // import VueRouter from 'vue-router'
// import axios from 'axios'

// import './assets/main.css'


// createApp(App).use(router).use(VueAxios, axios).mount('#app')