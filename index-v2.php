<?php
session_start();

require_once('db/config.php');

require_once('const/mail.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail/src/Exception.php';
require 'mail/src/PHPMailer.php';
require 'mail/src/SMTP.php';
require_once('db/config.php');
require_once('const/web-info.php');
require_once('const/check_session.php');
require_once('const/my_profile.php');

error_reporting(0);
ini_set('display_errors', 0);

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
$age = $row[7];
$type = "f*";
$genres = $row[9];
$email = $row[17];
$outgroup = $row[15];

if ($rates < 10) {
  $rates = ''.$rates.'.0';
}
$posts[] = array('ID'=> $row[2], 'name'=> $title, 'lineage'=> $age, 'outgroup'=> $outgroup, 'mode'=> $genres, 'input'=> $description, 'output'=> 'Running', 'type'=> $type, 'email'=> $email);

$stmt = $conn->prepare("UPDATE tbl_items SET run_time='Running' WHERE item_id = ?");
$stmt->execute([$row[2]]);

}
$response['posts'] = $posts;

$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

}
$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time='Running'");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row){
    if(file_exists("admin/core/".$row[3]."out/tree.svg")){
        $stmt = $conn->prepare("UPDATE tbl_items SET run_time='Done' WHERE item_id = ?");
        $stmt->execute([$row[2]]);
    }
}

$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time='Done'");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row){
if($row['rates']==0){
$img = '<img src="https://buscophylo.inra.org.ma/img/logo-1.png" style="height:70px;" />';
$email = $row[17];
$message ='Your Project '.$row[1].' Number '.$row[2].' is in Done You can check the result in the link bellow : <br> <span style="color:#FFA500"><a href="https://'.$_SERVER[HTTP_HOST].'/item/'.$row[2].'/'.$row[1].'">https://'.$_SERVER[HTTP_HOST].'/item/'.$row[2].'/'.$row[1].'</a></span>';
$mail_subject = ucwords("BuscoPhylo | ".$row[1]);
$mail_message = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #5d5dd9;">
            <tr>
              <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
              <td class="container" style="font-family: Montserrat;font-size: 16px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
                <div class="content" style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">

                  <table role="presentation" class="main" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #000022;border-radius: 3px;color: white !important;">
                    <tr>
                      <td class="wrapper" style="font-family: Montserrat;font-size: 16px;vertical-align: top;box-sizing: border-box;padding: 20px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
                          <tr>
                            <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">
                              '.$img.'
                              <p style="font-family: Montserrat;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;color: white;">'.$message.'<br></p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

                  <div class="footer" style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
                      <tr>
                        <td class="content-block" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: white;text-align: center;">
                          <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">'.AppName.', All rights reserved '.date('Y').'</span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #FFA500;text-align: center;">
                          Powered by BuscoPhylo.
                        </td>
                      </tr>
                    </table>
                  </div>

                </div>
              </td>
              <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
      </body>';

$mail = new PHPMailer;
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$mail->isSMTP();
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = $mymail_server;
$mail->SMTPAuth = true;
$mail->Username = $mymail_user;
$mail->Password = $mymail_password;
$mail->SMTPSecure = $mymail_conn;
$mail->Port = $mymail_port;

$mail->setFrom($mymail_user, AppName);
$mail->addAddress($row[17]);

$mail->isHTML(true);

$mail->Subject = $mail_subject;
$mail->Body    = $mail_message;
$mail->AltBody = $mail_message;

$mail->send();
$stmt = $conn->prepare("UPDATE tbl_items SET rates=1 WHERE item_id = ?");
        $stmt->execute([$row[2]]);
}
}
/*foreach($result as $row){
if(!file_exists(exec('pwd').'/'.$row[3])){
    $stmt = $conn->prepare("DELETE FROM tbl_items WHERE item_id=?");
    $stmt->execute([$row[2]]);
}
}*/
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE date < NOW() - INTERVAL 30 DAY");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row){
    if($row[2]!="1660667670" && $row[2]!="1660738313"){
    $stmt = $conn->prepare("DELETE FROM tbl_items WHERE item_id=?");
    $stmt->execute([$row[2]]);
    }
}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
} else if($output=="Progress"){
if (!file_exists("results_2.json")) {
    $fp = fopen('results_2.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}
$json = file_get_contents('results_2.json');
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
$age = $row[7];
$type = "f*";
$genres = $row[9];
$email = $row[17];
$outgroup = $row[15];

if ($rates < 10) {
  $rates = ''.$rates.'.0';
}
$posts[] = array('ID'=> $row[2], 'name'=> $title, 'lineage'=> $age, 'outgroup'=> $outgroup, 'mode'=> $genres, 'input'=> $description, 'output'=> 'Running', 'type'=> $type, 'email'=> $email);

$stmt = $conn->prepare("UPDATE tbl_items SET run_time='Running' WHERE item_id = ?");
$stmt->execute([$row[2]]);

}
$response['posts'] = $posts;

$fp = fopen('results_2.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

}
$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time='Running'");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row){
    if(file_exists("admin/core/".$row[3]."out/tree.svg")){
        $stmt = $conn->prepare("UPDATE tbl_items SET run_time='Done' WHERE item_id = ?");
        $stmt->execute([$row[2]]);
    }
}

$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time='Done'");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row){
if($row['rates']==0){
$img = '<img src="https://buscophylo.inra.org.ma/img/logo-1.png" style="height:70px;" />';
$email = $row[17];
$message ='Your Project '.$row[1].' Number '.$row[2].' is in Done You can check the result in the link bellow : <br> <span style="color:#FFA500"><a href="https://'.$_SERVER[HTTP_HOST].'/item/'.$row[2].'/'.$row[1].'">https://'.$_SERVER[HTTP_HOST].'/item/'.$row[2].'/'.$row[1].'</a></span>';
$mail_subject = ucwords("BuscoPhylo | ".$row[1]);
$mail_message = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #5d5dd9;">
            <tr>
              <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
              <td class="container" style="font-family: Montserrat;font-size: 16px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
                <div class="content" style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">

                  <table role="presentation" class="main" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #000022;border-radius: 3px;color: white !important;">
                    <tr>
                      <td class="wrapper" style="font-family: Montserrat;font-size: 16px;vertical-align: top;box-sizing: border-box;padding: 20px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
                          <tr>
                            <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">
                              '.$img.'
                              <p style="font-family: Montserrat;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;color: white;">'.$message.'<br></p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

                  <div class="footer" style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
                      <tr>
                        <td class="content-block" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: white;text-align: center;">
                          <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">'.AppName.', All rights reserved '.date('Y').'</span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #FFA500;text-align: center;">
                          Powered by BuscoPhylo.
                        </td>
                      </tr>
                    </table>
                  </div>

                </div>
              </td>
              <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
      </body>';

$mail = new PHPMailer;
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$mail->isSMTP();
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = $mymail_server;
$mail->SMTPAuth = true;
$mail->Username = $mymail_user;
$mail->Password = $mymail_password;
$mail->SMTPSecure = $mymail_conn;
$mail->Port = $mymail_port;

$mail->setFrom($mymail_user, AppName);
$mail->addAddress($row[17]);

$mail->isHTML(true);

$mail->Subject = $mail_subject;
$mail->Body    = $mail_message;
$mail->AltBody = $mail_message;

$mail->send();
$stmt = $conn->prepare("UPDATE tbl_items SET rates=1 WHERE item_id = ?");
        $stmt->execute([$row[2]]);
}
}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
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
	<link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    
	<title><?php echo AppName; ?> – Home</title>
  <?php require_once('const/cms_scripts.php'); 
  try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$about = "";
