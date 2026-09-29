<?php
session_start();

require_once('db/config.php');
require_once('const/web-info.php');
require_once('const/check_session.php');
require_once('const/my_profile.php');


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
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
}
}
if($_SESSION['project']!="" && isset($_SESSION['project'])){
$item = $_SESSION['project'];
} else {
header("location:./");
}
if(isset($_GET['q'])){
    $item = $_GET['q'];
}
 try {
              $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              
              $stmt = $conn->prepare("SELECT * FROM tbl_items WHERE run_time = ?");
              $stmt->execute(['Waiting list']);
              $result = $stmt->fetchAll();
              $stmt2 = $conn->prepare("SELECT * FROM tbl_items WHERE item_id = ?");
              $stmt2->execute([$item]);
              $result2 = $stmt2->fetchAll();
              foreach($result2 as $row)
{
    $item_tit = $row[1];
}
              
//               $img = '<img src="http://196.200.148.23/img/logo-1.png" style="height:70px;" />';
// $email = $email;
// $message ='Your Project '.$item_tit.' Number '.$item.' is in Done You can check the result in the link bellow : <br> <span style="color:#FFA500"><a href="https://buscophylo.inra.org.ma/item/'.$item.'/'.$item_tit.'">https://buscophylo.inra.org.ma/item/'.$item.'/'.$item_tit.'</a></span>';
// $mail_subject = ucwords("BuscoPhylo | ".$item_tit);
// $mail_message = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
//           <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #5d5dd9;">
//             <tr>
//               <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
//               <td class="container" style="font-family: Montserrat;font-size: 16px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
//                 <div class="content" style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">

//                   <table role="presentation" class="main" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #000022;border-radius: 3px;color: white !important;">
//                     <tr>
//                       <td class="wrapper" style="font-family: Montserrat;font-size: 16px;vertical-align: top;box-sizing: border-box;padding: 20px;">
//                         <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
//                           <tr>
//                             <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">
//                               '.$img.'
//                               <p style="font-family: Montserrat;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;color: white;">'.$message.'<br></p>
//                             </td>
//                           </tr>
//                         </table>
//                       </td>
//                     </tr>
//                   </table>

//                   <div class="footer" style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
//                     <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
//                       <tr>
//                         <td class="content-block" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: white;text-align: center;">
//                           <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">'.AppName.', All rights reserved '.date('Y').'</span>
//                         </td>
//                       </tr>
//                       <tr>
//                         <td class="content-block powered-by" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #FFA500;text-align: center;">
//                           Powered by BuscoPhylo.
//                         </td>
//                       </tr>
//                     </table>
//                   </div>

//                 </div>
//               </td>
//               <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
//             </tr>
//           </table>
//       </body>';

// $mail = new PHPMailer;
// $mail->SMTPOptions = array(
//     'ssl' => array(
//         'verify_peer' => false,
//         'verify_peer_name' => false,
//         'allow_self_signed' => true
//     )
// );

// $mail->isSMTP();
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
// $mail->Host = $mymail_server;
// $mail->SMTPAuth = true;
// $mail->Username = $mymail_user;
// $mail->Password = $mymail_password;
// $mail->SMTPSecure = $mymail_conn;
// $mail->Port = $mymail_port;

// $mail->setFrom($mymail_user, AppName);
// $mail->addAddress($email);

// $mail->isHTML(true);

// $mail->Subject = $mail_subject;
// $mail->Body    = $mail_message;
// $mail->AltBody = $mail_message;

// $mail->send();
foreach($result2 as $row2) {
    $title=$row2[1];
}
              foreach($result as $row) {
                  if(!isset($i)){$i=0;}
                  if($row[2]==$item){$count=$i;}
                  $i++;
              }
              if(isset($count)){
                  
              } else {
                  $count = 0;
              }
              if($count==0){
                  
$json = file_get_contents('results.json');
 $data = json_decode($json,true);
  if (!empty($data["posts"][0]["output"])) {
    $output = $data["posts"][0]["output"];
    $output_id = $data["posts"][0]["ID"];
  } else { $output = 'Done'; }
  if ($output=='Done') { $out1=1;
                  ?> <meta http-equiv="refresh" content="4; URL=https://buscophylo.inra.org.ma/item/<?php echo $item.'/'.$title; ?>"><?php
  } else {
      if ($output_id != $item) {
          $json2 = file_get_contents('results_2.json');
 $data2 = json_decode($json2,true);
 if($data2["posts"][0]["ID"]!=$item){ if($data2["posts"][0]["output"]=='Done'){$out2=1;}
      $count = $count + 1;
 } else {
     ?> <meta http-equiv="refresh" content="4; URL=https://buscophylo.inra.org.ma/item/<?php echo $item.'/'.$title; ?>"><?php
 }
      } else {
          ?> <meta http-equiv="refresh" content="4; URL=https://buscophylo.inra.org.ma/item/<?php echo $item.'/'.$title; ?>"><?php
      }
  }
              }
 ?>
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
  <link rel="icon" href="icon/<?php echo AppIcon; ?>" sizes="32x32">
	<meta name="description" content="<?php echo AppDesc; ?>">
	<meta name="keywords" content="<?php echo AppKeywords; ?>">
	<meta name="author" content="ASahbou">
	<meta name="DC.title" content="BuscoPhylo: A webserver for Busco-Based Phylogenomic Analysis for non-specialists" />
    <meta name="DC.creator" content="A Sahbou, S Khayi" />
    <meta name="DC.subject" content="Busco, Phylogenomic" />
    <meta name="DC.description" content="RESUME : Thanks to the advances in DNA sequencing technolgies, thousands of genome sequences of living organisms are being released in the public databases every day. BuscoPhylo has been implemented to provide a fully automated and complete pipeline to quickly perform BUSCO-based phylogenomic analysis starting from assembled genome sequences as inputs. The BuscoPhylo is a free, on-line and user-friendly webserver accepting genome sequences in FASTA format as inputs and enabling to the user to export the tree ready for publication and all the results of the steps included in the pipeline for downstream analyses." />
    <meta name="DC.type" content="Application Note" />
	<title><?php echo AppName; ?> – Process</title>
  <?php require_once('const/cms_scripts.php'); ?>

</head>

<body>
	<?php require_once('const/draws/header_v3.php'); ?>
 <style>
            @media only screen and (max-width: 1200px) {
  .tab-content {
                margin-top: 11%;  
              }
              #logoh{
                  display: none;
              }
}
@media only screen and (max-width: 900px) {
    #datatbl2 {
    }
}
@media only screen and (min-width: 900px){
    #datatbl2 {
        width: 95%;
    }
}

        </style>
	<section class="section section--head section--head-fixed" style="display:none">
		<div class="container">
			<div class="row">
				<div style="margin-left: 24%;margin-bottom: 2%">
					<img src="/img/logo-1.png" width="10%" style="float: left; margin-left: 5%; margin-right: 10px;" >
					<h2 style="color: #000022;margin-top: 1%;">Phylogenomics analysis pipeline by <?php echo AppName; ?></h2>
				</div>
				<br><br>
				<div class="col-12 col-xl-6">
					<h3 style="font-weight: normal">Your submission is processing!</h3>
				</div>

				<div class="col-12 col-xl-6">
					<ul class="breadcrumb">
						<li class="breadcrumb__item"><a href="./">Home</a></li>
						<li class="breadcrumb__item breadcrumb__item--active">Process</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<section class="section section--head section--head-fixed" style="display:none">
			<div class="container">
			<div class="row">
				<div class="col-12">
					<p class="section__text section__text--small"><?php 
	    foreach($result as $row) {$id=$row[2];}
              echo "Job ID: ".$item."<br>";
              echo "Please wait, you will be redirected when your results are ready. You can also bookmark this page and come back later to check the progress and view your results. Or check Remember Me to save this page and check the results later by clicking My Searches in the top right corner.<br></p>";
              ?> 

