import './bootstrap'
import { createApp } from 'vue/dist/vue.esm-bundler.js'
import HomeProducts from './components/HomeProducts.vue'
import CartPage from './components/CartPage.vue'
import OrderPage from './components/OrderPage.vue'
import DashboardPage from './components/Dashboard.vue'
const app = createApp({})

app.component('home-products', HomeProducts)
app.component('cart-page', CartPage)
app.component('order-page', OrderPage)
app.component('dashboard-page', DashboardPage)

app.mount('#vue-app')
