<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    order: Object
})

const addresses = ref([])
const selectedAddress = ref(null)
const showForm = ref(false)

const form = ref({
    name: '',
    phone: '',
    address_line1: '',
    city: '',
    state: '',
    pincode: ''
})

const fetchAddresses = async () => {
    const res = await axios.get('/addresses')
    addresses.value = res.data
    if (addresses.value.length === 0) {
        showForm.value = true
    }
}

const saveAddress = async () => {
    await axios.post('/addresses', form.value)
    form.value = {}
    showForm.value = false
    fetchAddresses()
}

const placeOrder = async () => {
    if (!selectedAddress.value) {
        alert('Please select address')
        return
    }

    await axios.post(`/checkout/${props.order.id}`, {
        address_id: selectedAddress.value
    })

    alert('Order placed successfully (COD)')
}

onMounted(fetchAddresses)
</script>

<template>
<div class="bg-white p-6 rounded shadow space-y-6">

    <!-- ORDER SUMMARY -->
    <h2 class="text-2xl font-bold">Order {{ order.order_number }}</h2>

    <table class="w-full border">
        <tr v-for="item in order.items" :key="item.id" class="border-t">
            <td class="p-2">{{ item.product_name_snapshot }}</td>
            <td>₹ {{ item.price_snapshot }}</td>
            <td>{{ item.quantity }}</td>
            <td>₹ {{ item.total }}</td>
        </tr>
    </table>

    <div class="text-right font-bold text-xl">
        Grand Total: ₹ {{ order.total }}
    </div>

    <!-- ADDRESS SECTION -->
    <h3 class="text-xl font-semibold">Delivery Address</h3>

    <div v-if="addresses.length">
        <div v-for="addr in addresses" :key="addr.id"
             class="border p-3 mb-2 rounded">
            <label class="flex items-start gap-2">
                <input type="radio" v-model="selectedAddress" :value="addr.id">
                <div>
                    <p class="font-semibold">{{ addr.name }} - {{ addr.phone }}</p>
                    <p>{{ addr.address_line1 }}, {{ addr.city }}</p>
                    <p>{{ addr.state }} - {{ addr.pincode }}</p>
                </div>
            </label>
        </div>

        <button @click="showForm = true"
            class="text-blue-600 underline">
            + Add New Address
        </button>
    </div>

    <!-- ADD ADDRESS FORM -->
    <div v-if="showForm" class="border p-4 rounded">
        <input v-model="form.name" placeholder="Name" class="input" />
        <input v-model="form.phone" placeholder="Phone" class="input" />
        <input v-model="form.address_line1" placeholder="Address" class="input" />
        <input v-model="form.city" placeholder="City" class="input" />
        <input v-model="form.state" placeholder="State" class="input" />
        <input v-model="form.pincode" placeholder="Pincode" class="input" />

        <button @click="saveAddress"
            class="bg-green-600 text-black px-4 py-2 rounded mt-2">
            Save Address
        </button>
    </div>

    <!-- PLACE ORDER -->
    <button @click="placeOrder"
        class="bg-orange-600 text-black px-6 py-3 rounded w-full">
        Place Order (Cash on Delivery)
    </button>

</div>
</template>
