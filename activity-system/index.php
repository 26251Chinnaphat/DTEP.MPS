<?php
//<a class="btn btn-secondary btn-lg" href="admin/dashboard.php" role="button">ผู้ดูแลระบบ</a>
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบลงทะเบียนกิจกรรมเจ้าหน้าที่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">ระบบลงทะเบียนกิจกรรม</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../register.php">ลงทะเบียน</a>
                    </li>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/dashboard.php">แดชบอร์ด</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/login.php">ผู้ดูแลระบบ</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                    <span class="navbar-text ms-auto">
                        ยินดีต้อนรับ, <?php echo $_SESSION['admin_username']; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
    <div class="jumbotron bg-light p-5 rounded-lg m-3 text-center">
        <h1 class="display-4">ยินดีต้อนรับสู่ระบบลงทะเบียนกิจกรรมเจ้าหน้าที่</h1>
        <p class="lead">ระบบนี้ใช้สำหรับลงทะเบียนกิจกรรมของเจ้าหน้าที่โรงเรียน</p>
        <hr class="my-4">
        <p>เริ่มต้นใช้งานโดยการลงทะเบียนหรือเข้าสู่ระบบผู้ดูแล</p>
        <div class="d-flex justify-content-center gap-3">
            <a class="btn btn-primary btn-lg" href="register.php" role="button">ลงทะเบียน</a>
            
        </div>
    </div>

    <?php
    require_once 'includes/footer.php';
    ?>