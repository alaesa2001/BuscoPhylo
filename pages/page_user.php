<?php
session_start();
require_once('../db/config.php');
require_once('../const/web-info.php');
require_once('../const/check_session.php');
require_once('../const/my_profile.php');
switch($res) {
	case '0':
	$_SESSION['reply'] = "006";
	header("location:../login");
	break;

	case '2':
	$_SESSION['reply'] = "005";
	header("location:../login");
	break;

	case '3':
	$_SESSION['reply'] = "007";
	header("location:../login");
	break;

	case '1':
	$logged = "1";
	break;
}


if ($role == "user") {
}else{
$_SESSION['reply'] = "008";
header("location:../login");
}
$dir = "./";
?>
<?php

	$server_check_version = '1.0.4';
	$start_time = microtime(TRUE);

	$operating_system = PHP_OS_FAMILY;

	if ($operating_system === 'Windows') {
		// Win CPU
		$wmi = new COM('WinMgmts:\\\\.');
		$cpus = $wmi->InstancesOf('Win32_Processor');
		$cpuload = 0;
		$cpu_count = 0;
		foreach ($cpus as $key => $cpu) {
			$cpuload += $cpu->LoadPercentage;
			$cpu_count++;
		}
		// WIN MEM
		$res = $wmi->ExecQuery('SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem');
		$mem = $res->ItemIndex(0);
		$memtotal = round($mem->TotalVisibleMemorySize / 1000000,2);
		$memavailable = round($mem->FreePhysicalMemory / 1000000,2);
		$memused = round($memtotal-$memavailable,2);
		// WIN CONNECTIONS
		$connections = shell_exec('netstat -nt | findstr :80 | findstr ESTABLISHED | find /C /V ""'); 
		$totalconnections = shell_exec('netstat -nt | findstr :80 | find /C /V ""');
	} else {
		// Linux CPU
		$load = sys_getloadavg();
		$cpuload = $load[0];
		$cpu_count = shell_exec('nproc');
		// Linux MEM
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
		$mem = array_merge($mem); // puts arrays back to [0],[1],[2] after 
		$memtotal = round($mem[1] / 1000000,2);
		$memused = round($mem[2] / 1000000,2);
		$memfree = round($mem[3] / 1000000,2);
		$memshared = round($mem[4] / 1000000,2);
		$memcached = round($mem[5] / 1000000,2);
		$memavailable = round($mem[6] / 1000000,2);
		// Linux Connections
		$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
		$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	}

	//$memusage = round(($memavailable/$memtotal)*100);
	$memusage = round(($memused/$memtotal)*100);		


	$phpload = round(memory_get_usage() / 1000000,2);

	$diskfree = round(disk_free_space(".") / 1000000000);
	$disktotal = round(disk_total_space(".") / 1000000000);
	$diskused = round($disktotal - $diskfree);

	$diskusage = round($diskused/$disktotal*100);

	if ($memusage > 85 || $cpuload > 85 || $diskusage > 85) {
		$trafficlight = 'red';
	} elseif ($memusage > 50 || $cpuload > 50 || $diskusage > 50) {
		$trafficlight = 'orange';
	} else {
		$trafficlight = '#2F2';
	}

	$end_time = microtime(TRUE);
	$time_taken = $end_time - $start_time;
	$total_time = round($time_taken,4);

	// use servercheck.php?json=1
	if (isset($_GET['json'])) {
		echo '{"ram":'.$memusage.',"cpu":'.$cpuload.',"disk":'.$diskusage.',"connections":'.$totalconnections.'}';
		exit;
	}

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
	 <link rel="stylesheet" href="plugins/datatable/css/jquery.dataTables.min.css">
	 <link rel="stylesheet" href="plugins/datatable/css/responsive.bootstrap4.min.css">
	<link rel="icon" href="icon/<?php echo AppIcon; ?>" sizes="32x32">
	<meta name="description" content="<?php echo AppDesc; ?>">
	<meta name="author" content="ASahbou">
	<title><?php echo AppName; ?> – My Profile</title>
    
	<script src="https://kit.fontawesome.com/d4e23f03c3.js" crossorigin="anonymous"></script>
	

</head>

<body>
	<header class="header header--static">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="header__content">
						<button class="header__menu" type="button">
							<span></span>
							<span></span>
							<span></span>
						</button>
