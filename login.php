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
                <h2>Вход</h2>
            </div>
            <div class="form-section2">
                <div class="item">
                    <input type="text" name="login" placeholder="Телефон или почта">
                </div>    
                <div class="item">
                    <input type="password" name="password" placeholder="Пароль">
                </div>    
            </div>
            
            <div class="submit-btn">
                <input type="submit" name="submit">
            </div>
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
        include("db.php");
        $login = $_POST["login"];
        $password = $_POST["password"];
        $sql = "SELECT name, phone, email, password  
                FROM user 
                WHERE (email='{$login}' OR phone='{$login}')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo "Неверные данные.";
            return;
        }
        $row = mysqli_fetch_assoc($result);
        if (!password_verify($password, $row["password"])) {
            echo "Неверные данные.";
            return;
        }
        session_start();
        $_SESSION["username"] = $row["name"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["phone"] = $row["phone"];
        $_SESSION["password"] = $row["password"];
        // TODO: CAPTCHA
        header("Location: profile.php");

        mysqli_close($conn);
    }
?>