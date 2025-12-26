<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    products: Array,
    isAuth: Boolean
})

const currentSlide = ref({})
const intervals = ref({})

const nextSlide = (id, total) => {
    currentSlide.value[id] =
        ((currentSlide.value[id] ?? 0) + 1) % total
}

const prevSlide = (id, total) => {
    currentSlide.value[id] =
        ((currentSlide.value[id] ?? 0) - 1 + total) % total
}

const startAutoSlide = (product) => {
    if (product.image_urls.length <= 1) return

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

onMounted(() => {
    props.products.forEach(product => {
        startAutoSlide(product)
    })
})

onBeforeUnmount(() => {
    Object.values(intervals.value).forEach(clearInterval)
})

const addToCart = (id) => {
    if (!props.isAuth) {
        window.location.href = `/login?redirect_product=${id}`
        return
    }

    axios.post(`/cart/add/${id}`)
        .then(res => {
            alert(res.data.message)
        })
        .catch(err => {
            console.error(err)
        })
}
</script>

<template>
<div class="w-full py-6 bg-[#f1f3f6]">
    <div class="max-w-7xl mx-auto px-4">
        <div
            class="grid gap-4"
            style="grid-template-columns: repeat(4, minmax(0, 1fr));"
        >
            <div
                v-for="p in products"
                :key="p.id"
                class="bg-white border border-gray-200 hover:shadow-lg transition rounded-sm flex flex-col"
            >
                <!-- Image container filling full width and height -->
                <div
                    class="relative w-full aspect-[1/1] bg-gray-100 overflow-hidden"
                    @mouseenter="stopAutoSlide(p.id)"
                    @mouseleave="startAutoSlide(p)"
                >
                    <img
                        :src="p.image_urls[currentSlide[p.id] ?? 0]"
                        class="w-full h-full object-cover"
                        alt="product image"
                    />

                    <!-- Previous button -->
                    <button
                        v-if="p.image_urls.length > 1"
                        @click="prevSlide(p.id, p.image_urls.length)"
                        class="absolute left-1 top-1/2 -translate-y-1/2 bg-white/80 text-black px-2 py-1 text-xs"
                    >
                        ‹
                    </button>

                    <!-- Next button -->
                    <button
                        v-if="p.image_urls.length > 1"
                        @click="nextSlide(p.id, p.image_urls.length)"
                        class="absolute right-1 top-1/2 -translate-y-1/2 bg-white/80 text-black px-2 py-1 text-xs"
                    >
                        ›
                    </button>
                </div>

                <!-- Product info -->
                <div class="p-3 flex flex-col flex-1">
                    <h3 class="text-sm font-medium text-gray-800 truncate">
                        {{ p.name }}
                    </h3>

                    <p class="text-base font-semibold text-gray-900 mt-1">
                        ₹ {{ p.price }}
                    </p>

                    <button
                        @click="addToCart(p.id)"
                        class="add-to-cart-btn mt-auto w-full text-sm font-semibold py-2 bg-blue-600 text-white hover:bg-blue-700 transition rounded"
                    >
                        ADD TO CART
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
