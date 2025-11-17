
CREATE DATABASE IF NOT EXISTS kriya_bali
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE kriya_bali;


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user','superadmin') NOT NULL DEFAULT 'user',
  verification_code VARCHAR(255) NULL,
  is_verified TINYINT(1) DEFAULT 0,
  verified_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO users (name, email, password, role, is_verified, verified_at) VALUES
('Admin', 'admin@kriya.local', '$2y$10$EskE7qZ3qmPPV/ZqQuuQ1e4AaY6Tlo5lOfpCYGsbYV2z999WCDZCC', 'admin', 1, NOW());


CREATE TABLE regions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

INSERT INTO regions (name) VALUES
('Denpasar'),
('Badung'),
('Gianyar'),
('Tabanan'),
('Bangli'),
('Klungkung'),
('Jembrana'),
('Buleleng'),
('Karangasem');


CREATE TABLE artisans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  region_id INT,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL
);

CREATE TABLE craft_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

INSERT INTO craft_categories (name) VALUES
('Ukiran Kayu'),
('Tenun'),
('Perak & Emas'),
('Seni Patung'),
('Aksesori'),
('Keramik');

CREATE TABLE crafts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  artisan_id INT,
  region_id INT,
  category_id INT,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(12,2),
  image_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (artisan_id) REFERENCES artisans(id) ON DELETE SET NULL,
  FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL,
  FOREIGN KEY (category_id) REFERENCES craft_categories(id) ON DELETE SET NULL
);

-- Table untuk menyimpan log penghapusan (audit)
CREATE TABLE IF NOT EXISTS deletes_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_name VARCHAR(100) NOT NULL,
  deleted_id INT NOT NULL,
  user_id INT NULL,
  deleted_by_name VARCHAR(150) NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
