# 🛒 Simple E-Commerce Shopping Cart (Laravel + Vue)

A simple, real-world e-commerce shopping cart application built using **Laravel** and **Vue.js**.  
The project demonstrates authenticated cart handling, order placement, and database-driven state management following Laravel best practices.

---

## 🚀 Features

- User authentication (Laravel Breeze)
- Product listing with image slider (Vue)
- Add to cart (AJAX, authenticated users only)
- Remove items from cart
- Database-based cart (no sessions/localStorage)
- Checkout & order creation
- Order details page (Vue)
- Clean MVC architecture
- Tailwind CSS UI

---

## 🧰 Tech Stack

| Layer | Technology |
|-----|-----------|
| Backend | Laravel |
| Frontend | Vue 3 (with Blade) |
| Styling | Tailwind CSS |
| Database | MySQL |
| Auth | Laravel Breeze |
| AJAX | Axios |
| Build Tool | Vite |

---

## 🗂️ Database Structure 

- `users`
- `products`
- `carts`
- `cart_items`
- `orders`
- `order_items`

> Price and product name snapshots are stored in `order_items` to preserve order history even if products change later.

---

## 🔐 Authentication Rules

- Only authenticated users can:
  - Add to cart
  - Remove items
  - Checkout
- Guests are redirected to the login page automatically.

---

## 🔄 Application Flow

1. User logs in
2. Browses products
3. Adds items to cart
4. Views cart page
5. Removes items if needed
6. Places order
7. Views order details

---

## 📁 Project Structure (Important Files)

```text
resources/
 └── js/
     └── components/
         ├── HomeProducts.vue
         ├── CartPage.vue
         └── OrderPage.vue

app/
 └── Http/
     └── Controllers/
         ├── CartController.php
         └── OrderController.php
