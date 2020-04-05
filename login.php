<?php
session_start();
// Change this to your connection info.
include 'config.php';
$alert='';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (isset($_POST['username'])) {
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	$alert = '<div class="alert alert-danger" role="alert">
Bitte alles ausfüllen.
</div>';

}


// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password, location, age, hash FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
  if ($stmt->num_rows > 0) {
  	$stmt->bind_result($id, $password, $location, $age, $hash);
  	$stmt->fetch();
  	// Account exists, now we verify the password.
  	// Note: remewmber to use password_hash in your registration file to store the hashed passwords.
  	if (password_verify($_POST['password'], $password)) {
  		// Verification success! User has loggedin!
  		// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
  		session_regenerate_id();
  		$_SESSION['loggedin'] = TRUE;
  		$_SESSION['name'] = $_POST['username'];
  		$_SESSION['id'] = $id;
			$_SESSION['age'] = $age;
			$_SESSION['location'] = $location;
			$_SESSION['hash']=$hash;
  		header('Location: home.php');
  	} else {
			$alert = '<div class="alert alert-danger" role="alert">
		Falsches Passwort
		</div>';
  	}
  } else {
		$alert = '<div class="alert alert-danger" role="alert">
	Falscher Benutzername
	</div>';
  }

	$stmt->close();
}}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login // Uwonym</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="height:100%">
    <div class="login-clean" style="height:100%">
        <form method="post">
            <h2 class="sr-only">Login</h2>
            <div class="illustration"><i class="icon ion-heart"></i></div>
						<?php echo ($alert);?>
            <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Benutzername"></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Passwort"></div>
            <div class="form-group"><input type="submit" class="btn btn-primary btn-block" role="button" value="Login"></a><a href="reset.php" class="forgot">Email oder Passwort vergessen?</a><a class="btn btn-primary btn-block" role="button" href="register.pho">Registrieren</a></div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
