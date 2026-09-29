<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__DIR__));
 $path1=$_SERVER["DOCUMENT_ROOT"].'/results.json';
 date_default_timezone_set('Africa/Casablanca');
$json = file_get_contents($path1);
 $data = json_decode($json,true);
 $path=$_SERVER["DOCUMENT_ROOT"].'/admin/core';
 $response = array();
	$posts = array();
 	$output = $data["posts"][0]["output"];
 	
  if ($output=='Running' || ($output=='Progress' && !file_exists($path.'/'.$data["posts"][0]['input'].'outBusco'))) {


	$ID=$data["posts"][0]['ID']; 
	  $name=$data["posts"][0]['name']; 
	  $lineage=$data["posts"][0]['lineage']; 
	  $mode=$data["posts"][0]['mode']; 
	  $input=$data["posts"][0]['input']; 
	  $output=$data["posts"][0]['output'];
	  $type=$data["posts"][0]['type'];
	  $email=$data["posts"][0]['email'];
	  if(isset($data["posts"][0]['outgroup'])){
	  $outgroup=$data["posts"][0]['outgroup'];
	  } else {
	  $outgroup="";
	  }

	  $posts[] = array('ID'=> $ID, 'name'=> $name, 'lineage'=> $lineage, 'mode'=> $mode, 'input'=> $input, 'output'=> 'Progress', 'type'=> $type, 'email'=> $email, 'outgroup'=> $outgroup);


$response['posts'] = $posts;

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

if(!file_exists($path.'/'.$data["posts"][0]['input'].'busco.log')){

$output = shell_exec(' echo "start : '.date('d-m-y H:i:s').'" >> '.$path.'/'.$data["posts"][0]['input'].'runtime.log');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input']);
	
$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'outBusco');

$output = shell_exec("cd ".$path."/".$data["posts"][0]['input']." && rename 's/\s/_/g' ./*.* ");

$output = shell_exec('source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.'.$data["posts"][0]['type'].' ; do output=$(basename ${file%%.'.$data["posts"][0]['type'].'*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ');

echo 'source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.'.$data["posts"][0]['type'].' ; do output=$(basename ${file%%.'.$data["posts"][0]['type'].'*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ';

$output = shell_exec('source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.pep ; do output=$(basename ${file%%.pep*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ');



$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'busco');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'outBusco; for i in *; do mkdir '.$path.'/'.$data["posts"][0]['input'].'busco/$i; cp -r $i/run_*/* '.$path.'/'.$data["posts"][0]['input'].'busco/$i ; done');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'busco; for i in *; do mv $i '.$path.'/'.$data["posts"][0]['input'].'busco/run_$i; done');

foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$data["posts"][0]['input']."busco/*") as $filename){
     if(!file_exists($filename.'/short_summary.txt')){
         $filenameBusco = $filename;
         $output = shell_exec('sudo rm -r '.$filenameBusco);
         echo 'sudo rm -r '.$filenameBusco;
     }
 }


$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input']);

if(isset($outgroup) && ($outgroup=="" || is_numeric($outgroup))){
     $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'; sudo python3 '.$path.'/scripts/script_phylo.py -d busco -o out -t 20 > '.$path.'/'.$data["posts"][0]['input'].'phylo.log');
     shell_exec('sudo touch '.$path.'/'.$data["posts"][0]['input'].'out/s.sh');
        $myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/admin/core/".$data["posts"][0]['input']."out/s.sh", "w") or die("Unable to open file!");
        $txt = "#!/bin/bash\n";
        fwrite($myfile, $txt);
        $txt = "exec su root --command 'export QT_QPA_PLATFORM=offscreen && export XDG_RUNTIME_DIR=/tmp/runtime-runner && python3 ".$path."/scripts/tree.py' \n";
        fwrite($myfile, $txt);
        fclose($myfile);
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'out; bash '.$path.'/'.$data["posts"][0]['input'].'out/s.sh  >> '.$path.'/'.$data["posts"][0]['input'].'tree.log');
} else {
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'; sudo python3 '.$path.'/scripts/script_phylo.py -og '.$data["posts"][0]['outgroup'].' -d busco -o out -t 20 >> '.$path.'/'.$data["posts"][0]['input'].'phylo.log');
   shell_exec('sudo touch '.$path.'/'.$data["posts"][0]['input'].'out/s.sh');
        $myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/admin/core/".$data["posts"][0]['input']."out/s.sh", "w") or die("Unable to open file!");
        $txt = "#!/bin/bash\n";
        fwrite($myfile, $txt);
        $txt = "exec su root --command 'export QT_QPA_PLATFORM=offscreen && export XDG_RUNTIME_DIR=/tmp/runtime-runner && python3 ".$path."/scripts/tree.py ".$data["posts"][0]['outgroup']." ' \n";
        fwrite($myfile, $txt);
        fclose($myfile);
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'out; bash '.$path.'/'.$data["posts"][0]['input'].'out/s.sh  >> '.$path.'/'.$data["posts"][0]['input'].'tree.log');
}


