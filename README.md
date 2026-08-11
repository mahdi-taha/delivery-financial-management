# Delivery Financial Management System

A custom financial and operational management system built for a delivery company to replace manual calculations, paper records, and spreadsheet-based workflows with one centralized web application.

The system was designed around the company's existing business process, with a focus on driver settlements, financial tracking, multi-currency operations, reporting, and traceability.

## Overview

The Delivery Financial Management System was developed to solve a real operational problem.

The company previously relied on manual calculations and scattered records to manage delivery orders, driver settlements, collections, and financial transactions.

Instead of forcing the business to adapt to a generic management system, the application was designed around its existing workflow and calculation rules.

It provides a centralized platform for managing daily operations while maintaining organized and searchable financial records.

## Key Features

### Order Management

Centralized order records used throughout the company's financial and settlement workflow.

### Driver Settlements

Structured settlement processing based on the company's business rules, reducing repeated manual calculations and keeping settlement records organized.

### Collections

Management of collections related to delivery operations and driver payments.

### Financial Transactions

Tracks incoming and outgoing financial movements and provides an organized transaction history.

### Drivers & Partners

Management of drivers and partner companies involved in the delivery workflow.

### Multi-Currency & Exchange Rates

Supports operations involving multiple currencies and maintains the exchange rates used for financial calculations and records.

### Reports

Provides financial and operational reporting for areas including:

- Company activity
- Drivers
- Partners
- Financial transactions

Reports provide a clearer view of business activity and historical financial information.

### Dashboard

A centralized dashboard provides an overview of important operational and financial information.

### Activity & Audit Logs

Records user activity throughout the system to provide traceability and accountability for actions performed within the application.

### Users, Roles & Permissions

Includes user management and role-based permissions to control access to different areas and operations within the system.

### Company Configuration

Administrative settings allow important company information, currencies, payment methods, users, and other system configuration to be managed from within the application.

## Workflow

The application's main operational flow connects:

**Orders → Settlements → Financial Transactions → Reports**

This allows information to move through the system without repeatedly rewriting or recalculating the same financial data.

## Built With

- Laravel
- PHP
- MySQL
- Blade
- JavaScript
- Bootstrap
- HTML
- CSS
- Vite

## Localization

The application supports both:

- English
- Arabic

with appropriate interface localization and RTL support.

## Installation

### Requirements

- PHP
- Composer
- MySQL
- Node.js and npm
- Laravel-compatible web server

### 1. Clone the repository

```bash
git clone https://github.com/mahdi-taha/delivery-financial-management.git
cd delivery-financial-management
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Configure the environment

Create the environment file:

```bash
cp .env.example .env
```

Configure your database and application settings inside `.env`.

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Run database migrations

```bash
php artisan migrate
```

### 7. Build frontend assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 8. Start the application

```bash
php artisan serve
```

## Demo

A live demonstration of the system is available through my portfolio.

The public demo uses dedicated demo data and credentials rather than production business data.

## Project Background

This application was developed for a real delivery business based on its operational and financial requirements.

The project involved understanding the company's existing workflow, translating its calculation rules into application logic, designing the database structure, developing the backend functionality, and building the interface used for daily operations.

## Author

**Mahdi Taha**

Full-Stack Web Developer / Laravel Developer
