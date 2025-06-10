# 🔗 Mini URL

MiniURL is a lightweight, fast, and user-friendly URL minimizing service designed to simplify long URLs into compact, easy-to-share links. It aims to enhance user experience by offering efficient redirection, analytics, and link management in a minimalistic interface.

---
## 🚀 Features
- 🔗URL Minimizing – Instantly convert long URLs into short, manageable links.
- 🏷️Custom Aliases – Option to create personalized short links (e.g., miniurl/alias).
---

## 🧰 Tech Stack
- **Backend**: PHP
- **Frontend**: HTML, JQuery
- **UI**: Bootstrap
- **Data Base**: Mysql
---

## 🛠️ Setup

### 1. Clone the Repository inside C:\xampp\htdocs

```bash
git clone https://github.com/chitalaR/miniURL.git
```

### 2. Modify .httaccess insde C:\xampp\htdocs

```bash
RewriteEngine On
RewriteBase /miniurl/

# If the requested file or directory does not exist
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Redirect all requests to index.php
RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]
```

### 3. Create miniurl Database

```bash
CREATE DATABASE miniurl
```

### 4. Create urls Table

```bash
CREATE TABLE urls (
    id INT PRIMARY KEY AUTO_INCREMENT , 
    alias TEXT NOT NULL UNIQUE , 
    url TEXT NOT NULL
);
```

### 5. Update config.php

```bash
$servername = "server_name";
$username = "user_name";
$password = "password";
$dbname = "miniurl";
$domainUrl = "your-domain/miniurl/";
```