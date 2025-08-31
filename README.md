# English Language

# Transaction Records

This project is a web application for recording transactions using the CodeIgniter framework.

## Features

- Adding a new transaction
- Adding a new transaction category
- Viewing the transaction list
- Deleting a transaction
- Deleting a transaction category

## Requirements

- PHP >= 7.2
- Composer
- Web server (Apache, Nginx, etc.)
- MySQL database

## Installation

1. Clone this repository:
```bash
git clone https://github.com/RulfaDev/walltrack.git
```

2. Navigate to the project directory:
```bash
cd walltrack
```

3. Install dependencies using Composer:
```bash
composer install
```

4. Copy the `env` file to `.env` and adjust the database configuration:
```bash
cp env .env
```

5. Create a database:
```bash
php spark db:create walltrack
```

6. Database migration:
```bash
php spark migrate
```

7. Create a seeder:
```bash
php spark db:seed
```

8. Run the development server:
```bash
php spark serve
```

9. Access the application in a browser via the URL:
```
http://localhost:8080
```

10. Access your account with:
```
email: user@rulfadev.my.id
password: user123
```

## Usage

1. Open the application in a browser.
2. Register your new account.
3. Log in to your account.
4. Add a new transaction using the form provided.
5. View or delete transactions from the transaction list.

## Contributions

If you would like to contribute to this project, please fork this repository and create a pull request with your changes.

## License

This project is licensed under the [MIT License](LICENSE).

# Bahasa Indonesia

# Catatan Transaksi

Proyek ini adalah aplikasi web untuk mencatat transaksi menggunakan framework CodeIgniter.

## Fitur

- Menambahkan transaksi baru
- Menambahkan kategori transaksi baru
- Melihat daftar transaksi
- Menghapus transaksi
- Menghapus kategori transaksi

## Persyaratan

- PHP >= 7.2
- Composer
- Web server (Apache, Nginx, dll.)
- Database MySQL

## Instalasi

1. Clone repositori ini:
    ```bash
    git clone https://github.com/RulfaDev/walltrack.git
    ```

2. Masuk ke direktori proyek:
    ```bash
    cd walltrack
    ```

3. Instal dependensi menggunakan Composer:
    ```bash
    composer install
    ```

4. Salin file `env` menjadi `.env` dan sesuaikan konfigurasi database:
    ```bash
    cp env .env
    ```

5. Membuat database:
    ```bash
    php spark db:create walltrack
    ```

6. Migrasi database:
    ```bash
    php spark migrate
    ```

7. Buat seeder:
    ```bash
    php spark db:seed
    ```

8. Jalankan server pengembangan:
    ```bash
    php spark serve
    ```

9. Akses aplikasi di browser melalui URL:
    ```
    http://localhost:8080
    ```

10. Akses akun dengan:
    ```
    email : user@rulfadev.my.id
    kata sandi : user123
    ```

## Penggunaan

1. Buka aplikasi di browser.
2. Register akun baru anda.
3. Login ke akun anda.
4. Tambahkan transaksi baru melalui formulir yang disediakan.
5. Lihat, atau hapus transaksi dari daftar transaksi.

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan fork repositori ini dan buat pull request dengan perubahan Anda.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
