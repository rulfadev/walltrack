# Walltrack

Walltrack is a simple and modern personal finance tracker built with CodeIgniter 4. It helps users manage personal finances by tracking wallets, income, expenses, transfers, categories, reports, and monthly budgets.

## About Walltrack

Walltrack is designed for personal finance management. This application allows users to record daily transactions, manage multiple wallets, monitor monthly budgets, and generate financial reports.

This project is focused on personal finance tracking and is not intended as a community or organization finance system.

## Features

### Authentication

- User registration
- User login
- User logout
- Protected dashboard pages
- Password hashing
- Safer session handling

### Dashboard

- Total wallet balance
- Monthly income summary
- Monthly expense summary
- Monthly transfer summary
- Monthly budget summary
- Budget usage warning
- Cashflow chart
- Expense by category chart
- Wallet overview
- Latest transactions

### Wallet Management

- Create wallet
- Edit wallet
- Delete wallet
- Set default wallet
- Automatic wallet balance update
- Supported wallet types:
  - Cash
  - Bank
  - E-Wallet
  - Saving

### Transaction Management

- Create transaction
- Edit transaction
- Delete transaction
- Filter transactions
- Pagination support
- Automatic wallet balance adjustment
- Supported transaction types:
  - Income
  - Expense
  - Transfer

### Transfer Between Wallets

Users can transfer money from one wallet to another. Transfer transactions do not affect income or expense totals because they only move money between wallets.

### Category Management

- Create category
- Edit category
- Delete category
- Category type validation
- Category icon support
- Category color support
- Supported category types:
  - Income
  - Expense

Income transactions can only use income categories. Expense transactions can only use expense categories. Transfer transactions do not require categories.

### Financial Reports

- Report page with filters
- Filter by date range
- Filter by wallet
- Filter by category
- Filter by transaction type
- Total income summary
- Total expense summary
- Net balance summary
- Total transfer summary
- Export to CSV
- Export to Excel-compatible `.xls`

### Monthly Budget

- Create monthly budget
- Edit monthly budget
- Delete monthly budget
- Budget by expense category
- Track spent amount
- Track remaining budget
- Budget usage percentage
- Budget status:
  - Safe
  - Almost Used Up
  - Over Budget

### Security Improvements

- CSRF protection
- Secure headers
- Ownership validation
- Server-side validation
- Soft delete support
- Safer AJAX delete
- Escaped output in views

## Tech Stack

- PHP 8.1+
- CodeIgniter 4
- MySQL / MariaDB
- Bootstrap 5
- Bootstrap Icons
- Chart.js
- JavaScript
- HTML
- CSS

## Requirements

Before installing this project, make sure your environment supports:

- PHP 8.1 or higher
- Composer
- MySQL or MariaDB
- CodeIgniter 4 required PHP extensions
- Apache, Nginx, or PHP built-in development server

## Installation

Clone the repository:

```bash
git clone https://github.com/rulfadev/walltrack.git
cd walltrack
```

Install dependencies:

```bash
composer install
```

Copy the environment file:

```bash
cp env .env
```

Set the application environment:

```ini
CI_ENVIRONMENT = development
```

Set the base URL:

```ini
app.baseURL = 'http://localhost:8080/'
```

Configure the database connection:

```ini
database.default.hostname = localhost
database.default.database = walltrack
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Run database migrations:

```bash
php spark migrate
```

Start the development server:

```bash
php spark serve
```

Open the application in your browser:

```text
http://localhost:8080
```

## Main Pages

| Page         | URL             |
| ------------ | --------------- |
| Landing Page | `/`             |
| Login        | `/login`        |
| Register     | `/signup`       |
| Dashboard    | `/dashboard`    |
| Wallets      | `/wallets`      |
| Transactions | `/transactions` |
| Categories   | `/categories`   |
| Reports      | `/reports`      |
| Budgets      | `/budgets`      |
| Profile      | `/profile`      |

## Database Modules

Main database modules:

```text
users
wallets
categories
transactions
budgets
```

## Transaction Types

| Type       | Description                  |
| ---------- | ---------------------------- |
| `income`   | Money received into a wallet |
| `expense`  | Money spent from a wallet    |
| `transfer` | Money moved between wallets  |

## Category Types

| Type      | Description                   |
| --------- | ----------------------------- |
| `income`  | Used for income transactions  |
| `expense` | Used for expense transactions |

Transfer transactions do not use categories.

## Budget Rules

Monthly budgets are only available for expense categories.

Budget usage is calculated from expense transactions in the selected month and year.

| Status         | Condition                           |
| -------------- | ----------------------------------- |
| Safe           | Usage is below 80%                  |
| Almost Used Up | Usage is between 80% and below 100% |
| Over Budget    | Usage is 100% or more               |

## Report Export

Financial reports can be exported to:

- CSV
- Excel-compatible `.xls`

The exported file follows the active filters on the report page.

## Development Commands

Clear cache:

```bash
php spark cache:clear
```

Run development server:

```bash
php spark serve
```

Run migrations:

```bash
php spark migrate
```

## Project Structure

Important folders:

```text
app/Config
app/Controllers
app/Database/Migrations
app/Models
app/Views
public/assets
writable
```

## License

This project is developed by RulfaDev as a personal finance tracker.