$tagline = "";
$cp = "";
$acc = "";
$en = "";

$stmt = $conn->prepare("SELECT * FROM tbl_about LIMIT 1");
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row)
{
  $about = $row[1];
  $tagline = $row[2];
  $cp = $row[3];
  $acc = $row[4];
  $en = $row[5];
}

$stmtlin = $conn->prepare("SELECT * FROM tbl_items");
$stmtlin->execute();
$resultlin = $stmtlin->fetchAll();
$lineageData = array();
$modeData = array();

foreach($resultlin as $row)
{
        $lineageData[] = $row[7];
        $modeData[] = $row[9];
}


}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}
?>
<style>
    @media only screen and (min-width: 1200px) {
  .nobr { white-space: nowrap }
  #banner {
      margin-top: -60px;
  }
}
@media only screen and (max-width: 1200px) {
  .nobr { white-space: nowrap }
  #banner {
      margin-top: -33px;
  }
}
</style>
</head>

<body>

	<?php require_once('const/draws/header.php'); ?>
	<section class="section section--head section--head-fixed">
	    <center><div id="banner" style="background-color: #E28413;color: white; margin-bottom: 30px;" ><h4><span style="color: #000022;">News!! </span>The paper of BuscoPhylo is published on Scientific Reports <a href="https://www.nature.com/articles/s41598-022-22461-0" target="_blank">LINK</a></h4></div></center>
		<div class="container">
			<div class="row">
				<div class="col-12 col-xl-6">
					<h1 class="section__title section__title--head nobr" ><?php echo AppName; ?> : Busco-based phylogenomics analysis tool</h1>
					<!--<canvas id="myChart"></canvas>-->
					<!--<canvas id="myChart_mode"></canvas>-->
				</div>

				
			</div>
		</div>
	</section>
	
		<section class="section section--pb0">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<p class="section__text section__text--small"><?php echo $about; ?></p>
					<a class="categories__item" href="project.php" style="color: white;" >Submit Project</a>
					<style>
					    @media only screen and (max-width: 900px) {
					        #showcase{
					            width: 100%;
					            margin-top: 10px;
					        }
					        #my-video{
					            width: 90%;
					            height: 300px;
					        }
					    }
					    @media only screen and (min-width: 900px) {
					        #showcase{
					            width: 90%;
					            margin-top: 10px;
					        }
					        #my-video{
					            width: 90%;
					            height: 550px;
					        }
					    }
					</style>
					<center><div id="showcase" style="border: 1px solid black; background-color: #e8e3e3; border-radius: 5px; padding: 10px 10px;">
					   <p>Video tutorial for getting started with BuscoPhylo:</p>
