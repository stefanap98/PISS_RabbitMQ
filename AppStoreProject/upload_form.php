<?php   
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Project upload</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="utf-8"/>
  
  <!-- Може би малко излишни мета данни, но са добавени за да се покаже повече знания -->
  <meta name="author" content="Stefan Pelke, Rosen Popov, Martin Veselinov, Plamen Hristov" />
  <meta name="description" content="This is our web assignment in which we develope an app store for people to upload and download their projects"/>
  <meta name="keywords" content="WEB,projects,App,Store"/>
  
  <!-- Вмъкване на външен css файл, добавен е php код за да не зе кешира css файла, защото иначе колкото и да го променям сайта не го показва "That will add the current timestamp on the end of a file path, so it will always be unique and never loaded from cache." -->
  <link href="index.css?<?php echo time(); ?>" rel="stylesheet">
  
  <!-- Вмъкване на jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <!-- Вмъкване на външен javascript файл -->
  <script src="javascript/index.js?<?php echo time(); ?>"></script>
  <script src="javascript/upload_validation.js?<?php echo time(); ?>"></script>
</head>

<body >

  <?php  

  echo "<p class='uname'> Logged in as: " . $_SESSION["name"] . "</p>"  
  ?>

  <input type="button" class="logout" value="Log Out" onClick="document.location.href='logout.php'" />
  
  <?php 
  if ($_SESSION["admin"] == 1) {
    echo "<input type='button' class='admin' value='Admin' onClick=\"document.location.href='admin_page.php'\" />" ;
	}
  ?>
	<!-- Post заявка със задаване на проекта и информация за нея -->
    <div id = "error_msg"></div>
	<form action="upload.php" id="reg_form" method="post" enctype="multipart/form-data">        
	  <label for="projectTitle">Project Title</label>
	  <input type="text" id="projectTitle" name="projectTitle" required />
	  
	  <label for="projectFile">Upload new project</label>
	  <input type="file" id="projectFile" name="projectFile" required />
	  
	  <label for="projectDescription"> Project Description</label>
	  <textarea id="projectDescription" name="projectDescription" required></textarea>
	  <input type="submit" onclick="return validate()"/>
	</form>
</body>
</html>
