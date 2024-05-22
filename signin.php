<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="center">
        <form class="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
            <div class="form-section1">
                <h2 class="title">Регистрация</h2>
            </div>
            <div class="form-section2">
                <div class="item">
                    <input type="text" name="username" placeholder="Имя">
                </div>    
                <div class="item">
                    <input type="phone" name="phone" placeholder="Телефон">
                </div>
                <div class="item">
                    <input type="email" name="email" placeholder="Почта">
                </div>
                <div class="item">
                    <input type="password" name="password" placeholder="Пароль">
                </div>
                <div class="item">
                    <input type="password" name="password_repeat" placeholder="Повтор пароля">
                </div>
            </div>
            <div class="submit-btn">
                <input class="" type="submit" name="submit">
            <div class="item">
        </form>
    </div>

</body>
</html>

<?php
    
    if (isset($_POST["submit"])) {
        foreach ($_POST as $key => $val) {
            if (empty($_POST[$key])) {
                echo "Все поля должны быть заполнены.";
                return;
            }
        }
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "phone", FILTER_VALIDATE_INT);
        if (empty($phone)) {
            echo "Не валидный номер телефона.";
            return;
        }
        include("db.php");
        $email = $_POST["email"];
        $sql = "SELECT * FROM user WHERE name='{$username}' OR phone='{$phone}' OR email='{$email}'";
        // echo $sql . "<br>";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "Пользователь с такими данными уже сществует.";
            return;
        }
        $password = $_POST["password"];
        $password_repeat = $_POST["password_repeat"];
        if ($password != $password_repeat) {
            echo "Пароли не совпадают.";
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (name, phone, email, password)
                VALUES ('{$username}', '{$phone}', '{$email}', '{$hashed_password}')";
        try {
            mysqli_query($conn, $sql);
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["phone"] = $phone;
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $hashed_password;
            header("Location: profile.php");
        } catch(mysqli_sql_exception) {
            echo "Не удалось зарегистрировать пользователя.";
        }
        
        mysqli_close($conn);
    }
?>