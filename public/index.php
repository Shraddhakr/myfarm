<!DOCTYPE html>
<html lang="en">
<head>
    <title>MyFarm - The FarmGame</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include 'vendor/autoload.php';

$container   = new \DI\Container();
$farmService = $container->get('FarmGame\\Service\\FarmService');

$response = "";

$farmerTab = '';
$cowTab = '';
$bunnyTab = '';

if (isset($_POST['newGame'])) {
    $response = $farmService->newGame();
}

if (isset($_POST['feed'])) {
    $response = $farmService->processTurn();
}

$liveStatus = json_decode($farmService->getLiveStatus(), true);
$animalArr = $liveStatus['animals'];

foreach($animalArr as $entity){
	if($entity['friendlyName'] == 'farmer'){
		$farmerTab .= '<p>'. $entity['displayName'] . '</p>';
	}else if($entity['friendlyName'] == 'cow'){
		$cowTab .= '<p>'. $entity['displayName'] . '</p>';
	}else if($entity['friendlyName'] == 'bunny'){
		$bunnyTab .= '<p>'. $entity['displayName'] . '</p>';
	}
}

?>
<div class="jumbotron text-center">
    <h1>MyFarm : The FarmGame</h1>
</div>
<div class="container">
    <form action="index.php" method="POST">
        <button type="submit" class="btn btn-info" name="newGame">START</button>
        <button type="submit" class="btn btn-primary" name="feed">FEED an ANIMAL</button>
    </form>
    <div class="row">
        <div class="col-sm-4">
			<h3>Farmer</h3><?php echo $farmerTab; ?>
		</div>
		<div class="col-sm-4">
			<h3>Cow</h3><?php echo $cowTab; ?>
		</div>
		<div class="col-sm-4">
			<h3>Bunnie</h3><?php echo $bunnyTab; ?>
		</div>
	</div>
	<div> 
	    <?php echo $response; ?> 
	</div>
</div>
</body>