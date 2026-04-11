# 🏪 Kirana Shop Manager

**Smart Inventory Management System for Small Kirana Shops**

A simple, user-friendly web application designed specifically for small grocery shop owners to manage inventory, track sales, and get smart insights.

---
## 📝 Project Description

Kirana Shop Manager is a web-based inventory management system designed for **small grocery shop owners (Kirana Stores)**.
USN:23BTRCN032

## 🎯 Features

### Core Features
✅ **Product Management** - Add, view, and manage products  
✅ **Daily Sales Entry** - Record sales quickly (2 clicks)  
✅ **Low Stock Alerts** - Automatic alerts when stock runs low  
✅ **Top Selling Products** - See which items sell the most  

### Smart Features
✅ **Running Out Soon Prediction** - Know when items will finish (based on sales speed)  
✅ **Dead Stock Detector** - Find slow-moving items  
✅ **Auto Combo Suggestions** - "When they buy bread, suggest butter"  
✅ **Customer Request Tracker** - Track what customers ask for  
✅ **Smart Restock Generator** - Daily list of what to buy  

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript (Responsive Design)
- **Backend:** PHP (Server-side logic)
- **Database:** MySQL (Data storage)
- **Server:** Apache (XAMPP)

---

## 📋 System Requirements

- XAMPP (Apache + MySQL + PHP)
- Windows/Mac/Linux
- Any modern browser (Chrome, Firefox, Edge)

---

## 🚀 How to Install & Run

### 1. Install XAMPP
- Download from: https://www.apachefriends.org/
- Install with default settings

### 2. Create Project Folder

### 3. Add Files
- Copy all files from this repository into the folder above

### 4. Create Database
- Open: `http://localhost/phpmyadmin`
- Create new database: `kirana_shop`
- Run the SQL schema (provided in setup)

### 5. Start XAMPP
- Open XAMPP Control Panel
- Click **Start** next to Apache
- Click **Start** next to MySQL

### 6. Access Application
- Open browser
- Go to: `http://localhost/kirana-shop/`
- Register & Login

---

## 📊 Database Schema

### Tables Created:
1. **users** - Shop owner information
2. **products** - Product inventory
3. **sales** - Daily sales records
4. **customer_requests** - Customer demands tracking

---

## 👤 Default Login (for testing)

**Username:** `admin`  
**Password:** `password123`

*(Create new account on registration page)*

---


## 📸 Features Breakdown

### Dashboard 📊
- Today's revenue at a glance
- Number of items sold
- Total products in stock
- Low stock alerts count

### Products 📦
- Add new products
- View all products with stock levels
- Track cost price vs selling price
- Set minimum stock threshold

### Daily Sales 🧾
- Select product
- Enter quantity sold
- Auto-calculates total amount
- Shows today's sales history

### Low Stock Alerts 🔴
- Red alert for items below minimum stock
- Shows only X packets left
- Critical for reordering

### Running Out Soon ⏰
- Predicts when items will finish
- Based on sales velocity (how fast they sell)
- "Will finish in 3 days"

### Best Sellers 🔥
- Top 10 selling products (last 30 days)
- Shows total units sold
- Shows total revenue per item

### Slow-Moving Items 🐢
- Items not selling well
- Suggestions: Apply discount, bundle deals
- Helps unblock stuck money

### Combo Suggestions 🤝
- Auto-suggests items to sell together
- "Bread → butter, milk"
- Increases average order value

### Customer Requests 🤔
- Log when customer asks for something you don't have
- Track demand signals
- Know what to stock next

### Smart Restock 📋
- Daily list of what to buy
- Shows how much to buy
- Total cost required
- Items prioritized by urgency

---

## 💡 How It Helps Shopkeepers

1. **Save Time** - No manual inventory tracking
2. **Never Run Out** - Get alerts before stock finishes
3. **Increase Profit** - Know what sells, what doesn't
4. **Smart Ordering** - Know exactly what to buy
5. **Understand Customers** - Track customer demands
6. **Smart Combos** - Sell more with recommendations

---

---
## Future Enhancements
Possible features to add:

Customer payment records
Supplier management
Profit/loss reports
Mobile app
Cloud backup
Multi-shop management
