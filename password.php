<?php
// Change this to your connection info.
include 'config.php';
$alert = '<div class="alert alert-success" role="alert">
Bitte gib ein neues Passwort ein.
</div>';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if( isset($_POST['email']) )
{
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// First we check if the email and code exists...
if (isset($_POST['email'], $_POST['code'], $_POST['pw'])) {

	if ($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND reset_token = ?')) {
		$stmt->bind_param('ss', $_POST['email'], $_POST['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();


		if ($stmt->num_rows > 0 and $_POST['code'] != 0) {

			// Account exists with the requested email and code.
			if ($stmt2 = $con->prepare('UPDATE accounts SET reset_token = ?, password = ? WHERE email = ?')) {
				// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
				$newcode = undefined;
        $pw_hash=password_hash($_POST['pw'], PASSWORD_DEFAULT);
				$stmt2->bind_param('sss',$newcode, $pw_hash, $_POST['email']);
				$stmt2->execute();

				$alert = '<div class="alert alert-success" role="alert">
			Dein Passwort wurde erfolgreich zurückgesetzt, du kannst dich nun <br><a href="login.php">anmelden</a>!
			</div>';
			}
		} else {
			$alert = '<div class="alert alert-danger" role="alert">
Der angegebene Aktivierungslink ist falsch oder abgelaufen.
		</div>';
		}
	}
}}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Passwort zurücksetzen // Uwonym</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="register-photo" style="height:100%;">
        <div class="form-container">
            <div class="image-holder" style="background-image: url(assets/img/boyfriend-girlfriend-relationship.jpg);"></div>
            <form method="post" autocomplete="off">
                <h2 class="text-center"><strong>Passwort zurücksetzen</strong></h2>
                <?php echo ($alert);?>
                <div class="form-group"><input class="form-control" style="display:none;" type="text" name="code" placeholder="Code" value="<?php echo $_GET['code'];?>"></div>
                <div class="form-group"><input class="form-control" style="display:none;" type="email" name="email" placeholder="Email" value="<?php echo $_GET['email'];?>"></div>
                <div class="form-group"><input class="form-control" type="password" name="pw" placeholder="Neues Passwort"></div>
                <div class="form-group"><input type=submit value="Zurücksetzen" class="btn btn-primary btn-block" role="button"></form>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
