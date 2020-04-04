<?php
// Change this to your connection info.
include 'config.php';
$alert = '<div class="alert alert-danger" role="alert">
Ein Fehler ist aufgetreten.
</div>';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code'])) {
	if ($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
		$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			// Account exists with the requested email and code.
			if ($stmt = $con->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
				// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
				$newcode = 'activated';
				$stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
				$stmt->execute();
				$alert = '<div class="alert alert-success" role="alert">
			Dein Konto ist jetzt aktiv, du kannst dich nun <br><a href="login.php">anmelden</a>!
			</div>';
			}
		} else {
			$alert = '<div class="alert alert-danger" role="alert">
	Dieses Konto wurde bereits aktiviert oder existiert nicht.
		</div>';
		}
	}
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Account aktivieren // Uwonym</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="login-clean">
    <div class="register-photo">
        <div class="form-container" style="background-color:#ffffff; padding:20px;">

                <h2 class="text-center"><strong>Account bestätigen</strong></h2>
                <?php echo ($alert);?>

      </div>

    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
