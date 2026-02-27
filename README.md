# 🌱 EcoPay

**EcoPay** is an IoT-powered smart recycling reward platform that detects plastic and metal bottles and instantly converts them into digital coins. It integrates hardware (ESP32 + Raspberry Pi) with a real-time web dashboard to promote sustainability through digital incentives.

---

## 🚀 Overview

EcoPay is a smart recycling ecosystem that combines:

- 📡 **ESP32 Microcontroller** – Sensor control & bottle detection  
- 🖥 **Raspberry Pi** – Edge processing & secure API communication  
- 🌐 **Laravel 12 Backend** – Business logic & authentication  
- 🗄 **Supabase PostgreSQL** – Cloud database with realtime updates  
- ⚛️ **React Landing Page** – Futuristic UI/UX  
- 🎨 **Tailwind CSS** – Modern interface styling  

When a bottle is inserted into the EcoPay device, the system detects the material, updates the database, and reflects changes instantly in the dashboard.

---

## 🔌 IoT Architecture

### 🧠 Hardware Layer

**ESP32**
- Detects plastic or metal bottles
- Controls sensors and I/O
- Sends detection signal to Raspberry Pi

**Raspberry Pi**
- Processes bottle detection logic
- Sends secure API requests to Laravel backend
- Can host edge services if required

---

### 🌐 Software Layer

- Laravel 12 (PHP 8.2)
- Supabase PostgreSQL (Realtime enabled)
- React (Landing Page)
- Blade + Tailwind Dashboard
- Chart.js (Analytics)

---

## 🔄 System Data Flow

1. User inserts bottle into EcoPay device.
2. ESP32 detects bottle type.
3. Raspberry Pi validates detection.
4. Raspberry Pi sends secure API request to Laravel.
5. Laravel updates:
   - ecopay_accounts
   - ecopay_activity_logs
6. Supabase triggers realtime update.
7. Dashboard updates instantly.

---

## ✨ Features

- ♻️ Plastic & Metal Bottle Detection
- 💰 Instant Coin Rewards
- 📊 Real-Time Dashboard Updates
- 📈 Analytics with Graphs
- 🧾 Activity Logs
- ⚙️ Account Settings
- 🔐 Secure Authentication (Laravel Breeze)
- ⚡ Supabase Realtime Integration
- ⚛️ Futuristic React Landing Page

---

## 🛠 Tech Stack

### Backend
- Laravel 12
- PHP 8.2
- Supabase PostgreSQL
- Laravel Breeze Authentication

### Frontend
- React
- Blade
- Tailwind CSS
- Vite
- Chart.js

### IoT Hardware
- ESP32
- Raspberry Pi
- Metal detection sensor
- Plastic detection sensor
- Optional: LED indicators / buzzer / relay module

---

## 📂 Project Structure

    EcoPay/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    │   └── EcoPay.png
    ├── resources/
    │   ├── views/
    │   └── js/
    ├── routes/
    ├── storage/
    └── vite.config.ts

---

## ⚙️ Installation Guide

### 1️⃣ Clone Repository

    git clone https://github.com/your-username/ecopay.git
    cd ecopay

---

### 2️⃣ Install Dependencies

    composer install
    npm install

---

### 3️⃣ Configure Environment

Copy environment file:

    cp .env.example .env

Update database connection:

    DB_CONNECTION=pgsql
    DB_HOST=your-supabase-host
    DB_PORT=5432
    DB_DATABASE=postgres
    DB_USERNAME=postgres
    DB_PASSWORD=your-password
    DB_SSLMODE=require

Add Supabase frontend variables:

    VITE_SUPABASE_URL=your-project-url
    VITE_SUPABASE_ANON_KEY=your-anon-key

Generate application key:

    php artisan key:generate

---

### 4️⃣ Run Migrations

    php artisan migrate

---

### 5️⃣ Start Development

Start backend:

    php artisan serve

Start frontend:

    npm run dev

Visit:

    http://127.0.0.1:8000

---

## 📡 Supabase Realtime Setup

Run this in Supabase SQL Editor:

    alter table public.ecopay_accounts replica identity full;
    alter publication supabase_realtime add table public.ecopay_accounts;

    alter table public.ecopay_activity_logs replica identity full;
    alter publication supabase_realtime add table public.ecopay_activity_logs;

---

## 🔐 IoT API Endpoint

Example API used by Raspberry Pi:

    POST /api/ecopay/deposit

Example payload:

    {
      "email": "user@example.com",
      "bottle_type": "plastic",
      "coins_earned": 1,
      "description": "Plastic bottle detected via ESP32"
    }

The backend will:
- Update bottle counters
- Add coins
- Insert activity log
- Trigger realtime UI update

---

## 📊 Dashboard Pages

- /dashboard – Live stats & activity logs
- /analytics – Graphs & performance data
- /settings – Account & EcoPay management

---

## 🌍 Vision

EcoPay bridges IoT hardware and modern web technology to incentivize sustainable behavior through smart digital rewards.

---

## 🛡 Security

- Service role keys are never exposed to frontend
- IoT communication secured via API key
- Authentication handled by Laravel Breeze
- All database operations validated server-side

---

## 📄 License

EcoPay is developed as an IoT-enabled sustainability startup platform.

---

### 🌱 Build Smart. Recycle Better. Earn Sustainably.