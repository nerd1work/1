<?php

// استقبال البيانات المُرسلة بواسطة طريقة POST
$data = json_decode(file_get_contents('php://input'), true);

// افتح ملف users.json للقراءة والكتابة
$filename = 'users.json';
$file = file_get_contents($filename);
$users = json_decode($file, true);

// إضافة مستخدم جديد إلى قائمة المستخدمين
$users[$data['username']] = $data['password'];

// تحويل المصفوفة إلى صيغة JSON
$users_json = json_encode($users);

// كتابة البيانات المحدثة إلى ملف users.json
file_put_contents($filename, $users_json);

// إرسال استجابة بنجاح
http_response_code(200);
echo json_encode(array("message" => "User added successfully"));

?>
