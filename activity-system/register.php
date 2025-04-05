<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// แก้ไขฟังก์ชัน fixImage
function fixImage($filePath) {
    // เพิ่มการตรวจสอบและปิดการแจ้งเตือน
    $image = @imagecreatefromstring(file_get_contents($filePath));
    
    if ($image === false) {
        throw new Exception("ไม่สามารถอ่านไฟล์รูปภาพได้");
    }
    
    $tempFile = tempnam(sys_get_temp_dir(), 'fixed_img') . '.jpg';

    // ตรวจสอบประเภทไฟล์
    $imageType = exif_imagetype($filePath);
    
    if ($imageType == IMAGETYPE_PNG) {
        $width = imagesx($image);
        $height = imagesy($image);
        $whiteBg = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($whiteBg, 255, 255, 255);
        imagefill($whiteBg, 0, 0, $white);
        imagecopy($whiteBg, $image, 0, 0, 0, 0, $width, $height);
        $image = $whiteBg;
    }

    imagejpeg($image, $tempFile, 90);
    imagedestroy($image);
    return $tempFile;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $nickname = $conn->real_escape_string($_POST['nickname']);
    $staff_id = $conn->real_escape_string($_POST['staff_id']);
    $birth_date = $conn->real_escape_string($_POST['birth_date']);
    $year_level = $conn->real_escape_string($_POST['year_level']);
    $crop_data = json_decode($_POST['crop_data'], true);
    
    // คำนวณอายุ
    $birthday = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birthday)->y;
    
    // อัพโหลดรูปภาพ
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = uniqid() . '.jpg';
        $target_path = $upload_dir . $file_name;
        
        try {
            // แก้ไขปัญหาโปรไฟล์สี
            $tempFile = fixImage($_FILES['image']['tmp_name']);
            
            // สร้างรูปภาพจากไฟล์ชั่วคราว
            $src_image = imagecreatefromjpeg($tempFile);
            
            // ตัดรูปตามข้อมูลที่ได้จาก cropper
            $dst_image = imagecreatetruecolor(300, 300);
            imagecopyresampled(
                $dst_image, $src_image,
                0, 0, $crop_data['x'], $crop_data['y'],
                300, 300, $crop_data['width'], $crop_data['height']
            );
            
            // บันทึกรูปภาพ
            imagejpeg($dst_image, $target_path, 90);
            
            // ลบไฟล์ชั่วคราว
            unlink($tempFile);
            imagedestroy($src_image);
            imagedestroy($dst_image);
            
            $image_path = $target_path;
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">เกิดข้อผิดพลาดในการประมวลผลรูปภาพ: ' . $e->getMessage() . '</div>';
        }
    }
    
    // บันทึกข้อมูล
    $sql = "INSERT INTO staff_registrations (first_name, last_name, nickname, staff_id, birth_date, age, image_path, year_level) 
            VALUES ('$first_name', '$last_name', '$nickname', '$staff_id', '$birth_date', '$age', '$image_path', '$year_level')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">ลงทะเบียนสำเร็จ!</div>';
    } else {
        echo '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
    }
}
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">แบบฟอร์มลงทะเบียนกิจกรรม</h4>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="registrationForm">
            <!-- ส่วนฟอร์มข้อมูล -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nickname" class="form-label">ชื่อเล่น</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" required>
                </div>
                <div class="col-md-6">
                    <label for="staff_id" class="form-label">เลขประจำตัว</label>
                    <input type="text" class="form-control" id="staff_id" name="staff_id" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="birth_date" class="form-label">วันเกิด</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" required onchange="calculateAge()">
                </div>
                <div class="col-md-6">
                    <label for="age" class="form-label">อายุ</label>
                    <input type="text" class="form-control" id="age" name="age" readonly>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="year_level" class="form-label">ชั้นปี</label>
                <select class="form-select" id="year_level" name="year_level" required>
                    <option value="">เลือกชั้นปี</option>
                    <option value="มัธยมศึกษาปีที่ 5">มัธยมศึกษาปีที่ 5</option>
                    <option value="มัธยมศึกษาปีที่ 6">มัธยมศึกษาปีที่ 6</option>
                </select>
            </div>
            
            <!-- ส่วนอัพโหลดรูปภาพ -->
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพ</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <input type="hidden" id="crop_data" name="crop_data">
                
                <div class="image-preview mt-3" id="imagePreview" style="display: none;">
                    <div class="cropper-container" style="width: 100%; max-width: 500px; height: 400px;">
                        <img id="imageToCrop" src="#" alt="Preview" style="max-width: 100%;">
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="rotateLeft">
                            <i class="bi bi-arrow-counterclockwise"></i> หมุนซ้าย
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm ms-2" id="rotateRight">
                            <i class="bi bi-arrow-clockwise"></i> หมุนขวา
                        </button>
                        <button type="button" class="btn btn-primary btn-sm ms-2" id="cropImage">
                            <i class="bi bi-check"></i> ตัดรูป
                        </button>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">ลงทะเบียน</button>
        </form>
    </div>
</div>

<!-- เพิ่ม CSS และ JavaScript -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<script>
// คำนวณอายุ
function calculateAge() {
    const birthDate = new Date(document.getElementById('birth_date').value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age;
}

let cropper;

// เมื่อเลือกไฟล์รูปภาพ
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const imagePreview = document.getElementById('imagePreview');
            const imageToCrop = document.getElementById('imageToCrop');
            
            imageToCrop.src = event.target.result;
            imagePreview.style.display = 'block';
            
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                ready: function() {
                    const containerData = cropper.getContainerData();
                    const cropBoxWidth = Math.min(containerData.width, containerData.height) * 0.8;
                    cropper.setCropBoxData({
                        width: cropBoxWidth,
                        height: cropBoxWidth
                    });
                }
            });
        };
        reader.readAsDataURL(file);
    }
});

// ควบคุมการตัดรูป
document.getElementById('rotateLeft').addEventListener('click', () => cropper?.rotate(-90));
document.getElementById('rotateRight').addEventListener('click', () => cropper?.rotate(90));

document.getElementById('cropImage').addEventListener('click', function() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            fillColor: '#fff',
        });
        
        if (canvas) {
            document.getElementById('crop_data').value = JSON.stringify(cropper.getData(true));
            document.getElementById('imageToCrop').src = canvas.toDataURL('image/jpeg');
            cropper.destroy();
            cropper = null;
            alert('ตัดรูปเรียบร้อยแล้ว!');
        }
    }
});

document.getElementById('registrationForm').addEventListener('submit', function(e) {
    if (!document.getElementById('crop_data').value) {
        e.preventDefault();
        alert('กรุณาตัดรูปภาพก่อนลงทะเบียน');
    }
});
</script>

<style>
.cropper-container { 
    margin: 0 auto; 
    background: #f0f0f0; 
}
.cropper-view-box, .cropper-face { 
    border-radius: 50%; 
}
.image-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}
</style>

<?php
require_once 'includes/footer.php';
?>