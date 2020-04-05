<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the useer is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include 'config.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if (mysqli_connect_errno()) {
	// If there is an error dwith the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if (empty($_SESSION['fragebogen'])){$_SESSION['fragebogen']=1;}
if (isset($_POST['11'])){
$_SESSION['fragebogen'] = 2;
$_SESSION['d1'] = $_POST['11'].$_POST['12'].$_POST['13'].$_POST['14'];
}
if (isset($_POST['21'])){
$_SESSION['fragebogen'] = 3;
$_SESSION['d2'] = $_POST['21'].$_POST['22'].$_POST['23'].$_POST['24'];
}
if (isset($_POST['31'])){
$_SESSION['d3'] = $_POST['31'].$_POST['32'].$_POST['33'].$_POST['34'];
$hash = $_SESSION['d1'].$_SESSION['d2'].$_SESSION['d3'];
if ($stmt = $con->prepare('UPDATE accounts SET hash = ? WHERE id = ?')) {
$stmt->bind_param('si', $hash, $_SESSION['id']);
$stmt->execute();
$stmt->close();
$_SESSION['fragebogen']="";
header("Location: profile.php");
}
}
if ($_SESSION['fragebogen']==1) {echo '
  <!DOCTYPE html>
  <html>

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>Fragebogen // Uwonym</title>
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/styles.css">
  </head>

  <body><nav class="navbar navbar-light navbar-expand-md">
      <div class="container-fluid"><a href="#" class="navbar-brand">uwonym.</a><a href="#" class="navbar-brand">Fragebogen<i class="fa fa-heart" style="color:red;"></i></a>

      </div>
  </nav>
      <div class="container">
          <form method="post"><label><strong>1. Persönliche Interessen</strong></label><label class="d-block">Ich bin lieber...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="11" id="11a" value="0"><label class="form-check-label" for="11a">...Draussen</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="11" id="11b" value="1"><label class="form-check-label" for="11b">...Drinnen</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Ich habe lieber...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="12" id="12a" value="0"><label class="form-check-label" for="12a">...warm</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="12" id="12b" value="1"><label class="form-check-label d-inline" for="12b">...kalt</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Ich habe lieber...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="13" id="13a" value="0"><label class="form-check-label" for="13a">...Sommer</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="13" id="13b" value="1"><label class="form-check-label d-inline" for="13b">...Winter</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Ich bin eher...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="14" id="14a" value="0><label class="form-check-label" for="14a">...abenteuerlich</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="14" id="14b" value="1"><label class="form-check-label d-inline" for="14b">...ruhig</label></div>
              <br><input class="btn btn-danger" role="button" type="submit" style="margin-top: 25px;" value="Weiter"></input>
          </form>
      </div>
      <div class="container"></div>
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>

  </html>
  ';}
if ($_SESSION['fragebogen']==2) {echo '
  <!DOCTYPE html>
  <html>

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>Fragebogen // Uwonym</title>
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/styles.css">
  </head>

  <body><nav class="navbar navbar-light navbar-expand-md">
      <div class="container-fluid"><a href="#" class="navbar-brand">uwonym.</a><a href="#" class="navbar-brand">Fragebogen<i class="fa fa-heart" style="color:red;"></i></a>

      </div>
  </nav>
      <div class="container">
          <form method="post"><label><strong>2. Ihr Sexualleben</strong><br></label><label class="d-block">Ich will...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="21" id="21a" value="0"><label class="form-check-label" for="21a">...direkt Geschlechtsverkehr</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="21" id="21b" value="1"><label class="form-check-label" for="21b">...es langsam angehen</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Ich will eine Beziehung...</label>
              <div
                  class="form-check d-block"><input class="form-check-input" type="radio" name="22" id="22a" value="0"><label class="form-check-label" for="22a">...wegen sexuellem Trieb</label></div>
      <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="22" id="22b" value="1"><label class="form-check-label d-inline" for="22b">...um die Liebe fürs Leben zu finden.</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Bin ich noch Jungfrau?</label>
      <div
          class="form-check d-block"><input class="form-check-input" type="radio" name="23" id="23a" value="0"><label class="form-check-label" for="23a">Ja</label></div>
          <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="23" id="23b" value="1"><label class="form-check-label d-inline" for="23b">Nein</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Verhütung ist...</label>
          <div class="form-check d-block"><input class="form-check-input" type="radio" name="24" id="24a" value="0"><label class="form-check-label" for="24a">...für mich Pflicht</label></div>
          <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="24" id="24b" value="1"><label class="form-check-label d-inline" for="24b">...für mich unnötig</label></div>
          <br><input class="btn btn-danger" type="submit" value="Weiter"></input>
          </form>
          </div>
          <script src="assets/js/jquery.min.js"></script>
          <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>

  </html>
';}
if ($_SESSION['fragebogen']==3) {echo '
  <!DOCTYPE html>
  <html>

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>Fragebogen // Uwonym</title>
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/styles.css">
  </head>

  <body><nav class="navbar navbar-light navbar-expand-md">
      <div class="container-fluid"><a href="#" class="navbar-brand">uwonym.</a><a href="#" class="navbar-brand">Fragebogen<i class="fa fa-heart" style="color:red;"></i></a>

      </div>
  </nav>
      <div class="container">
          <form method="post"><label><strong>3. Ihre Weltansichten</strong><br></label><label class="d-block">Klimaschutz...</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="31" id="31a" value="0"><label class="form-check-label" for="31a">...ist sehr wichtig</label></div>
              <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="31" id="31b" value="1"><label class="form-check-label" for="31b">...es langsam angehen</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Ernähren Sie sich vegetarisch/vegan?</label>
              <div class="form-check d-block"><input class="form-check-input" type="radio" name="32" id="32a" value="0"><label class="form-check-label" for="32a">Ja</label></div>
      <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="32" id="32b" value="1"><label class="form-check-label d-inline" for="32b">Nein</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Glauben Sie an Gott?</label>
      <div class="form-check d-block"><input class="form-check-input" type="radio" name="33" id="33a" value="0"><label class="form-check-label" for="33a">Ja</label></div>
      <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="33" id="33b" value="1"><label class="form-check-label d-inline" for="33b">Nein</label></div><label class="d-block d-xl-flex" style="margin-top: 25px;">Wollen Sie Kinder?</label>
      <div class="form-check d-block"><input class="form-check-input" type="radio" name="34" id="34a" value="0"><label class="form-check-label" for="34a">Eher Ja</label></div>
      <div class="form-check d-inline-block"><input class="form-check-input" type="radio" name="34" id="34b" value="1"><label class="form-check-label d-inline" for="34b">Eher nein</label></div>
      <br><input class="btn btn-danger" type="submit"  value="Weiter"></input>
      </form>
      </div>
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
  </html>
';}