<style>
@media only screen and (max-width: 900px) {
    #headerlogo{
        height: 100%;
        width: 110%;
    }
}
@media only screen and (min-width: 900px) {
    #headerlogo{
        height: 100%;
        width: 150%;
    }
}
</style>
						<a href="./" class="header__logo">
							<?php echo AppLogo; ?>
						</a>

						<ul class="header__nav">

	            <li class="header__nav-item">
	              <a class="header__nav-link" href="<?php echo $dir; ?>">Home</a>
	            </li>
	            <li class="header__nav-item">
	              <a class="header__nav-link" href="<?php echo $dir; ?>project">Create Project</a>
	            </li>
	            <li class="header__nav-item">
	              <a class="header__nav-link" href="<?php echo $dir; ?>documentation">Documentation</a>
	            </li>
	            <li class="header__nav-item">
	              <a class="header__nav-link" href="<?php echo $dir; ?>contact">Contact</a>
	            </li>


	          </ul>

						<div class="header__actions">
							<form autocomplete="OFF" method="GET" action="<?php echo $dir; ?>search_catalog" class="header__form">
	              <input class="header__form-input" name="keywords" type="text" placeholder="I'm looking for..." required>
	              <button type="submit" class="header__form-btn" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z"/></svg></button>
	              <button  class="header__form-close"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.3345 0.000183105H5.66549C2.26791 0.000183105 0.000488281 2.43278 0.000488281 5.91618V14.0842C0.000488281 17.5709 2.26186 20.0002 5.66549 20.0002H14.3335C17.7381 20.0002 20.0005 17.5709 20.0005 14.0842V5.91618C20.0005 2.42969 17.7383 0.000183105 14.3345 0.000183105ZM5.66549 1.50018H14.3345C16.885 1.50018 18.5005 3.23515 18.5005 5.91618V14.0842C18.5005 16.7653 16.8849 18.5002 14.3335 18.5002H5.66549C3.11525 18.5002 1.50049 16.7655 1.50049 14.0842V5.91618C1.50049 3.23856 3.12083 1.50018 5.66549 1.50018ZM7.07071 7.0624C7.33701 6.79616 7.75367 6.772 8.04726 6.98988L8.13137 7.06251L9.99909 8.93062L11.8652 7.06455C12.1581 6.77166 12.6329 6.77166 12.9258 7.06455C13.1921 7.33082 13.2163 7.74748 12.9984 8.04109L12.9258 8.12521L11.0596 9.99139L12.9274 11.8595C13.2202 12.1524 13.2202 12.6273 12.9273 12.9202C12.661 13.1864 12.2443 13.2106 11.9507 12.9927L11.8666 12.9201L9.99898 11.052L8.13382 12.9172C7.84093 13.2101 7.36605 13.2101 7.07316 12.9172C6.80689 12.6509 6.78269 12.2343 7.00054 11.9407L7.07316 11.8566L8.93843 9.99128L7.0706 8.12306C6.77774 7.83013 6.77779 7.35526 7.07071 7.0624Z"/></svg></button>
	            </form>

							<a href="./<?php echo $role; ?>" class="header__user">
								<span style="white-space: nowrap;">My Account</span>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"/></svg>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<section class="section section--head">
		<div class="container">
			<div class="row">
				<div class="col-12 col-xl-6">
					<h1 class="section__title section__title--head">Profile</h1>
				</div>

				<div class="col-12 col-xl-6">
					<ul class="breadcrumb">
						<li class="breadcrumb__item"><a href="./">Home</a></li>
						<li class="breadcrumb__item breadcrumb__item--active">Profile</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<div class="catalog catalog--page">
		<div class="container mb-10">
			<div class="row">
				<div class="col-12">
					<div class="profile">
						<div class="profile__user">
							<div class="profile__avatar">
								<img class="profile_in" src="img/users/<?php echo $image; ?>" alt="">
							</div>
							<div class="profile__meta">
								<h3><?php echo $fname; ?> <?php echo $lname; ?> <?php if ($badge == "1") {print '<img title="Verified Account" id="verified" class="verification_inside" src="img/check.svg">';} ?></h3>
								<span style="margin-bottom: 2px"><strong><?php echo AppName; ?> ID:</strong> <?php echo $user_id; ?></span>
								<span style="color: #E28413"><strong><?php echo AppName; ?> TOKEN:</strong> <?php 
								try {
                                $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                
                                $stmt = $conn->prepare("SELECT * FROM tbl_token WHERE user_id = ".$user_id);
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                if (count($result) > 0) {
                                    // Results exist
                                    foreach($result as $row){
                                        echo $row["token"];
                                    }
                                } else {
                                    // No results found
                                    $prefix = "BUSCOPHYLO_";
                                    $token = uniqid($prefix).time();
                                    $stmt = $conn->prepare("INSERT INTO tbl_token (user_id, token) VALUES (?,?)");
                                    $stmt->execute([$user_id, $token]);
                                    $stmt = $conn->prepare("SELECT * FROM tbl_token WHERE user_id = ".$user_id);
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();
                                    foreach($result as $row){
                                        echo $row["token"];
                                    }
                                }
                                }catch(PDOException $e)
                                {
                                  echo "Connection failed: " . $e->getMessage();
                                }
								?></span>
							</div>
						</div>

						<ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Profile</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Settings</a>
							</li>
						</ul>
						<?php 
						if($email=="slimane.khayi@inra.ma" || $email=="slimane.khayi@gmail.com" || $email=="alae.shb147@gmail.com"){
						?>
						<!--<a href="news" class="profile__logout" type="button" style="margin-right: 10px;" >-->
						<!--	<span>Manage News <i class="fa-solid fa-list-check"></i></span> -->
						<!--</a>-->
						<a href="https://analytics.google.com/analytics/web/?authuser=0&hl=fr#/p334915145/reports/reportinghub?params=_u..nav%3Dmaui" class="profile__logout" type="button" style="margin-right: 10px;" target="_blank">
							<span>Google Analytics</span>
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
                              <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                              <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                              <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
						</a>
						<?php } ?>
						<a href="logout" class="profile__logout" type="button">
							<span>Sign out</span>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4,12a1,1,0,0,0,1,1h7.59l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l4-4a1,1,0,0,0,.21-.33,1,1,0,0,0,0-.76,1,1,0,0,0-.21-.33l-4-4a1,1,0,1,0-1.42,1.42L12.59,11H5A1,1,0,0,0,4,12ZM17,2H7A3,3,0,0,0,4,5V8A1,1,0,0,0,6,8V5A1,1,0,0,1,7,4H17a1,1,0,0,1,1,1V19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V16a1,1,0,0,0-2,0v3a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V5A3,3,0,0,0,17,2Z"></path></svg>
						</a>
						
					</div>
				</div>
			</div>
		</div>

		<div class="container">

			<div class="tab-content">
				<?php require_once('../const/check_reply.php'); ?>
				

						<div class="tab-pane fade show active" id="tab-1" role="tabpanel">
					<div class="row row--grid">
						<div class="col-12 col-xl-12">

							<div class="dashbox">
								<div class="dashbox__title">
									<h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8,11a1,1,0,1,0,1,1A1,1,0,0,0,8,11Zm4,0a1,1,0,1,0,1,1A1,1,0,0,0,12,11Zm4,0a1,1,0,1,0,1,1A1,1,0,0,0,16,11ZM12,2A10,10,0,0,0,2,12a9.89,9.89,0,0,0,2.26,6.33l-2,2a1,1,0,0,0-.21,1.09A1,1,0,0,0,3,22h9A10,10,0,0,0,12,2Zm0,18H5.41l.93-.93a1,1,0,0,0,.3-.71,1,1,0,0,0-.3-.7A8,8,0,1,1,12,20Z"></path></svg> Your Projects</h3>

								</div>

								<div class="dashbox__table-wrap dashbox__table-wrap--2">
								    <?php 
								    try {
											$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


											$stmt->execute([$email]);
											$result = $stmt->fetchAll();
								    if($email=="slimane.khayi@inra.ma" || $email=="slimane.khayi@gmail.com" || $email=="alae.shb147@gmail.com"){
											$stmt = $conn->prepare("SELECT * FROM tbl_items ORDER BY c_id DESC");
											$admin=1;
} else {
											$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE email = ? OR trailer_link=".$user_id." ORDER BY c_id DESC");
}
if($admin==1){
								    ?>
								    <style>
								        ::selection {
  background: orange;
}
								    </style>
								<table class="main__table main__table--dash">
										<thead>
											<tr>
												<th>💽 Hard Disk</th>
												<th>💽 Hard Disk Size</th>
												<th>💽 Hard Disk Usage</th>
												<th>🖥️ CPU Usage</th>
												<th>🌡️ RAM Usage</th>
											</tr>
										</thead>
										<tbody>
											<?php
											
												?>
												<tr>
													<td>
														<div class="main__table-text">disk3</div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $disktotal; ?> GB</div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $diskusage; ?>%</div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $cpuload; ?>%</div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $memusage; ?>%</div>
													</td>
												</tr>
												<?php
}
											}catch(PDOException $e)
											{
											echo "Connection failed: " . $e->getMessage();
											}
											?>

										</tbody>
									</table>
									<table id="datatbl2" class="main__table main__table--dash">
										<thead>
											<tr>
												<th>Name</th>
												<th>Information</th>
												<th>DATE</th>
												<th>Status</th>
												<th>Email</th>
												<th></th>
												
						<?php 
						if($email=="slimane.khayi@inra.ma" || $email=="slimane.khayi@gmail.com" || $email=="alae.shb147@gmail.com"){
						?>
						                        <th></th>
						<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php
											try {
											$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if($email=="slimane.khayi@inra.ma" || $email=="slimane.khayi@gmail.com" || $email=="alae.shb147@gmail.com"){
											$stmt = $conn->prepare("SELECT * FROM tbl_items ORDER BY c_id DESC");
											$admin==1;
} else {
											$stmt = $conn->prepare("SELECT * FROM tbl_items WHERE email = ? OR trailer_link=".$user_id." ORDER BY c_id DESC");
}
											$stmt->execute([$email]);
											$result = $stmt->fetchAll();

											foreach($result as $row)
											{
												$st1 = preg_replace("/[^a-zA-Z]/", " ", $row[1]);
												$st2 =  preg_replace('/\s+/', ' ', $st1);
												$item_title = strtolower(str_replace(' ', '-', $st2));

												?>
												<tr>
													<td>
														<?php
														if(in_array($row[0], array('183','184','201'), true )){
														?>
														<div class="main__table-text"><a target="_blank" href="item/<?php echo $row[2]; ?>/<?php echo $item_title; ?>" id="TestData" ><?php echo $row[1];?></a></div>
														<?php } else { ?>
														<div class="main__table-text"><a target="_blank" href="item/<?php echo $row[2]; ?>/<?php echo $item_title; ?>"><?php echo $row[1];?></a></div>
														<?php } ?>
													</td>
													<td>
														<div class="main__table-text"><?php echo $row[7]." | ".$row[9]; ?></div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $row[12]; ?></div>
													</td>
													<td>
														<div class="main__table-text" <?php if($row[5]=="Error"){ ?> style="color: red;" <?php } else if($row[5]=="Failed"){ ?> style="color: orange;" <?php } ?> ><?php echo $row[5]; ?></div>
													</td>
													<td>
														<div class="main__table-text"><?php echo $row['email']; ?></div>
													</td>
													<td width="100">
														<div class="main__table-text">
													<a href="/item/<?php echo $row[2]; ?>/<?php echo $row[1]; ?>" class="delete_link">
														View
													</a></div>
												</td>
												<?php 
						if($email=="slimane.khayi@inra.ma" || $email=="slimane.khayi@gmail.com" || $email=="alae.shb147@gmail.com"){
						?>
						                        <td width="100">
														<div class="main__table-text-red">
													<a href="./admin/core/delete_admin.php?q=<?php echo $row[2]; ?>" class="delete_link">
														<i class="fa-solid fa-trash-can"></i> Delete
													</a></div>
												</td>
						<?php } ?>
												</tr>
												<?php
											}

											}catch(PDOException $e)
											{
											echo "Connection failed: " . $e->getMessage();
											}
											?>

										</tbody>
									</table>
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
							<?php if ($pages > 12) {
							?><button id="loadBtn" class="catalog__more weeee" >Load more</button><?php
							}?>

						</div>
						<input type="hidden" id="row" value="0">
						<input type="hidden" id="postCount" value="<?php echo $postCount; ?>">
					</div>
				</div>

				<div class="tab-pane fade" id="tab-3" role="tabpanel">
					<div class="row">
						<div class="col-12">
							<div class="sign__wrap">
								<div class="row">

									<div class="col-12 col-lg-6">
										<form method="POST" id="app_frm" action="core/update_profile" class="sign__form sign__form--profile sign__form--first" enctype="multipart/form-data" autocomplete="OFF">
		                  <div class="row">
		                    <div class="col-12">
		                      <h4 class="sign__title">Profile details</h4>
		                    </div>

		                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
		                      <div class="sign__group">
		                        <label class="sign__label label_white">First Name</label>
		                        <input value="<?php echo $fname; ?>" type="text" name="first_name" class="sign__input txt-cap" required>
		                      </div>
		                    </div>

		                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
		                      <div class="sign__group">
		                        <label class="sign__label label_white">Last Name</label>
		                        <input value="<?php echo $lname; ?>" type="text" name="last_name" class="sign__input txt-cap" required>
		                      </div>
		                    </div>

		                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
		                      <div class="sign__group">
		                        <label class="sign__label label_white">Email</label>
		                        <input value="<?php echo $email; ?>" type="email" name="email" class="sign__input" required>
		                      </div>
		                    </div>

		                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
		                      <div class="sign__group">
		                        <label class="sign__label label_white">Username</label>
		                        <input value="<?php echo $rusername; ?>" type="text" name="username" class="sign__input" required>
		                      </div>
		                    </div>

		                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
		                      <div class="sign__group">
		                        <label class="sign__label label_white" >Avator</label>
		                        <div class="form__gallery">
		    											<label id="gallery1" for="form__gallery-upload">Upload photo (Leave blank if no update)</label>
		    											<input data-name="#gallery1" id="form__gallery-upload" name="avator" class="form__gallery-upload" type="file" accept=".png, .jpg, .jpeg">
		    										</div>
		                      </div>
		                    </div>

		                    <div class="col-6">
		                      <button name="submit" value="1" class="sign__btn" type="submit">Save Changes</button>
		                    </div>
												<?php
												if ($image == "user.svg") {
												}else{
												?>
												<div class="col-6">
													<a onclick="return confirm('Delete image?');" href="core/drop_avator" class="sign__btn">Delete Image</a>
												</div>
												<?php
												}
												?>
		                  </div>
		                </form>
									</div>

									<div class="col-12 col-lg-6">
										<form method="POST" id="app_frmc" action="core/update_password" class="sign__form sign__form--profile sign__form--first" autocomplete="OFF">
	                  <div class="row">
	                    <div class="col-12">
	                      <h4 class="sign__title">Change password</h4>
	                    </div>

	                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
	                      <div class="sign__group">
	                        <label class="sign__label">Old Password</label>
	                        <input id="oldpass" type="password" name="oldpass" class="sign__input">
	                      </div>
	                    </div>

	                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
	                      <div class="sign__group">
	                        <label class="sign__label">New password</label>
	                        <input id="newpass" type="password" name="newpass" class="sign__input">
	                      </div>
	                    </div>

	                    <div class="col-12 col-md-6 col-lg-12 col-xl-6">
	                      <div class="sign__group">
	                        <label class="sign__label">Confirm new password</label>
	                        <input id="confirmpass" type="password" name="confirmpass" class="sign__input">
	                      </div>
	                    </div>

	                    <div class="col-12">
	                      <button name="submit" value="1" class="sign__btn" type="submit">Save Changes</button>
	                    </div>
	                  </div>
	                </form>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php require_once('../const/draws/footer.php'); ?>

	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/slider-radio.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/smooth-scrollbar.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/plyr.min.js"></script>
	<script src="js/main.js"></script>
	<script src="plugins/loader/waitMe.js"></script>
	<script src="js/forms.js"></script>
	<script src="js/favourites.js"></script>
	<script src="plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="plugins/datatable/js/responsive.bootstrap4.min.js"></script>
	<script src="plugins/datatable/js/dataTables.responsive.min.js"></script>
	<script>
	$(document).ready( function () {
		$('#datatbl').DataTable({
			responsive: true,
		"ordering": false
	});

	$('#datatbl2').DataTable({
		responsive: true,
	"ordering": false
});
	} );
</script>
</body>

</html>
