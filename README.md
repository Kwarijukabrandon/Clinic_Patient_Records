# Clinic Patient Records System

A simple database-driven web application built with **PHP (OOP-MySQLi)** and **MySQL** that
replaces a clinic's paper record books. It allows staff to register patients, view and search
records, update details and delete records.

## Features
- **Create** - register a new patient (HTML form, validation, prepared statement)
- **Read** - list all patients in a table with doctor names (JOIN), plus search
- **Update** - edit a patient with a pre-filled form (prepared statement)
- **Delete** - remove a patient with a confirmation prompt (prepared statement)
- Error handling with `try/catch` and `mysqli_sql_exception`
- Connection closed at the end of every request
- Output escaped with `htmlspecialchars()` to prevent XSS

## Database design
Database: `clinic_db`

| Table | Purpose |
|-------|---------|
| `doctors` | Doctors working at the clinic (parent table) |
| `patients` | Patient records, linked to `doctors` through `doctor_id` (foreign key) |

## Requirements
- XAMPP (or WAMP/LAMP) with PHP 7.4+ and MySQL/MariaDB

## Setup
1. Copy the `clinic_system` folder into `C:\xampp\htdocs\`
2. Start **Apache** and **MySQL** in the XAMPP Control Panel
3. Open `http://localhost/phpmyadmin`, click **Import**, choose `database.sql`, click **Go**
4. If your MySQL has a password, edit it in `config.php`
5. Open `http://localhost/clinic_system/` in your browser

## Project structure
```
clinic_system/
├── config.php            Database connection (OOP-MySQLi)
├── database.sql          Database + tables + sample data
├── index.php             READ
├── create.php            CREATE
├── update.php            UPDATE
├── delete.php            DELETE
├── includes/
│   ├── header.php
│   ├── footer.php        (closes the connection)
│   ├── functions.php     Helpers and validation
│   └── patient_form.php  Shared HTML form
├── assets/
│   └── style.css
└── README.md
```
