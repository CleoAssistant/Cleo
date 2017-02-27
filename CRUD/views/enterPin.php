<?php 

if (isset($_POST['submit'])) {
	$data = array();

	$data['username'] = $_POST['username'];
	$data['pin'] = $_POST['pin'];

	$updated = checkPin($data);
	if (!$updated) {
		echo 'There was an error.';
	} else {
		echo 'Booking updated.';
	}
}

 ?>

<div class="outer-wrapper">
	<h2>Enter a Pin</h2>

	<form action="" method="POST">
		<table border="1" cellspacing="0">
			<thead>
				<td>Username</td>
				<td>Pin Number</td>
			</thead>
			<tr>
				<td>
					<input type="text" name="username" placeholder="Username">
				</td>
				<td>
					<input type="text" name="pin" placeholder="PIN">
				</td>
			</tr>
		</table>

		<br>
		<input type="submit" name="submit">
	</form>
</div>