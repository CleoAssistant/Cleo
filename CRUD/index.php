<?php 
error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born
 ?>

<?php require_once 'functions.php'; ?>

<?php include 'header.php'; ?>

<?php 

if (!isset($_GET['page'])) {
	$page_id = 'home';
} else {
	$page_id = $_GET['page'];
}

switch ($page_id) {
	case 'home':
		include 'views/home.php';
		break;
	case 'create':
		include 'views/create.php';
		break;
	case 'read':
		include 'views/read.php';
		break;
	case 'confirmed':
		include 'views/confirmed.php';
		break;
	case 'cancel':
		include 'views/cancel.php';
		break;
	case 'users':
		include 'views/users.php';
		break;
	case 'add-user':
		include 'views/add-user.php';
		break;
	case 'enter-pin':
		include 'views/enterPin.php';
		break;
	default:
		include 'views/home.php';
		break;
}

 ?>


 <?php include 'footer.php'; ?>