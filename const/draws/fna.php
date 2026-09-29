<?php 

$load=1;

$filecount = 0;
error_reporting(0);
ini_set('display_errors', 0);
$files = glob($_SERVER["DOCUMENT_ROOT"]."/V2/admin/core/".$description."*".$type);
if ($files){
 $filecount = count($files);
}
$filecountout = 0;
$filesout = glob(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.svg");
if ($filesout){
 $filecountout = count($filesout);
} if($filecountout>0){$r=1;} 

if($r==1){
// shell_exec('sudo rsvg-convert -f pdf -o '.dirname(dirname(__DIR__))."/admin/core/".$description.'tree.pdf '.dirname(dirname(__DIR__))."/admin/core/".$description.'tree.svg');
}

if (!file_exists(dirname(dirname(__DIR__))."/results.json")) {
    $fp = fopen(dirname(dirname(__DIR__)).'/results.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}
$json = file_get_contents(dirname(dirname(__DIR__)).'/results.json');
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

$fp = fopen(dirname(dirname(__DIR__)).'/results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
?>
<script> setTimeout(function(){ window.location.reload(1);}, 500);</script>
<?php
}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
} else if($output=="Progress"){
if (!file_exists(dirname(dirname(__DIR__))."/results_2.json")) {
    $fp = fopen(dirname(dirname(__DIR__)).'/results_2.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
}
$json = file_get_contents(dirname(dirname(__DIR__)).'/results_2.json');
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

$fp = fopen(dirname(dirname(__DIR__)).'/results_2.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
}
}


try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("SELECT * FROM tbl_items WHERE item_id=?");
$stmt2->execute([$item_id]);
$result2 = $stmt2->fetchAll();
foreach($result2 as $row)
{
    if($row[5]=='Waiting list'){
        $_SESSION['project'] = $item_id;
        ?><meta http-equiv="refresh" content="0; URL=/process.php"><?php
    }
}
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}



error_reporting(0);
ini_set('display_errors', 0);




?>




        <?php
        if ($logged == "1") {
          $prev_auth = "1";
        }else{
        if (AppGuest == "1") {
          $prev_auth = "1";
        }else{
          $prev_auth = "0";
        }
        }
        function auth_2() {
          foreach($GLOBALS as $k => $v) {
             $$k=&$GLOBALS[$k];
          }
          if ($plan == "Free") {
            global $streaming;
            global $max_vid_size;
            $streaming = "1";
            $max_vid_size = 1080;
          }else{
            global $streaming;
            global $reason;
            global $max_vid_size;
            $streaming = "1";

            if ($logged == "1") {
              try {
              $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


              $stmt = $conn->prepare("SELECT * FROM tbl_user_plans WHERE user = ?");
              $stmt->execute([$user_id]);
              $result = $stmt->fetchAll();

              foreach($result as $row) {
              $expire__date = $row[4];
              $max_vid_size = $row[5];

              if (new DateTime() > new DateTime($expire__date)) {

              } else {
              $streaming = "1";
              }

              }
              }catch(PDOException $e)
              {
              echo "Connection failed: " . $e->getMessage();
              }
            }



          }
        }

        if ($prev_auth == "1") {

        if ($logged == "1") {
          if ($role == "admin") {
            $streaming = "1";
            $max_vid_size = 1080;
          }else{
            auth_2();
          }
        }else{
          auth_2();
        }



        }else{
          $streaming = "0";
          $_SESSION['rs'] = "You must be a registered user";
        }

        ?>
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
    <div class="container" style="margin-bottom: 3%;">

      <div class="tab-content">

            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
          <div class="row row--grid">
            <div class="col-12 col-xl-12">

              <div class="dashbox" style="background-color: white;">
                  <div class="dashbox__table-wrap dashbox__table-wrap--2">
                  <div style="padding: 10px 10px;border: none;"><img src="/img/logo-1.png" id="logoh" width="10%" style="float: left; margin-left: 20%; margin-right: 10px;" > <h2 style="color: #000022;margin-top: 1%;">Phylogenomics analysis pipeline by <?php echo AppName; ?></h2></div>
                  <?php 
                  if(!file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.contree")){
                  ?>
                  
                  <a class='categories__item' href='../../admin/core/delete.php?q=<?php echo $item_id; ?>' style='width: 7%; margin-left: 90%;background-color: red;'><i class="fa-solid fa-trash" style="margin-right: 5px;"></i>Delete</a>
                  
                  <?php } ?>
                  <?php 
                  if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.png")){
                      $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."BuscoPhylo.log",'r');
                     while ($line = fgets($txt_file)) {
 $nwk = $line;
 if(strpos($nwk, 'Total BUSCO groups searched') !== false){
    $lineageBUSCOs = $nwk;
    $lineageBUSCOs = (int) filter_var($lineageBUSCOs, FILTER_SANITIZE_NUMBER_INT);
}}
                  ?>
                <div class="dashbox__title" style="margin-bottom: -2%;display: none;">
                  <h5 style="color: black;font-weight: normal;"><?php echo $title; ?></h5>
                  <h5 style="color: black;font-weight: normal;">Job ID : <?php echo $item_id; ?></h5>

                </div>
                </div>

                <div class="dashbox__table-wrap dashbox__table-wrap--2">
                    <h4 style="color: #000022; padding: 10px 10px;"><i class="fa-solid fa-circle-info"></i> Project Details</h4>
                    <ul style="padding-left: 40px;">
                        <li><h5 style="font-weight:normal">•	<strong>Name : </strong><?php echo $title; ?></h5></li>
                        <li><h5 style="font-weight:normal">•	<strong>Job ID : </strong><?php echo $item_id; ?></h5></li>
                        <li><h5 style="font-weight:normal">•	<strong>Lineage : </strong><?php echo $age." ({$lineageBUSCOs} BUSCOs)"; ?></h5></li>
                        <li><h5 style="font-weight:normal">•	<strong>Mode : </strong><?php echo $row[9]; ?></h5></li>
                        <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'runtime.log')){ ?>
                        <?php 
                  if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."Result.zip")){
                  ?>
                  <li><h5 style="font-weight:normal">•	<strong>Run time : </strong><?php 
                        
                 
$txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."runtime.log",'r');

