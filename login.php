<?php
require_once 'db.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
        $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
                $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                            session_start();
                                    $_SESSION['username'] = $username;
                                            header('Location: home.php');
                                                    exit;
                                                        } else {
                                                                echo "اسم المستخدم أو كلمة المرور غير صحيحة";
                                                                    }
                                                                    } else {
                                                                        echo "يرجى إدخال اسم المستخدم وكلمة المرور";
                                                                        }
                                                                        ?>
                                        <!DOCTYPE html>
                                        <html>
                                        <head>
                                            <title>Login</title>
                                            </head>
                                            <body>
                                                <h2>Login</h2>
                                                    <form action="login.php" method="post">
                                                            <label for="username">اسم المستخدم:</label><br>
                                                                    <input type="text" id="username" name="username"><br>
                                                                            <label for="password">كلمة المرور:</label><br>
                                                                                    <input type="password" id="password" name="password"><br><br>
                                                                                            <input type="submit" value="تسجيل الدخول">
                                                                                                </form>
                                                                                                </body>
                                                                                                </html>
                                                                                                                                