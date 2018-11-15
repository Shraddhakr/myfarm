<?php
include 'vendor/autoload.php';
$container   = new \DI\Container();
$farmService = $container->get('FarmGame\\Service\\FarmService');

$responseArr = array();
$animal_disp_arr = array('farmer_1', 'cow_1', 'cow_2', 'bunny_1', 'bunny_2', 'bunny_3', 'bunny_4');

$farmerTab = '';
$cowTab = '';
$bunnyTab = '';

$action = $_POST['action'];

if ($action == 'newGame') {
    $responseArr['result'] = $farmService->newGame();
}

if ($action == 'feed') {
    $responseArr['result'] = $farmService->processTurn();
}

$responseArr['liveStatus'] = json_decode($farmService->getLiveStatus(), true);

echo json_encode($responseArr);
?>