while ($line = fgets($txt_file)) {
    if (strpos($line, 'start') !== false) {
        $start = $line;
    } elseif (strpos($line, 'end') !== false) {
        $end = $line;
    }
}

$start_date = explode(" ", $start)[2];
$start_time = explode(" ", $start)[3];
$end_date = explode(" ", $end)[2];
$end_time = explode(" ", $end)[3];

$start_time = explode(":", $start_time);
$end_time = explode(":", $end_time);

$hour = $end_time[0] - $start_time[0];
$minute = $end_time[1] - $start_time[1];
$second = $end_time[2] - $start_time[2];

if ($minute < 0) {
    $minute += 60;
    $hour--;
}

if ($second < 0) {
    $second += 60;
    $minute--;
}

if ($hour < 0) {
    $hour += 24;
}

if(explode("-", $end_date)[0]>=explode("-", $start_date)[0]){
    $day_diff = explode("-", $end_date)[0] - explode("-", $start_date)[0];
} else {
    $day_diff = (explode("-", $end_date)[0] + 30) - explode("-", $start_date)[0];
}


if ($day_diff >= 1) {
if ($day_diff > 1) {
$s = "days";
} else {
$s = "day";
}
echo abs($day_diff) . " $s ";

    
}

    if ($hour != 0) {
        echo abs($hour) . " h " . $minute . " min " . $second . " s ";
    } elseif ($minute != 0) {
        echo $minute . " min " . $second . " s ";
    } else {
        echo $second . " s ";
    }





 ?></h5></li><?php } }?>
 <li><h5 style="font-weight:normal">•	<strong>Total length of amino acid positions : </strong><?php 
 $aminoacidFile = $_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'out/SUPERMATRIX.trimmed.aln';
$aminoacid = file($aminoacidFile);
echo strlen($aminoacid[1]);
 ?></h5></li>
                        <li><h5 style="font-weight:normal">•	<strong><?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'out/proteins/')){
    echo exec('ls '.$_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'out/proteins/ | wc -l');}?></strong> BUSCOs are single copy in all <strong><?php echo exec('ls '.$_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'*.f* | wc -l');?></strong> species</h5></li>
    <?php
    $files = glob($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'*.f*' , GLOB_BRACE);
