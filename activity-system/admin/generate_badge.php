<?php
require_once '../config/db.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_ids = $_POST['selected_ids'] ?? [];
    
    if (!empty($selected_ids)) {
        $ids = implode(',', array_map('intval', $selected_ids));
        $sql = "SELECT * FROM staff_registrations WHERE id IN ($ids)";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Display action buttons
            echo '<div class="text-center mb-4">
                    <button onclick="generateBadges()" class="btn btn-green">
                        <i class="bi bi-download"></i> ดาวน์โหลดทั้งหมด
                    </button>
                    <button onclick="printBadges()" class="btn btn-gray-outline ms-2">
                        <i class="bi bi-printer"></i> พิมพ์ทั้งหมด
                    </button>
                    <button onclick="downloadAsZip()" class="btn btn-dark-green ms-2">
                        <i class="bi bi-file-zip"></i> บันทึกเป็น ZIP
                    </button>
                  </div>';
            
            // Display badges in a grid
            echo '<div id="badges-container" class="row justify-content-center">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">
                        <div class="badge-card green-gray-theme" id="badge-' . $row['id'] . '">
                            <div class="badge-header">
                                <div class="header-pattern"></div>
                                <h5 class="badge-title">
                                    <i class="bi bi-code-square"></i> ห้องเรียนความเป็นเลิศด้านเทคโนโลยีดิจิทัล
                                </h5>
                                <div class="header-pattern"></div>
                            </div>
                            <div class="badge-body">
                                <div class="avatar-container">
                                    <div class="avatar-frame">
                                        <img src="../' . $row['image_path'] . '" alt="รูปภาพนักเรียน">
                                    </div>
                                    <div class="tech-tag">
                                        <i class="bi bi-cpu"></i>
                                    </div>
                                </div>
                                <div class="student-info">
                                    <div class="nickname-tag">
                                        ' . $row['nickname'] . '
                                    </div>
                                    <h3 class="student-name">
                                        ' . $row['first_name'] . ' <span class="last-name">' . $row['last_name'] . '</span>
                                    </h3>
                                    <div class="detail-item">
                                        <i class="bi bi-person-vcard">เลขประจำตัวนักเรียน: </i>
                                        <span>' . $row['staff_id'] . '</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-book">ระดับ: </i>
                                        <span>' . $row['year_level'] . '</span>
                                    </div>
                                </div>
                            </div>
                            <div class="badge-footer">
                                <div class="footer-pattern"></div>
                                <p class="footer-text">
                                    <i class="bi bi-calendar3"></i> ปีการศึกษา 2568
                                </p>
                            </div>
                        </div>
                    </div>';
            }
            echo '</div>';
        }
    }
} else {
    // Display staff selection form
    $sql = "SELECT id, first_name, last_name, nickname, staff_id FROM staff_registrations ORDER BY first_name";
    $result = $conn->query($sql);
    ?>
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-person-badge"></i> สร้างป้ายชื่อเจ้าหน้าที่</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th width="50px">เลือก</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>ชื่อเล่น</th>
                                <th>เลขประจำตัว</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td><input type="checkbox" name="selected_ids[]" value="' . $row['id'] . '" class="form-check-input"></td>
                                            <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
                                            <td><span class="badge bg-success">' . $row['nickname'] . '</span></td>
                                            <td>' . $row['staff_id'] . '</td>
                                          </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">ไม่พบข้อมูล</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-person-badge"></i> สร้างป้ายชื่อ
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<!-- CSS and JavaScript includes -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* Green-Gray Theme */
:root {
    --primary-green: #2e7d32;
    --dark-green: #1b5e20;
    --light-green: #81c784;
    --gray-dark: #424242;
    --gray-medium: #757575;
    --gray-light: #e0e0e0;
    --text-dark: #212121;
    --text-light: #f5f5f5;
}

/* Badge Card Design */
.badge-card.green-gray-theme {
    width: 100%;
    max-width: 360px;
    height: 560px;
    border-radius: 12px;
    margin: 0 auto 30px;
    position: relative;
    background: white;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
    border: 1px solid var(--gray-light);
}

.badge-card.green-gray-theme:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Badge Header */
.badge-header {
    background: linear-gradient(to right, var(--primary-green), var(--dark-green));
    color: white;
    padding: 18px 0;
    position: relative;
    text-align: center;
}

.header-pattern {
    height: 6px;
    background: repeating-linear-gradient(
        45deg,
        var(--gray-light),
        var(--gray-light) 10px,
        var(--light-green) 10px,
        var(--light-green) 20px
    );
    opacity: 0.3;
    margin: 5px 0;
}

.badge-title {
    font-family: 'Kanit', sans-serif;
    font-size: 1.3rem;
    margin: 0;
    padding: 0 25px;
    font-weight: 500;
    color: white;
    letter-spacing: 0.5px;
}

.badge-title i {
    margin-right: 10px;
}

/* Avatar Frame */
.avatar-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 15px auto;
}

