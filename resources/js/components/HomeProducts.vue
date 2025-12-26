<script setup>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'

const props = defineProps({
    products: Array,          
    cart: Object,           
    isAuth: Boolean
})


const cartState = reactive({
    items: [...(props.cart?.items || [])]
})


const currentSlide = ref({})
const intervals = ref({})


const nextSlide = (id, total) => {
    currentSlide.value[id] = ((currentSlide.value[id] ?? 0) + 1) % total
}
const prevSlide = (id, total) => {
    currentSlide.value[id] = ((currentSlide.value[id] ?? 0) - 1 + total) % total
}
const startAutoSlide = (product) => {
    if (!product.image_urls || product.image_urls.length <= 1) return
    intervals.value[product.id] = setInterval(() => {
        nextSlide(product.id, product.image_urls.length)
    }, 3000)
}
const stopAutoSlide = (id) => {
    if (intervals.value[id]) {
        clearInterval(intervals.value[id])
        delete intervals.value[id]
    }
}
onMounted(() => props.products.forEach(startAutoSlide))
onBeforeUnmount(() => Object.values(intervals.value).forEach(clearInterval))


const redirectToLogin = () => window.location.href = '/login'


const cartItemForProduct = (productId) => {
    return cartState.items.find(i => i.product_id === productId)
}

// Add product to cart
const addToCart = async (product) => {
    if (!props.isAuth) return redirectToLogin()
    try {
        const res = await axios.post(`/cart/add/${product.id}`, { quantity: 1 })
        cartState.items.push(res.data.cart_item)
    } catch (err) {
        console.error(err)
        alert('Failed to add to cart')
    }
}

// Remove from cart
const removeFromCart = async (cartItem) => {
    if (!props.isAuth) return redirectToLogin()
    try {
        await axios.post(`/cart/remove/${cartItem.id}`)
        cartState.items = cartState.items.filter(i => i.id !== cartItem.id)
    } catch (err) {
        console.error(err)
    }
}

// Update quantity
const updateQuantity = async (cartItem, qty) => {
    if (!props.isAuth) return redirectToLogin()
    if (qty < 1) qty = 1
    if (qty > cartItem.product.stock_quantity) qty = cartItem.product.stock_quantity
    try {
        await axios.post(`/cart/update/${cartItem.id}`, { quantity: qty })
        cartItem.quantity = qty
    } catch (err) {
        console.error(err)
    }
}
</script>

<template>
<div class="w-full py-6 bg-[#f1f3f6]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid gap-4" style="grid-template-columns: repeat(4, minmax(0, 1fr));">
            <div v-for="p in products" :key="p.id" class="bg-white border border-gray-200 hover:shadow-lg transition rounded-sm flex flex-col">
                
                <!-- Image slider -->
                <div class="relative w-full aspect-[1/1] bg-gray-100 overflow-hidden"
                     @mouseenter="stopAutoSlide(p.id)"
                     @mouseleave="startAutoSlide(p)">
                    <img :src="p.image_urls[currentSlide[p.id] ?? 0]"
                         class="w-full h-full object-cover" alt="product image"/>
                    <button v-if="p.image_urls.length > 1" @click="prevSlide(p.id, p.image_urls.length)"
                            class="absolute left-1 top-1/2 -translate-y-1/2 bg-white/80 text-black px-2 py-1 text-xs">‹</button>
                    <button v-if="p.image_urls.length > 1" @click="nextSlide(p.id, p.image_urls.length)"
                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-white/80 text-black px-2 py-1 text-xs">›</button>
                </div>

                <!-- Product info -->
                <div class="p-3 flex flex-col flex-1">
                    <h3 class="text-sm font-medium text-gray-800 truncate">{{ p.name }}</h3>
                    <p class="text-base font-semibold text-gray-900 mt-1">₹ {{ p.price }}</p>
                    <p class="text-xs text-gray-500 mt-1">Stock: {{ p.stock_quantity }}</p>

                    <!-- Cart actions -->
                    <div v-if="cartItemForProduct(p.id)" class="flex items-center gap-2 mt-auto">
                        <button @click="updateQuantity(cartItemForProduct(p.id), cartItemForProduct(p.id).quantity - 1)"
                                class="px-2 py-1 border rounded">-</button>

                        <input type="number"
                               class="w-12 text-center border rounded"
                               v-model.number="cartItemForProduct(p.id).quantity"
                               @change="updateQuantity(cartItemForProduct(p.id), cartItemForProduct(p.id).quantity)"
                               :max="p.stock_quantity" :min="1" />

                        <button @click="updateQuantity(cartItemForProduct(p.id), cartItemForProduct(p.id).quantity + 1)"
                                class="px-2 py-1 border rounded">+</button>

                        <button @click="removeFromCart(cartItemForProduct(p.id))"
                                class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700">
                            Remove
                        </button>
                    </div>

                    <!-- Add to cart button -->
                    <button v-else @click="addToCart(p)"
                            class="add-to-cart-btn mt-auto w-full text-sm font-semibold py-2 bg-blue-600 text-white hover:bg-blue-700 transition rounded">
                        ADD TO CART
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