foreach($files as $file) {
  $x = basename($file, '.f*');
  $path_parts = pathinfo($x);
  if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'busco/run_'.$path_parts['filename'])){
      $seqid = "";
      $contents = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'outBusco/'.$path_parts['filename'].'/logs/busco.log');
        $lines = explode("\n", $contents); // this is your array of words
        
        foreach($lines as $word) {
            if (strpos($word, "Duplicate of sequence") !== false) {
                $y = $word;
                $y = str_replace("busco.Exceptions.BuscoError: Duplicate of sequence ", "", $y);
                $y = str_replace(" in input file", "", $y);
            }
        }
        
        
      ?>
      <li><h5 style="font-weight:normal">•	<strong><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i> Warning</strong> Duplicate of sequence <strong><?php echo $y; ?></strong> in your input file (<?php echo $x; ?>)</h5></li>
      <?php
  }
}
    ?>
                </ul>
                <div style="padding: 10px 10px; border: none; color: #000022;">
                    <h4><i class="fa-solid fa-file-invoice"></i> Method</h4>
                    <ul style="padding-left: 30px;">
                        <li><h5 style="font-weight:normal">•	BUSCO predictions were performed on each genome using BUSCO V5 <a href="https://academic.oup.com/mbe/article/38/10/4647/6329644" target="_blank">(Manni et al 2021)</a> and  OrthoDB V10 <a href="https://academic.oup.com/nar/article/49/D1/D389/5983625" target="_blank">( Zdobnov EM et al, 2020)</a>.</h5></li>
                        <li><h5 style="font-weight:normal">•	Alignments was performed using Muscle and trimAl (Edgar, 2004; Capella-Gutiérrez et al., 2009).</h5></li>
                        <li><h5 style="font-weight:normal">•	ML tree was inferred using IQ-TREE version 1.6.12 (Nguyen et al., 2015) with the model selection from ModelFinder (Kalyaanamoorthy et al., 2017) using the following defaults parameters: “-bb 1000 -alrt 1000 -nt AUTO -ntmax”.</h5></li>
                        <li><h5 style="font-weight:normal">•	The  tree file is visualized using ETE Toolkit (Huerta-Cepas et al., 2016).</h5></li>
                        <li><h6 style="font-weight:normal">(These texts may be used for your publication.)</h6></li>
                </ul>
                </div>
                  
                  <?php
                  }
                  else {
                      
                $total_items  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") );
                $total_dirs  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*"), GLOB_ONLYDIR );
                $count_busco  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*/run_*/*.txt"), GLOB_ONLYDIR );
                if(($total_items == $total_dirs) && ((file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco")))){
                    $busco =true;
                    if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco")){
                        $singlecopy =true;
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/RAxML_bootstrap.nwk")){
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.contree")){
                        echo ""; $alignement =true;
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.contree")){
                            echo ""; $tree =true;
                        } else {
                            echo "<h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Creating phylogenomic Tree : </strong>Running<span id='wait'>.</span></h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>
<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                        }
                    } else {
                        echo "<h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Alignement : </strong>Running<span id='wait'>.</span></h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                    }
                    } else {
                        
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/s.sh")){
                            $file_path = dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.log";

if (file_exists($file_path)) {
    $file_content = file_get_contents($file_path);
    if (strpos($file_content, "ERROR: It makes no sense to perform bootstrap with less than 4 sequences") !== false) {
        echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
        ?>
        <center><h5 style='font-weight:normal'>It makes no sense to perform bootstrap with less than 4 sequences.</h5></center>
        <?php
        $load=0;
     $error = true;
    }
} else
                            if(!file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln")){
                                
     $load=0;
     $error = true;
                            }
                        }
                        
                        foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*/logs/busco.log") as $filename){
     $genomename = $filename;
 }
 
 $genomenun = 0;
 
 foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") as $filename){
     $genomenun += 1;
 }
 
 if($genomenun<4){
     if(!$error){
     echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
     }
     $load=0;
     try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);



$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE item_id=?");
$stmt->execute([$item_id]);
$result = $stmt->fetchAll();

foreach($result as $row)
{
    if($row['rates']==0 && $row[5]=='Error'){
        
    }
}

}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
     ?>
         <center><h5 style='font-weight:normal'>Alignment must have at least 4 sequences</h5></center>
         <?php
         
 } else { 
                      
                          

if( strpos(file_get_contents($genomename),"The input file does not contain nucleotide sequences.") !== false) {
    
     if(!$error){
         echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
     }
     $load=0;
      try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);



}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
         
         foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") as $filename){
     $fastaname = $filename;
 }
 if( $regex = preg_match('[@_!#$%^&*()<>?/|}{~:]', file_get_contents($fastaname)) !== false) {
     ?>
         <center><h5 style='font-weight:normal'>Please check your sequences</h5></center>
         <?php
 } else {
         
         ?>
         <center><h5 style='font-weight:normal'>Your inputs seem to be protein sequences while you have selected transcriptome mode. Try to use protein mode instead.
You can delete and resubmit your project.</h5></center>
         <?php
 }
        
    } else if(strpos(file_get_contents($genomename),"Please provide a protein file as input") !== false){
              
     if(!$error){  
                echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
     }
     ?>
         <center><h5 style='font-weight:normal'>Please provide a protein file as input</h5></center>
         <?php
 try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
    } else if(strpos(file_get_contents($genomename),"0	Complete BUSCOs (C)") !== false || $error==true){
        
                echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-circle-exclamation' style='color: orange;' ></i><br>Failed</h1></center>";
                $load=0;
     ?>
         <center><h5 style='font-weight:normal'>BuscoPhylo didn't find any shared busco genes within your input sequences</h5>
            <img src="<?php  echo "../../admin/core/".$description."busco_summaries/busco_figure.png"; ?>" width="50%" style="border: 1px solid black; border-radius: 10px; padding: 10px; margin-bottom: 20px" alt="BuscoPlot">
         </center>
         <?php
 try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
    } else {
        foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."busco.log") as $filename){
     $genomen = $filename;
 }
 $logFilePath = dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.trimmed.aln.log";
