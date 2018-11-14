<!DOCTYPE html>
<html lang="en">
<head>
    <title>MyFarm - The FarmGame</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
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
$turnCount = $liveStatus['turns'];

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
    <h1>MyFarm : The Farm Game</h1>
</div>
<div class="container">
	<div class="row">
		<div class="col-6">
			<form action="index.php" method="POST">
				<button type="submit" class="btn btn-primary" name="newGame">START</button>
				<button type="submit" class="btn btn-primary ml-3" name="feed">FEED</button>		
			<!-- add class disabled & attribute disabled for diabled buttons -->
			</form>
		</div>
		<div class="col-6">
			<span class="rounded-circle bg-info d-block float-right text-center" style="width:50px; height:50px;"><span class="align-middle h1 text-light font-weight-normal"><?php echo $turnCount; ?></span></span>
		</div>	
		<div class="col-12 mt-3">
		<?php echo $response; ?> 
		</div>
	</div>
	<div class="row">

        <div class="col-4">
			<h3>Farmer</h3><?php echo $farmerTab; ?>
		</div>
		<div class="col-4">
			<h3>Cow</h3><?php echo $cowTab; ?>
		</div>
		<div class="col-4">
			<h3>Bunnie</h3><?php echo $bunnyTab; ?>
		</div>
	</div>

	<!-- add condition to show game summary table here-->
	<div class="row">
	<div class="col-12 mt-4">	
	<div class="card">
  <div class="card-header h4">
    Game Summary
  </div>
  <div class="card-body">
<table class="table table-bordered">
    <thead>
      <tr>
        <th class="bg-light">Round</th>
        <th>Farmer</th>
        <th>Cow 1</th>
		<th>Cow 2</th>
		<th>Bunny 1</th>
		<th>Bunny 2</th>
		<th>Bunny 3</th>
		<th>Bunny 4</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="bg-light">John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
      </tr>
    </tbody>
  </table>
  </div>
</div>	
  </div>
  </div>
</div>
</body>