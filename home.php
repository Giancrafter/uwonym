<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the useer is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
if ($_SESSION['age']=='0') {
	header('Location: setup.php');
	exit;
}
include 'config.php';
function lovehash($h1, $h2){
$percent=0;
for($i = 0; $i < 11; $i++){
if ($h1[$i]==$h2[$i]){
$percent++;

}}
return 100/12*$percent;
};

//DISTANZ
function distance($lat1, $lon1, $lat2, $lon2, $unit="K") {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if($stmt = $con->prepare('SELECT accounts.gender, accounts.search, swiss.E, swiss.N FROM accounts LEFT JOIN swiss ON (swiss.plz = accounts.location) WHERE accounts.id= ?')){
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($gender, $search, $owne, $ownn);
$stmt->fetch();
$stmt->close();
}


if ($stmt = $con->prepare('SELECT * FROM accounts WHERE gender = ? AND search = ? AND NOT id = ?')) {
$stmt->bind_param('iii', $search, $gender, $_SESSION['id']);
$stmt->execute();
$user = array();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
        $user[]=$row['username'];
				$desc[]=$row['description'];
				$age[]=$row['age'];
				$loc[]=$row['location'];
				$url[]=$row['profile_pic'];
				$hash[]=$row['hash'];
    }


for($i = 0; $i < count($loc); ++$i) {
	if ($stmt = $con->prepare('SELECT E, N FROM swiss WHERE plz = ?')){
			$stmt->bind_param('i', $loc[$i]);
			$stmt->execute();
			$stmt->bind_result($ue, $un);
			$stmt->fetch();
			$stmt->close();
	}
$distance[$i]=distance($ownn, $owne, $un, $ue);
}
//array_multisort($distance, $descr, $age, $user, $url, $hash);

$lovers="";
for($i = 0; $i < count($user); ++$i) {
$lovers.= '
<div class="card">
		<div class="card-body"><img height="100px" width="100px" src="'.$url[$i].'" />
				<h4 class="card-title" style="margin-bottom: 5px;">'.$user[$i].'</h4>
				<i>'.$desc[$i].'</i>
				<p style="margin-bottom: 0;"><i class="fa fa-user" style="color: rgb(0,181,62);padding-right: 20px;"></i>'.$age[$i].' Jahre alt</p>
				<p style="margin-bottom: 0;"><i class="fa fa-heart" style="color: rgb(249,0,0);margin-right: 16px;"></i>'.lovehash($_SESSION['hash'], $hash[$i]).'% Matching</p>
				<p style="margin-bottom: 0;"><i class="fa fa-location-arrow" style="color: rgb(0,128,255);margin-right: 19px;"></i>'.round($distance[$i]).' km</p><a class="card-link" href="#">Profil ansehen</a><a class="card-link" href="#">Nachricht senden</a></div>
</div>
</div>';


}

}
//, profile_pic, description, hash, age, location
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Uwonym Backend</title>
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
                    <li class="nav-item" role="presentation"><a class="nav-link" href="home.php"><strong>Home</strong></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="chat.php">Chat</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="profile.php">Mein Profil</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link text-danger border-warning" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main style="padding: 20px;">
        <h3>Partner finden</h3>
        <div class="dropdown"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Sortieren nach&nbsp;</button>
            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">Matching</a><a class="dropdown-item" role="presentation" href="#">Entfernung</a><a class="dropdown-item" role="presentation" href="#">Alter</a></div>
        </div>
    </main>
		<?php echo $lovers;?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