$fileContent = file_get_contents($logFilePath);
 if( strpos(file_get_contents($genomen),"Download connection problem") !== false && $item_id!="1660738313") {
    
     if(!$error){
        echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
     }
     ?>
         <center><h5 style='font-weight:normal'>Download connection problem. Retry later</h5></center>
         <?php
 try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}

        $load=0;
        $error = 1;
        
    } elseif(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.trimmed.aln.log") && strpos($fileContent, 'ERROR:') !== false){
                        $file = dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.trimmed.aln.log";
                        
                        // Read the file contents
                        $fileContent = file_get_contents($file);
                        ?>
                        <center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>
                        <?php
                        
                        // Check if the file contains any errors
                        if (strpos($fileContent, 'ERROR:') !== false) {
                            // Get the error messages
                            preg_match_all('/ERROR: (.+?)\n/', $fileContent, $matches);
                            
                            // Display the error messages
                            foreach ($matches[1] as $error) {
                            ?>
                            <center><h5 style='font-weight:normal'><?php echo $error . "\n"; ?></h5></center>
                            <?php
                            try {
                            $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            
                            $stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
                            $stmt2->execute([$item_id]);
                            }catch(PDOException $e)
                            {
                              echo "Connection failed: " . $e->getMessage();
                            }

                            $load=0;
                            $error = true;
                            $script = false;
                            }
                        }
        
    }  else {
                        echo "<h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Creating phylogenomic Tree : </strong>Running<span id='wait'>.</span></h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
}}}
                    }
                    } else {
                    echo "<h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Collecting Single Copy : </strong>Running<span id='wait'>.</span></h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                }
                } else {
                    
                  if($total_dirs>0){ $pourcentage =($total_dirs*100)/$total_items;
                  foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."busco.log") as $filename){
     $genomen = $filename;
 }
 if( strpos(file_get_contents($genomen),"Download connection problem") !== false || $error ==true) {
    
        echo "<center><h1 style='padding-left: 10px;'><i class='fa-solid fa-triangle-exclamation' style='color: red;'></i><br>Error</h1></center>";
     ?>
         <center><h5 style='font-weight:normal'>Download connection problem. Retry later</h5></center>
         <?php
 try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt2 = $conn->prepare("UPDATE tbl_items SET run_time='Error' WHERE item_id=?");
$stmt2->execute([$item_id]);
}catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}

        $load=0;
        $error = 1;
        
    } else {
        if($total_items == 0){ ?> <meta http-equiv="refresh" content="0; URL=../../admin/core/delete.php?q=<?php echo $item_id; ?>"> <?php }
                  echo "
                    <h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Busco : </strong>Running<span id='wait'>.</span> $total_dirs/$total_items</h5></li>
                        <li><h5 style='font-weight:normal'>•	<strong>Current genome : </strong>".shell_exec('cd '.dirname(dirname(__DIR__))."/admin/core/".$description.'outBusco/ ; ls -td -- * | head -n 1')."</h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>
<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>"; }
                } else {echo "<h4 style='padding-left: 10px;'><i class='fa-solid fa-spinner fa-spin-pulse fa-spin-reverse'></i> Progress</h4>
                  <ul style='padding-left: 40px;'>
                        <li><h5 style='font-weight:normal'>•	<strong>Busco : </strong>Getting things ready for process <span id='wait2'>.</span></h5></li>
</ul>
<h6 style='padding-left: 40px; font-weight: normal;'>(You will receive an email when your job is completed)</h6>
                <script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait2');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";}
                 }
                 foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*/logs/busco.log") as $filename){
     $genomename = $filename;
 }
                      if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."BuscoPhylo.log")){
                          $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."BuscoPhylo.log",'r');
$a = 1;
foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") as $filename){
     $genomenun += 1;
 }
 

