<?php   
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Project information </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="utf-8" />

  <!-- Може би малко излишни мета данни, но са добавени за да се покаже повече знания -->
  <meta name="author" content="Stefan Pelke, Rosen Popov, Martin Veselinov, Plamen Hristov" />
  <meta name="description" content="This is our web assignment in which we develope an app store for people to upload and download their projects" />
  <meta name="keywords" content="WEB,projects,App,Store" />

  <!-- Вмъкване на външен css файл, добавен е php код за да не зе кешира css файла, защото иначе колкото и да го променям сайта не го показва "That will add the current timestamp on the end of a file path, so it will always be unique and never loaded from cache." -->
  <link href="index.css?<?php echo time(); ?>" rel="stylesheet">

  <!-- Вмъкване на jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Вмъкване на външен javascript файл -->
  <script src="javascript/index.js?<?php echo time(); ?>"></script>
  <script src="javascript/update_validation.js?<?php echo time(); ?>"></script>
</head>

<body>
  <!-- php script за свързване с базата и извличане на данните по селектирания проект-->
<?php
require 'db_setup.php';
if (isset($_SESSION["id"]) == false) {
	header("Location:login.php");
  }
  echo "<p class='uname'> Logged in as: " . $_SESSION["name"] . "</p>";
  echo "<input type='button' class='logout' value='Log Out' onClick=\"document.location.href='logout.php'\" />";
  if ($_SESSION["admin"] == 1) {
	  echo "<input type='button' class='admin' value='Admin' onClick=\"document.location.href='admin_page.php'\" />";
  }
	if (isset($_POST['newProjectTitle']))
	{
	    try {
	      $conn = new PDO(
	        "mysql:host=$serverName;dbname=$database;",
	        $user,
	        $pass
	      );
	    } catch (PDOException $e) {
	      die("Error connecting to SQL Server: " . $e->getMessage());
	    }
		
		$idd = $_GET["id"];
		$sql = "SELECT * FROM Projects WHERE id = $idd";
		$sth = $conn->query($sql);
		$project = $sth->fetch(); 

		$projectName = $_POST['newProjectTitle'];
		$projectDescr= $_POST['newProjectDescription'];
		
		if (isset($_POST['newProjectFile'])){
			$projName = htmlspecialchars( basename( $_FILES["newProjectFile"]["name"])); //създавам променлива в която складирам името на файла
			
			// Код за качване на файла в папка
			$targetDir = "ProjectsFileLocation/".$_SESSION['group']."/";
			$targetFile = $targetDir . $projName;
			$oldfile = $project['FileLocation'];
			$uploadOk = 1;
			$fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
			
			//Проверка дали има създадена папка за проекти, ако не я създаваме
			if (!file_exists($targetDir)) {
				mkdir($targetDir, 0777, true);
			}
			
			// Проверка дали съществува файла
			if (file_exists($targetFile)) {
				echo "<h1 class='error'>Sorry, file already exists.</h1>";
				$uploadOk = 0;
			}
			
			// Проверка дали файла надвишава лимит
			if ($_FILES["projectFile"]["size"] > 35000000) {
				echo "<h1 class='error'>Sorry, your file is too large.</h1>";
				$uploadOk = 0;
			}
			
			// Проверка за extensiona
			if($fileType != "zip" && $fileType != "rar" && $fileType != "gz"
			&& $fileType != "tar" && $fileType != "7z") {
				echo "<h1 class='error'>Sorry, only ZIP, RAR, GZIP, TAR or 7ZIP files are allowed.</h1>";
				$uploadOk = 0;
			}
			
			// Проверка дали променливата $uploadOk има стойност 0 поради грешка
			if ($uploadOk == 0) {
				echo "<h1 class='error'>Sorry, your file was not uploaded.</h1>";
			// Ако всичко е добре качваме файла
			} else {
				if (move_uploaded_file($_FILES["newProjectFile"]["tmp_name"], $targetFile)) {
					
					echo "<h1 class='success'>The file ". $projName . " has been uploaded.</h1>";
					$grpId = $_SESSION['group'];
					$sql = "INSERT INTO `Projects` (GroupId,`Title`,`Description`,`DateCreated`,`DateModified`,FileLocation) VALUES ('$grpId','$_POST[projectTitle]','$_POST[projectDescription]','$_POST[projectDate]','$_POST[projectDate]','$projName')";
					$sth = $conn->prepare($sql);
					$sth->execute();
					echo "<h1 class='success'> CONGRATS you just uploaded your project</h1>";
				} else {
					echo "<h1 class='error'>Sorry, there was an error uploading your file.</h1>";
				}
			}
		} 
		$projectName = $_POST['newProjectTitle'];
		$projectDescr= $_POST['newProjectDescription'];
        $sql = "UPDATE Projects SET Title='$projectName' Description='$projectDescr' WHERE Id='$idd'";
	    $result = $conn->query($sql);
	    $id = $result->fetch();
		$conn = null;
		}

  try {
	  $conn = new PDO(
		  "mysql:host=$serverName;dbname=$database;",
		  $user,
		  $pass
	  );
		
	  $idd = $_GET["id"];
	  $sql = "SELECT * FROM Projects WHERE id = '$idd'";
	  $result = $conn->query($sql);
	  $project = $result->fetch();

	  //тука извличаме всички коментари от базата
	  $projId = $_GET["id"];
	  $commentsQuery = "SELECT * FROM Comments WHERE ProjectId = $projId ";
	  $result = $conn->query($commentsQuery);
	  $allComments = $result->fetchAll();//обект с всички коментари по дадения проект
	  
	  //Тук пишем динамичната html страница
	  echo "<div>
		  <h1>" . $project['Title'] . "</h1>
		  <h3 class='commentHeader'> Project created: <time>" . $project ['DateCreated'] . "</time> </h3>
		  <h3 class='commentHeader'> Project modified: <time>" . $project['DateModified'] . "</time> </h3>
		  <h3 class='grade'>Grade: " . $project['Grade'] . "</h3>
		  <div class='comment'>
		  <h3 class='commentHeader'> Project Description</h3> <p class='commentBody'>" . $project['Description'] . "</p>
		  </div>
		  <a href='ProjectsFileLocation/" . $project['GroupId'] . "/" . $project['FileLocation'] . "' download>
		  <button> Download project </button>
		  </a>";
	  if ($project['GroupId'] == $_SESSION['group']){
			$projectTitle = $project['Title'];
			$projectDescription = $project['Description'];

		  echo "<button id='updateButton' onclick='toggleFormDisplay()'>Update Project</button>
				<div id = 'error_msg'></div>
				<form id='updateForm' action='update_project.php' method='post' enctype='multipart/form-data'>
					<label for='modProjectTitle'>Project Title</label>
	  				<input type='text' id='modProjectTitle' name='modProjectTitle' value='$projectTitle' required/>
					
					<label for='modProjectFile'>Upload new project</label>
	  				<input type='file' id='modProjectFile' name='modProjectFile' required/>
					
					<label for='modProjectDescription'> Project Description</label>
	  				<textarea id='modProjectDescription' name='modProjectDescription' required>$projectDescription </textarea>
					
					<input type='hidden' name='modProjId' value='$projId'>
					
	  				<input type='submit' value='Update'  onclick='return validate()' />
				</form> ";
			}
			
	echo "<h3> Comment Section </h3>";
	$usr = $_SESSION["admin"];
	if($usr) {
		//Опция за показване на емайлите, ако съществува 
		//Първо извличаме GroupId на проекта
		$projId = $_GET["id"];
	    $groupIdQuery = "SELECT GroupId FROM Projects Where Id=$projId; ";
	    $sth = $conn->prepare($groupIdQuery);
	    $sth->execute();
	    $groupId = $sth->fetch();
		$grpId = $groupId['GroupId'];

		//След това с този GroupId извличаме емайлите на хората
		$emailQuery = "SELECT Email FROM Users Where GroupId = $grpId";
		$sth = $conn->prepare($emailQuery);
		$sth->execute();
		$emails = $sth->fetchAll();
		
		//Визуализираме емайлите
		echo "<h1>Email: ";
		$emailCount = 1;
		foreach ($emails as $em) {
			if(strlen($em['Email']) > 0 ) {
				if($emailCount > 1) {
					echo " ; " ;
				}
				echo $em['Email'];
				$emailCount++;
			}
		}
		echo "</h1>";
		echo  "<div id='error_msg_comm'></div>
			  <form action='comments.php' method='post'>
				<label class='grade' for='grade'>Give grade: (between 2 and 6):</label>
				<input type='number' id='grade' name='grade' min='2' max='6' required>
				<textarea id='comment' name='comment' rows='4' cols='50' placeholder='Type comment here [Note: atleast 5  long!]' required></textarea> 
				<input type='hidden' id='projectId' name ='projectId' value='$projId'>
				<input type='submit' onclick = 'return validate_comm()'>
			  </form>
			  </div>";
		
		$commentsQueryy = "SELECT * FROM Comments WHERE ProjectId = $projId;";
		$result=$conn->query($commentsQueryy);
		$userInfo = $result->fetchAll();

	}
	foreach ($allComments as $comm) {
		$userId = $comm["UserId"]; //променлива за това кой е писал коментара

		// цялата информация за коментарите от таблицата
		$userQuery="SELECT Username FROM Users WHERE ID = $userId";
		$result=$conn->query($userQuery);
		$user = $result->fetch();

		echo "<div class='comment'>
			<h3 class='commentHeader'>" . $user["Username"] . "</h3>
			<h3 class='commentHeader'>" . $comm["Timestamp"] . "</h3>
			<p class='commentBody'>" . $comm["Text"]  . "</p>
			</div>";

	}
  } catch (PDOException $e) {
	  echo "Error: " . $e->getMessage();
  }

  $conn = null;
?>
	<button type="button" onclick="Home()"> Head back to Home screen </button>
	</body>
	</html>
