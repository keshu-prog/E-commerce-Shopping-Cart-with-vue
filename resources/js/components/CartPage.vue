<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
    products: Array,
    cart: {
        type: Object,
        default: () => ({ items: [] })
    },
    isAuth: Boolean
})

const redirectToLogin = () => {
    window.location.href = '/login'
}

// Remove item
const removeItem = (id) => {
    if (!props.isAuth) return redirectToLogin()

    axios.post(`/cart/remove/${id}`)
        .then(() => location.reload())
        .catch(err => console.error(err))
}

// Place order
const placeOrder = () => {
    if (!props.isAuth) return redirectToLogin()

    axios.post('/cart/checkout')
        .then(res => {
            alert('Checkout success')
            window.location.href = `/orders/${res.data.order_id}`
        })
        .catch(err => {
            console.error(err)
            alert('Checkout failed')
        })
}

// Update quantity
const updateQuantity = (itemId, qty) => {
    if (!props.isAuth) return redirectToLogin()

    if (qty < 1) return 

    axios.post(`/cart/update/${itemId}`, { quantity: qty })
        .then(() => location.reload())
        .catch(err => console.error(err))
}
</script>

<template>
<div class="bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4">Your Cart</h2>

    <div v-if="!cart || cart.items.length === 0" class="text-gray-500">
        Your cart is empty.
    </div>

    <div v-else>
        <table class="w-full border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="item in cart.items" :key="item.id" class="border-t">
                    <td class="p-2">{{ item.product.name }}</td>
                    <td>₹ {{ item.product.price }}</td>

                    <!-- Quantity controls -->
                    <td class="flex items-center gap-2">
                        <button @click="updateQuantity(item.id, item.quantity - 1)"
                                class="px-2 py-1 border rounded">-</button>
                        <input type="number"
                               min="1"
                               v-model.number="item.quantity"
                               @change="updateQuantity(item.id, item.quantity)"
                               class="w-12 text-center border rounded"/>
                        <button @click="updateQuantity(item.id, item.quantity + 1)"
                                class="px-2 py-1 border rounded">+</button>
                    </td>

                    <td>₹ {{ item.quantity * item.product.price }}</td>

                    <td>
                        <button
                            @click="removeItem(item.id)"
                            class="text-red-600 hover:underline">
                            Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center">
            <div class="text-lg font-semibold">
                Total:
                ₹ {{
                    cart.items.reduce(
                        (sum, i) => sum + (i.quantity * i.product.price),
                        0
                    )
                }}
            </div>

            <button
                @click="placeOrder"
                class="bg-green-600 text-black px-6 py-2 rounded hover:bg-green-700">
                Place Order
            </button>
        </div>
    </div>

</div>
</template>
