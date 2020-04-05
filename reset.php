<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$alert = '';
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
include 'config.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if( isset($_POST['email']) )
{
// Try and connect using the info above.

if (mysqli_connect_errno()) {
	// If there is an error dwith the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data was submitted, isset() function will check if the data exists.

// Make sure the submitted registration values are not empty.
// We need to check if the account with that username exists.

	// Store the result so we can check if the account exists in the database.

    // Username doesnt exists, insert new account
    if ($stmt = $con->prepare('UPDATE accounts SET reset_token = ? WHERE email = ?')) {
    	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
      $uniqid = uniqid();
      $stmt->bind_param('ss', $uniqid, $_POST['email']);
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
    $mail->addAddress($_POST['email'], $_POST['email']); // Add a recipient address

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Passwort zurücksetzen // '.$SITE_NAME;
		$activate_link = 'https://'.$SITE_DOMAIN.'/password.php?email=' . $_POST['email'] . '&code=' . $uniqid;

    $mail->Body    =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
													"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<title>Passwort zurücksetzen // '.$SITE_NAME.'</title>
		</head>
		<body style="margin: 0; padding: 0;">
		<h1>'.$SITE_NAME.'</h1>
		<hr />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><p style="margin-top: 20px;">
Hallo, <br />
		Bitte klicke <a href="' . $activate_link .'">hier</a>, um dein Passwort zurückzusetzen<br />
		 Klappt der Link nicht nicht? Kopiere ihn von Hand in deinen Browser: '.$activate_link.'<br /><br />Dein '.$SITE_NAME.'-Team</p></td>
	</tr>
</table>
</body>
';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
		$alert = '<div class="alert alert-success" role="alert">
	Wir haben dir eine Mail zum Zurücksetzen deines Passworts gesendet.
	</div>';
} catch (Exception $e) {
	$alert = '<div class="alert alert-danger" role="alert">
Ein unbekannter Fehler ist aufgetreten.
</div>';
}}	$stmt->close();}




$con->close();



?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Passwort zurücksetzen// <?php echo $SITE_NAME; ?></title>
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
                <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email"></div>
                <div class="form-group"><input type=submit value="Zurücksetzen" class="btn btn-primary btn-block" role="button"></div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
