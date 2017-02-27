<?php 
	if (isset($_POST['submit'])) {
		$data = array();

		$data['userName'] = $_POST['name'];
		$data['facebookId'] = $_POST['facebookId'];

		$user = addUser($data);
		if (!$user) {
			echo 'There was an error.';
		} else {
			echo 'User added.';
		}
	}
 ?>

<div class="outer-wrapper">
	<h2>Add a User</h2>

	<form action="" method="POST">
		<table border="1" cellspacing="0">
			<thead>
				<td>User Name</td>
				<td>Facebook ID</td>
			</thead>
			<tr>
				<td>
					<input type="text" name="name" placeholder="Username">
				</td>
				<td>
					<input type="text" name="facebookId" placeholder="Facebook ID">
				</td>
				
			</tr>
		</table>

		<br>
		<input type="submit" name="submit">
	</form>
</div>