<!--<a href="#" OnClick="window.external.AddFavorite(location.href+, document.title);">Add to favorites</a>-->
              <?php
              echo "<center><img src='img/loading.gif' /></center><br>";
              echo "<p>This project will be automatically removed after 7 days</p>";
              echo "<p>You will receive an email when your job is completed</p>";
              echo "<p>Status: ".($count)." submissions ahead of yours...</p>";
              
              }catch(PDOException $e)
              {
              echo "Connection failed: " . $e->getMessage();
              }
	?></p>

				</div>

			</div>

			
		</div>
	</section>
	
	<div class="container" style="margin-bottom: 3%;">

      <div class="tab-content">

            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
          <div class="row row--grid">
            <div class="col-12 col-xl-12">

              <div class="dashbox" style="background-color: white;">
                  <div class="dashbox__table-wrap dashbox__table-wrap--2">
                  <div style="padding: 10px 10px;border: none;"><img src="/img/logo-1.png" id="logoh" width="10%" style="float: left; margin-left: 22%; margin-right: 10px;" > <h2 style="color: #000022;margin-top: 1%;">Phylogenetic analysis pipeline by <?php echo AppName; ?></h2></div>
                  
                <div class="dashbox__title" style="margin-bottom: -2%;display: none;">
                  <h5 style="color: black;font-weight: normal;"><?php echo $title; ?></h5>
                  <h5 style="color: black;font-weight: normal;">Job ID : <?php echo $item_id; ?></h5>

                </div>
                </div>

                <div class="dashbox__table-wrap dashbox__table-wrap--2">
                    <h4 style="color: #000022; padding: 10px 10px;">Thanks! Your task has been submitted.</h4>
                    <ul style="padding-left: 40px;">
                        <li><h5 style="font-weight:normal"><strong>Job ID : </strong><?php echo $item; ?></h5></li>
                        <li><h5 style="font-weight:normal"><strong>Link : </strong><a href="item/<?php echo $item.'/'.$title; ?>">https://buscophylo.inra.org.ma/item/<?php echo $item.'/'.$title; ?></a></h5></li>
                        <li><h5 style="font-weight:normal"><strong>Status: </strong><?php echo $count; ?> submissions ahead of yours...</h5></li>
                        <li><h5 style="font-weight:normal"><strong>Current server load:</strong><?php if($count<=3){ echo " &#128522; (good)";} else { echo " &#128530; (busy)";} ?></h5></li>
                        <li><h5 style="font-weight:normal">Once the task is completed, you will be automatically redirected to the output.</h5></li>
                        <li><h5 style="font-weight:normal">This project will be automatically removed after 7 days.</h5></li>
                        <li><h5 style="font-weight:normal">You have received an email with the task details, and you will receive an email when your task is complete.</h5></li>
                </ul>
                
                </div>
                </div>
                </div>
                </div>
                </div>
                </div>
                </div>

	

  <?php require_once('const/draws/footer.php'); ?>
	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/slider-radio.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/smooth-scrollbar.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/plyr.min.js"></script>
	<script src="js/main.js"></script>
	<script>
	    setTimeout(function(){
   window.location.reload(1);
}, 5000);
	</script>
</body>

</html>