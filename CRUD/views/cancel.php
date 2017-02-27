<?php 

if (isset($_POST['submit'])) {
	$data = array();

	$data['username'] = $_POST['username'];
	$data['id'] = $_POST['id'];

	$cancelled = cancelBooking($data);
	if (!$cancelled) {
		echo 'There was an error.';
	} else {
		echo 'Booking updated.';
	}
}

 ?>

<div class="outer-wrapper">
	<h2>Cancel a Booking</h2>

	<form action="" method="POST">
		<table border="1" cellspacing="0">
			<thead>
				<td>Booking ID</td>
				<td>Username</td>
			</thead>
			<tr>
				<td>
					<input type="text" name="id" placeholder="Booking ID">
				</td>
				<td>
					<input type="text" name="username" placeholder="Username">
				</td>
			</tr>
		</table>

		<br>
		<input type="submit" name="submit">
	</form>
</div>