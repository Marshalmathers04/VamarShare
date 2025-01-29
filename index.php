<?php
    session_start();
    $host = "localhost";
    $username = "root";
    $password = "";
    $serverdb = "vamarshare_db";
    $conn = mysqli_connect($host,$username,$password,$serverdb);        
    
    if (!isset($_SESSION["i"])){
        $_SESSION["i"]=0;
    }
    if (!is_dir(__DIR__."/uploads")){
        mkdir(__DIR__."/uploads");
    }
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if (!preg_match('/Mobi|Android|iPhone|Windows/i', $agent)) {
        echo "This website is only accessible on mobile devices.";
        exit;
    }
    $query = "SELECT photo_url from photos";
    $result = mysqli_query($conn,$query);
    if (isset($_POST["submit"])){
        $_SESSION["i"]++;   
        echo "<h1>added {$_SESSION['i']}</h1>";
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
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>VamarShare</title>
</head>
<body>
    <div class="container">
        <div class="section-container">
            <header class="header">
                <nav>
                    <ul class="header__menu">
                        <li><a href=""><i class="fa-solid fa-camera"></i></a></li>
                        <li><a href="">VamarShare</a></li>
                        <li><a href=""><i class="fa-regular fa-bell"></i></a></li>
                    </ul>
                </nav>
                <hr>
                <div class="header__stories-container">
                    <div class="add-story">
                        <i class="fa-solid fa-plus"></i>    
                    </div>
                </div>
                <hr>
            </header>
            <main class="main">
                <div class="main__posts-container">
                    <?php

                        while ($row = mysqli_fetch_assoc($result)){
                            echo "<img src='{$row['photo_url']}'>";
                        }
                    ?>
                </div>
            </main>
        </div>
        <div class="section-container" style="display:none;">
            <form class="main__form" method="POST">
                        <input type="file" id="file_upload">
                        <div class="main__form__description">
                            <label for="name">Название</label>
                            <input type="text" id="name" placeholder="Название">
                            <label for="description">Описание</label>
                            <input type="text" id="description" placeholder="Описание">
                        </div>
                        <input type="submit" name="submit" value="Pateto">
                    </form>
        </div>
        <div class="section-container" style="display:none;">
            <div class="circle" style="background-color:red;width:100px;height:100px;"></div>
        </div>
        </main>
        <footer class="footer">
            <ul class="footer__menu">
                <li><a href=""><i class="fa-solid fa-house"></i></a><span>Glavne</span></li>
                <li><a href=""><i class="fa-solid fa-plus"></i></a><span>Pateto</span></li>
                <li><a href=""><i class="fa-solid fa-user"></i></a><span>Profil</span></li>
            </ul>
        </footer>        
    </div>
    <script>
        const buttons = document.querySelectorAll(".footer__menu li")
        const containers = document.querySelectorAll(".section-container")   
        buttons.forEach((e,i)=>{
                e.addEventListener("click",(event)=>{
                    event.preventDefault()
                    buttons.forEach(element => {
                        element.classList.remove("active")
                    }); 
                    e.classList.add("active")
                    containers.forEach(e=>{
                        e.style.display = "none"
                    })
                    containers[i].style.display = "block"   
                })
        })
    </script>
</body>
</html>