$output = shell_exec('cat '.$path.'/'.$data["posts"][0]['input'].'busco.log '.$path.'/'.$data["posts"][0]['input'].'phylo.log '.$path.'/'.$data["posts"][0]['input'].'tree.log > '.$path.'/'.$data["posts"][0]['input'].'BuscoPhylo.log');

$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'busco_summaries');
$output = shell_exec('cp '.$path.'/'.$data["posts"][0]['input'].'outBusco/*/short_summary*.txt '.$path.'/'.$data["posts"][0]['input'].'busco_summaries/');

$output = shell_exec('python3 '.$path.'/scripts/generate_plot.py -wd '.$path.'/'.$data["posts"][0]['input'].'busco_summaries/ ');

$output = shell_exec(' echo "end : '.date('d-m-y H:i:s').'" >> '.$path.'/'.$data["posts"][0]['input'].'runtime.log');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].' ; zip -r Result.zip *');

}
$response = array();
	$posts = array();
$ID=$data["posts"][0]['ID']; 
	  $name=$data["posts"][0]['name']; 
	  $lineage=$data["posts"][0]['lineage']; 
	  $mode=$data["posts"][0]['mode']; 
	  $input=$data["posts"][0]['input']; 
	  $output=$data["posts"][0]['output'];
	  $type=$data["posts"][0]['type'];
	  $email=$data["posts"][0]['email'];
	  if(isset($data["posts"][0]['outgroup'])){
	  $outgroup=$data["posts"][0]['outgroup'];
	  } else {
	  $outgroup="";
	  }
	  
 $posts[] = array('ID'=> $ID, 'name'=> $name, 'lineage'=> $lineage, 'mode'=> $mode, 'input'=> $input, 'output'=> 'Done', 'type'=> $type, 'email'=> $email, 'outgroup'=> $outgroup);


$response['posts'] = $posts;

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

}
else {
    $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__DIR__));
 $path1=$_SERVER["DOCUMENT_ROOT"].'/results_2.json';
$json = file_get_contents($path1);
 $data = json_decode($json,true);
 $path=$_SERVER["DOCUMENT_ROOT"].'/admin/core';
 $response = array();
	$posts = array();
 	$output = $data["posts"][0]["output"];
 	
  if ($output=='Done' || ($output=='Progress' && !file_exists($path.'/'.$data["posts"][0]['input'].'outBusco'))) {
      

	$ID=$data["posts"][0]['ID']; 
	  $name=$data["posts"][0]['name']; 
	  $lineage=$data["posts"][0]['lineage']; 
	  $mode=$data["posts"][0]['mode']; 
	  $input=$data["posts"][0]['input']; 
	  $output=$data["posts"][0]['output'];
	  $type=$data["posts"][0]['type'];
	  $email=$data["posts"][0]['email'];
	  if(isset($data["posts"][0]['outgroup'])){
	  $outgroup=$data["posts"][0]['outgroup'];
	  } else {
	  $outgroup="";
	  }

	  $posts[] = array('ID'=> $ID, 'name'=> $name, 'lineage'=> $lineage, 'mode'=> $mode, 'input'=> $input, 'output'=> 'Progress', 'type'=> $type, 'email'=> $email, 'outgroup'=> $outgroup);


$response['posts'] = $posts;

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/results_2.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

if(!file_exists($path.'/'.$data["posts"][0]['input'].'busco.log')){

$output = shell_exec(' echo "start : '.date('d-m-y H:i:s').'" >> '.$path.'/'.$data["posts"][0]['input'].'runtime.log');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input']);
	
$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'outBusco');

$output = shell_exec("cd ".$path."/".$data["posts"][0]['input']." && rename 's/\s/_/g' ./*.* ");

$output = shell_exec('source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.'.$data["posts"][0]['type'].' ; do output=$(basename ${file%%.'.$data["posts"][0]['type'].'*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ');

echo 'source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.'.$data["posts"][0]['type'].' ; do output=$(basename ${file%%.'.$data["posts"][0]['type'].'*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ';

$output = shell_exec('source ~/.bashrc ; cd '.$path.'/'.$data["posts"][0]['input'].' && for file in '.$path.'/'.$data["posts"][0]['input'].'*.pep ; do output=$(basename ${file%%.pep*}) ; busco -f -i "$file" -l '.$data["posts"][0]['lineage'].' -o $output -m '.$data["posts"][0]['mode'].' --out_path  '.$path.'/'.$data["posts"][0]['input'].'outBusco >> '.$path.'/'.$data["posts"][0]['input'].'busco.log ; done ');



$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'busco');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'outBusco; for i in *; do mkdir '.$path.'/'.$data["posts"][0]['input'].'busco/$i; cp -r $i/run_*/* '.$path.'/'.$data["posts"][0]['input'].'busco/$i ; done');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'busco; for i in *; do mv $i '.$path.'/'.$data["posts"][0]['input'].'busco/run_$i; done');

