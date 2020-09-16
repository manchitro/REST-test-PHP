<?php
session_start();
if (isset($_SESSION['user_data'])) {
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['newPassword']) && isset($_POST['oldPassword']) ){
			require_once 'dbh.inc.php';
			$sql ="SELECT password FROM users WHERE id=?";
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				$response['error'] = true;
				$response['message'] = "SQL Error";
			}
			else{
				mysqli_stmt_bind_param($stmt, "s", $_SESSION['userId']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);

				if ($row = mysqli_fetch_assoc($result)){
					if ($_POST['oldPassword'] == $row['password']) {
						$response['error'] = false;
						$response['message'] = "Password correct";
					}
					else{
						$response['error'] = true;
						$response['message'] = "Password incorrect";
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
			$response['messaged'] = "Please fill in all the fields";
		}
	}
	else{
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