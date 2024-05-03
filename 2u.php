
<?php
// تعيين معلومات الاتصال بقاعدة البيانات
$servername = "localhost"; // اسم خادم قاعدة البيانات
$username = "username"; // اسم مستخدم قاعدة البيانات
$password = "password"; // كلمة مرور قاعدة البيانات
$dbname = "dbname"; // اسم قاعدة البيانات

// استدعاء ملف db_connect.php باستخدام cURL
$url = 'https://tshrih.000webhostapp.com/Admin/Test/db_connect.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

// التحقق من الاستجابة
if ($response === false) {
    echo 'فشل في جلب معلومات الاتصال بقاعدة البيانات عبر cURL';
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

// استعلام SQL لاسترداد بيانات المحاضرات
$sql = "SELECT * FROM lectures"; // استبدل 'lectures' بالجدول الصحيح
$result = $conn->query($sql);

$lectures = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lectures[] = $row;
    }
}

// إغلاق اتصال قاعدة البيانات
$conn->close();

// إرجاع البيانات كـ JSON
header('Content-Type: application/json');
echo json_encode($lectures);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectures</title>
    <!-- تضمين الأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- تضمين Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Lectures</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="lecturesTable">
                <!-- البيانات ستتم إضافتها هنا بواسطة JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- تضمين Bootstrap و jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // استرداد البيانات من الخادم باستخدام AJAX
            $.ajax({
                url: '<?php echo $_SERVER['PHP_SELF']; ?>', // نفس الصفحة PHP
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        var tableRows = '';
                        $.each(data, function(index, lecture) {
                            var urlStatus = lecture.url ? 'Exist' : 'Not Exist';
                            tableRows += '<tr>';
                            tableRows += '<td>' + lecture.id + '</td>';
                            tableRows += '<td>' + lecture.title + '</td>';
                            tableRows += '<td>' + lecture.subtitle + '</td>';
                            tableRows += '<td>' + urlStatus + '</td>';
                            tableRows += '<td>';
                            if (lecture.url) {
                                tableRows += '<a href="' + lecture.url + '" class="btn btn-primary mr-2"><i class="fas fa-eye"></i> View</a>';
                            }
                            tableRows += '<button class="btn btn-danger deleteBtn" data-id="' + lecture.id + '"><i class="fas fa-trash"></i> Delete</button>';
                            tableRows += '</td>';
                            tableRows += '</tr>';
                        });
                        $('#lecturesTable').html(tableRows); // إضافة صفوف الجدول إلى الجدول الحالي
                    } else {
                        $('#lecturesTable').html('<tr><td colspan="5">No lectures found</td></tr>');
                    }
                },
                error: function() {
                    $('#lecturesTable').html('<tr><td colspan="5">Error fetching lectures</td></tr>');
                }
            });

            // حدث مسح المحاضرة عند النقر على الزر
            $('#lecturesTable').on('click', '.deleteBtn', function() {
                var lectureId = $(this).data('id');
                var confirmed = confirm('Are you sure you want to delete this lecture?');
                if (confirmed) {
                    // اتصال AJAX لحذف المحاضرة
                    $.ajax({
                        url: 'delete_lecture.php', // تغيير هذا بالملف الذي يحذف المحاضرة
                        method: 'POST',
                        data: { id: lectureId },
                        success: function(response) {
                            alert(response.message);
                            // إعادة تحميل الجدول بعد الحذف
                            location.reload();
                        },
                        error: function() {
                            alert('Failed to delete lecture');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
