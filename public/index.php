<!DOCTYPE html>
<html lang="en">
<head>
    <title>MyFarm - The FarmGame</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<style>
		.game-control{
			position: sticky;
			top: 0;
			z-index: 1000;
			background: #fff;
			padding: 2rem 0 2rem;
			margin-bottom: 2rem;
			border-bottom: 1px solid #ccc;
		}
	</style>
</head>
<body>
<?php
include 'vendor/autoload.php';
$container   = new \DI\Container();
$farmService = $container->get('FarmGame\\Service\\FarmService');

$farmerTab = '';
$cowTab = '';
$bunnyTab = '';

//On page refresh, initiate game again
$response = $farmService->newGame();

$liveStatus = json_decode($farmService->getLiveStatus(), true);

$animalArr = $liveStatus['animals'];
$animalList = array();
$animalHeader = '';

foreach($animalArr as $entity){
	$animalList[] = $entity['displayName'];
	if($entity['friendlyName'] == 'farmer'){
		$farmerTab .= '<p>'. $entity['displayName'] . '</p>'; 
	}else if($entity['friendlyName'] == 'cow'){
		$cowTab .= '<p>'. $entity['displayName'] . '</p>';
	}else if($entity['friendlyName'] == 'bunny'){
		$bunnyTab .= '<p>'. $entity['displayName'] . '</p>';
	}
	$animalHeader .= '<th>'.$entity['displayName'].'</th>'; 
}

?>
<div class="jumbotron text-center mb-0">
    <h1>MyFarm : The Farm Game</h1>
</div>
<div class="container">

	<!-- game control-->
	<div class="row game-control">
		<div class="col-6">
			<button type="button" class="btn btn-primary actionTab" name="newGame">START</button>
			<button type="button" class="btn btn-primary ml-3 actionTab" name="feed" disabled>FEED</button>
		</div>
		<div class="col-6">
			<span class="rounded-circle bg-info d-block float-right text-center" style="width:50px; height:50px;"><span class="align-middle h1 text-light font-weight-normal turns-played">0</span></span>
		</div>	
		<div class="col-12 mt-3 message-class">
			<div class="alert alert-info d-none" data-action="newGame"><strong>New Game Created</strong></div>
			<div class="alert alert-danger d-none" data-action="lost"><strong>You Lost this Game!! Try again!!</strong></div>
			<div class="alert alert-success d-none" data-action="won"><strong>Won!! Won!! Won!!</strong></div>
			<div class="alert alert-warning d-none" data-action="feed"><strong>Feeding </strong></div>
		</div>
	</div>
	
	<!-- game status-->
	<div class="row game-status">
        <div class="col-4">
			<h3>Farmer</h3>
				<span class="animals farmerTab"><?php echo $farmerTab; ?></span>
		</div>
		<div class="col-4">
			<h3>Cow</h3>
				<span class="animals cowTab"><?php echo $cowTab; ?></span>
		</div>
		<div class="col-4">
			<h3>Bunnie</h3>
				<span class="animals bunnyTab"><?php echo $bunnyTab; ?></span>
		</div>
	</div>

	<!-- game summary-->
	<div class="row game-summary d-none mb-3">
        <div class="col-12 mt-4">	
            <div class="card">
                <div class="card-header h4">Game Summary</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="bg-light">Round</th>
								<?php echo $animalHeader; ?>
							</tr>
						</thead>
						<tbody class="summary-tr">
							
						</tbody>
					</table>
				</div>
			</div>	
		</div>
	</div>
</div>
<script>
		$(function(){
		
			var animalList = <?php echo json_encode($animalList); ?>;
			
			$('.actionTab').click(function(e){
				e.preventDefault();
				var action = $(this).attr('name');
				$.ajax({
				  type: "POST",
				  url: '/myfarm/gameprocess.php',
				  data: {action : action},
				  success: successFunction,
				  dataType: 'json'
				});
			});
			
			function successFunction(response){
				var result = response.result;
				
				$('.message-class .alert').addClass('d-none');

				switch(result){
					case 'newGame':
						$('[name=feed]').prop('disabled', false);
						$('.game-summary').removeClass('d-none').find('.summary-tr').empt;
					break;
					case 'lost': 
					case 'won': 
						$('[name=feed]').prop('disabled', true);
						buildLiveAnimals(response.liveStatus.animals);
						updateCount(response.liveStatus.turns);
						buildRow(response.liveStatus);
						break;
					case 'feed':
						$('[name=newGame]').text('START Again');
						buildLiveAnimals(response.liveStatus.animals);
						updateCount(response.liveStatus.turns);
						buildRow(response.liveStatus);
					break;
					default:
				}
				$('.alert[data-action="'+result+'"]').removeClass('d-none');
			}
			
			function buildRow(liveStatus){
				var newTr = '<tr><td class="bg-light">' + liveStatus.turns + '</td>';
				for(var i = 0; i < animalList.length ; i++){
					newTr += '<td>'+ (liveStatus.feedTo.displayName === animalList[i] ? "&#10004; Fed" : "") +'</td>';
				}
				newTr += '</tr>';
				
				$('.summary-tr').append(newTr)
			}
			
			function buildLiveAnimals(animals){
				$('.animals').empty()
				
				$.each(animals, function(index, animal) {
					var animalP = '<p>' + animal.displayName + '</p>'
					
					$('.animals.'+animal.friendlyName+'Tab').append(animalP)
				})
			}
			
			function updateCount(turn) {
				$('.turns-played').text(turn)
			}
		})		
	</script
</body>