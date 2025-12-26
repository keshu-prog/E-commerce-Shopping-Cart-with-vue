<script setup>
import { ref } from 'vue'
import axios from 'axios'

defineProps({
    orders: Array,
    addresses: Array
})

const showAddressForm = ref(false)

const form = ref({
    name: '',
    phone: '',
    address_line1: '',
    city: '',
    state: '',
    pincode: ''
})

const addAddress = async () => {
    await axios.post('/api/addresses', form.value)
    location.reload()
}
</script>

<template>
<div class="space-y-10">

    <!-- ADD ADDRESS -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">My Addresses</h2>
        <button
            @click="showAddressForm = !showAddressForm"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            + Add Address
        </button>
    </div>

    <!-- ADDRESS LIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            v-for="addr in addresses"
            :key="addr.id"
            class="border p-4 rounded bg-white">
            <p class="font-semibold">{{ addr.name }} - {{ addr.phone }}</p>
            <p>{{ addr.address_line1 }}</p>
            <p>{{ addr.city }}, {{ addr.state }} - {{ addr.pincode }}</p>
        </div>
    </div>

    <!-- ADD ADDRESS FORM -->
    <div v-if="showAddressForm" class="border p-4 rounded bg-gray-50">
        <input v-model="form.name" class="input" placeholder="Name" />
        <input v-model="form.phone" class="input" placeholder="Phone" />
        <input v-model="form.address_line1" class="input" placeholder="Address" />
        <input v-model="form.city" class="input" placeholder="City" />
        <input v-model="form.state" class="input" placeholder="State" />
        <input v-model="form.pincode" class="input" placeholder="Pincode" />

        <button
            @click="addAddress"
            class="bg-green-600 text-white px-4 py-2 rounded mt-2">
            Save Address
        </button>
    </div>

    <!-- ORDERS -->
    <h2 class="text-2xl font-bold">My Orders</h2>

    <div
        v-for="order in orders"
        :key="order.id"
        class="border rounded bg-white p-4 space-y-3">

        <h3 class="font-semibold">
            Order #{{ order.order_number }}
        </h3>

        <table class="w-full text-sm border">
            <tr
                v-for="item in order.order_items"
                :key="item.id"
                class="border-t">
                <td class="p-2">{{ item.product_name_snapshot }}</td>
                <td>₹{{ item.price_snapshot }}</td>
                <td>x{{ item.quantity }}</td>
                <td>₹{{ item.total }}</td>
            </tr>
        </table>

        <!-- PAYMENT -->
        <p class="text-sm">
            <strong>Payment:</strong>
            {{ order.payment?.payment_method?.toUpperCase() }}
            ({{ paymentStatus(order.payment?.status) }})
        </p>

        <p class="font-semibold text-right">
            Total: ₹{{ order.total }}
        </p>
    </div>

</div>
</template>

<script>
export default {
    methods: {
        paymentStatus(status) {
            if (status === 0) return 'Pending'
            if (status === 1) return 'Paid'
            if (status === 2) return 'Cancelled'
            return 'Unknown'
        }
    }
}
</script>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #000;
    padding: 8px;
    margin-bottom: 6px;
    border-radius: 4px;
}
</style>
