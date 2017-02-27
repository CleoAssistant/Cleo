<div class="outer-wrapper">
	<h2>View Confirmed Bookings</h2>

	<table border="1" cellspacing="0">
		<thead>
			<td>Booking ID</td>
			<td>User ID</td>
			<td>User Name</td>
			<td>Room Number</td>
			<td>Booking Date</td>
			<td>Booking Time</td>
			<td>Booking Length</td>
			<td>Status</td>
		</thead>
		<?php 
			$results = getConfirmedBookings();


			if (mysqli_num_rows($results) == 0) {
				echo '<tr><td colspan="8">There are no confirmed bookings.</td></tr>';
			} else {
				while ($row = mysqli_fetch_assoc($results)) {
					echo '<tr>';
					echo '<td>'.$row['b_id'].'</td>';
					echo '<td>'.$row['b_userId'].'</td>';
					echo '<td>'.$row['u_username'].'</td>';
					echo '<td>'.$row['b_roomId'].'</td>';
					echo '<td>'.$row['b_bookingDate'].'</td>';
					echo '<td>'.$row['b_timeSlot1Id'].'</td>';
					echo '<td>'.$row['b_length'].'</td>';
					echo '<td>'.$row['b_status'].'</td>';
					echo '</tr>';
				}
			}

		 ?>
	</table>
</div>