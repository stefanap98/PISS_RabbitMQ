<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('RabbitQueue', false, false, false, false);

$callback = function ($msg) {

    $grade = explode("|", $msg->body);
	
	//Връзка с базата в която записваме полученото съобщение
	require(__DIR__ . '/../db_setup_2.php');
    try {
        $conn = new PDO(
            "mysql:host=$serverName;dbname=$database;",
            $user,
            $pass
        );
        
		$projId = $grade[0];
		$projGrade = $grade[1];
        $sql = "INSERT INTO `projectgrades` (`Id`,`Grade`) VALUES ('$projId','$projGrade') ON DUPLICATE KEY UPDATE `Grade` = '$projGrade' "; 
        $sth = $conn->prepare($sql);
        $sth->execute();

        } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null; 
};

//Тъй като гърми, когато опашката е празна:
list($queue, $messageCount, $consumerCount) = $channel->queue_declare('RabbitQueue', true);

if($messageCount > 0) {
	$channel->basic_consume('RabbitQueue', '', false, true, false, false, $callback);
	$counter = $messageCount ;
	
	//Цикъл за обхождане на всички съобщения от опашката
	while ($counter) {
		$channel->wait();
		--$counter;
	}
}

$channel->close();
$connection->close();
?>