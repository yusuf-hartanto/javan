# Sales

Aplikasi terdiri dari:
- **Backend**: Laravel & MySQL
- **Frontend**: Vue 3

---

## Prasyarat Sistem

Pastikan perangkat Anda telah terinstall:
- PHP >= 8.2
- Composer
- MySQL Database
- Node.js (v18+) & npm

---

## Panduan Instalasi

### 1. Setup Backend (Laravel)

1. Masuk ke direktori backend:
   ```bash
   cd backend
   ```

2. Install dependensi composer:
   ```bash
   composer install
   ```

3. Buat file `.env` dari template:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```

7. Jalankan server backend:
   ```bash
   php artisan serve
   ```
   Backend berjalan di: `http://127.0.0.1:8000`

---

### 2. Setup Frontend (Vue 3)

1. Buka terminal baru dan masuk ke direktori frontend:
   ```bash
   cd frontend
   ```

2. Install dependensi node:
   ```bash
   npm install
   ```

3. Buat file `.env` dari template:
   ```bash
   cp .env.example .env
   ```

4. Jalankan frontend development server:
   ```bash
   npm run dev
   ```
   Frontend berjalan di: `http://localhost:5173`