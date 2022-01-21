<?php
session_start();
if (isset($_SESSION["id"]) == false) {
  header("Location:login.php");
}
if (isset($_SESSION["admin"]) == true) {
  $comment = htmlspecialchars($_POST["comment"]);
  $projId = $_POST["projectId"];
  $usrId = $_SESSION["id"];
  $date = date('Y-m-d H:i:s');
  $grade = $_POST["grade"];
  
  require 'db_setup.php';
  try {
    $conn = new PDO(
		"mysql:host=$serverName;dbname=$database;",
		$user,
		$pass
	  );
	
	$sql = "INSERT INTO `comments` (`ProjectId`,`UserId`,`Text`,`Timestamp`) VALUES ('$projId','$usrId','$comment','$date');
          UPDATE `projects` SET `Grade` ='$grade' WHERE (`Id` = '$projId');"; 
	
	$sth = $conn->prepare($sql);
	$sth->execute();
	
	} catch (PDOException $e) {
	  echo "Error: " . $e->getMessage();
  }
  require './rabbit/send.php';
  sendMessage($projId . "|" . $grade);
  $conn = null; 
}
header("Location:project.php?id=$projId");
?>