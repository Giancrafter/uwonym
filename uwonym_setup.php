<?php
if (isset ($_POST['name'])){
$myfile = fopen("config.php", "w") or die("Unable to open file!");
$txt = '<?php
//SITE CONFIG
$SITE_NAME = \''.$_POST['name'].'\'; //INSERT YOUR SITE NAME
$SITE_DOMAIN = \''.$_POST['domain'].'\'; //INSERT SITE DOMAIN, FOR EXAMPLE google.com


//MYSQL CONFIG
$DATABASE_HOST = \''.$_POST['dbhost'].'\';
$DATABASE_USER = \''.$_POST['dbuser'].'\'; //INSERT DB USER HERE
$DATABASE_PASS = \''.$_POST['dbpass'].'\'; //INSERT DB PASSWORD HERE
$DATABASE_NAME = \''.$_POST['dbname'].'\'; //INSERT DB NAME HERE
$DATABASE_PORT = \''.$_POST['dbport'].'\'; //INSERT DB PORT HERE


//SMTP CONFIG
$SMTP_HOST = \''.$_POST['smtphost'].'\'; //INSERT SMTP HOST HERE
$SMTP_AUTH = true; //ENABLE SMTP AUTHENTICATION
$SMTP_USERNAME = \''.$_POST['smtpuser'].'\'; //INSERT SMTP USER NAME HERE
$SMTP_PASSWORD = \''.$_POST['smtppass'].'\'; //INSERT SMTP PASSWORD HERE
$SMTP_ENCRYPTION = \''.$_POST['smtpencryption'].'\'; //SET SMTP ENCRYPTION MODE
$SMTP_PORT = \''.$_POST['smtpport'].'\'; //SET SMTP PORT
?>';
fwrite($myfile, $txt);
fclose($myfile);
$mysqli = new mysqli($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'], $_POST['dbport']);
$mysqli -> query("CREATE TABLE IF NOT EXISTS `accounts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `password` varchar(255) NOT NULL,
 `email` varchar(100) NOT NULL,
 `activation_code` varchar(50) DEFAULT '',
 `profile_pic` varchar(10000) NOT NULL DEFAULT 'https://images.assetsdelivery.com/compings_v2/kritchanut/kritchanut1406/kritchanut140600093.jpg',
 `age` int(11) NOT NULL DEFAULT '0',
 `reset_token` varchar(50) DEFAULT NULL,
 `gender` int(1) NOT NULL DEFAULT '3',
 `search` int(1) DEFAULT '3',
 `description` varchar(10000) DEFAULT 'Keine Beschreibung :(',
 `location` int(4) NOT NULL DEFAULT '0',
 `hash` varchar(12) NOT NULL DEFAULT '222222222222',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8");

  	header('Location: index.html');
  	exit;



}





?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Setup</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <main style="padding: 30px;">
        <div>
            <h3>Uwomym - Setup</h3>
            <hr>
        </div>
        <form method="post">
            <div class="input-group" style="margin: 10px;margin-right: 0;margin-left: 0;">
                <div class="input-group-prepend"><span class="input-group-text">Seitenname</span></div><input class="form-control" type="text" name="name">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Domain</span></div><input class="form-control" type="text" name="domain">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 30px;">
                <div class="input-group-prepend"><span class="input-group-text">Datenbank-Host</span></div><input class="form-control" type="text" name="dbhost" value="localhost">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">Datenbank-Name</span></div><input class="form-control" type="text" name="dbname">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">Datenbank-Port</span></div><input class="form-control" type="text" name="dbport">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">Datenbank-Benutzername</span></div><input class="form-control" type="text" name="dbuser">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">Datenbank-Passwort</span></div><input class="form-control" type="text" name="dbpass">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 30px;">
                <div class="input-group-prepend"><span class="input-group-text">SMTP-Host</span></div><input class="form-control" type="text" name="smtphost">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">SMTP-Benutzername</span></div><input class="form-control" type="text" name="smtpuser">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">SMTP-Passwort</span></div><input class="form-control" type="text" name="smtppass">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">SMTP-Verschlüsselung</span></div><input class="form-control" type="text" name="smtpencryption">
                <div class="input-group-append"></div>
            </div>
            <div class="input-group" style="margin-top: 10px;">
                <div class="input-group-prepend"><span class="input-group-text">SMTP-Port</span></div><input class="form-control" type="text" name="smtpport">
                <div class="input-group-append"></div>
            </div><input class="btn btn-primary" type="submit" value="Speichern" style="margin-top:10px;" /></form>
    </main>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
