<!----------------------------------------------------------------------------
    ไฟล์: place_detail.php
    หน้ารายละเอียดสถานที่ท่องเที่ยว
    ดีไซน์: Modern Minimal Style
----------------------------------------------------------------------------->

<?php
// ============================================================================
// ⚙️ BACKEND: การเชื่อมต่อฐานข้อมูลและดึงข้อมูล
// ============================================================================
include "includes/connection.php";

// รับค่า ID จาก URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ดึงข้อมูลสถานที่จากฐานข้อมูล
if ($id > 0) {
    $sql = "SELECT * FROM places WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $place = mysqli_fetch_assoc($result);
}

// ถ้าไม่พบข้อมูล กลับไปหน้าแรก
if (!isset($place)) {
    header("Location: index.php");
    exit;
}

// ป้องกัน XSS Attack
$name = htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8');
$detail = htmlspecialchars($place['detail'], ENT_QUOTES, 'UTF-8');
$lat = $place['latitude'];
$lng = $place['longitude'];
$image = !empty($place['image']) 
    ? 'assets/images/' . htmlspecialchars($place['image'], ENT_QUOTES, 'UTF-8') 
    : '';

mysqli_close($conn);
// ============================================================================
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?> - เที่ยวศรีราชา</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!------------------------------------------------------------------------
        🎨 CSS STYLING - ส่วนนี้คือการตกแต่งหน้าเว็บ
        ลบหรือแก้ไขได้ตามต้องการโดยไม่กระทบการทำงาน
    ------------------------------------------------------------------------->
    <style>
        /* ========================================
           RESET & BASE STYLES
        ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background: #fafafa;
            color: #2d3748;
            line-height: 1.6;
        }
        
        /* ========================================
           NAVBAR - แถบด้านบน
        ======================================== */
        .navbar {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95em;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s;
            background: #f7fafc;
        }
        
        .btn-back:hover {
            background: #667eea;
            color: white;
            gap: 12px;
        }
        
        .btn-back::before {
            content: '←';
            font-size: 1.2em;
        }
        
        /* ========================================
           HERO IMAGE - รูปภาพหลัก
        ======================================== */
        .hero-image-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .hero-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* ========================================
           CONTENT - เนื้อหาหลัก
        ======================================== */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .place-title {
            font-size: 2.5em;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 30px;
            line-height: 1.2;
        }
        
        .detail-text {
            color: #4a5568;
            line-height: 2;
            font-size: 1.1em;
            margin-bottom: 40px;
            white-space: pre-line;
        }
        
        /* ========================================
           MAP SECTION - ส่วนแผนที่
        ======================================== */
        .map-section {
            margin-top: 50px;
        }
        
        .section-title {
            font-size: 1.6em;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .map-container {
            width: 100%;
            height: 450px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #f7fafc;
            margin-bottom: 15px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .map-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 0;
            transition: gap 0.3s;
        }
        
        .map-link:hover {
            gap: 12px;
        }
        
        .map-link::after {
            content: '→';
        }
        
        /* ========================================
           DIVIDER - เส้นแบ่ง
        ======================================== */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 40px 0;
        }
        
        /* ========================================
           ACTION BUTTONS
        ======================================== */
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #667eea;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f7fafc;
            color: #4a5568;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }
        
        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 768px) {
            .hero-image {
                height: 300px;
                border-radius: 12px;
            }
            
            .content-card {
                padding: 30px 20px;
            }
            
            .place-title {
                font-size: 1.8em;
            }
            
            .detail-text {
                font-size: 1em;
            }
            
            .map-container {
                height: 300px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* ========================================
           ANIMATIONS
        ======================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .content-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .hero-image {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
    <!------------------------------------------------------------------------
        🎨 จบส่วน CSS STYLING
    ------------------------------------------------------------------------->
</head>

<body>

<!----------------------------------------------------------------------------
    📍 NAVBAR - แถบนำทาง
----------------------------------------------------------------------------->
<div class="navbar">
    <div class="navbar-content">
        <a href="index.php" class="btn-back">หน้าหลัก</a>
    </div>
</div>

<!----------------------------------------------------------------------------
    🖼️ HERO IMAGE - รูปภาพหลัก
----------------------------------------------------------------------------->
<div class="hero-image-wrapper">
    <?php
    // ========================================================================
    // ⚙️ BACKEND: แสดงรูปภาพ (ถ้ามี)
    // ========================================================================
    if ($image && file_exists($image)): 
    ?>
        <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="hero-image">
    <?php else: ?>
        <!-- ถ้าไม่มีรูป แสดงพื้นหลังสีไล่ระดับ -->
        <div class="hero-image"></div>
    <?php endif; ?>
</div>

<!----------------------------------------------------------------------------
    📄 CONTENT - เนื้อหาหลัก
----------------------------------------------------------------------------->
<div class="container">
    <div class="content-card">
        
        <!-- ============================================================
            ⚙️ BACKEND: แสดงชื่อสถานที่
        ============================================================ -->
        <h1 class="place-title"><?php echo $name; ?></h1>
        
        <!-- ============================================================
            ⚙️ BACKEND: แสดงรายละเอียด
        ============================================================ -->
        <div class="detail-text"><?php echo nl2br($detail); ?></div>
        
        <div class="divider"></div>
        
        <?php if ($lat && $lng): ?>
        <!-- ============================================================
            🗺️ MAP SECTION - แผนที่ตำแหน่ง
        ============================================================ -->
        <div class="map-section">
            <h2 class="section-title">
                <span>📍</span>
                <span>ตำแหน่งบนแผนที่</span>
            </h2>
            
            <div class="map-container">
                <!-- ====================================================
                    ⚙️ BACKEND: OpenStreetMap Embed
                    คำนวณ bbox จากพิกัด lat, lng
                ==================================================== -->
                <iframe 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo ($lng-0.01); ?>%2C<?php echo ($lat-0.01); ?>%2C<?php echo ($lng+0.01); ?>%2C<?php echo ($lat+0.01); ?>&layer=mapnik&marker=<?php echo $lat; ?>%2C<?php echo $lng; ?>"
                    loading="lazy">
                </iframe>
            </div>
            
            <!-- ====================================================
                ⚙️ BACKEND: ลิงก์เปิดแผนที่ใหญ่
            ==================================================== -->
            <a href="https://www.openstreetmap.org/?mlat=<?php echo $lat; ?>&mlon=<?php echo $lng; ?>#map=16/<?php echo $lat; ?>/<?php echo $lng; ?>" 
               target="_blank" 
               class="map-link">
                เปิดดูในแผนที่ใหญ่
            </a>
        </div>
        <?php endif; ?>
        
        <!-- ============================================================
            🎯 ACTION BUTTONS
        ============================================================ -->
        <div class="action-buttons">
            <a href="index.php" class="btn-primary">
                ← กลับหน้าหลัก
            </a>
            
            <?php if ($lat && $lng): ?>
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $lat; ?>,<?php echo $lng; ?>" 
               target="_blank" 
               class="btn-secondary">
                📱 เปิดใน Google Maps
            </a>
            <?php endif; ?>
        </div>
        
    </div>
</div>

</body>
</html>