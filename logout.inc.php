<?php
session_start();
if (isset($_SESSION['user_data'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        session_destroy();
        $response['error'] = false;
        $response['message'] = "Logged out";
        $response['userData'] = $_SESSION['user_data'];
    }
    else {
        $response['error'] = true;
		$response['message'] = "Invalid request";
    }
}
else{
    $response['error'] = true;
	$response['message'] = "Session Not Found";
	$response['sessionID'] = session_id();
}

echo json_encode($response);