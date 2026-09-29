<?php
session_start();
function php_func(){
    if(isset($_GET['value'])){
        $item = $_GET['item'];
        $title = $_GET['title'];
        if($_GET['value']=='nonode'){
           if($_SESSION['tree1']=="tree_nonode"){
               $_SESSION['tree1']="";
           } else {
           $_SESSION['tree1']="tree_nonode";
           }
        }
        if($_GET['value']=='nolength'){
            if($_SESSION['tree2']=="tree_nolength"){
               $_SESSION['tree2']="";
           } else {
           $_SESSION['tree2']="tree_nolength";
           }
        }
    }
}
php_func();
        $item = $_GET['item'];
        $title = $_GET['title'];
?> <script>
history.back()
</script>