# Đề 13: Website Portfolio / Giới Thiệu Cá Nhân
**Sinh viên thực hiện:** DTC245180088  
**Repository:** [https://github.com/huytran-01/DTC245180088-Portfolio-De13.git](https://github.com/huytran-01/DTC245180088-Portfolio-De13.git)

---

## 📌 Giới Thiệu Đề Tài
Dự án xây dựng hệ thống **Website Portfolio cá nhân** dành cho sinh viên mã số **DTC245180088** (Đề 13), được đóng gói và triển khai theo kiến trúc Microservices / Containerization hiện đại:
- **Nginx Reverse Proxy:** Đóng vai trò là cổng vào duy nhất, hỗ trợ HTTPS với SSL tự ký trên cổng 443, tự động chuyển hướng HTTP (80) sang HTTPS, ẩn phiên bản Nginx (`server_tokens off`), tăng cường bảo mật với các Security Headers, và mở endpoint nội bộ `/stub_status` trên cổng 8081 cho Prometheus.
- **Backend Web Application:** Viết bằng PHP 8.2 trên nền Apache, đổi cổng lắng nghe sang **8080**, phân quyền thư mục cho `www-data` và chạy container dưới quyền **non-root** (`USER www-data`).
- **Database:** Sử dụng **MySQL 8.0**, tự động nạp dữ liệu mẫu khởi tạo qua file `mysql/init.sql`, quản lý thông tin bảo mật mật khẩu thông qua biến môi trường trong `.env`.
- **Monitoring & Metrics:** Tích hợp **Prometheus**, **Grafana**, **cAdvisor**, **Nginx Exporter**, **MySQL Exporter** để theo dõi hiệu năng container, web server và database.
- **Centralized Logging:** Thu thập và lưu trữ log tập trung thông qua **Loki** và **Promtail**.
- **System Hardening:** Thiết lập giới hạn tài nguyên (CPU, RAM), cơ chế `no-new-privileges`, cô lập network và bảo mật container.

---

## 🏗️ Kiến Trúc Hệ Thống

```
+-----------------------------------------------------------------------------------+
|                                  CLIENT BROWSER                                   |
+-----------------------------------------------------------------------------------+
       | (HTTP 80 -> HTTPS 443)                           | (Admin / Viewer)
       v                                                  v
+---------------------------------------------+    +--------------------------------+
|             NGINX REVERSE PROXY             |    |        GRAFANA DASHBOARD       |
| - SSL Port 443 | HTTP Port 80               |    |  Port 3000 (Metrics & Logs)    |
| - Security Headers | server_tokens off      |    +--------------------------------+
| - Metrics stub_status: Port 8081            |                    |
+---------------------------------------------+                    | Query
       |                                                           v
       | Proxy (8080)                             +---------------------------------+
       v                                          |        PROMETHEUS (Port 9090)   |
+---------------------------------------------+   | - Scrapes: cAdvisor, Nginx,     |
|      PORTFOLIO APP (Apache / PHP 8.2)       |   |            MySQL, Prometheus    |
| - Port 8080 | Non-root: USER www-data       |   +---------------------------------+
| - mysqli & pdo_mysql extensions             |                    ^
+---------------------------------------------+                    | Scrapes
       |                                          +---------------------------------+
       | TCP 3306                                 | EXPORTERS (cAdvisor, Nginx, DB) |
       v                                          +---------------------------------+
+---------------------------------------------+                    ^
|             MYSQL DATABASE 8.0              |                    | Push Logs
| - DB: portfolio_db                          |   +---------------------------------+
| - Init: mysql/init.sql (DTC245180088)       |   | LOKI (Port 3100) + PROMTAIL     |
+---------------------------------------------+   +---------------------------------+
```

---

## 📂 Cấu Trúc Thư Mục Dự Án

```text
DTC245180088-Portfolio-De13/
├── .env                              # Biến môi trường cấu hình mật khẩu mạnh MySQL
├── .gitignore                        # Loại bỏ các file tạm của hệ điều hành
├── docker-compose.yml                # Cấu hình khởi chạy toàn bộ dịch vụ (Stack hoàn chỉnh)
├── README.md                         # Tài liệu hướng dẫn cài đặt và thông tin đề tài
├── app/
│   ├── Dockerfile                    # Image php:8.2-apache, mysqli, non-root www-data, cổng 8080
│   ├── db.php                        # Kết nối MySQL bằng biến môi trường getenv()
│   ├── index.php                     # Giao diện Portfolio giới thiệu sinh viên DTC245180088
│   ├── admin.php                     # Trang quản trị thêm/xóa dự án
│   └── style.css                     # Giao diện Dark-mode hiện đại, responsive
├── mysql/
│   └── init.sql                      # Tạo bảng projects và thêm 2 dự án mẫu cho DTC245180088
├── nginx/
│   ├── nginx.conf                    # Cấu hình Reverse Proxy, SSL, Security Headers, stub_status
│   └── certs/
│       ├── selfsigned.crt            # Chứng chỉ SSL tự ký
│       └── selfsigned.key            # Private key SSL tự ký
├── prometheus/
│   ├── prometheus.yml                # Cấu hình Scrape jobs (cAdvisor, Nginx, MySQL, Prometheus)
│   ├── alert_rules.yml               # Bộ quy tắc cảnh báo sự cố container, tải CPU/RAM, MySQL, Nginx
│   └── grafana/
│       └── provisioning/
│           ├── datasources/          # Tự động nạp Data Source Prometheus và Loki
│           │   └── datasources.yml
│           └── dashboards/           # Tự động nạp Dashboard giám sát hệ thống
│               ├── dashboards.yml
│               └── overview_dashboard.json
└── loki/
    ├── loki-config.yml               # Cấu hình lưu trữ và index log tập trung
    └── promtail-config.yml           # Cấu hình Promtail thu thập log container và Nginx
```

---

## ⚙️ Cấu Hình Môi Trường (.env)

File `.env` được cấu hình mật khẩu bảo mật cao:
```env
# Cấu hình MySQL Database - DTC245180088
MYSQL_ROOT_PASSWORD=PortfolioRoot@Secure2026!#99
MYSQL_DATABASE=portfolio_db
MYSQL_USER=portfolio_user
MYSQL_PASSWORD=PortfolioUser@SecretPass2026!$

# Cấu hình kết nối ứng dụng
DB_HOST=mysql_db
DB_PORT=3306
```

---

## 🚀 Hướng Dẫn Cài Đặt & Chạy Ứng Dụng

### 1. Yêu Cầu Tiên Quyết
- Đã cài đặt [Docker](https://www.docker.com/) và [Docker Compose](https://docs.docker.com/compose/).
- Đã mở các cổng: `80`, `443`, `3000`, `8081`, `8082`, `9090`.

### 2. Khởi Động Toàn Bộ Hệ Thống
Mở terminal tại thư mục gốc của dự án và chạy:
```bash
docker compose up -d --build
```

Kiểm tra trạng thái các container đang chạy:
```bash
docker compose ps
```

---

## 🌐 Danh Mục Cổng & Dịch Vụ Truy Cập

| Dịch Vụ | Địa Chỉ Truy Cập | Tài Khoản (nếu có) | Mô Tả |
| :--- | :--- | :--- | :--- |
| **Trang Chủ Portfolio** | [https://localhost](https://localhost) | - | Portfolio giới thiệu sinh viên DTC245180088 & danh sách dự án |
| **Trang Quản Trị Admin** | [https://localhost/admin.php](https://localhost/admin.php) | - | Thêm dự án mới và Xóa dự án hiện có |
| **Grafana Dashboard** | [http://localhost:3000](http://localhost:3000) | `admin` / `Admin@DTC245180088` | Bảng điều khiển trực quan hóa Metrics và Logs |
| **Prometheus UI** | [http://localhost:9090](http://localhost:9090) | - | Giao diện truy vấn Prometheus Metrics |
| **cAdvisor Metrics** | [http://localhost:8082](http://localhost:8082) | - | Thống kê tài nguyên CPU/RAM/IO của Docker Container |
| **Nginx stub_status** | [http://localhost:8081/stub_status](http://localhost:8081/stub_status) | - | Endpoint nội bộ số liệu Nginx cho Exporter |

> *Lưu ý về SSL:* Do sử dụng chứng chỉ SSL tự ký (`selfsigned.crt`), trình duyệt có thể hiển thị cảnh báo bảo mật ban đầu. Hãy bấm **Nâng cao (Advanced)** -> **Tiếp tục truy cập localhost (Proceed to localhost)**.

---

## 🛡️ Kiểm Tra Các Tiêu Chuẩn Bảo Mật & Hardening

### 1. Kiểm tra Ẩn phiên bản Nginx & Security Headers
Chạy lệnh curl để kiểm tra Header phản hồi:
```bash
curl -kI https://localhost
```
Kết quả hiển thị:
- `Server: nginx` (không lộ số phiên bản cụ thể)
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `X-Content-Type-Options: nosniff`
- `Strict-Transport-Security: max-age=31536000; includeSubDomains`

### 2. Kiểm tra Quyền Non-Root trong Container App
Chạy lệnh kiểm tra người dùng đang thực thi bên trong container `portfolio_app`:
```bash
docker exec -it portfolio_app whoami
```
Kết quả trả về chính xác: `www-data` (không chạy dưới quyền root).

### 3. Kiểm tra Giới Hạn Tài Nguyên & Bảo Mật Docker Compose (Hardening)
- `security_opt: [no-new-privileges:true]` ngăn ngừa leo thang đặc quyền.
- `deploy.resources.limits` giới hạn trần CPU và RAM cho từng dịch vụ để chống DoS tài nguyên máy chủ.

---

## 📋 Lệnh Quản Trị Thường Dùng

- Xem log thời gian thực của toàn bộ hệ thống:
  ```bash
  docker compose logs -f
  ```
- Dừng toàn bộ hệ thống:
  ```bash
  docker compose down
  ```
- Dừng và xóa toàn bộ dữ liệu database & monitoring volumes:
  ```bash
  docker compose down -v
  ```
