<div align="center">

# 🏠 EasyColoc

### _Redefining Shared Living through Collective Finance Management_

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge)](LICENSE)

[**Live Presentation**](https://sahhoutiamine.github.io/easycoloc-Presentation/)

---

**EasyColoc** is a premium, all-in-one platform designed to simplify the complexities of roomsharing (colocation). From tracking coffee expenses to calculating monthly rent settlements, it ensures that living together remains about the friendship, not the finances.

</div>

---

## 📽️ Project Presentation

> [!IMPORTANT]
> **View the official project presentation here:**
> [🔗 Click to View Presentation](https://sahhoutiamine.github.io/easycoloc-Presentation/)

---

## ✨ Key Features

### 👥 Colocation Management

- **Smart Groups**: Create or join multiple colocations with a single account.
- **Invitation System**: Securely invite new members via email tokens (powered by Mailtrap).
- **Membership Tracking**: Automatic history of who joined when, ensuring fair expense splitting.

### 💰 Financial Intelligence

- **Collective Expenses**: Log expenses with categories (Rent, Food, Utilities) and clear timestamps.
- **Settlement Algorithm**: A robust "Zero-Sum" logic that automatically calculates "who owes whom" the minimum number of transactions.
- **Instant Balances**: Real-time visibility into your current standing within the group.

### 🛡️ Governance & Security

- **Reputation System**: Earn points for timely settlements and lose them for leaving debts behind.
- **Admin Dashboard**: Comprehensive control for platform administrators to manage users, categories, and moderation.
- **Role-Based Access**: Granular permissions for Owners, Members, and Admins.

### 🔔 Smart Notifications

- **In-App Alerts**: Stay updated on new invitations, group changes, and settlement requests.

---

## 🛠️ Technical Stack

- **Core Framework**: [Laravel 11](https://laravel.com/) (The PHP Framework for Web Artisans)
- **Frontend Architecture**:
    - **Blade Templates**: For powerful server-side rendering.
    - **Tailwind CSS**: Utility-first styling for a sleek, modern UI.
    - **Alpine.js**: Lightweight interactivity for modals and dropdowns.
- **Development & Tooling**:
    - **MySQL**: Relational database for complex financial relationships.
    - **Vite**: Ultra-fast asset bundling and HMR.
    - **Artisan**: Command-line interface for internal management.
    - **Mailtrap**: SMTP testing for the invitation system.

---

## 🚀 Getting Started

Follow these steps to get your local environment up and running.

### Prerequisites

- **PHP** >= 8.2
- **Composer** (PHP Package manager)
- **Node.js & NPM** (For frontend assets)
- **MySQL/PostgreSQL**

### Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/sahhoutiamine/EasyColoc-Colocation-Management-Platform.git
    cd EasyColoc
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Edit `.env` to configure your database and Mailtrap credentials._

4. **Database & Assets**

    ```bash
    php artisan migrate --seed
    npm run build
    ```

5. **Start the Server**
    ```bash
    php artisan serve
    ```
    Visit `http://localhost:8000` to see your app in action!

---

## 📖 Documentation

For a deep dive into the architecture, database schema, and the balance algorithm, please refer to our technical documentation:

- [Technical Documentation](docs/TECHNICAL_DOCUMENTATION.md)
- [Project Overview](README.md) _(You are here)_

---

## 🤝 Contributing

We welcome contributions from the community!

1. Fork the Project.
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`).
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the Branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<div align="center">
  Developed with ❤️ for better shared living.
</div>
