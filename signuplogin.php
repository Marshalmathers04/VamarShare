<?php
    session_start();
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vamarshare_db";
    $conn = mysqli_connect($server,$username,$password,$dbname);
    if (isset($_POST["submit"])){
        $tel = $_POST["tel"];
        $pass = $_POST["password"];
        if (preg_match('/^\+992\d{9}$/',$tel)){
            $stmt = $conn -> prepare("SELECT hashed FROM user_data WHERE tel=?");
            $stmt -> bind_param("s",$tel);
            $stmt -> execute();
            $res = $stmt->get_result();
            $user_data = $res->fetch_assoc();
            if($res->num_rows>0&&$user_data["hashed"]===$pass){
                $_SESSION["tel"]=$tel;
                $_SESSION["hashed"]=$pass;
                header("Location:index.html");
            }
            if($res->num_rows>0&&$user_data["hashed"]!==$pass){
                echo "<script>alert('Khato parol')</script>";
            }
            else{
                $stmt->prepare("INSERT INTO user_data (tel,hashed) VALUES (?,?)");
                $stmt->bind_param("ss",$tel,$pass);
                $stmt->execute();
                $_SESSION["tel"]=$tel;
                $_SESSION["hashed"]=$pass;
                header("Location:index.php");
                exit();
            }

        
        }
        else{
            echo "<script>alert('Masha dasge nomeren nist')</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/signup.css?v=<?php echo time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>SignUp</title>
</head>
<body>
    <form action="signuplogin.php" method="post">
        <h1>VamarShare</h1>
        <label for="tel">Telefon</label>
        <input type="tel" name="tel" placeholder="+992*********" required>
        <label for="text">Parol</label>
        <input type="text" name="password" required>
        <input type="submit" class="submit-btn" name="submit" value="Registrac">
    </form>
</body>
</html>