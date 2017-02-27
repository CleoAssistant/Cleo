<?php 

##################################################
##################################################
##################################################
function connectToDB() {
	$host = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'CleoAssistant';

	global $con;
	$con = mysqli_connect($host, $username, $password, $database, 65536);

	if (mysqli_connect_errno()) {
		exit(mysqli_connect_error());
	}
}

##################################################
##################################################
##################################################
function disconnectDB() {
	global $con;
	if (mysqli_ping($con)) {
		mysqli_close($con);
	}
}

##################################################
##################################################
##################################################
function getAllBookings() {
	global $con;
	$sql = "SELECT
					cleo_bookings.b_id,
					cleo_bookings.b_userId,
					cleo_users.u_username,
					cleo_bookings.b_timeSlot1Id,
					cleo_bookings.b_bookingDate,
					cleo_bookings.b_status,
					cleo_rooms.r_id,
					cleo_rooms.r_roomNumber,
					cleo_timeSlots.ts_time,
					cleo_bookings.b_length
			FROM 
					cleo_users
			RIGHT JOIN 
					cleo_bookings ON cleo_users.u_id = cleo_bookings.b_userId 
			JOIN 
					cleo_rooms ON cleo_bookings.b_roomId = cleo_rooms.r_id
			JOIN 
					cleo_timeSlots ON cleo_bookings.b_timeSlot1Id = cleo_timeSlots.ts_id;";

	$query = mysqli_query($con, $sql);

	return $query;
}

##################################################
##################################################
##################################################
function getConfirmedBookings() {
	global $con;
	$sql = "SELECT
					cleo_bookings.b_id,
					cleo_bookings.b_userId,
					cleo_users.u_username,
					cleo_bookings.b_roomId,
					cleo_bookings.b_timeSlot1Id,
					cleo_bookings.b_length,
					cleo_bookings.b_bookingDate,
					cleo_bookings.b_status
			FROM 
					cleo_users
			RIGHT JOIN 
					cleo_bookings ON cleo_users.u_id = cleo_bookings.b_userId 
			WHERE 
					cleo_bookings.b_status = 'confirmed' ;";

	$query = mysqli_query($con, $sql);

	return $query;
}

##################################################
##################################################
##################################################
function addBooking($data) {

	$userID = $data['userId'];
	$roomID = $data['roomId'];
	$date = $data['date'];
	$time1 = $data['time1'];
	$length = $data['length'];

	$sql = "INSERT INTO 
						cleo_bookings ( b_userId,
										b_roomId,
										b_timeSlot1Id,
										b_length,
										b_bookingDate
										)
			VALUES ('".$userID."',
					'".$roomID."',
					'".$time1."',
					'".$length."',
					'".$date."'
					);";
	
	global $con;

	mysqli_autocommit($con, false);

	$result = mysqli_query($con, "START TRANSACTION");

	$query = mysqli_query($con, $sql);

	if (mysqli_affected_rows($con) == 1) {
		$bookingId = mysqli_insert_id($con);
		$pinAdded = addPin($bookingId);

		if ($pinAdded != false) {
			mysqli_commit($con);
		} else {
			mysqli_rollback($con);
			return false;
		}
		return true;
	} else {
		print_r(mysqli_error($con));
		mysqli_rollback($con);
		return false;
	}

	return false;
}

##################################################
##################################################
##################################################
function getUsers() {
	global $con;

	$sql = "SELECT * FROM cleo_users;";

	$query = mysqli_query($con, $sql);

	return $query;
}

##################################################
##################################################
##################################################
function addUser($data) {

	$username = $data['userName'];
	$facebookId = $data['facebookId'];

	$sql = "INSERT INTO 
						cleo_users (u_facebookId,
									u_username
									) 
			VALUES ('".$facebookId."',
					'".$username."'
					);";

	global $con;

	$query = mysqli_query($con, $sql);

	if (mysqli_affected_rows($con) == 1) {
		return true;
	} else {
		print_r(mysqli_error());
		return false;
	}

	return false;
}

