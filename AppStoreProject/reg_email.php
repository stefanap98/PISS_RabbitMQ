<?php   
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Projects App Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="utf-8" />

  <!-- Може би малко излишни мета данни, но са добавени за да се покаже повече знания -->
  <meta name="author" content="Stefan Pelke, Rosen Popov, Martin Veselinov, Plamen Hristov" />
  <meta name="description" content="This is our web assignment in which we develope an app store for people to upload and download their projects" />
  <meta name="keywords" content="WEB,projects,App,Store" />

  <!-- Вмъкване на външен css файл, добавен е php код за да не зе кешира css файла, защото иначе колкото и да го променям сайта не го показва "That will add the current timestamp on the end of a file path, so it will always be unique and never loaded from cache."-->
  <link href="index.css?<?php echo time(); ?>" rel="stylesheet">

  <!-- Вмъкване на jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Вмъкване на външен javascript файл -->
  <script src="javascript/index.js?<?php echo time(); ?>"></script>

</head>

<body>
<?php
  if (isset($_SESSION["id"]) == false) {
    header("Location:login.php");
  }
  echo "<p class='uname'> Logged in as: " . $_SESSION["name"] . "</p>"  
  ?>
  <input type="button" class="logout" value="Log Out" onClick="document.location.href='logout.php'" />
  <?php 
  if ($_SESSION["admin"] == 1) {
    echo "<input type='button' class='admin' value='Admin' onClick=\"document.location.href='admin_page.php'\" />" ;
	}

if (count($_POST) > 0) {
      $userEmail = $_POST['emailInput'];
      if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Email is valid</p>";

        require 'db_setup.php';
        try {
          $conn = new PDO( "mysql:host=$serverName;dbname=$database;", $user, $pass);
        } catch (PDOException $e) {
          die("Error connecting to SQL Server: " . $e->getMessage());
        }
        $idd = $_SESSION["id"];
        echo $idd;
        $sql = "UPDATE Users SET Email='$userEmail' WHERE Id='$idd'";
        $conn->prepare($sql)->execute();
        $_SESSION["mail"] = $userEmail ;
        $conn = null;
        if (isset($_SESSION["id"])) { 
          header("Location:index.php"); 
        }
      }
      else{
        echo "<h3>Invlaid email format</h3>";
      }
}
?>
  <h1>Enter your email. It will be used by teachers to send you information.</h1>
  <form method="POST">
    <label for="emailInput">Email</label>
	<input type="text" id="emailInput" name="emailInput" required/> 
    <input type="submit" value=" Submit " />
  </form>
</body>
</html>

