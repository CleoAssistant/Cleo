<div class="outer-wrapper">
	<h2>All Users</h2>

	<table border="1" cellspacing="0">
		<thead>
			<td>User ID</td>
			<td>Facebook ID</td>
			<td>User Name</td>
		</thead>
		<?php 
			$results = getUsers();


			if (mysqli_num_rows($results) == 0) {
				echo '<tr><td colspan="7">There are no users.</td></tr>';
			} else {
				while ($row = mysqli_fetch_assoc($results)) {
					echo '<tr>';
					echo '<td>'.$row['u_id'].'</td>';
					echo '<td>'.$row['u_facebookId'].'</td>';
					echo '<td>'.$row['u_username'].'</td>';
					echo '</tr>';
				}
			}

		 ?>
	</table>
</div>