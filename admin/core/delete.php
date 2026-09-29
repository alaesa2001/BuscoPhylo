<?php
session_start();
require_once('../../db/config.php');
require_once('../../const/check_session.php');

if(isset($_GET['q']) && !empty($_GET['q'])){
    


try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("DELETE FROM tbl_items WHERE item_id = ?");
$stmt->execute([$_GET['q']]);
$_SESSION['reply'] = "011";
$json = file_get_contents(dirname(dirname(__DIR__)).'/results.json');
 $data = json_decode($json,true);
  if (!empty($data["posts"][0]["ID"])) {
    $output = $data["posts"][0]["ID"];
  } else { $output = 0; }
  if($output==$_GET['q']){
shell_exec('sudo rm '.dirname(dirname(__DIR__)).'/results.json');
} else {
    shell_exec('sudo rm '.dirname(dirname(__DIR__)).'/results_2.json');
}
header("location:../../project.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}
} else {
    ?> <script> window.history.back(); </script> <?php
}