<!-- Всеки проект може да се отваря в нова страница с описание, бутон за сваляне и коментари -->
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

<body onload="javascript:load_sug()">
  <?php  
  if (isset($_SESSION["id"]) == false) {
    header("Location:login.php");
  } else if (strlen($_SESSION["mail"]) == 0) {
    header("Location:reg_email.php");
  }
  echo "<p class='uname'> Logged in as: " . $_SESSION["name"] . "</p>"  
  ?>

  <input type="button" class="logout" value="Log Out" onClick="document.location.href='logout.php'" />
  
  <?php 
  if ($_SESSION["admin"] == 1) {
    echo "<input type='button' class='admin' value='Admin' onClick=\"document.location.href='admin_page.php'\" />" ;
	}
  ?>

  <h1> List of available projects</h1>
    <!-- php код за връзка с базата  -->
    <?php
    require 'db_setup.php';
    try {
      $conn = new PDO(
        "mysql:host=$serverName;dbname=$database;",
        $user,
        $pass
      );
    } catch (PDOException $e) {
      die("Error connecting to SQL Server: " . $e->getMessage());
    }
    // избираме id, заглавие и описание на проекта от базата
    $sql = "SELECT id ,groupid ,datemodified, title FROM Projects  ORDER BY datemodified ASC ";
    $result = $conn->query($sql);
    $projects = $result->fetchAll();
    $onetime_echo =1;
    foreach ($projects as $row) {
        if ($onetime_echo){
          echo "<label for='sorts' class='sorting'>Sort by:</label> 
            <select class='sorting' id='sort_criteria' name='sort_criteria' onchange='Sort()'>
            <option value='date'>Date Uploaded</option>
            <option value='last_uploaded'>Last Updated</option>
            <option value='name'>Name</option>
            <option value='group'>Group</option>
          </select>";
          echo "<label for='type' class='sorting'>Order:</label> 
            <select class='sorting' id='sort_type' name='sort_type' onchange='Sort()'>
            <option value='asc'>Ascending</option>
            <option value='desc'>Descending</option>
          </select>";
          echo "<div class='sorting' id='searchWrapper'>
              <input
                type='text'
                name='searchBar'
                id='searchBar'
                placeholder='Search' />
            </div>";
        echo "<div id='projects'>";
          $onetime_echo =0;
        }
        echo "
        <div class='project' id='" . $row["id"] . "' project_group=".$row["groupid"]." uploaded_time='".$row["datemodified"]."' project_name='".$row["title"]."'>
		<h3 project_id='".$row["id"] ."'> ". $row["title"] . "</h3>
		</div>" ;
    }
    if (!$onetime_echo){
      echo "</div>";
    }

    if ($onetime_echo){
      echo "<h1 class='error'>There are no projects uploaded yet!</h1>";
      if($_SESSION["admin"] == 1) {
		  echo "<h1 class='error'>Click the 'Admin' button to generate users!</h1>";
	  }
    }

    $conn = null;
    ?>
  <button type="button" onclick="UploadForm()"> Upload new project </button>
  <script src="javascript/sorting.js?<?php echo time(); ?>"></script>
</body>
</html>