if( (strpos(file_get_contents($genomename),"The input file does not contain nucleotide sequences.") !== false) || $genomenun<4 || strpos(file_get_contents($genomename),"Please provide a protein file as input") !== false || strpos(file_get_contents($genomename),"0	Complete BUSCOs (C)") !== false || $error == 1) {
    if(strpos(file_get_contents($genomename),"0	Complete BUSCOs (C)") !== false || $error == true){
        ?>
        <?php if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."Result.zip")){?>
<?php $result_size = shell_exec('cd '.dirname(dirname(__DIR__))."/admin/core/".$description.' ; ls -l --b=MB Result.zip | cut -d " " -f5');
    function count_digit($number) {
  return strlen($number);
}

//function call
$number_of_digits = count_digit($result_size);

if($number_of_digits>=7){
    $result_size = str_replace("MB", "",$result_size) / 1024;
    $result_size = number_format((float)$result_size, 2, '.', '')."GB";
}

?>
<center>
    <a class='categories__item' href='../../admin/core/<?php echo $description; ?>busco_summaries/busco_figure.png' style='margin-left: 10px;margin-bottom: 10px;color: white;' Download><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>Download Busco Plot</a>
    <?php
    if (file_exists($file_path)) {
    $file_content = file_get_contents($file_path);
    if (strpos($file_content, "ERROR: It makes no sense to perform bootstrap with less than 4 sequences") !== false) {
        ?>
        <a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/SUPERMATRIX.trimmed.aln.log' style='margin-left: 10px;margin-bottom: 10px;color: white;' Download><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>Download logs</a>
        <?php
    }
}
    ?>
    <a class='categories__item' href='../../admin/core/<?php echo $description; ?>Result.zip' style='margin-left: 10px;margin-bottom: 10px;color: white;'><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>Download Result (<?php echo $result_size; ?>)</a></center>
<?php } else { ?>
<center><a class='categories__item' href='#' style='margin-left: 10px;margin-bottom: 10px;color: white;'><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>ZIP being created <span id='wait'>.</span></a></center>
<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>
<script> setTimeout(function(){ window.location.reload(1);}, 30000);</script>
<?php } ?>
        <?php
    }
    
        // echo "<h5 style='font-weight:normal'>Your inputs seem to be  protein sequences while you have selected transcriptome mode. Try to use protein mode instead.<br>You can delete and resubmit your project.</h5>";
        
    } else {
        ?><div id='scroll' style='height: 400px; overflow-y: scroll;'><?php
while ($line = fgets($txt_file)) {
 $nwk = $line;
 echo "<h5 style='font-weight:normal;padding-left: 40px'>$nwk</h5>";
 $a++;
}
?></div><?php
}
fclose($txt_file);
                      } else
                      if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."phylo.log")){
                          $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."phylo.log",'r');
$a = 1;

foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") as $filename){
     $genomenun += 1;
 }
 

if( (strpos(file_get_contents($genomename),"The input file does not contain nucleotide sequences.") !== false) || $genomenun<4 || strpos(file_get_contents($genomename),"Please provide a protein file as input") !== false || strpos(file_get_contents($genomename),"0	Complete BUSCOs (C)") !== false || $error == 1) {
    
        // echo "<h5 style='font-weight:normal'>Your inputs seem to be  protein sequences while you have selected transcriptome mode. Try to use protein mode instead.<br>You can delete and resubmit your project.</h5>";
        
    } else {
        ?><div id='scroll' style='height: 400px; overflow-y: scroll;'><?php
while ($line = fgets($txt_file)) {
 $nwk = $line;
 echo "<h5 style='font-weight:normal;padding-left: 40px'>$nwk</h5>";
 $a++;
}
?></div><?php
}

fclose($txt_file);
                      } else
                      if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco.log")){
                          $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."busco.log",'r');
$a = 1;
?><?php
foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."busco.log") as $filename){
     $genomen = $filename;
 }
foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.f*") as $filename){
     $genomenun += 1;
 }
 

if( (strpos(file_get_contents($genomename),"The input file does not contain nucleotide sequences.") !== false) || $genomenun<4 ) {
    
        echo "<div id='scroll' style='height: 400px; overflow-y: scroll;'><h5 style='font-weight:normal;padding-left: 40px'>Your inputs seem to be  protein sequences while you have selected transcriptome mode. Try to use protein mode instead.<br>You can delete and resubmit your project.</h5></div>";
        
    } else if( strpos(file_get_contents($genomen),"Download connection problem") !== false) {
    
        
        
    } else {
        ?><div id='scroll' style='height: 400px; overflow-y: scroll;'><?php
while ($line = fgets($txt_file)) {
 $nwk = $line;
    
 echo "<h5 style='font-weight:normal;padding-left: 40px'>$nwk</h5>";
 
 $a++;
} ?></div><?php
}
?><?php
fclose($txt_file);
                      }
                      
                  }
                if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.png")){
                    $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.png",'r');
$a = 1;
while ($line = fgets($txt_file)) {
 $nwk = $line;
 $a++;
}
fclose($txt_file);
?> <!-- <img src="<?php // echo "../../admin/core/".$description."out/Tree.png"; ?>" alt="PhyloTree"><br> -->
<style>
    input[type="checkbox"]{
    -webkit-appearance: radio;
}
</style>

<?php
if((!isset($_SESSION['tree1']) || $_SESSION['tree1']=="") && (!isset($_SESSION['tree2']) || $_SESSION['tree2']=="")){
    $tree = 'tree';
} else if((!isset($_SESSION['tree1']) || $_SESSION['tree1']=="") && (isset($_SESSION['tree2']) && $_SESSION['tree2']!="")){
    $tree = $_SESSION['tree2'];
} else if((isset($_SESSION['tree1']) && $_SESSION['tree1']!="") && (!isset($_SESSION['tree2']) || $_SESSION['tree2']=="")){
    $tree = $_SESSION['tree1'];
} else {
    $tree = 'tree_no';
}
?>

