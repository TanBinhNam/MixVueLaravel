require('./bootstrap');

import { createApp } from 'vue'
import Pagination from 'v-pagination-3';

import App from './App.vue'
import Menu from './menu.vue'
import Login from './Login.vue'


import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import '/../public/app.css'
import VueAxios from 'vue-axios'
import axios from 'axios'
import router from './router'
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { TailwindPagination } from 'laravel-vue-pagination';


import swal from 'sweetalert2';
window.Swal = swal;
const app = createApp(App)
app.use(router,axios,VueAxios,Bootstrap4Pagination,Bootstrap5Pagination,TailwindPagination).component('pagination', Pagination);
app.mount('#app')

const menu = createApp(Menu)
menu.use(router,axios,VueAxios);
menu.mount('#menu')