foreach(glob(dirname(dirname(__DIR__))."/admin/core/".$data["posts"][0]['input']."busco/*") as $filename){
     if(!file_exists($filename.'/short_summary.txt')){
         $filenameBusco = $filename;
         $output = shell_exec('sudo rm -r '.$filenameBusco);
         echo 'sudo rm -r '.$filenameBusco;
     }
 }


$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input']);

if(isset($outgroup) && ($outgroup=="" || is_numeric($outgroup))){
     $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'; sudo python3 '.$path.'/scripts/script_phylo.py -d busco -o out -t 20 > '.$path.'/'.$data["posts"][0]['input'].'phylo.log');
     shell_exec('sudo touch '.$path.'/'.$data["posts"][0]['input'].'out/s.sh');
        $myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/admin/core/".$data["posts"][0]['input']."out/s.sh", "w") or die("Unable to open file!");
        $txt = "#!/bin/bash\n";
        fwrite($myfile, $txt);
        $txt = "exec su root --command 'export QT_QPA_PLATFORM=offscreen && export XDG_RUNTIME_DIR=/tmp/runtime-runner && python3 ".$path."/scripts/tree.py' \n";
        fwrite($myfile, $txt);
        fclose($myfile);
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'out; bash '.$path.'/'.$data["posts"][0]['input'].'out/s.sh  >> '.$path.'/'.$data["posts"][0]['input'].'tree.log');
} else {
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'; sudo python3 '.$path.'/scripts/script_phylo.py -og '.$data["posts"][0]['outgroup'].' -d busco -o out -t 20 >> '.$path.'/'.$data["posts"][0]['input'].'phylo.log');
   shell_exec('sudo touch '.$path.'/'.$data["posts"][0]['input'].'out/s.sh');
        $myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/admin/core/".$data["posts"][0]['input']."out/s.sh", "w") or die("Unable to open file!");
        $txt = "#!/bin/bash\n";
        fwrite($myfile, $txt);
        $txt = "exec su root --command 'export QT_QPA_PLATFORM=offscreen && export XDG_RUNTIME_DIR=/tmp/runtime-runner && python3 ".$path."/scripts/tree.py ".$data["posts"][0]['outgroup']." ' \n";
        fwrite($myfile, $txt);
        fclose($myfile);
   $output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].'out; bash '.$path.'/'.$data["posts"][0]['input'].'out/s.sh  >> '.$path.'/'.$data["posts"][0]['input'].'tree.log');
}


$output = shell_exec('cat '.$path.'/'.$data["posts"][0]['input'].'busco.log '.$path.'/'.$data["posts"][0]['input'].'phylo.log '.$path.'/'.$data["posts"][0]['input'].'tree.log > '.$path.'/'.$data["posts"][0]['input'].'BuscoPhylo.log');

$output = shell_exec('mkdir '.$path.'/'.$data["posts"][0]['input'].'busco_summaries');
$output = shell_exec('cp '.$path.'/'.$data["posts"][0]['input'].'outBusco/*/short_summary*.txt '.$path.'/'.$data["posts"][0]['input'].'busco_summaries/');

$output = shell_exec('python3 '.$path.'/scripts/generate_plot.py -wd '.$path.'/'.$data["posts"][0]['input'].'busco_summaries/ ');

$output = shell_exec(' echo "end : '.date('d-m-y H:i:s').'" >> '.$path.'/'.$data["posts"][0]['input'].'runtime.log');

$output = shell_exec('cd '.$path.'/'.$data["posts"][0]['input'].' ; zip -r Result.zip *');

}
$response = array();
	$posts = array();
$ID=$data["posts"][0]['ID']; 
	  $name=$data["posts"][0]['name']; 
	  $lineage=$data["posts"][0]['lineage']; 
	  $mode=$data["posts"][0]['mode']; 
	  $input=$data["posts"][0]['input']; 
	  $output=$data["posts"][0]['output'];
	  $type=$data["posts"][0]['type'];
	  $email=$data["posts"][0]['email'];
	  if(isset($data["posts"][0]['outgroup'])){
	  $outgroup=$data["posts"][0]['outgroup'];
	  } else {
	  $outgroup="";
	  }
	  
 $posts[] = array('ID'=> $ID, 'name'=> $name, 'lineage'=> $lineage, 'mode'=> $mode, 'input'=> $input, 'output'=> 'Done', 'type'=> $type, 'email'=> $email, 'outgroup'=> $outgroup);


$response['posts'] = $posts;

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/results_2.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
}
}

?>