<h4 style="color: #000022; padding: 10px 10px;" id="download"><i class="fa-solid fa-download"></i> Result files</h4>
<div id="box">
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/<?php echo $tree; ?>.svg' style='margin-left: 10px;margin-bottom: 10px;' download><i class="fa-solid fa-image" style="margin-right: 3px"></i>Download image (SVG)</a>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/<?php echo $tree; ?>.png' style='margin-left: 10px;margin-bottom: 10px;' download><i class="fa-solid fa-image" style="margin-right: 3px"></i>Download image (PNG)</a>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/SUPERMATRIX.trimmed.aln.contree' style='margin-left: 10px;margin-bottom: 10px;' download><i class="fa-solid fa-diagram-project" style="margin-right: 3px"></i>Download newick file</a>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/<?php echo $tree; ?>.pdf' style='margin-left: 10px;margin-bottom: 10px;' download><i class="fa-solid fa-file-pdf" style="margin-right: 3px"></i>Download pdf</a>
<?php if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."Result.zip")){?>
<?php $result_size = shell_exec('cd '.dirname(dirname(__DIR__))."/admin/core/".$description.' ; ls -l --b=MB Result.zip | cut -d " " -f5');
    function count_digit($number) {
  return strlen($number);
}

//function call
$number_of_digits = count_digit($result_size);

if($number_of_digits>=7){
    $result_size = str_replace("MB", "",$result_size) / 1024;
    $result_size = number_format((float)$result_size, 2, '.', '')."GB";
}

?>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>Result.zip' style='margin-left: 10px;margin-bottom: 10px;'><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>Download Result (<?php echo $result_size; ?>)</a>
<?php } else { ?>
<a class='categories__item' href='#' style='margin-left: 10px;margin-bottom: 10px;'><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>ZIP being created <span id='wait'>.</span></a>
<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>
<script> setTimeout(function(){ window.location.reload(1);}, 30000);</script>
<?php } ?>
<?php if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."BuscoPhylo.log")){?>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>BuscoPhylo.log' style='margin-left: 10px;margin-bottom: 10px;' download><i class='fa-solid fa-file' style='margin-right: 3px'></i>Download log</a><?php } else { ?>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>BuscoPhylo.log' style='margin-left: 10px;margin-bottom: 10px;' download><i class='fa-solid fa-file' style='margin-right: 3px'></i>Download log</a><?php } ?>
</div>
<h4 style="color: #000022; padding: 10px 10px; margin-top: 10px;"><i class="fa-solid fa-diagram-project"></i> Phylogram</h4>
<style>
    #<?php if($tree=="tree_no"){echo "tree_nonode{
        background-color: orange;
    } #tree_nolength";} else {echo $tree;} ?> {
        background-color: orange;
    }
    #<?php if($tree=="tree_no"){echo "tree_nonode:hover {
        background-color: #000022;
    } #tree_nolength";} else {echo $tree;} ?>:hover {
        background-color: #000022;
    }
    @media only screen and (min-width: 900px) {
        #break{
            display: none;
        }
    }
</style>
<div>
      <input type="checkbox" id="nonode" name="nonode"
             onclick="location.href='/const/draws/echo.php?value=nonode&item=<?php echo $item_id; ?>&title=<?php echo $title; ?>';" <?php if($_SESSION['tree1']=="tree_nonode"){echo "checked";} ?>>
      <label for="nonode" style="margin-top:3px;margin-right:10px;">disable bootstrap</label>
    <br id="break">
      <input type="checkbox" id="nonode" name="nonode"
             onclick="location.href='/const/draws/echo.php?value=nolength&item=<?php echo $item_id; ?>&title=<?php echo $title; ?>';" <?php if($_SESSION['tree2']=="tree_nolength"){echo "checked";} ?>>
      <label for="nonode" style="margin-top:3px;margin-right:10px;">disable branch length</label>
    </div>

<center><img src="../../admin/core/<?php echo $description; ?>out/<?php echo $tree; ?>.svg" alt="Tree" style="border: 1px solid #000022; border-radius: 10px; margin-top: 20px; max-width: 80%;"></center>
    

<style>
    .categories__item{
        color: white;
    }
    body {
        background-color: #f6f6f6;
    }
</style>
<?php
// $species_count = exec('ls '.$_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'*.f* | wc -l');
// if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco_summaries/busco_figure.png") && $species_count <=70){
 ?><!--<h4 style="color: #000022; padding: 10px 10px; margin-top: 10px;"><i class="fa-solid fa-chart-bar"></i> BUSCO Figure</h4>-->
 <!--<center><img src="../../admin/core/echo $description; busco_summaries/busco_figure.png" alt="Tree" style="border: 1px solid #000022; border-radius: 10px; margin-top: 20px;width: 80%;"></center>-->
 <?php
