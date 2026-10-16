<?php
require_once __DIR__ . '/db.php';

$message = '';
$message_type = '';

// Xu ly xoa du an
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $delete_id = (int)($_POST['id'] ?? 0);
    if ($delete_id > 0 && $db_connected && $conn) {
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                $message = "Đã xóa dự án #$delete_id thành công!";
                $message_type = 'success';
            } else {
                $message = "Lỗi khi xóa dự án: " . $stmt->error;
                $message_type = 'error';
            }
            $stmt->close();
        }
    } else {
        $message = "Yêu cầu xóa không hợp lệ hoặc cơ sở dữ liệu chưa sẵn sàng!";
        $message_type = 'error';
    }
}

// Xu ly them du an moi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tech_stack = trim($_POST['tech_stack'] ?? '');

    if ($title === '' || $description === '' || $tech_stack === '') {
        $message = "Vui lòng điền đầy đủ tiêu đề, mô tả và công nghệ sử dụng!";
        $message_type = 'error';
    } elseif (!$db_connected || !$conn) {
        $message = "Không thể kết nối cơ sở dữ liệu: " . ($db_error_message ?? '');
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, tech_stack) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $title, $description, $tech_stack);
            if ($stmt->execute()) {
                $message = "Thêm dự án mới thành công cho sinh viên DTC245180088!";
                $message_type = 'success';
            } else {
                $message = "Lỗi khi thêm dự án: " . $stmt->error;
                $message_type = 'error';
            }
            $stmt->close();
        }
    }
}

// Lay danh sach du an
$projects = [];
if ($db_connected && $conn) {
    $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
    if ($result) {
        $projects = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Dự Án - DTC245180088 | Đề 13</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- Navbar -->
    <header class="navbar">
        <a href="index.php" class="nav-brand" id="admin-brand-link">
            <span class="brand-badge">DTC245180088</span>
            <span>Trang Quản Trị • Đề 13</span>
        </a>
        <nav class="nav-links">
            <a href="index.php" class="btn btn-secondary" id="btn-back-home">⬅ Quay lại Portfolio</a>
        </nav>
    </header>

    <div class="admin-header">
        <h1 style="font-size: 2.2rem; margin-bottom: 8px;">Quản Trị Danh Mục Dự Án</h1>
        <p style="color: var(--text-secondary);">Thêm mới hoặc xóa bỏ dự án hiển thị trên Portfolio sinh viên DTC245180088</p>
    </div>

    <!-- Alert Message -->
    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>" id="admin-alert">
            <span><?= ($message_type === 'success' ? '✅ ' : '⚠️ ') . htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!$db_connected): ?>
        <div class="alert alert-error">
            <span>⚠️ <strong>Cảnh báo:</strong> Cơ sở dữ liệu MySQL chưa kết nối. Vui lòng kiểm tra Docker container MySQL và file cấu hình <code>.env</code>.</span>
        </div>
    <?php endif; ?>

    <!-- Admin Content Grid -->
    <div class="admin-grid">
        <!-- Form Them Du An -->
        <div class="form-card">
            <h2 style="font-size: 1.3rem; margin-bottom: 20px; color: #fff;">+ Thêm Dự Án Mới</h2>
            <form action="admin.php" method="POST" id="form-create-project">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label for="project-title">Tên dự án / Tiêu đề *</label>
                    <input 
                        type="text" 
                        id="project-title" 
                        name="title" 
                        class="form-control" 
                        placeholder="VD: Cổng thanh toán trực tuyến - DTC245180088" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="project-tech">Công nghệ sử dụng (ngăn cách bởi dấu phẩy) *</label>
                    <input 
                        type="text" 
                        id="project-tech" 
                        name="tech_stack" 
                        class="form-control" 
                        placeholder="VD: PHP 8.2, MySQL, Docker, Redis, Nginx" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="project-desc">Mô tả dự án *</label>
                    <textarea 
                        id="project-desc" 
                        name="description" 
                        class="form-control" 
                        placeholder="Mô tả chi tiết tính năng, kiến trúc hoặc kết quả đạt được của dự án..." 
                        required
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-primary" id="btn-submit-project" style="width: 100%;">
                    💾 Lưu Dự Án
                </button>
            </form>
        </div>

        <!-- Danh Sach Du An Hien Co -->
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.3rem; color: #fff;">Danh Sách Hiện Có (<?= count($projects) ?>)</h2>
                <span class="tech-tag" style="font-size: 0.75rem;">MySQL: portfolio_db</span>
            </div>

            <?php if (!empty($projects)): ?>
                <table class="custom-table" id="projects-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên Dự Án</th>
                            <th>Công Nghệ</th>
                            <th>Thời Gian</th>
                            <th style="width: 90px; text-align: center;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $item): ?>
                            <tr id="row-project-<?= (int)$item['id'] ?>">
                                <td>
                                    <span class="project-id-badge">#<?= (int)$item['id'] ?></span>
                                </td>
                                <td>
                                    <strong style="color: #fff; display: block; margin-bottom: 4px;"><?= htmlspecialchars($item['title']) ?></strong>
                                    <span style="font-size: 0.82rem; color: var(--text-secondary);"><?= htmlspecialchars(mb_strimwidth($item['description'], 0, 80, '...')) ?></span>
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: #a5b4fc;"><?= htmlspecialchars($item['tech_stack']) ?></span>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                                </td>
                                <td style="text-align: center;">
                                    <form action="admin.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dự án #<?= (int)$item['id'] ?> không?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" id="btn-delete-<?= (int)$item['id'] ?>">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 40px 20px; color: var(--text-secondary);">
                    <p>Chưa có dự án nào trong cơ sở dữ liệu.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2026 <strong>DTC245180088</strong> • Đề 13: Website Portfolio / Giới thiệu Cá nhân</p>
    </footer>
</div>

</body>
</html>
