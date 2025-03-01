<?php
    session_start();

    if(!isset($_SESSION["tel"])){
        header("Location:signuplogin.php");
        ob_clean();
        exit;
    }
    

    $host = "localhost";
    $user = "root";
    $password = "";
    $server = "vamarshare_db";

    if (!file_exists(__DIR__."/images")){
        mkdir(__DIR__."/images",0777,true);
    }

    if(!$conn=mysqli_connect($host,$user,$password,$server)){     
        echo "nothin";
    }
    else{
        $photodownloadquery = "SELECT photo_id,photo_url,photo_name,photo_description,year,town,post_likes from photos";
        $photosdownload = mysqli_query($conn,$photodownloadquery);
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
            if (isset($_FILES["file"]) && isset($_POST["name"]) && isset($_POST["description"])) {                
                $filename=pathinfo($_FILES["file"]["name"],PATHINFO_FILENAME);
                $extensionname = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
                while (file_exists("images/".$filename.".".$extensionname)){
                    $filename = pathinfo($_FILES["file"]["name"],PATHINFO_FILENAME).mt_rand(1000,9999).".".pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
                }
                $stmt = $conn->prepare("INSERT INTO photos (photo_description,photo_url,photo_name,year,town) VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss",$_POST["description"],$filename,$_POST["name"],$_POST["sol"],$_POST["region"]);
                $stmt->execute();
                $result = $stmt->get_result();
                if (move_uploaded_file($_FILES["file"]["tmp_name"],"images/".$filename)){
                    if ($stmt){
                        echo "<script>alert('$filename successfully uploaded');</script>";
                        echo "<script>window.location.href = 'index.php';</script>";
                        exit();
                    }
                    else{
                        echo"<script>alert('Couldnt upload')</script>";
                    }
                }
                else{
                    die("something went wrong");
                }
                
            }
        }
    }
    $stmt = $conn->prepare("SELECT id,tel from user_data where tel=?");
    $stmt->bind_param("s",$_SESSION["tel"]);
    $stmt->execute();
    $userdata=$stmt->get_result();
    while($row=mysqli_fetch_assoc($userdata)){
        $_SESSION["user_id"] = $row["id"];
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
    <link rel="stylesheet" href="./styles/style.css?v=<?php echo time()?>">
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
            <div class='main__posts__text-container'><h2>{$row['photo_name']}</h2><p>{$row['photo_description']}</p><p>{$row['town']}</p><p>{$row['year']}</p></div>
            <main class="main">
                <div class="main__posts-container">
                    <?php
                        if ($conn){
                            while ($row = mysqli_fetch_assoc($photosdownload)){
                                echo "<div class='main__posts-container__container'><img src='images/$row[photo_url]'><div class='likes-container' id='{$row['photo_id']}'><p>{$row['post_likes']}</p><i class='fa-regular fa-heart'></i></div></div>";
                            }
                        } 
                    ?>
                </div>
            </main>
        </div>
        <div class="section-container" style="display:none;">
            <form class="main__form" method="post" enctype="multipart/form-data">
                <label id="fileLabel" for="file_upload">Surat vibrat ke:</label>        
                <input type="file" name="file" id="file_upload" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="main__form__description" style="display:flex;flex-direction:column;">
                            <label for="name" name="fileName">Nom</label>
                            <input type="text" name="name" id="name" placeholder="Название" required>
                            <label for="description" name="file_desc">Opisanie</label>
                            <textarea name="description" id="description" placeholder="Описание" required></textarea>
                            <div class="selects-container">
                                <div class="select-container">
                                    <label for="sol">Chdom sol?</label>
                                    <select name="sol" id="solen">
                                        <option value="2000-">2000</option>
                                        <option value="2000+">2000</option>
                                        <option value="2010">2010</option>
                                        <option value="2015">2015</option>
                                        <option value="2017">2017</option>
                                        <option value="2019">2019</option>
                                        <option value="2020+">2020</option>
                                    </select>
                                </div>
                                <div class="select-container">
                                    <label for="region">region</label>
                                    <select name="region" id="region">
                                        <option value="Barzud">Barzud</option>
                                        <option value="Derrushon">Derrushon</option>
                                        <option value="Derzud">Derzud</option>
                                        <option value="Vamar">Vamar</option>
                                        <option value="Khujand">Khujand</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <label class="upload-submit" for="submit"><i style="color:#443627;" class="fa-solid fa-upload"></i></label>
                        <input id="submit" type="submit" style="display:none;" name="submit" value="Pateto">
                    </form>
        </div>
        <div class="section-container" style="display:none;">
            <div class="circle" style="background-color:red;width:100px;height:100px;"></div>
            <h1><?php echo $_SESSION["user_id"];?></h1>
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
        const texts = document.querySelectorAll(".footer__menu li span")
        const containers = document.querySelectorAll(".section-container")   
        buttons.forEach((e,i)=>{
                e.addEventListener("click",(event)=>{
                    event.preventDefault()
                    texts.forEach(e=>{
                        e.style.transform = "translateY(-70%)"
                        e.style.opacity = 0
                    })
                    texts[i].style.opacity = 1
                    setTimeout(() => {
                        texts[i].style.transform = "translateY(70%)"
                    }, 10);
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
        const filelabel = document.getElementById("fileLabel")
        const file = document.getElementById("file_upload")
        file.onchange=()=>{
            const img = URL.createObjectURL(file.files[0])
            filelabel.style.backgroundImage=`url("${img}")`
            filelabel.style.color="transparent"
        }
        const hearts = document.querySelectorAll(".main__posts-container__container i")
        const likes = document.querySelectorAll(".likes-container")
        hearts.forEach((heart,i)=>{
            heart.addEventListener("click",()=>{
                if (heart.style.fontWeight<550){
                    document.querySelectorAll(".likes-container i").forEach((heart, i) => {
                        heart.addEventListener("click", () => {
                            let postId = document.querySelectorAll(".likes-container")[i].id;

                            fetch("like.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: "postId=" + encodeURIComponent(postId)
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById(postId).querySelector("p").innerText = data.likes; 
                                } else {
                                    console.error("Error:", data.error);
                                }
                            })
                            .catch(e => console.log("Fetch error:", e));
                        });
                    });
                    heart.style.fontWeight = 600
                    setTimeout(() => {
                        heart.style.transform = "scale(1.2)"
                    }, 1);
                    setTimeout(() => {
                        heart.style.transform = "scale(1)"
                        heart.style.color = "red"
                    }, 100);
                    
                }
                else{
                    heart.style.fontWeight = 500
                    heart.style.color = "white"
                }
            })
        })
        
    </script>
</body>
</html>
