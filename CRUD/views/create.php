<?php 
	if (isset($_POST['submit'])) {
		$data = array();

		$data['userId'] = $_POST['userID'];
		$data['roomId'] = $_POST['room'];
		$data['date'] = $_POST['date'];
		$data['time1'] = $_POST['time1'];
		$data['length'] = $_POST['length'];

		$booking = addBooking($data);
		if (!$booking) {
			echo "There was an error.";
		} else {
			echo 'Booking added.';
		}
	}

	$createData = getCreateData();
 ?>

<div class="outer-wrapper">
	<h2>Add a Booking</h2>

	<form action="" method="POST">
		<table border="1" cellspacing="0">
			<thead>
				<td>User ID / Name</td>
				<td>Room Number</td>
				<td>Booking Date</td>
				<td>Booking Time 1</td>
				<td>Length</td>
			</thead>
			<tr>
				<td>
					<select name="userID">
						<option value="default" selected="selected">Users...</option>
						<?php 
						foreach ($createData['userData'] as $item) {
							echo '<option value="'.$item[0].'">'.$item[0].' / '.$item[1].'</option>';
						}
						 ?>
					</select>
				</td>
				<td>
					<select name="room">
						<option value="default" selected="selected">Rooms...</option>
						<?php 
						foreach ($createData['roomData'] as $item) {
							echo '<option value="'.$item[0].'">'.$item[0].' / '.$item[1].'</option>';
						}
						 ?>
					</select>
				</td>
				<td><input type="date" name="date"></td>
				<td>
					<select name="time1">
						<option value="default">Time...</option>
						<?php 
						foreach ($createData['timeData'] as $item) {
							echo '<option value="'.$item[0].'">'.$item[1].'</option>';
						}
						 ?>
					</select>
				</td>
				<td>
					<select name="length">
						<option value="1-hour" selected="selected">1 Hour</option>
						<option value="2-hours">2 Hours</option>
					</select>
				</td>
			</tr>
		</table>

		<br>
		<input type="submit" name="submit">
	</form>
</div>