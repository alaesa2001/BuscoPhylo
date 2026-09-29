<?php

header("location:https://buscophylo.inra.org.ma/process.php");
// require_once(dirname(__DIR__).'/db/config.php');


// try {
// $conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $stmtlin = $conn->prepare("SELECT * FROM tbl_items");
// $stmtlin->execute();
// $resultlin = $stmtlin->fetchAll();
// $lineageData = array();
// $modeData = array();

// foreach($resultlin as $row)
// {
//         $lineageData[] = $row[7];
//         $modeData[] = $row[9];
// }

// ?>
 <!--<html>
//   <head>
//     <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
//   </head>
//   <body>
//     <canvas id="myChart" width="400" height="400"></canvas>
//     <canvas id="myChart_mode" width="400" height="400"></canvas>
//     <script>
// 	window.onload = function() {
// 	    //get the data from the database and store it in an array
// var lineageData = <?php //echo json_encode($lineageData); ?>;

// // Function to generate random color
// function getRandomColor() {
//     var letters = '0123456789ABCDEF';
//     var color = '#';
//     for (var i = 0; i < 6; i++) {
//         color += letters[Math.floor(Math.random() * 16)];
//     }
//     return color;
// }

// // Count the occurrences of each lineage
// var counts = {};
// lineageData.forEach(function(x) {
//   counts[x] = (counts[x] || 0) + 1;
// });

// // Calculate the total number of lineages
// var total = lineageData.length;

// // Calculate the percentage of each lineage
// var percentages = Object.keys(counts).map(function(lineage) {
//   return {
//     lineage: lineage,
//     percentage: (counts[lineage] / total) * 100
//   };
// });

// var ctx = document.getElementById('myChart').getContext('2d');
// var myChart = new Chart(ctx, {
//     type: 'doughnut',
//     data: {
//         labels: percentages.map(function(e) {
//             return e.lineage;
//         }),
//         datasets: [{
//             label: 'Percentage of each lineage',
//             data: percentages.map(function(e) {
//                 return e.percentage;
//             }),
//           backgroundColor: lineageData.map(function(e) {
//                 return getRandomColor();
//             }),
//             borderColor: lineageData.map(function(e) {
//                 return getRandomColor();
//             }),
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: { xAxes: [{ display: false }], yAxes: [{ display: false }]}
//     }
// });

// var modeData = <?php //echo json_encode($modeData); ?>;

// // Count the occurrences of each mode
// var counts = {};
// modeData.forEach(function(x) {
//   counts[x] = (counts[x] || 0) + 1;
// });

// // Calculate the total number of mode
// var total = modeData.length;

// // Calculate the percentage of each mode
// var percentages = Object.keys(counts).map(function(mode) {
//   return {
//     mode: mode,
//     percentage: (counts[mode] / total) * 100
//   };
// });

// var ctx_mode = document.getElementById('myChart_mode').getContext('2d');
// var myChart_mode = new Chart(ctx_mode, {
//     type: 'doughnut',
//     data: {
//         labels: percentages.map(function(e) {
//             return e.mode;
//         }),
//         datasets: [{
//             label: 'Percentage of each mode',
//             data: percentages.map(function(e) {
//                 return e.percentage;
//             }),
//           backgroundColor: [
//                 'rgba(255, 99, 132, 0.2)',
//                 'rgba(54, 162, 235, 0.2)',
//                 'rgba(255, 206, 86, 0.2)'
//             ],
//             borderColor: [
//                 'rgba(255, 99, 132, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 206, 86, 1)'
//             ],
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: { xAxes: [{ display: false }], yAxes: [{ display: false }]}
//     }
// });

// }

// // var canvas = document.getElementById("myChart");

// // setTimeout(function() {
// //   canvas.toBlob(function(blob) {
// //     var img = document.createElement("img");
// //     img.src = URL.createObjectURL(blob);
// //     document.body.appendChild(img);
// //   });
// // }, 2000);

// // var canvas_mode = document.getElementById("myChart_mode");

// // setTimeout(function() {
// //   canvas_mode.toBlob(function(blob) {
// //     var img = document.createElement("img");
// //     img.src = URL.createObjectURL(blob);
// //     document.body.appendChild(img);
// //   });
// // }, 2000);


 </script>-->
 <!--</body>-->
 <?php



// // require_once('mail/mail.php');
// //     $mail->setFrom('info@buscophylo.me', 'BuscoPhylo');
// //     $mail->addAddress('alae.shb147@gmail.com');
// //     $img = '<img src="https://buscophylo.inra.org.ma/img/logo-1.png" style="height:70px;" />';
// //     $mail->Subject = "BuscoPhylo | ";
// //     $mail->Body    = '<body class="" style="background-color: #5d5dd9;font-family: Montserrat;-webkit-font-smoothing: antialiased;font-size: 16px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
// //           <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #5d5dd9;">
// //             <tr>
// //               <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
// //               <td class="container" style="font-family: Montserrat;font-size: 16px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
// //                 <div class="content" style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">

// //                   <table role="presentation" class="main" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #000022;border-radius: 3px;color: white !important;">
// //                     <tr>
// //                       <td class="wrapper" style="font-family: Montserrat;font-size: 16px;vertical-align: top;box-sizing: border-box;padding: 20px;">
// //                         <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
// //                           <tr>
// //                             <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">
// //                               '.$img.'
// //                               <p style="font-family: Montserrat;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;color: white;"><br></p>
// //                             </td>
// //                           </tr>
// //                         </table>
// //                       </td>
// //                     </tr>
// //                   </table>

// //                   <div class="footer" style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
// //                     <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
// //                       <tr>
// //                         <td class="content-block" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: white;text-align: center;">
// //                           <span class="apple-link" style="color: white;font-size: 12px;text-align: center;">BuscoPhylo, All rights reserved '.date('Y').'</span>
// //                         </td>
// //                       </tr>
// //                       <tr>
// //                         <td class="content-block powered-by" style="font-family: Montserrat;font-size: 12px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #FFA500;text-align: center;">
// //                           Powered by BuscoPhylo.
// //                         </td>
// //                       </tr>
// //                     </table>
// //                   </div>

// //                 </div>
// //               </td>
// //               <td style="font-family: Montserrat;font-size: 16px;vertical-align: top;">&nbsp;</td>
// //             </tr>
// //           </table>
// //       </body>';

   
// //   //  $mail->addAttachment('files/' . $_POST['file']);    // Optional name
// //     $mail->send();

// }catch(PDOException $e)
// {
// echo "Connection failed: " . $e->getMessage();
// }

?>
