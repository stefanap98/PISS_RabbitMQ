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
</head>

<body>

<?php
	//връзка с базата
	require 'db_setup.php';
    try {
        $conn = new PDO(
        "mysql:host=$serverName;dbname=$database;",
        $user,
        $pass
        );
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
	
	//променливи за update на проект
	$projId = $_POST["modProjId"];
	$updateProjectTitle = htmlspecialchars($_POST["modProjectTitle"]);
	$updateProjectDescription = htmlspecialchars($_POST["modProjectDescription"]);
	$projModDate = date('Y-m-d H:i:s', strtotime('+1 hour'));
	
	//проверка дали съществува такъв проект
	$sql = "SELECT COUNT(1) FROM projects WHERE Title='$updateProjectTitle' And Id!=$projId";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	if($result==null || $result[0][0] >= 1) {
	  echo "<h1 class='error'> Warning: Project with this title already exists! Project hasn't been updated...</h1>";
		} else {
		
		//Заявка към базата за извличане на името и директорията на стария файл
		$sql = "Select GroupId, FileLocation From projects WHERE Id = '$projId';";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		$oldFile = "ProjectsFileLocation/" . $result['GroupId'] . "/" . $result['FileLocation'];
		
		// Процедури за файла
		$projName = htmlspecialchars( basename( $_FILES["modProjectFile"]["name"])); //създавам променлива в която складирам името на файла
			
		// Код за качване на файла в папка
		$targetDir = "ProjectsFileLocation/" . $result['GroupId'] . "/";
		$targetFile = $targetDir . $projName;
		$uploadOk = 1;
		$fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
			
		// Проверка дали файла надвишава лимит
		if ($_FILES["modProjectFile"]["size"] > 35000000) {
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
			if (move_uploaded_file($_FILES["modProjectFile"]["tmp_name"], $targetFile)) {
				//Да изтрием стария файл, ако е с различно име
				if ($targetFile != $oldFile){
						unlink($oldFile);
					}
						
				echo "<h1 class='success'>The file ". $projName . " has been uploaded.</h1>";
					
				//Заявка към базата данни за Update-ване на ред
				$sql = "UPDATE `projects` SET `Title` = '$updateProjectTitle', `Description` = '$updateProjectDescription', `DateModified` = '$projModDate', `FileLocation` = '$projName' WHERE Id = '$projId';"; 
				$stmt = $conn->prepare($sql);
				$stmt->execute();
								
				echo "<h1 class='success'> CONGRATS you just updated your project!</h1>";
			} else {
				echo "<h1 class='error'>Sorry, there was an error updating your project.</h1>";
			}
		}
	}
	// затваряне на връзката с базата
    $conn = null;
?>

<button type="button" onclick="Home()"> Head back to Home screen </button>
</body>
</html>