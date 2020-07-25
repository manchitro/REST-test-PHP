<?php
$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (isset($_POST['username']) && isset($_POST['password']) ){
		require_once 'dbh.inc.php';
		$sql ="SELECT * FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			$response['error'] = true;
			$response['message'] = "SQL Error";
		}
		else{
			mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			if ($row = mysqli_fetch_assoc($result)) {
				if ($_POST['password'] == $row['password']) {
					session_start();
					$_SESSION['user_data'] = json_encode($row);

					$response['error'] = false;
					$response['message'] = "Logged in";
					$response['userId'] = $row['id'];
					$response['userName'] = $row['username'];
				}
				else{
					$response['error'] = true;
					$response['message'] = "Wrong Password";
				}
			}
			else{
				$response['error'] = true;
				$response['message'] = "User not found";
			}
		}
	}
	else{
		$response['error'] = true;
		$response['message'] = "Please enter your username and password";
	}
}
else{
	$response['error'] = true;
	$response['message'] = "Invalid request";
}

echo json_encode($response);