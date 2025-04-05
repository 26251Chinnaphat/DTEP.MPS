<?php
require_once '../config/db.php';
require_once '../includes/header.php';

// ตรวจสอบการล็อกอิน (ไม่ใช้ฐานข้อมูล)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// ดึงข้อมูลจากฐานข้อมูล
$sql_total = "SELECT COUNT(*) as total FROM staff_registrations";
$result_total = $conn->query($sql_total);
$total_staff = $result_total->fetch_assoc()['total'];

$sql_year2 = "SELECT COUNT(*) as count FROM staff_registrations WHERE year_level = 'ปีที่ 2 (ม.5)'";
$result_year2 = $conn->query($sql_year2);
$year2_count = $result_year2->fetch_assoc()['count'];

$sql_year3 = "SELECT COUNT(*) as count FROM staff_registrations WHERE year_level = 'ปีที่ 3 (ม.6)'";
$result_year3 = $conn->query($sql_year3);
$year3_count = $result_year3->fetch_assoc()['count'];
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">แดชบอร์ดผู้ดูแลระบบ</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">ผู้ลงทะเบียนทั้งหมด</h5>
                        <h1 class="display-4"><?php echo $total_staff; ?></h1>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">ปีที่ 2 (ม.5)</h5>
                        <h1 class="display-4"><?php echo $year2_count; ?></h1>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">ปีที่ 3 (ม.6)</h5>
                        <h1 class="display-4"><?php echo $year3_count; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="view_registrations.php" class="btn btn-primary me-2">ดูรายชื่อทั้งหมด</a>
            <a href="generate_badge.php" class="btn btn-secondary">สร้างป้ายชื่อ</a>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>