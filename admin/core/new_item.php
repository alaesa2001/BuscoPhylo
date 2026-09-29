<?php
session_start();
require_once('../../db/config.php');
require_once('../../const/web-info.php');
require_once('../../const/check_session.php');
require_once('../../const/my_profile.php');

// Sanitize Project_Name to be safe for filesystem / terminal
if (isset($_POST['Project_Name'])) {
    // Replace spaces with underscores and remove unsafe characters
    $_POST['Project_Name'] = preg_replace('/[^A-Za-z0-9._-]/', '_', $_POST['Project_Name']);

    // Fallback if name becomes empty after sanitization
    if (empty($_POST['Project_Name'])) {
        $_POST['Project_Name'] = 'project_' . time();
    }
}

if (isset($_POST['submit']) || !empty($_POST['submit']) || isset($_POST['Project_Name'])) {
    if (!file_exists("data/")) {
        mkdir("data");
    }

    if (!file_exists("data/" . $_POST['Project_Name'])) {
        mkdir("data/" . $_POST['Project_Name']);
        $target_dir = "data/" . $_POST['Project_Name'] . "/";
    } else {
        mkdir("data/" . $_POST['Project_Name'] . time());
        $target_dir = "data/" . $_POST['Project_Name'] . time() . "/";
    }

    // ✅ FIXED UPLOAD LOOP
    $total_count = count($_FILES['fileToUpload']['name']);
    for ($i = 0; $i < $total_count; $i++) {
        // Skip empty slots (browser sometimes appends one at the end)
        if (empty($_FILES["fileToUpload"]["name"][$i])) {
            continue;
        }

        $filename = basename($_FILES["fileToUpload"]["name"][$i]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // If file already exists, rename with timestamp
        if (file_exists($target_file)) {
            $target_file = $target_dir . time() . "_" . $filename;
        }

        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
            error_log("Upload failed for " . $filename);
        }
    }

    $x = $_POST['lineage'];
    $z = $target_dir;
    $name = $_POST['Project_Name'];

    $filecount = 0;
    $files = glob($target_dir . "*fna");
    if ($files) {
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
    $up_type = $imageFileType ?? '';
    $genre = $_POST['mode'];
    $destn_file_b = "TreeA.png";
    $mail = "";
    $destn_file = "TreeA.png";
    $date_check = date('Y-m-d');

    if (isset($_POST['Project_groupe'])) {
        $outgroup = str_replace(' ', '_', $_POST['Project_groupe']);
    } else {
        $outgroup = '';
    }

    if (!empty($user_id)) {
        $trailer = $user_id;
    } else {
        $trailer = "";
    }

    $email = $_POST['email_user'];

    try {
        $conn = new PDO(
            'mysql:host=' . DBHost . ';dbname=' . DBName . ';charset=' . DBCharset . ';collation=' . DBCollation . ';prefix=' . DBPrefix . '',
            DBUser,
            DBPass
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO tbl_items (title, item_id, description, year, run_time, plan, age, upload_type, genres, thumbnail, trailer_link, upload_date, cover, email, views, date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $item_id, $description, $year, $running_time, $plan_type, $age, $up_type, $genre, $destn_file_b, $trailer, $upload_date, $destn_file, $email, $outgroup, $date_check]);
        $_SESSION["project"] = $item_id;

        require_once 'mail/mail.php';
        $mail->setFrom('info@buscophylo.me', 'BuscoPhylo');
        $mail->addAddress($email);
        $img = '<img src="https://buscophylo.inra.org.ma/img/logo-1.png" style="height:70px;" />';
        $mail->Subject = "BuscoPhylo | " . $title;
        $message = 'Your Project ' . $title . ' Number ' . $item_id . ' is in Running You can check the progress in the link bellow : <br> <span style="color:#FFA500"><a href="https://' . $_SERVER['HTTP_HOST'] . '/item/' . $item_id . '/' . $title . '">https://' . $_SERVER['HTTP_HOST'] . '/item/' . $item_id . '/' . $title . '</a></span>';
        $mail->Body = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #5d5dd9;">
            <tr>
              <td>&nbsp;</td>
              <td class="container" style="max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
                <div class="content" style="max-width: 580px;padding: 10px;">
                  <table role="presentation" class="main" style="width: 100%;background: #000022;border-radius: 3px;color: white !important;">
                    <tr>
                      <td class="wrapper" style="padding: 20px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                          <tr>
                            <td>
                              ' . $img . '
                              <p style="font-size: 14px;margin: 0;margin-bottom: 15px;color: white;">' . $message . '<br></p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  <div class="footer" style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                      <tr>
                        <td class="content-block" style="font-size: 12px;padding: 10px;color: white;text-align: center;">
                          <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">' . AppName . ', All rights reserved ' . date('Y') . '</span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by" style="font-size: 12px;padding: 10px;color: #FFA500;text-align: center;">
                          Powered by BuscoPhylo.
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </td>
              <td>&nbsp;</td>
            </tr>
          </table>
      </body>';

        $mail->send();

        $_SESSION["project"] = $item_id;

        try {
            header("Location: https://buscophylo.inra.org.ma/project.php");
            exit;
        } catch (Exception $e) {
            ?>
            <script type="text/javascript">
                window.location.replace("/process.php");
            </script>
            <noscript>
                <meta http-equiv="refresh" content="0;url=https://buscophylo.inra.org.ma/process.php" />
            </noscript>
            <?php
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    header("location:../../");
}
?>
