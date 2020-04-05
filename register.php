<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
include 'config.php';

$alert = '';

if ( isset($_GET['mail'])){
$val=$_GET['mail'];
} else {$val="";}
if( isset($_POST['username']) )
{
//DATABASE CONNECTION
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if (mysqli_connect_errno()) {
	// If there is an error dwith the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
  $alert = '<div class="alert alert-danger" role="alert">
Bitte alles ausfüllen.
</div>';}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['ck'])) {
  $alert = '<div class="alert alert-danger" role="alert">
Bitte alles ausfüllen.
</div>';
} else {

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
    $alert = '<div class="alert alert-danger" role="alert">
Benutzername existiert bereits
</div>';
} else {
	if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
		// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
		$stmt->bind_param('s', $_POST['email']);
		$stmt->execute();
		$stmt->store_result();
		// Store the result so we can check if the account exists in the database.
		if ($stmt->num_rows > 0) {
			// Username already exists
	    $alert = '<div class="alert alert-danger" role="alert">
	Email wurde bereits verwendet
	</div>';
		} else
    // Username doesnt exists, insert new account
    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
    	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
    	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $uniqid = uniqid();
      $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
    	$stmt->execute();
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings                              // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $SMTP_HOST;                   // Specify main and backup SMTP servers
    $mail->SMTPAuth = $SMTP_AUTH;                               // Enable SMTP authentication
    $mail->Username = $SMTP_USERNAME;              // SMTP username
    $mail->Password = $SMTP_PASSWORD;                           // SMTP password
    $mail->SMTPSecure = $SMTP_ENCRYPTION;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $SMTP_PORT;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@'.$SITE_DOMAIN, $SITE_NAME);          //This is the email your form sends From
    $mail->addAddress($_POST['email'], $_POST['username']); // Add a recipient address

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Account aktivieren // '.$SITE_NAME;
		$activate_link = 'https://'.$SITE_DOMAIN.'/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;

    $mail->Body    =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
													"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<title>Account aktivieren // '.$SITE_NAME.'</title>
		</head>
		<body style="margin: 0; padding: 0;">
		<h1>'.$SITE_NAME.'</h1>
		<hr />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><p style="margin-top: 20px;">
Hallo, ' . $_POST['username'] . ', <br />
		Bitte klicke <a href="' . $activate_link .'">hier</a>, um deinen Account zu aktivieren.<br />
		 Klappt der Link nicht nicht? Kopiere ihn von Hand in deinen Browser: '.$activate_link.'<br /><br />Dein '.$SITE_DOMAIN.'-Team</p></td>
	</tr>
</table>
</body>
';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
		$alert = '<div class="alert alert-success" role="alert">
	Wir haben dir eine Mail zur Bestätigung deines Accounts gesendet.
	</div>';
} catch (Exception $e) {
	$alert = '<div class="alert alert-danger" role="alert">
Ein unbekannter Fehler ist aufgetreten.
</div>';
}}}

	$stmt->close();
}} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
  $alert = '<div class="alert alert-danger" role="alert">
Ein unbekannter Fehler ist aufgetreten.
</div>';
}

$con->close();}}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registrieren // <?php echo $SITE_NAME;?></title>
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
            <form action="register.php" method="post" autocomplete="off">
                <h2 class="text-center"><strong>Account erstellen</strong></h2>
                <?php echo ($alert);?>
                <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Benutzername"></div>
                <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $val;?>"></div>
                <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Passwort"></div>
                <div class="form-group">
                    <div class="form-check"><label class="form-check-label"><input class="form-check-input" name="ck" type="checkbox">Ich akzeptiere die Nutzungsbedingungen</label></div>
                </div>
                <div class="form-group"><input type=submit class="btn btn-primary btn-block" role="button"></a></div><a href="login.php" class="already">Haben Sie bereits einen Account? Hier einloggen.</a></form>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
