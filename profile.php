<?php 
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="profile-content">
        <div class="top">
            <h2>Добро пожаловать, <?php echo $_SESSION["username"];?></h2>
            <form action="profile.php" method="post">
                <div class="exit-btn">    
                    <input type="submit" name="logout" value="Выйти">
                </div>
            </form>
        </div>
        <div>
            <form class="profile-form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
            <div class="title">
                <h2>Редактирование данных</h2>
            </div>
                <div class="item">
                    <input type="text" name="username" value="<?php echo $_SESSION["username"]; ?>" placeholder="Имя">
                </div>
                <div class="item">
                    <input type="phone" name="phone" value="<?php echo $_SESSION["phone"]; ?>" placeholder="Телефон">
                </div>
                <div class="item">
                    <input type="email" name="email" value="<?php echo $_SESSION["email"]; ?>" placeholder="Почта">
                </div>
                <div class="item">
                    <input type="password" name="old_password" placeholder="Старый пароль">
                </div>
                <div class="item">
                    <input type="password" name="new_password" placeholder="Новый пароль">
                </div>
                <div class="submit-btn">
                    <input type="submit" name="submit" value="Сохранить">
                </div>
            </form>
        </div>
        
    </div>
</body>
</html>

<?php 
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST["submit"])) {
        $tmp_arr = array("old_password", "new_password");
        foreach ($_POST as $key => $val) {
            // echo empty($_POST[$key]) . $key . "<br>";
            if (empty($_POST[$key]) && !in_array($key, $tmp_arr)) {
                echo "Поля Имя, Телефон, Почта не могут быть пустыми.";
                return;
            }
        }
        $phone = filter_input(INPUT_POST, "phone", FILTER_VALIDATE_INT);
        if (empty($phone)) {
            echo "Не валидный номер телефона.";
            return;
        }
        include("db.php");
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $_POST["email"];
        $password = $_SESSION["password"];
        if (!empty($_POST["old_password"]) || !empty($_POST["new_password"])) {
            if (empty($_POST["old_password"]) || empty($_POST["new_password"])) {
                $tmp_dict = array("0" => "Введите старый пароль.", "1" => "Введите новый пароль.");
                echo $tmp_dict[!empty($_POST["old_password"])] . "<br>";
                return;
            } elseif (password_verify($_POST["old_password"], $_SESSION["password"])) {
                $password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
            } else {
                echo "Старый пароль не верный.";
            }
        }
        echo "<br>" . "Password = " . $password . "<br>";
        $old_phone = $_SESSION['phone'];
        $sql = "UPDATE user 
                SET name='{$username}',
                email='{$email}',
                phone='{$phone}',
                password='{$password}'
                WHERE phone='{$old_phone}'";
        mysqli_query($conn, $sql);
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;
        $_SESSION["phone"] = $phone;
        $_SESSION["password"] = $password;
        // $_POST["username"] = $username;
        echo "Данные сохранены.";
        mysqli_close($conn);
        header("Location: profile.php");
    }
?>