##################################################
##################################################
##################################################
function getCreateData() {

	global $con;
	$data = array();

	$userData = mysqli_query($con, "SELECT u_id, u_username FROM cleo_users;");
	$roomData = mysqli_query($con, "SELECT * FROM cleo_rooms;");
	$timeData = mysqli_query($con, "SELECT * FROM cleo_timeSlots;");

	$count = 0;
	while ($row = mysqli_fetch_assoc($userData)) {
		$data['userData'][$count] = [$row['u_id'], $row['u_username']];
		$count++;
	}

	$count = 0;
	while ($row = mysqli_fetch_assoc($roomData)) {
		$data['roomData'][$count] = [$row['r_id'], $row['r_roomNumber']];
		$count++;
	}

	$count = 0;
	while ($row = mysqli_fetch_assoc($timeData)) {
		$data['timeData'][$count] = [$row['ts_id'], $row['ts_time']];
		$count++;
	}

	return $data;
}

##################################################
##################################################
##################################################
function generatePin() {
	$pinNumber = mt_rand(1, 9) . mt_rand(00000, 99999);

	return $pinNumber;
}

##################################################
##################################################
##################################################
function addPin($ID) {
	$pin = generatePin();
	$sql = "INSERT INTO 
						cleo_confirmationPins ( cp_bookingId,
												cp_pinNumber)
			VALUES ('".$ID."', '".$pin."');";

	global $con;
	$query = mysqli_query($con, $sql);

	if (mysqli_affected_rows($con) == 1) {
		return $pin;
	} else {
		return false;
	}
}

##################################################
##################################################
##################################################
function checkPin($data) {
	$pin = $data['pin'];
	$username = $data['username'];
	global $con;

	mysqli_query($con, "START TRANSACTION");

	$sql = "UPDATE
			  cleo_bookings
			SET
			  b_status = CASE WHEN b_status = 'pending-confirm' THEN 'confirmed' WHEN b_status = 'pending-cancel' THEN 'cancelled' END
			WHERE
			  b_status IN(
			    'pending-confirm',
			    'pending-cancel'
			  ) AND b_userId =(
			  SELECT
			    u_id
			  FROM
			    cleo_users
			  WHERE
			    u_username = '".$username."'
			  LIMIT 1
			) AND b_id =(
			SELECT
			  cp_bookingId
			FROM
			  cleo_confirmationPins
			WHERE
			  cp_pinNumber = ".$pin."
			LIMIT 1
			);";

	$query = mysqli_query($con, $sql);

	if (mysqli_affected_rows($con) == 1) {
		$sql2 = "DELETE FROM cleo_confirmationPins WHERE cp_pinNumber=".$pin.";";

		$query2 = mysqli_query($con, $sql2);

		if (mysqli_affected_rows($con) == 1) {
			mysqli_commit($con);
			return true;
		} else {
			print_r(mysqli_error($con));
			mysqli_rollback($con);
			return false;
		}
	} else {
		print_r(mysqli_error($con));
		mysqli_rollback($con);
		return false;
	}

	return true;
}

##################################################
##################################################
##################################################
function cancelBooking($data) {
	$bookingID = $data['id'];
	$username = $data['username'];
	global $con;

	$sql = "UPDATE
			  cleo_bookings
			SET
			  b_status = 'pending-cancel' 
			WHERE
			  b_status IN(
			    'pending-confirm',
			    'confirmed'
			  ) AND b_userId =(
			  SELECT
			    u_id
			  FROM
			    cleo_users
			  WHERE
			    u_username = '".$username."'
			  LIMIT 1
			  ) AND b_id = ".$bookingID.";";

	$query = mysqli_query($con, $sql);

	if (mysqli_affected_rows($con) == 1) {
		return true;
	} else {
		return false;
	}
}

 ?>