# Password Hashing Integration – Flask API

> ⚠️ The base web application belongs to [BAGUS BUDI SATOTO](https://github.com/bagussatoto/Aplikasi-Pembayaran-SPP-Berbasis-Website).  
> This repository contains **only my contribution**: integrating a CLI-based password hashing system into the Flask backend, adding a registration form, and modifying the login form to use hashed passwords.

### Overview
<p align="justify">
  This project integrates a <b>SHA256-based password hashing and password strength validation system</b> into an existing web application.
  Originally, the hashing logic was implemented as a Command Line Interface (CLI) program (<code>main.py</code>).
  My work involved converting it into a <b>Flask API</b> (<code>auth_api.py</code>) and modifying the HTML templates for registration and login to ensure all passwords are hashed before being stored or verified.
</p>

### Key Features (Integration)
- Secure password hashing using **SHA256**.
- Password strength validation (minimum length, uppercase, number, symbol).
- Flask API endpoints:
  - `POST /hash` → Returns hashed password.
  - `POST /check_strength` → Validates password strength.
- New **registration form** integrated with API hashing.
- Modified **login form** to hash password before authentication.

### How It Works
1. User fills the registration form.
2. Password is sent to the Flask API `/check_strength` to validate complexity.
3. If valid, password is sent to `/hash` to generate a SHA256 hash.
4. Hashed password is stored in the MySQL database.
5. During login, the entered password is hashed again and compared with the stored hash.

### Tech Stack
<p>
  <img src="https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white" />
  <img src="https://img.shields.io/badge/Flask-000000?style=for-the-badge&logo=flask&logoColor=white" />
  <img src="https://img.shields.io/badge/SHA256-4CAF50?style=for-the-badge" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
</p>

### Demo (Presentation)
For a full walkthrough of the integration process and functionality, see the presentation below:  
[View Demo Presentation](https://drive.google.com/file/d/1ilMdENIIHl9P3nmQdqmjUhZ9ZM81y4KA/view?usp=drive_link)
