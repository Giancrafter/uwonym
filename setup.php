<?php
// Change this to your connection info.
include 'config.php';
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
if( isset($_POST['age']) and isset($_POST['location']) and isset($_POST['profile_url']) and isset($_POST['description']))
{
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if (mysqli_connect_errno()) {
	// If there is an error dwith the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$_SESSION['age'] = $_POST['age'];
// We need to check if the account with that username exists.
$stmt = $con->prepare('UPDATE accounts SET location = ?, description = ?, age = ?, profile_pic = ? WHERE id = ?');

	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('ssssi', $_POST['location'], $_POST['description'], $_POST['age'], $_POST['profile_url'], $_SESSION['id']);
	$stmt->execute();
  $stmt->close();
header("Location: profile.php");

	// Store the result so we can check if the account exists in the database.
$con->close();}


?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Account vervolständigen // <?php echo $SITE_NAME;?></title>
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
            <form action="" method="post" autocomplete="off">
                <h2 class="text-center"><strong>Account vervollständigen</strong></h2>
                <?php echo ($alert);?>
                <div class="form-group">URL zu deinem Profilbild.<input class="form-control" type="text" name="profile_url" placeholder="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/1920px-Google_2015_logo.svg.png" required></input></div>
                <div class="form-group">Beschreibung:<textarea class="form-control" name="description" placeholder="Hey! Mein Name ist Max Muster. Meine Hobbies sind..." required></textarea></div>
                <div class="form-group">Alter:<input class="form-control" type="number" name="age" placeholder="15" min="12" max="20" required></input></div>
                <div class="form-group">PLZ (Schweiz):<input class="form-control" type="number" name="location" placeholder="1234" min="1000" max="9999" required></div>

                <div class="form-group"><input type=submit class="btn btn-primary btn-block" role="button" value="Speichern"></a></div>
              </form>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
