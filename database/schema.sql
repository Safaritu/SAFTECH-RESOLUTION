CREATE DATABASE IF NOT EXISTS saftech_resolution
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE saftech_resolution;

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    project_url VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(150) NOT NULL,
    client_phone VARCHAR(30),
    service_id INT,
    preferred_date DATE,
    message TEXT,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

CREATE TABLE quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    project_interest VARCHAR(150),
    message TEXT,
    status ENUM('new','contacted','closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Extended columns for dynamic cards (added later)
ALTER TABLE projects
  ADD COLUMN category VARCHAR(30) NOT NULL DEFAULT 'portfolio' AFTER title,
  ADD COLUMN category_label VARCHAR(50) NULL AFTER category,
  ADD COLUMN icon VARCHAR(50) NULL AFTER category_label,
  ADD COLUMN tags VARCHAR(255) NULL AFTER icon,
  ADD COLUMN color_theme VARCHAR(30) NULL DEFAULT 'sky' AFTER tags,
  ADD COLUMN is_live TINYINT(1) DEFAULT 0 AFTER color_theme,
  ADD COLUMN sort_order INT DEFAULT 0 AFTER is_live;

ALTER TABLE services
  ADD COLUMN icon VARCHAR(50) NULL AFTER description,
  ADD COLUMN color_theme VARCHAR(30) NULL DEFAULT 'sky' AFTER icon,
  ADD COLUMN sort_order INT DEFAULT 0 AFTER color_theme;
