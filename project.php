<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once('db/config.php');
require_once('const/web-info.php');
require_once('const/check_session.php');
require_once('const/my_profile.php');
switch($res) {
	
	case '1':
	$logged = "1";
	break;
}



if (!file_exists("results.json")) {
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}
$json = file_get_contents('results.json');
 $data = json_decode($json,true);
  if (!empty($data["posts"][0]["output"])) {
    $output = $data["posts"][0]["output"];
  } else { $output = 'Done'; }
  if ($output=='Done') {
  try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time='Waiting list' ORDER BY c_id LIMIT 1");
$stmt->execute();
$result = $stmt->fetchAll();
if (count($result) < 1) {
  
} else {
$response = array();
$posts = array();
foreach($result as $row)
{
$st1 = preg_replace("/[^a-zA-Z]/", " ", $row[1]);
$st2 =  preg_replace('/\s+/', ' ', $st1);
$item_title = strtolower(str_replace(' ', '-', $st2));

$title = $row[1];
$description = $row[3];
$year = $row[4];
$run_time = $row[5];
$plan = $row[6];
$age = $row[7];
$type = $row[8];
$genres = $row[9];
$thumbnail = $row[10];
$trailer = $row[11];
$upload_date = $row[12];
$rates = $row[14];
$views = $row[15];
$email = $row[17];

if ($rates < 10) {
  $rates = ''.$rates.'.0';
}
$posts[] = array('ID'=> $row[2], 'name'=> $title, 'lineage'=> $age, 'mode'=> $genres, 'input'=> $description, 'output'=> 'Running', 'type'=> $type, 'email'=> $email);

$stmt = $conn->prepare("UPDATE tbl_items SET run_time='Running' WHERE item_id = ?");
$stmt->execute([$row[2]]);

}
$response['posts'] = $posts;

$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
}
$dir = "./";
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/slider-radio.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/plyr.css">
	<link rel="stylesheet" href="css/main.css">
	<link type="text/css" rel="stylesheet" href="plugins/loader/waitMe.css">
	<link rel="icon" href="icon/<?php echo AppIcon; ?>" sizes="32x32">
	<meta name="description" content="<?php echo AppDesc; ?>">
	<meta name="keywords" content="<?php echo AppKeywords; ?>">
	<meta name="author" content="ASahbou">
	<meta name="DC.title" content="BuscoPhylo: A webserver for Busco-Based Phylogenomic Analysis for non-specialists" />
    <meta name="DC.creator" content="Alae-Eddine Sahbou, Driss Iraqi, Rachid Mentag, Slimane KHAYI" />
    <meta name="DC.subject" content="Phylogenomics, Webserver, Genome, Evolution" />
    <meta name="DC.Date" content="August 29th, 2022" />
    <meta name="DC.description" content="BuscoPhylo is an efficient and user-friendly web server freely accessible at
https://buscophylo.inra.org.ma/. The source code, along with documentation, is freely available under an
MIT license at https://github.com/alaesahbou/BuscoPhylo." />
    <meta name="DC.type" content="Webserver" />
    
	<script src="https://kit.fontawesome.com/d4e23f03c3.js" crossorigin="anonymous"></script>
	<title><?php echo AppName; ?></title>
  <?php require_once('const/cms_scripts.php'); ?>
  
              <style>
                  .progress {
  margin: 10px auto;
  position: relative;
  width: 90%;
}
.bar {
  background: #E28413;
  height: 20px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  transition: width 0.3s ease 0s;
  width: 0;
}
.percent {
  color: white;
  left: 48%;
  position: absolute;
  top: 0;
}
              </style>

</head>

<body>

	<?php require_once('const/draws/header.php'); ?>
	<?php require_once('const/draws/catalog.php'); ?>
	<?php require_once('const/draws/footer.php'); ?>
	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="plugins/loader/waitMe.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/slider-radio.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/smooth-scrollbar.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/plyr.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/home_catalog.js"></script>
	<script src="lineage_list/type.js?v=2"></script>
	<script type="text/javascript" src="js/upload/jquery.js"></script>
    <script type="text/javascript" src="js/upload/jquery.form.js"></script>
    <script type="text/javascript" src="js/upload/script.js?v=2"></script>
</body>

</html>
