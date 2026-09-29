<?php
session_start();
require_once('../db/config.php');
require_once('../const/web-info.php');
require_once('../const/check_session.php');
require_once('../const/single_item.php');
require_once('../const/mail.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../mail/src/Exception.php';
require '../mail/src/PHPMailer.php';
require '../mail/src/SMTP.php';
$dir = "../../";

switch($res) {
	case '0':
	$logged = "0";
	break;

	case '1':
	$logged = "1";
	break;

	case '2':
	$logged = "0";
	break;

	case '3':
	$logged = "0";
	break;

}
$dlink = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="../../css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="../../css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="../../css/owl.carousel.min.css">
	<link rel="stylesheet" href="../../css/slider-radio.css">
	<link rel="stylesheet" href="../../css/select2.min.css">
	<link rel="stylesheet" href="../../css/magnific-popup.css">
	<link rel="stylesheet" href="../../css/plyr.css">
	<link rel="stylesheet" href="../../css/main.css">
	<link type="text/css" rel="stylesheet" href="../../plugins/loader/waitMe.css">
	<link rel="icon" href="../../icon/<?php echo AppIcon; ?>" sizes="32x32">
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
	<title><?php echo AppName; ?> – <?php echo $title; ?></title>
  <?php require_once('../const/cms_scripts.php'); ?>
</head>

<body onload="scroll()">

	<?php require_once('../const/draws/header_v2.php'); ?>
	<?php require_once('../const/draws/fna.php'); ?>
	<?php require_once('../const/draws/footer.php'); ?>

	<script>
		function scroll(){
		document.getElementById('scroll').scrollTop = document.getElementById('scroll').scrollHeight;
		}
	</script>
	<script src="../../js/jquery-3.5.1.min.js"></script>
	<script src="../../js/bootstrap.bundle.min.js"></script>
	<script src="../../js/owl.carousel.min.js"></script>
	<script src="../../js/slider-radio.js"></script>
	<script src="../../js/select2.min.js"></script>
	<script src="../../js/smooth-scrollbar.js"></script>
	<script src="../../js/jquery.magnific-popup.min.js"></script>
	<script src="../../js/plyr.min.js"></script>
	<script src="../../js/main.js"></script>
	<script src="../../plugins/loader/waitMe.js"></script>
	<script src="../../js/forms.js"></script>
	<script src="../../js/single_item.js"></script>
</body>

</html>