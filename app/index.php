<?php
require_once __DIR__ . '/db.php';

$projects = [];
$total_projects = 0;
$db_status = "Đang kết nối...";

if ($db_connected && $conn) {
    $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
    if ($result) {
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        $total_projects = count($projects);
        $db_status = "Đã kết nối MySQL";
    }
} else {
    $db_status = "Chưa kết nối MySQL: " . ($db_error_message ?? 'Không xác định');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio cá nhân sinh viên DTC245180088 - Đề 13: Website Portfolio / Giới thiệu Cá nhân. Triển khai Docker, Nginx, PHP 8.2, MySQL.">
    <title>Portfolio Sinh Viên - DTC245180088 | Đề 13</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- Navbar -->
    <header class="navbar">
        <a href="index.php" class="nav-brand" id="brand-link">
            <span class="brand-badge">DTC245180088</span>
            <span>Portfolio • Đề 13</span>
        </a>
        <nav class="nav-links">
            <a href="#about" class="nav-link">Giới thiệu</a>
            <a href="#projects" class="nav-link">Dự án</a>
            <a href="#architecture" class="nav-link">Hạ tầng</a>
            <a href="admin.php" class="btn btn-primary" id="btn-admin-nav">⚙️ Quản trị Admin</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="about">
        <div class="hero-pill">
            <span class="dot"></span>
            <span>Sinh viên DTC245180088 • Khóa K24</span>
        </div>
        <h1>Xin chào, tôi là <span class="gradient">DTC245180088</span></h1>
        <p class="hero-subtitle">
            Kỹ sư Công nghệ Thông tin đam mê phát triển phần mềm, kiến trúc vi dịch vụ (Microservices), 
            và tối ưu hóa hạ tầng điện toán đám mây với Docker, Nginx Reverse Proxy và CI/CD.
        </p>
        <div class="hero-actions">
            <a href="#projects" class="btn btn-primary" id="btn-view-projects">🚀 Khám phá Dự án</a>
            <a href="admin.php" class="btn btn-secondary" id="btn-view-admin">🛠 Quản lý Dự án</a>
            <a href="https://github.com/huytran-01/DTC245180088-Portfolio-De13" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" id="btn-github">📦 GitHub Repo</a>
        </div>

        <div class="tech-pills">
            <span class="tech-tag">🐳 Docker</span>
            <span class="tech-tag">🌐 Nginx Reverse Proxy</span>
            <span class="tech-tag">🐘 PHP 8.2 (Apache non-root)</span>
            <span class="tech-tag">🗄️ MySQL 8.0</span>
            <span class="tech-tag">🔒 HTTPS / SSL Tự Ký</span>
            <span class="tech-tag">📊 Prometheus Exporter</span>
            <span class="tech-tag">🛡️ Security Headers</span>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="stats-grid">
        <div class="stat-card" id="stat-projects">
            <div class="stat-number"><?= $total_projects ?></div>
            <div class="stat-label">Dự án tiêu biểu</div>
        </div>
        <div class="stat-card" id="stat-reverse-proxy">
            <div class="stat-number">443 / 8080</div>
            <div class="stat-label">Nginx Proxy → Apache</div>
        </div>
        <div class="stat-card" id="stat-ssl">
            <div class="stat-number">SSL / TLS</div>
            <div class="stat-label">Bảo mật Tự ký</div>
        </div>
        <div class="stat-card" id="stat-db">
            <div class="stat-number" style="font-size: 1.4rem; color: <?= $db_connected ? 'var(--accent-green)' : 'var(--accent-red)' ?>;">
                <?= $db_connected ? 'Đang hoạt động' : 'Chưa kết nối' ?>
            </div>
            <div class="stat-label">MySQL (portfolio_db)</div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects">
        <div class="section-header">
            <div>
                <h2 class="section-title">Dự Án Nổi Bật</h2>
                <p class="section-subtitle">Dữ liệu được nạp thời gian thực từ cơ sở dữ liệu MySQL</p>
            </div>
            <a href="admin.php" class="btn btn-secondary" id="btn-add-project-cta">+ Thêm dự án mới</a>
        </div>

        <?php if (!$db_connected): ?>
            <div class="alert alert-error" id="db-alert-error">
                <span>⚠️ <strong>Lỗi kết nối cơ sở dữ liệu:</strong> <?= htmlspecialchars($db_error_message ?? '') ?>. Hãy đảm bảo container MySQL đang chạy và file .env được cấu hình đúng.</span>
            </div>
        <?php endif; ?>

        <div class="projects-grid" id="projects-container">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <article class="project-card" id="project-card-<?= (int)$project['id'] ?>">
                        <div>
                            <div class="project-card-header">
                                <span class="project-id-badge">ID: #<?= (int)$project['id'] ?></span>
                                <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                            </div>
                            <p class="project-desc"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                        </div>
                        <div class="project-card-footer">
                            <div class="project-tech">
                                <?php 
                                $tags = explode(',', $project['tech_stack']);
                                foreach ($tags as $tag):
                                    $tag = trim($tag);
                                    if ($tag):
                                ?>
                                    <span class="project-tech-pill"><?= htmlspecialchars($tag) ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                            <div class="project-meta">
                                <span>📅 <?= date('d/m/Y H:i', strtotime($project['created_at'])) ?></span>
                                <span>DTC245180088</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="stat-card" style="grid-column: 1 / -1; padding: 40px;">
                    <p style="color: var(--text-secondary); margin-bottom: 16px;">Chưa có dự án nào trong hệ thống.</p>
                    <a href="admin.php" class="btn btn-primary" id="btn-add-first-project">Thêm dự án đầu tiên</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Architecture Section -->
    <section class="arch-card" id="architecture">
        <h3 style="font-size: 1.5rem; margin-bottom: 12px; color: #fff;">Kiến Trúc Hạ Tầng Hệ Thống (Đề 13)</h3>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">
            Hệ thống được thiết kế theo mô hình 3-Tier chuẩn DevOps, tách biệt rõ ràng giữa tầng Reverse Proxy, Tầng Ứng dụng Web và Tầng Cơ sở dữ liệu.
        </p>
        <div class="arch-grid">
            <div class="arch-item">
                <h4>🌐 Nginx Reverse Proxy</h4>
                <p>Lắng nghe cổng 80 (HTTP) và 443 (HTTPS SSL). Chuyển tiếp request đến container ứng dụng cổng 8080. Đã bật Security Headers và ẩn server_tokens.</p>
            </div>
            <div class="arch-item">
                <h4>🐘 Apache / PHP 8.2</h4>
                <p>Chạy container dưới quyền non-root (<code>USER www-data</code>), cấu hình Apache đổi cổng lắng nghe sang 8080 để tăng cường bảo mật.</p>
            </div>
            <div class="arch-item">
                <h4>🗄️ MySQL Database</h4>
                <p>Lưu trữ cơ sở dữ liệu <code>portfolio_db</code>, cấu hình mật khẩu bảo mật cao qua file <code>.env</code>, khởi tạo dữ liệu tự động với <code>init.sql</code>.</p>
            </div>
            <div class="arch-item">
                <h4>📈 Giám sát Prometheus</h4>
                <p>Nginx mở cổng nội bộ 8081 tại endpoint <code>/stub_status</code> cho phép các công cụ thu thập số liệu (Prometheus exporter) giám sát hiệu năng.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2026 <strong>DTC245180088</strong> • Đề 13: Website Portfolio / Giới thiệu Cá nhân</p>
        <p style="margin-top: 8px;">
            <a href="index.php">Trang chủ</a> • 
            <a href="admin.php">Quản trị</a> • 
            <a href="https://github.com/huytran-01/DTC245180088-Portfolio-De13" target="_blank" rel="noopener noreferrer">GitHub</a>
        </p>
    </footer>
</div>

</body>
</html>