// }
                } else { if($load!=0){?><script> setTimeout(function(){ window.location.reload(1);}, 5000); </script><?php } }
                 ?>
                </div>
              </div>
            </div>
          </div>
        </div>



            

        <div class="tab-pane fade" id="tab-2" role="tabpanel">
          <div class="row row--grid postList">

            <?php
            try {
            $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM tbl_favourites LEFT JOIN tbl_items ON tbl_favourites.item = tbl_items.item_id WHERE tbl_items.status = 'Visible' AND tbl_favourites.user = ?");
            $stmt->execute([$user_id]);
            $result = $stmt->fetchAll();
            $postCount = count($result);

            if ($postCount > 0) {
              $pages = ($postCount/1);
            }else{
              $pages = 0;
            }
            $limit = 12;

            $stmt = $conn->prepare("SELECT * FROM tbl_favourites LEFT JOIN tbl_items ON tbl_favourites.item = tbl_items.item_id WHERE tbl_items.status = 'Visible' AND tbl_favourites.user = ? ORDER BY tbl_favourites.id DESC LIMIT 0,$limit");
            $stmt->execute([$user_id]);
            $result = $stmt->fetchAll();

            foreach($result as $row)
            {
              $st1 = preg_replace("/[^a-zA-Z]/", " ", $row[4]);
              $st2 =  preg_replace('/\s+/', ' ', $st1);
              $item_title = strtolower(str_replace(' ', '-', $st2));
              $genr = (explode(",",$row[12]));

              ?>
              <div class="col-6 col-sm-4 col-lg-3 col-xl-2" id="item<?php echo $row[0]; ?>">
                <div class="card card--favorites" >
                  <a href="item/<?php echo $row[5]; ?>/<?php echo $item_title; ?>" class="card__cover">
                    <img class="movie_cover" src="uploads/cover/<?php echo $row[16]; ?>" alt="">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 1C16.5228 1 21 5.47716 21 11C21 16.5228 16.5228 21 11 21C5.47716 21 1 16.5228 1 11C1 5.47716 5.47716 1 11 1Z" stroke-linecap="round" stroke-linejoin="round"/><path fill-rule="evenodd" clip-rule="evenodd" d="M14.0501 11.4669C13.3211 12.2529 11.3371 13.5829 10.3221 14.0099C10.1601 14.0779 9.74711 14.2219 9.65811 14.2239C9.46911 14.2299 9.28711 14.1239 9.19911 13.9539C9.16511 13.8879 9.06511 13.4569 9.03311 13.2649C8.93811 12.6809 8.88911 11.7739 8.89011 10.8619C8.88911 9.90489 8.94211 8.95489 9.04811 8.37689C9.07611 8.22089 9.15811 7.86189 9.18211 7.80389C9.22711 7.69589 9.30911 7.61089 9.40811 7.55789C9.48411 7.51689 9.57111 7.49489 9.65811 7.49789C9.74711 7.49989 10.1091 7.62689 10.2331 7.67589C11.2111 8.05589 13.2801 9.43389 14.0401 10.2439C14.1081 10.3169 14.2951 10.5129 14.3261 10.5529C14.3971 10.6429 14.4321 10.7519 14.4321 10.8619C14.4321 10.9639 14.4011 11.0679 14.3371 11.1549C14.3041 11.1999 14.1131 11.3999 14.0501 11.4669Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </a>
                  <button id="<?php echo $row[0]; ?>" onclick="remove_fav(this.id);" class="card__add" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16,2H8A3,3,0,0,0,5,5V21a1,1,0,0,0,.5.87,1,1,0,0,0,1,0L12,18.69l5.5,3.18A1,1,0,0,0,18,22a1,1,0,0,0,.5-.13A1,1,0,0,0,19,21V5A3,3,0,0,0,16,2Zm1,17.27-4.5-2.6a1,1,0,0,0-1,0L7,19.27V5A1,1,0,0,1,8,4h8a1,1,0,0,1,1,1Z"/></svg></button>
                  <span class="card__rating"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z"/></svg> <?php echo $row[17]; ?></span>
                  <h3 title="<?php echo $row[4]; ?>" class="top_fix card__title"><a href="item/<?php echo $row[4]; ?>/<?php echo $item_title; ?>"><?php echo $row[4]; ?></a></h3>
                  <ul class="card__list">
                    <li><?php echo $row[9]; ?></li>
                    <?php
                    $i = 0;
                    foreach( $genr as $el) {
                      if( $i >= 1) break;
                      ?><li><?php echo $el; ?></li><?php
                      $i++;
                    }
                    ?>
                    <li><?php echo $row[7]; ?></li>
                  </ul>
                </div>
              </div>
              <?php
            }
            }catch(PDOException $e)
            {
            echo "Connection failed: " . $e->getMessage();
            }
            ?>


          </div>

          <div class="row">
            <div class="col-12" id="load_area" style="margin:auto">
             

            </div>
            <input type="hidden" id="row" value="0">
            <input type="hidden" id="postCount" value="<?php echo $postCount; ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
