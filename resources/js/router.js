import { createRouter, createWebHistory } from 'vue-router'

import User from './components/CRUD/User.vue'
import Customer from './components/CRUD/Customer.vue'
import Product from './components/CRUD/Product.vue'
import ProductAdd from './components/CRUD/ProductAdd.vue'

import Login from './Login.vue'
import Menu from './menu.vue'


const routes = [
    {
        path: '/login',
        component: Login
    },

    {
        path: '/user',
        component: User
    },
    {
        path: '/customer',
        component: Customer
    },
    {
        path: '/product',
        component: Product
    },
    {
        path: '/product/product-form/:productId?',
        component: ProductAdd
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

export default router
