-- Khoi tao co so du lieu Portfolio - DTC245180088
CREATE DATABASE IF NOT EXISTS `portfolio_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `portfolio_db`;

-- Tao bang projects
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `tech_stack` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Them 2 du an mau cua sinh vien DTC245180088
INSERT INTO `projects` (`title`, `description`, `tech_stack`, `created_at`) VALUES
(
    'He thong Quan ly Thu vien Thong minh - DTC245180088',
    'He thong quan ly va muon tra sach truc tuyen, tich hop tra cuu danh muc sach, quan ly doc gia va tao bao cao thong ke tu dong.',
    'PHP 8.2, MySQL, Bootstrap 5, Docker, Apache',
    NOW() - INTERVAL 5 DAY
),
(
    'Cong Thong tin Sinh vien & Dang ky Tin chi - DTC245180088',
    'He thong tra cuu diem, thoi khoa bieu va dang ky hoc phan chiu tai cao, tich hop Nginx Reverse Proxy, bao mat SSL/TLS va giam sat Prometheus.',
    'PHP, MySQL, Nginx, Docker Compose, SSL, Prometheus',
    NOW() - INTERVAL 2 DAY
);
