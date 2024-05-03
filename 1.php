<?php
// تعيين العنوان URL الذي يحتوي على معلومات الاتصال بقاعدة البيانات
$url = 'https://tshrih.000webhostapp.com/Admin/Test/db_config.php';

// استرداد المعلومات كـ JSON
$response = file_get_contents($url);

// التحقق من وجود أخطاء في الاستجابة
if ($response === false) {
    echo 'فشل في جلب معلومات الاتصال بقاعدة البيانات عبر file_get_contents';
    exit; // إيقاف التنفيذ في حالة الخطأ
}

// تحويل الاستجابة المسترجعة من JSON إلى مصفوفة PHP
$database_info = json_decode($response, true);

// إنشاء اتصال بقاعدة البيانات باستخدام المعلومات المسترجعة
$conn = new mysqli($database_info['servername'], $database_info['username'], $database_info['password'], $database_info['dbname']);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// Fetch lectures data
$output = '';
$sql = "SELECT * FROM lectures"; // استبدل 'lectures' بالجدول الصحيح
$result = $conn->query($sql);

// التحقق من وجود بيانات
if ($result->num_rows > 0) {
    // بناء النتيجة كجدول HTML
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td>' . $row['subtitle'] . '</td>';

        // التحقق مما إذا كان هناك عنوان URL متاح للمحاضرة
        if (!empty($row['url'])) {
            echo '<td>Exist</td>';
        } else {
            echo '<td>Not Exist</td>';
        }

        echo '<td>';
        echo '<button type="button" class="btn btn-primary viewBtn" data-url="' . $row['url'] . '"><i class="far fa-eye"></i></button>';
        echo '<button type="button" class="btn btn-success editBtn" data-id="' . $row['id'] . '"><i class="fas fa-edit"></i></button>';
        echo '<button type="button" class="btn btn-danger deleteBtn" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i></button>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No lectures found</td></tr>';
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
