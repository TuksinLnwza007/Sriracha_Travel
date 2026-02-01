<!----------------------------------------------------------------------------
    ไฟล์: index.php
    หน้าหลัก - แสดงรายการสถานที่ท่องเที่ยวศรีราชา
    ดีไซน์: Modern Minimal Style
----------------------------------------------------------------------------->

<?php
// ============================================================================
// ⚙️ BACKEND: การเชื่อมต่อฐานข้อมูลและดึงข้อมูล
// ============================================================================
include "includes/connection.php";
$sql = "SELECT * FROM places ORDER BY id";
$result = mysqli_query($conn, $sql);
// ============================================================================
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เที่ยวศรีราชา - สถานที่ท่องเที่ยวในศรีราชา จ.ชลบุรี</title>
    
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
           HEADER - ส่วนหัวเว็บ
        ======================================== */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 3em;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: -1px;
        }
        
        .hero p {
            font-size: 1.2em;
            font-weight: 300;
            opacity: 0.95;
        }
        
        /* ========================================
           CONTAINER
        ======================================== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* ========================================
           MAP SECTION - ส่วนแผนที่
        ======================================== */
        .map-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            font-size: 1.8em;
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
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* ========================================
           PLACES GRID - ตารางแสดงสถานที่
        ======================================== */
        .places-header {
            margin-bottom: 30px;
        }
        
        .places-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }
        
        /* ========================================
           PLACE CARD - การ์ดแต่ละสถานที่
        ======================================== */
        .place-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid #f0f0f0;
        }
        
        .place-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }
        
        .place-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: transform 0.3s;
        }
        
        .place-card:hover .place-image {
            transform: scale(1.05);
        }
        
        .place-content {
            padding: 25px;
        }
        
        .place-title {
            font-size: 1.4em;
            color: #2d3748;
            margin-bottom: 12px;
            font-weight: 600;
            line-height: 1.3;
        }
        
        .place-detail {
            color: #718096;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 0.95em;
        }
        
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95em;
            transition: all 0.3s;
        }
        
        .btn-detail:hover {
            background: #5568d3;
            gap: 12px;
        }
        
        .btn-detail::after {
            content: '→';
        }
        
        /* ========================================
           FOOTER
        ======================================== */
        .footer {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
            margin-top: 60px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }
        
        .footer p {
            font-size: 0.9em;
        }
        
        /* ========================================
           EMPTY STATE - เมื่อไม่มีข้อมูล
        ======================================== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #a0aec0;
        }
        
        .empty-state svg {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        /* ========================================
           RESPONSIVE - รองรับหน้าจอเล็ก
        ======================================== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2em;
            }
            
            .hero p {
                font-size: 1em;
            }
            
            .places-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .map-container {
                height: 300px;
            }
        }
        
        /* ========================================
           LOADING ANIMATION
        ======================================== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .place-card {
            animation: fadeIn 0.5s ease-out;
        }
        
        .place-card:nth-child(1) { animation-delay: 0.1s; }
        .place-card:nth-child(2) { animation-delay: 0.2s; }
        .place-card:nth-child(3) { animation-delay: 0.3s; }
        .place-card:nth-child(4) { animation-delay: 0.4s; }
        .place-card:nth-child(5) { animation-delay: 0.5s; }
        .place-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
    <!------------------------------------------------------------------------
        🎨 จบส่วน CSS STYLING
    ------------------------------------------------------------------------->
</head>

<body>

<!----------------------------------------------------------------------------
    📍 HERO SECTION - ส่วนหัวเว็บ
----------------------------------------------------------------------------->
<div class="hero">
    <div class="hero-content">
        <h1>🏖️ เที่ยวศรีราชา</h1>
        <p>ค้นพบสถานที่ท่องเที่ยวสุดพิเศษในอำเภอศรีราชา จังหวัดชลบุรี</p>
    </div>
</div>

<div class="container">
    
    <!------------------------------------------------------------------------
        🗺️ MAP SECTION - แผนที่สถานที่ท่องเที่ยว
    ------------------------------------------------------------------------->
    <div class="map-section">
        <h2 class="section-title">
            <span>📍</span>
            <span>แผนที่สถานที่ท่องเที่ยว</span>
        </h2>
        <div class="map-container">
            <!-- ============================================================
                ⚙️ BACKEND: OpenStreetMap Embed
                แสดงแผนที่อำเภอศรีราชา
            ============================================================ -->
            <iframe 
                src="https://www.openstreetmap.org/export/embed.html?bbox=100.8800%2C13.1400%2C100.9800%2C13.2000&layer=mapnik&marker=13.1700%2C100.9300"
                loading="lazy">
            </iframe>
        </div>
    </div>

    <!------------------------------------------------------------------------
        📋 PLACES LIST - รายการสถานที่ท่องเที่ยว
    ------------------------------------------------------------------------->
    <div class="places-header">
        <h2 class="section-title">
            <span>🌟</span>
            <span>สถานที่ท่องเที่ยวแนะนำ</span>
        </h2>
    </div>

    <div class="places-grid">
        <?php
        // ====================================================================
        // ⚙️ BACKEND: วนลูปแสดงสถานที่จากฐานข้อมูล
        // ====================================================================
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // ป้องกัน XSS Attack
                $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $detail = htmlspecialchars($row['detail'], ENT_QUOTES, 'UTF-8');
                $id = $row['id'];
                
                // จัดการรูปภาพ
                $image = !empty($row['image']) 
                    ? 'assets/images/' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') 
                    : '';
                
                // ตัดข้อความให้สั้นลง (แสดงแค่ 120 ตัวอักษร)
                $shortDetail = mb_strlen($detail) > 120 
                    ? mb_substr($detail, 0, 120) . '...' 
                    : $detail;
                
                // ====================================================================
                // 🎨 HTML: แสดงการ์ดแต่ละสถานที่
                // ====================================================================
                echo "<div class='place-card' onclick=\"window.location.href='place_detail.php?id={$id}'\">";
                
                // แสดงรูปภาพ (ถ้ามี)
                if ($image && file_exists($image)) {
                    echo "<img src='{$image}' alt='{$name}' class='place-image'>";
                } else {
                    // ถ้าไม่มีรูป แสดงพื้นหลังสีไล่ระดับ
                    echo "<div class='place-image'></div>";
                }
                
                echo "<div class='place-content'>";
                echo "  <div class='place-title'>{$name}</div>";
                echo "  <div class='place-detail'>{$shortDetail}</div>";
                echo "  <a href='place_detail.php?id={$id}' class='btn-detail'>ดูรายละเอียด</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            // ====================================================================
            // 🎨 HTML: แสดงเมื่อไม่มีข้อมูล
            // ====================================================================
            echo "<div class='empty-state'>";
            echo "  <svg fill='currentColor' viewBox='0 0 20 20'>";
            echo "    <path d='M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z'/>";
            echo "  </svg>";
            echo "  <p>ยังไม่มีข้อมูลสถานที่ท่องเที่ยว</p>";
            echo "</div>";
        }
        
        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
        // ====================================================================
        ?>
    </div>

</div>

<!----------------------------------------------------------------------------
    🦶 FOOTER
----------------------------------------------------------------------------->
<div class="footer">
    <p>&copy; 2025 เที่ยวศรีราชา | ข้อมูลสถานที่ท่องเที่ยวอำเภอศรีราชา จังหวัดชลบุรี</p>
</div>

</body>
</html>