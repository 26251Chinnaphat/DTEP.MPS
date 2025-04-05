<?php
require_once '../config/db.php';
require_once '../includes/header.php';

// ตรวจสอบการล็อกอิน (ไม่ใช้ฐานข้อมูล)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">รายชื่อเจ้าหน้าที่ที่ลงทะเบียน</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>รูปภาพ</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ชื่อเล่น</th>
                        <th>เลขประจำตัว</th>
                        <th>อายุ</th>
                        <th>ชั้นปี</th>
                        <th>วันที่ลงทะเบียน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM staff_registrations ORDER BY registration_date DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        $counter = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $counter . '</td>';
                            echo '<td><img src="../' . $row['image_path'] . '" width="50" height="50" class="rounded-circle"></td>';
                            echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                            echo '<td>' . $row['nickname'] . '</td>';
                            echo '<td>' . $row['staff_id'] . '</td>';
                            echo '<td>' . $row['age'] . '</td>';
                            echo '<td>' . $row['year_level'] . '</td>';
                            echo '<td>' . date('d/m/Y H:i', strtotime($row['registration_date'])) . '</td>';
                            echo '</tr>';
                            $counter++;
                        }
                    } else {
                        echo '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>