.avatar-frame {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 5px solid var(--gray-light);
    padding: 3px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.tech-tag {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: var(--primary-green);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16);
    border: 2px solid white;
}

/* Student Information */
.student-info {
    margin-top: 20px;
    text-align: center;
}

.nickname-tag {
    display: inline-block;
    background: var(--primary-green);
    color: white;
    padding: 6px 10px;
    border-radius: 40px;
    font-size: 1.8rem;
    font-weight: 450;
    font-family: 'Kanit', sans-serif;
    margin-bottom: 1px;
    box-shadow: 0 3px 6px rgba(46, 125, 50, 0.2);
}

.student-name {
    margin: 15px 0;
    color: var(--text-dark);
    font-size: 1.4rem;
    font-weight: 450;
    font-family: 'Kanit', sans-serif;
}

.last-name {
    color: var(--primary-green);
    font-weight: 600;
}

.detail-item {
    background: var(--gray-light);
    padding: 1px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px auto;
    max-width: 80%;
}

.detail-item i {
    color: var(--primary-green);
    margin-right: 8px;
    font-size: 1.1rem;
}

.detail-item span {
    font-family: 'Kanit', sans-serif;
    font-weight: 400;
    color: var(--gray-dark);
}

/* Badge Footer */
.badge-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 12px 0;
    background: var(--gray-dark);
}

.footer-pattern {
    height: 4px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        var(--light-green) 30%, 
        var(--light-green) 70%, 
        transparent 100%);
    margin-bottom: 8px;
}

.footer-text {
    margin: 0;
    color: white;
    font-size: 0.9rem;
    font-family: 'Kanit', sans-serif;
}

.footer-text i {
    margin-right: 8px;
    color: var(--light-green);
}

/* Control Buttons */
.btn-green {
    background: linear-gradient(to right, var(--primary-green), var(--dark-green));
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 25px;
    font-weight: 500;
    font-family: 'Kanit', sans-serif;
    box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
    transition: all 0.3s;
}

.btn-green:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

.btn-gray-outline {
    background: transparent;
    color: var(--gray-dark);
    border: 2px solid var(--gray-medium);
    border-radius: 6px;
    padding: 10px 25px;
    font-weight: 500;
    font-family: 'Kanit', sans-serif;
    transition: all 0.3s;
}

.btn-gray-outline:hover {
    background: var(--gray-medium);
    color: white;
}

.btn-dark-green {
    background: linear-gradient(to right, var(--gray-dark), var(--dark-green));
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 25px;
    font-weight: 500;
    font-family: 'Kanit', sans-serif;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s;
}

.btn-dark-green:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .badge-card.green-gray-theme {
        max-width: 320px;
        height: 540px;
    }
    
    .student-name {
        font-size: 1.6rem;
    }
    
    .btn-green, .btn-gray-outline, .btn-dark-green {
        padding: 8px 15px;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
}
</style>

<!-- JavaScript Functions -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
// Function to download badges as ZIP
async function downloadAsZip() {
    const zip = new JSZip();
    const badges = document.querySelectorAll('.badge-card');
    const imgFolder = zip.folder("badges");
    
    const promises = Array.from(badges).map((badge, index) => {
        return new Promise((resolve) => {
            html2canvas(badge).then(canvas => {
                canvas.toBlob((blob) => {
                    imgFolder.file(`badge-${index+1}.png`, blob);
                    resolve();
                }, 'image/png');
            });
        });
    });
    
    await Promise.all(promises);
    
    zip.generateAsync({type:"blob"}).then((content) => {
        saveAs(content, "digital_classroom_badges.zip");
    });
}

// Function to generate individual badges
function generateBadges() {
    const badges = document.querySelectorAll('.badge-card');
    
    badges.forEach((badge, index) => {
        html2canvas(badge).then(canvas => {
            const link = document.createElement('a');
            link.download = `badge-${index+1}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
}

// Function to print badges
function printBadges() {
    window.print();
}
</script>

<?php
require_once '../includes/footer.php';
?>