<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Address Book</title>
		<meta name="description" content="Address Book">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="images/favicon.ico" />

		<!-- Link Bootstrap CSS -->
		<link rel="stylesheet" href="./Public/CSS/Bootstrap/bootstrap.min.css" />
		<link href="./Public/CSS/DataTables/datatables.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="./Public/CSS/styles.css" />
	</head>

	<body class="d-flex flex-column min-vh-100">

		<!-- Navbar -->
		<?php include __DIR__ .'/Layout/Navbar.php'; ?>

		<!-- DataTable - Address List -->
		<div class="container-sm container py-5">
			<div class="row">
				<div class="col">
					<table id="myTable" class="display table table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>First Name</th>
								<th>Email</th>
								<th>Street</th>
								<th>Zip Code</th>
								<th>City</th>
								<th>Actions</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>

		<!-- Form Dialog -->
		<?php include __DIR__ .'/Layout/AddressFormDialog.php'; ?>

		<!-- Footer -->
		<?php include __DIR__ .'/Layout/Footer.php'; ?>

		<!-- Include Bootstrap JS (with Popper.js for dropdowns, modals, etc.) -->
		<script src="./Public/JS/jQuery/jquery.min.js"></script>
		<script src="./Public/JS/Bootstrap/bootstrap.bundle.min.js"></script>
		<script src="./Public/JS/FontAwsome/font-awsome.js"></script>
		<script src="./Public/JS/DataTables/datatables.min.js"></script>
		<script src="./Public/JS/FileSaver/FileSaver.min.js"></script>
		<script src="./Public/JS/main.js"></script>
	</body>

</html>