<!--<iframe id="tutorial" src="https://drive.google.com/file/d/1-cI-8umrUj7ZlAp8JdngIGtYz2R6iqGC/preview" allow="autoplay" allowfullscreen></iframe>-->
<!--<video id="tutorial" poster="uploads/tutorial/poster.png" controls muted>-->
<!--  <source src="uploads/tutorial/BuscoPhylo.mp4" type="video/mp4">-->
<!--</video>-->
<video
    id="my-video"
    class="video-js vjs-default-skin vjs-big-play-centered"
    controls
    preload="auto"
    width="640"
    height="264"
    poster="uploads/tutorial/poster.png"
    data-setup="{}"
  >
    <source src="uploads/tutorial/BuscoPhylo.mp4" type="video/mp4" />
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a
      web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank"
        >supports HTML5 video</a
      >
    </p>
  </video>

  <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
					
					   <p>Click on the links below  to view a showcase results on:</p>
					   <ul>
					       
					       <li><a href="https://buscophylo.inra.org.ma/item/1660667670/Dickeya">36 <i>Dickeya solani</i> genomes</a></li> 
					       <li><a href="https://buscophylo.inra.org.ma/item/1660738313/Pectobacterium">318 <i>Pectobacterium spp.</i> genomes</a></li>
					   </ul>
					</div></center>
				</div>

			</div>

			
		</div>
	</section>
	<script>
	window.onload = function() {
	    //get the data from the database and store it in an array
var lineageData = <?php echo json_encode($lineageData); ?>;

// Function to generate random color
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Count the occurrences of each lineage
var counts = {};
lineageData.forEach(function(x) {
  counts[x] = (counts[x] || 0) + 1;
});

// Calculate the total number of lineages
var total = lineageData.length;

// Calculate the percentage of each lineage
var percentages = Object.keys(counts).map(function(lineage) {
  return {
    lineage: lineage,
    percentage: (counts[lineage] / total) * 100
  };
});

var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: percentages.map(function(e) {
            return e.lineage;
        }),
        datasets: [{
            label: 'Percentage of each lineage',
            data: percentages.map(function(e) {
                return e.percentage;
            }),
           backgroundColor: lineageData.map(function(e) {
                return getRandomColor();
            }),
            borderColor: lineageData.map(function(e) {
                return getRandomColor();
            }),
            borderWidth: 1
        }]
    },
    options: {
        scales: { xAxes: [{ display: false }], yAxes: [{ display: false }]}
    }
});

var modeData = <?php echo json_encode($modeData); ?>;

// Count the occurrences of each mode
var counts = {};
modeData.forEach(function(x) {
  counts[x] = (counts[x] || 0) + 1;
});

// Calculate the total number of mode
var total = modeData.length;

// Calculate the percentage of each mode
var percentages = Object.keys(counts).map(function(mode) {
  return {
    mode: mode,
    percentage: (counts[mode] / total) * 100
  };
});

var ctx_mode = document.getElementById('myChart_mode').getContext('2d');
var myChart_mode = new Chart(ctx_mode, {
    type: 'doughnut',
    data: {
        labels: percentages.map(function(e) {
            return e.mode;
        }),
        datasets: [{
            label: 'Percentage of each mode',
            data: percentages.map(function(e) {
                return e.percentage;
            }),
           backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: { xAxes: [{ display: false }], yAxes: [{ display: false }]}
    }
});

}

	</script>
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
</body>

</html>
