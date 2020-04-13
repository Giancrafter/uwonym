<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include 'config.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//UPDATE

if (isset($_POST['changename'])) {
if ($stmtu = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
$stmtu->bind_param('s', $_POST['changename']);
$stmtu->execute();
$stmtu->store_result();

if ($stmtu->num_rows > 0) {
    // Username already exists
    $alert = '<div class="alert alert-danger" role="alert">
	Benutzername existiert bereits
	</div>';} else {
$stmt = $con->prepare('UPDATE accounts SET username = ?, gender = ?, search = ?, description = ?, age = ? location = ? WHERE id = ?');
$stmt->bind_param('siissiii', $_POST['changename'], $_POST['changegender'],  $_POST['changegender2'], $_POST['changedescription'], $_POST['changeage'], $_POST['changelocation'], $_SESSION['id']);
$stmt->execute();
$stmt->close();

$alert = '<div class="alert alert-success" role="alert">
Profil erfolgreich bearbeitet.
</div>';
}


$stmt = $con->prepare('SELECT password, email, gender, search, profile_pic, description, hash, age, location FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $gender, $search, $profile_pic, $description, $hash, $age, $location);
$stmt->fetch();
$stmt->close();
$name = $_SESSION['name'];
if ($gender==1){$genders='Männlich';}
if ($gender==2){$genders='Weiblich';}
if ($gender==3){$genders='Nicht definiert';}
if ($search==1){$searchs='Mann';}
if ($search==2){$searchs='Frau';}
if ($search==3){$searchs='Egal';}
$stmt = $con->prepare('SELECT name, canton FROM swiss WHERE plz = ?');
$stmt->bind_param('s', $location);
$stmt->execute();
$stmt->bind_result($locname, $canton);
$stmt->fetch();
$stmt->close();
$content= $alert . '<form method="get" style="margin-bottom: 30px;"><input type="text" style="display:none;" name="edit" value="1"/><img height="100px" width="100px" src="'.$profile_pic.'" /><br />
<p>'.$_SESSION['name'].'</p>
<p><i>'.$description.'</i></p>
<p><strong>Alter:</strong>'.$age.' Jahre</p>
<p><strong>Ist:</strong>'.$genders.'<br><strong>Sucht:</strong>'.$searchs.'<br></p>
<p><strong>Wohnort:</strong>'.$location.' '.$locname.', '.$canton.'<br><br><input type="submit" class="btn btn-primary d-flex align-items-end" type="button"value="Profil bearbeiten"></p>
	</form>';


if (isset($_GET['edit'])) {
    if ($gender==1) {$t1='checked';}else{$t1='';}
    if ($gender==2) {$t2='checked';}else{$t2='';}
    if ($gender==3) {$t3='checked';}else{$t3='';}
    if ($search==1) {$t4='checked';}else{$t4='';}
    if ($search==2) {$t5='checked';}else{$t5='';}
    if ($search==3) {$t6='checked';}else{$t6='';}
$content = '<<form action="profile.php" method="post">
    
<p><strong>Profilbild: </strong><input class="form-control" type="text" name="changeimage" value="'.$profile_pic.'" /></p>
<p><strong>Benutzername: </strong><input class="form-control" type="text" name="changename" value="'.$_SESSION['name'].'" /></p>
<p><strong>Beschreibung: </strong><input class="form-control" type="text" name="changedescription" value="'.$description.'" /></p>
<p><strong>Alter: </strong><input class="form-control" type="number" name="changeage" value="'.$age.'" min="12" max="20" required /></p>

<p><strong>Geschlecht:</strong><br>
<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender" id="g1" value="1" '.$t1.'>
  <label class="form-check-label" for="g1">
    Männlich
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender" id="g2" value="2" '.$t2.'>
  <label class="form-check-label" for="g2">
    Weiblich
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender" id="g3" value="3" '.$t3.'>
  <label class="form-check-label" for="g3">
    Nicht definiert
  </label>
</div>
		<br /><strong>Ich suche:</strong>
		<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender2" id="s1" value="1" '.$t4.'>
  <label class="form-check-label" for="s1">
    Mann
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender2" id="s2" value="2" '.$t5.'>
  <label class="form-check-label" for="s2">
    Frau
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="changegender2" id="s3" value="3" '.$t6.'>
  <label class="form-check-label" for="s3">
    Egal
  </label>
</div><br />
<p><strong>PLZ: </strong><input class="form-control" type="number" name="changelocation" value="'.$age.'" min="1000" max="9999" required /></p>
    <input type="submit" class="btn btn-primary d-flex align-items-end" type="button"value="Speichern">
	</form>';
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Mein Profil // Uwonym</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md shadow-sm navigation-clean">
        <div class="container"><a class="navbar-brand" href="#">uwonym.</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="chat.php">Chat</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="profile.php"><strong>Mein Profil</strong></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link text-danger border-warning" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main style="padding: 20px;">
      <?php echo $content;?>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