<section class="section" style="min-height:51vh; display: none"><div class="container" style="border: 1px solid black; padding: 10px 10px;">
    <h2><?php echo $title; ?></h2>
    <h3>Job ID : <?php echo $item_id; ?></h3>
    <p><?php
    $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__DIR__));
    echo 'Lineage : '.$age.' | Mode : '.$row[9].' '; 
    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'out/proteins/')){
    echo '| Number of Single Copy genes : '.exec('ls '.$_SERVER['DOCUMENT_ROOT'].'/admin/core/'.$description.'out/proteins/ | wc -l'); 
    }
    ?>
    </p>
          <?php
          if ($GLOBALS['streaming'] == "1") {

          }else{
          ?><p style="margin-top:20px;" class="text_white"><?php echo $_SESSION['rs']; ?></p><?php
          }
          ?>
          <?php if($run_time=="Running"){ ?>
            
            <?php
                if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.png")){
                    $txt_file = fopen(dirname(dirname(__DIR__))."/admin/core/".$description."out/tree.png",'r');
$a = 1;
while ($line = fgets($txt_file)) {
 $nwk = $line;
 $a++;
}
fclose($txt_file);
?> <!-- <img src="<?php // echo "../../admin/core/".$description."out/Tree.png"; ?>" alt="PhyloTree"><br> -->
<center><img src="../../admin/core/<?php echo $description; ?>out/tree.png" alt="Tree" width="80%"></center>
<br>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/tree.png' style='margin-left: 10px;' download><i class="fa-solid fa-image" style="margin-right: 3px"></i>Download image</a>
<a class='categories__item' href='../../admin/core/<?php echo $description; ?>out/tree.pdf' style='margin-left: 10px;' download><i class="fa-solid fa-file-pdf" style="margin-right: 3px"></i>Download pdf</a>
<style>
    .categories__item{
        color: white;
    }
</style>
<?php
                } else { if($load!=0){?><script>  setTimeout(function(){ window.location.reload(1);}, 5000);</script><?php }}
                
                $total_items  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."*.".$type) );
                $total_dirs  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*"), GLOB_ONLYDIR );
                $count_busco  = count( glob(dirname(dirname(__DIR__))."/admin/core/".$description."outBusco/*/run_*/*.txt"), GLOB_ONLYDIR );
                if(($total_items == $total_dirs) || ((file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco")))){
                    $busco =true;
                    if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."busco")){
                        $singlecopy =true;
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/RAxML_bootstrap.nwk")){
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.contree")){
                        echo "<a class='categories__item' href='../../admin/core/{$description}Result.zip' style='margin-left: 10px;'><i class='fa-solid fa-file-zipper' style='margin-right: 3px'></i>Download Result</a>"; $alignement =true;
                        if(file_exists(dirname(dirname(__DIR__))."/admin/core/".$description."out/SUPERMATRIX.trimmed.aln.contree")){
                            echo "<a class='categories__item' href='../../admin/core/{$description}BuscoPhylo.log' style='margin-left: 10px;' download><i class='fa-solid fa-file' style='margin-right: 3px'></i>Download log</a>"; $tree =true;
                        } else {
                            
                            echo "<div style='margin-left: 20px;'><div>Creating phylogenomic Tree : Running<span id='wait'>.</span></div></div>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                        }
                    } else {
                        echo "<div>Alignement : Running<span id='wait'>.</span></div>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                    }
                    } else {
                        echo "<div>Creating phylogenetic bootstrap : Running<span id='wait'>.</span></div>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                    }
                    } else {
                    echo "<div>Collecting Single Copy : Running<span id='wait'>.</span></div>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                }
                } else {
                    
                  if($total_dirs>0){ echo "<div>Busco : Running<span id='wait'>.</span> $total_dirs/$total_items </div><br><div></div>

<script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";
                } else {echo "<div>Busco : Getting things ready for process<span id='wait2'>.</span></div>
                <script>
var dots = window.setInterval( function() {
    var wait = document.getElementById('wait2');
    if ( wait.innerHTML.length > 3 ) 
        wait.innerHTML = '';
    else 
        wait.innerHTML += '.';
    }, 200);
</script>";}
                }
                ?><?php } ?></div></section>
<?php if($script){ ?>
<script>
    function deleteSpecificElement() {
  const element = document.querySelector('center > h1');

  if (element) {
    console.log(`Found the element ${element.tagName}, deleting`);
    element.remove();
  } else {
    console.log('Element not found, no action needed');
  }
}

// Call the function
deleteSpecificElement();
</script>
<?php } ?>