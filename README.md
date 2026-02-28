# Perfume Store Web Application

## Overview
This is a PHP-based web application for managing a perfume store.  
It supports managing products, orders, customers, and staff.  
The application uses **phpMyAdmin / MySQL** as the backend database.

## Features
- User authentication: admin and non-admin users
- Product management
- Order management and order details management
- Customer management
- Staff management
- Invoice generation
- Responsive layout using HTML/CSS/JS

## User Accounts (Demo)
- **Admin**: S002 / admin123  
- **Non-admin staff**: 123123 / staff123

## User Accounts and Permissions
### Non-Admin
- Products: Read only
- Product Details: Read only
- Staffs: No access
- Customers: No access
- Orders: Create and Read
- Order Details: Create, Read, Update, Delete
- Invoice: Read only

### Admin
- Products: Create, Read, Update, Delete
- Product Details: Read only
- Staffs: Create, Read, Update, Delete
- Customers: Create, Read, Update, Delete
- Orders: Create, Read, Update, Delete
- Order Details: Create, Read, Update, Delete
- Invoice: Read only

> **Do not upload real database credentials.**  
> Use `database.example.php` as a template and fill in your phpMyAdmin/MySQL credentials locally.

## Project Structure
myPT4/
│
├─ .gitignore
├─ index.php
├─ login.php
├─ login_process.php
├─ logout.php
├─ invoice.php
├─ orders.php
├─ orders_crud.php
├─ orders_details.php
├─ orders_details_crud.php
├─ products.php
├─ products_crud.php
├─ products_details.php
├─ product_modal.php
├─ customers.php
├─ customers_crud.php
├─ staffs.php
├─ staffs_crud.php
├─ nav_bar.php
├─ css/
├─ js/
├─ products/
├─ logo.png
├─ database.example.php (template)
└─ README.md


## How to Setup
1. Copy `database.example.php` to `database.php` and fill in your local phpMyAdmin/MySQL credentials.
2. Import the provided database SQL into your MySQL server via phpMyAdmin.
3. Open `index.php` in a browser via a local server (e.g., XAMPP, WAMP). or via the demo server at:  
http://lrgs.ftsm.ukm.my/users/a199441
4. Login using the demo accounts above.


## License
This project is for learning and demonstration purposes.
