<?php
//връзка с базата данни 
require 'rabbit/receive.php';
require 'db_setup_2.php';
try {
	$conn = new PDO(
	    "mysql:host=$serverName;dbname=$database;",
		$user,
		$pass
	  );
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}

// избираме id на проекта и grade от базата
$sql = "SELECT id ,grade FROM projectgrades";
$result = $conn->query($sql);
$grades = $result->fetchAll();

$conn = null;

//променлива за броя на 2/3/4/5/6
$gradesCount = array(0,0,0,0,0);
$gradesNum = 0;
$gradesPercent = array(0.0,0.0,0.0,0.0,0.0);
		
//Принтирам идта на проекти и техните оценки	
foreach ($grades as $row) {
	$index = (int)$row["grade"];

	//index-2 зашото почваме от индекс 0, а нямаме оценки 0 и 1
	$gradesCount[$index-2]++;
	$gradesNum++;
}

$dataPoints = array( );	
//Цикъл за смятане на процентите за всяка една оценка, използвам функция round(num,2), за да закръгля до 2ри знак след запетайката
for($index = 0; $index < 5 ; $index++) {
		if($gradesNum > 0) {
			$gradesPercent[$index] = round( ( ($gradesCount[$index] * 100) / $gradesNum ) , 2);
			if($gradesCount[$index] > 0) {
				array_push($dataPoints , array("label"=>$index+2 , "y"=>$gradesPercent[$index]) );
			}
		}
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Писс 2022 Оценки от проекти"
	},
	subtitles: [{
		text: "Брой оценки: " + <?php echo $gradesNum ?>
	}],
	data: [{
		type: "pie",
		yValueFormatString: "#,##0.00\"%\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>     