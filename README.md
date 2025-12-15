# 🚀 PBL-IVSS

<div align="center">

<!-- TODO: Add project logo -->

[![GitHub stars](https://img.shields.io/github/stars/RyuuShiro30/PBL-IVSS?style=for-the-badge)](https://github.com/RyuuShiro30/PBL-IVSS/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/RyuuShiro30/PBL-IVSS?style=for-the-badge)](https://github.com/RyuuShiro30/PBL-IVSS/network)
[![GitHub issues](https://img.shields.io/github/issues/RyuuShiro30/PBL-IVSS?style=for-the-badge)](https://github.com/RyuuShiro30/PBL-IVSS/issues)
[![GitHub license](https://img.shields.io/github/license/RyuuShiro30/PBL-IVSS?style=for-the-badge)](LICENSE) <!-- TODO: Add actual license file if available -->

**A multi-role web portal for comprehensive laboratory information and member management.**

[Live Demo](https://demo-link.com) <!-- TODO: Add live demo link if available --> |
[Documentation](https://docs-link.com) <!-- TODO: Add documentation link if available -->

</div>

## 📖 Overview

Laboratorium Intelligent Vision and Smart System (IVSS) memiliki berbagai kegiatan penelitian, publikasi ilmiah, serta dokumentasi aktivitas yang perlu disampaikan kepada mahasiswa dan sivitas akademika. Namun, website yang ada belum mampu menyajikan informasi tersebut secara terstruktur dan terdokumentasi dengan baik. Ketiadaan sistem manajemen konten serta desain web yang belum modern juga menyulitkan pembaruan informasi secara rutin. Kondisi ini mendorong perlunya pengembangan ulang website yang lebih informatif, terstruktur, dan mudah dikelola sebagai media resmi publikasi dan dokumentasi laboratorium.

## ✨ Features

👤 User

Access facilities, rooms, research, news, galleries, and member profiles (Mahasiswa & Dosen).

Navigate through Home, About, News, Research, and Members pages.

🧪 Lab Administrator

Manage facilities, research, publications, galleries, members, and administrator accounts.

Monitor system activity, access dashboard, and manage personal profile.

📰 News Administrator

Manage laboratory news, admin accounts, dashboard access, and personal profile.

👨‍🔬 Head of Laboratory

View laboratory dashboard, activity history, and manage personal profile.

## 🛠️ Tech Stack

**Backend:**
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)

**Database:**
[![Postgres](https://img.shields.io/badge/Postgres-%23316192.svg?logo=postgresql&logoColor=white)](#)

**Frontend:**
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

**Browser:**
[![Google Chrome](https://img.shields.io/badge/Google%20Chrome-4285F4?logo=GoogleChrome&logoColor=white)](#)
[![Microsoft Edge](https://custom-icon-badges.demolab.com/badge/Microsoft%20Edge-2771D8?logo=edge-white&logoColor=white)](#)
[![Firefox](https://img.shields.io/badge/Firefox-FF7139?logo=firefoxbrowser&logoColor=white)](#)
**Web Server:**
- Apache HTTP Server


## 🚀 Quick Start

### Prerequisites
Before you begin, ensure you have the following installed on your system:
-   **Web Server**: Apache
-   **PHP**: Version 7.4 or higher
-   **Database Server**: PostgresSQL

### Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/RyuuShiro30/PBL-IVSS.git
    cd PBL-IVSS
    ```

2.  **Place project files on your web server**
    Move the cloned project directory into your web server's document root (e.g., `htdocs` for Apache, `www` for Nginx).

3.  **Database setup**
    ### Option 1: Using DBeaver
    1. Open DBeaver and connect to your PostgreSQL server.
    2. Create a new database named `IVSS`.
    3. Right-click the database → **Tools** → **Execute SQL Script**.
    4. Select the SQL file (e.g., `database/schema.sql`) and execute it.

    ### Option 2: Using pgAdmin
    1. Open pgAdmin and connect to your PostgreSQL server.
    2. Create a new database named `IVSS`.
    3. Right-click the database → **Restore**.
    4. Choose the SQL file and restore it.

4.  **Configuration**
    *   Locate the database connection file. This is typically named `config.php` or `db_connect.php` and might be located in the root or a common `includes/` directory.
    *   Open this file and update the database connection details (hostname, username, password, database name) to match your setup.
    ```php
    <?php
    // Example: Update these values in your config file
    $host = "localhost";
    $port = "5432";
    $dbname = "IVSS";
    $user = "postgres";
    $password = "your_password";

    try {
        $pdo = new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    } catch (PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
    ```
    <!-- TODO: Provide actual path to the config file once detected -->

5.  **Access the application**
    Open your web browser and navigate to the URL where you placed the project (e.g., `http://localhost/PBL-IVSS` or `http://your-domain.com/PBL-IVSS`).

## 📁 Project Structure

```
PBL-IVSS/
├── admin-berita/        # Admin panel for news/article management
├── admin-lab/           # Admin panel for laboratory resource management
├── anggota/             # Section for member (anggota) management and display
├── kepala-lab/          # Interface for lab head specific functionalities
├── user/                # General user interface and functionalities
└── README.md            # Project README file
```

## ⚙️ Configuration

### Database Configuration
Database connection settings (hostname, username, password, database name) are typically managed within a central PHP file. Please refer to the `Installation` section for details on where to configure these.

## 🚀 Deployment

This application is designed for traditional web server deployment. After setting up the prerequisites and following the installation steps, the application will be accessible via your web server.

## 🤝 Contributing

We welcome contributions to PBL-IVSS! If you'd like to contribute, please fork the repository and submit a pull request with your enhancements.

### Development Setup for Contributors
To set up your development environment, follow the `Quick Start` instructions. Ensure your local web server environment (Apache/Nginx, PHP, MySQL) is correctly configured.

## 📄 License

This project is licensed under the [LICENSE_NAME](LICENSE) - see the LICENSE file for details. <!-- TODO: Specify actual license and create LICENSE file -->

## 🙏 Acknowledgments

-   **PHP**: For powering the backend logic.
-   **PostgresSQL**: For database management.
-   **Apache**: For serving the web application.

---

<div align="center">

**⭐ Star this repo if you find it helpful!**

Made with ❤️ by [RyuuShiro30](https://github.com/RyuuShiro30)

</div>