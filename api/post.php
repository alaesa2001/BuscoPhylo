<?php
require_once(dirname(__DIR__).'/db/config.php');

if(!empty($_POST["Token"])){
    
    try {
    $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM tbl_token WHERE token = '".$_POST["Token"]."'");
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if (count($result) > 0) {
        echo "valid";
        // Results exist
        foreach($result as $row){
            // Do something with each row
            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE id = ".$row["user_id"]);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach($result as $row){
                $email=$row["email"];
            }
        }
    } else {
        // No results found
        die("Token not valid. Please login to https://buscophylo.inra.org.ma/user and click on 'Get my token' to obtain a valid token.");
    }
    
    }catch(PDOException $e)
    {
      echo "Connection failed: " . $e->getMessage();
    }

if (!empty($_POST["Project_Name"])) {


if (!file_exists(dirname(__DIR__)."/admin/core/data")) {
    mkdir(dirname(__DIR__)."/admin/core/data");
  }
  
if (!file_exists(dirname(__DIR__)."/admin/core/data/".$_POST['Project_Name'])) {
    mkdir(dirname(__DIR__)."/admin/core/data/".$_POST['Project_Name']);
  $directory = dirname(__DIR__)."/admin/core/data/".$_POST['Project_Name']."/";
  } else {
    mkdir(dirname(__DIR__)."/admin/core/data/".$_POST['Project_Name'].time());
  $directory = dirname(__DIR__)."/admin/core/data/".$_POST['Project_Name'].time()."/";
  }


$up_type = "fna";
if (!empty($_FILES)) {
    foreach ($_FILES as $file) {
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        if($ext != "pep" && $ext != "fa" && $ext != "fasta" && $ext != "faa" && $ext != "fna" && $ext != "fsa"){
            echo "The file must have a .pep, .fa, .fasta, .faa, .fna, or .fsa extension.";
        } else {
            $up_type = $ext;
            $file_path = $directory . basename($file["name"]);
            if (move_uploaded_file($file["tmp_name"], $file_path)) {
                echo "File ". basename($file["name"]). " has been uploaded.\n";
            } else {
                echo "There was an error uploading the file.\n";
            }
        }
    }
}

$folder_name = basename(rtrim($directory, '/'));
$directory2 = "data/".$folder_name."/";

$x=$_POST['lineage'];
    $z=$directory2;
    $name=$_POST['Project_Name'];
  $filecount = 0;
$files = glob($directory . "*." . $up_type);
if ($files){
 $filecount = count($files);
}

$upload_date = date('M d, Y h:i:s');
$item_id = time();
$title = $name;
$description = $z;
$year = date("Y");
$running_time = "Waiting list";
$plan_type = "BUSCO";
$age = $x;
$genre = $_POST['mode'];
$destn_file_b = "TreeA.png";
$mail = "";
$destn_file = "TreeA.png";
$date_check = date('Y-m-d');
if(isset($_POST['Project_groupe'])){
    $outgroup = str_replace(' ', '_', $_POST['Project_groupe']);
} else {
    $outgroup = '';
}
if(!empty($user_id)){
    $trailer=$user_id;
} else {
$trailer="";
}


try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("INSERT INTO tbl_items (title, item_id, description, year, run_time, plan, age, upload_type, genres, thumbnail, trailer_link, upload_date, cover, email, views, date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute([$title, $item_id, $description, $year, $running_time, $plan_type, $age, $up_type, $genre, $destn_file_b, $trailer, $upload_date, $destn_file, $email, $outgroup, $date_check]);

require_once('mail/mail.php');
    $mail->setFrom('info@buscophylo.me', 'BuscoPhylo');
    $mail->addAddress($email);
    $img = '<img src="https://buscophylo.inra.org.ma/img/logo-1.png" style="height:70px;" />';
    $mail->Subject = "BuscoPhylo | ".$title;
    $message ='Your Project '.$title.' Number '.$item_id.' is in Running You can check the progress in the link bellow : <br> <span style="color:#FFA500"><a href="https://buscophylo.inra.org.ma/item/'.$item_id.'/'.$title.'">https://buscophylo.inra.org.ma/item/'.$item_id.'/'.$title.'</a></span>';
    $mail->Body    = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
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
                          <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">BuscoPhylo, All rights reserved '.date('Y').'</span>
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

   
  //  $mail->addAttachment('files/' . $_POST['file']);    // Optional name
    $mail->send();
    
    echo "Thanks! Your task has been submitted.\n";
    echo "link : https://buscophylo.inra.org.ma/item/".$item_id."/".$title."\n";
    echo "This project will be automatically removed after 7 days.\n";
    echo "You have received an email with the task details, and you will receive an email when your task is complete.\n";
    

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}
}
}


?>
