<?php
   include '../config.php';
   $page = 'PROFILE';
   
   // CEK USER LOGIN
   if (!isset($_COOKIE['login'])) {
       header('Location: ../index.php');
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo $title_name; ?>: <?php echo $slogan; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <link rel="icon" href="../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <!-- META SEO -->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/checkout/detail.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <div class="width">
         <div class="checkout">
            <?php
               if (isset($_GET['waybill'])) {
                   $waybill = $_GET['waybill'];
                   $courier = $_GET['courier'];
               
                   function cekresi($courier, $waybill) {
                       $courierwaybill = 'courier=' . $courier . '&waybill=' . $waybill;
                       $curl = curl_init();
               
                       curl_setopt_array($curl, array(
                           CURLOPT_URL => 'https://pro.rajaongkir.com/api/waybill',
                           CURLOPT_RETURNTRANSFER => true,
                           CURLOPT_ENCODING => '',
                           CURLOPT_MAXREDIRS => 10,
                           CURLOPT_TIMEOUT => 0,
                           CURLOPT_FOLLOWLOCATION => true,
                           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                           CURLOPT_CUSTOMREQUEST => 'POST',
                           CURLOPT_POSTFIELDS => $courierwaybill,
                           CURLOPT_HTTPHEADER => array(
                               'key: 42d9164584a209caad6f635480f01b35',
                               'Content-Type: application/x-www-form-urlencoded'
                           ),
                       ));
                       $response = curl_exec($curl);
                       curl_close($curl);
               
                       return json_decode($response, true);
                   }
               
                   $result = cekresi($courier, $waybill);
               
                   if (isset($result['rajaongkir']['result']['summary']) && $result['rajaongkir']['result']['summary'] != "") {
                       $data = $result['rajaongkir']['result']['summary'];
                       $manifest = $result['rajaongkir']['result']['manifest'];
                       $delivery_status = $result['rajaongkir']['result']['delivery_status'];
                       ?>
            <div class="alamat">
               <div class="box_alamat">
                  <h1><i class="ri-time-line"></i> Status Pengiriman</h1>
                  <h5 id="ubah_alamat" onclick="ubahcatatan()"><?= $data['status']; ?></h5>
               </div>
            </div>
            <div class="detail_checkout">
               <table>
                  <tr>
                     <th>Tanggal Kirim</th>
                     <td><?= $data['waybill_date']; ?></td>
                  </tr>
                  <tr>
                     <th>Penerima</th>
                     <td><?= $data['receiver_name']; ?></td>
                  </tr>
                  <tr>
                     <th>Asal Kota</th>
                     <td><?= $data['origin']; ?></td>
                  </tr>
                  <tr>
                     <th>Destinasi</th>
                     <td><?= $data['destination']; ?></td>
                  </tr>
                  <tr>
                     <th>Status Pengiriman</th>
                     <td colspan="2">
                        <?= @$delivery_status['pod_receiver']; ?><br>
                        <?= @$delivery_status['pod_date']; ?> | <?= @$delivery_status['pod_time']; ?>
                     </td>
                  </tr>
               </table>
            </div>
            <br/>
            <div class="alamat">
               <div class="box_alamat">
                  <h1><i class="ri-map-pin-2-line"></i> Detail Pengiriman</h1>
               </div>
            </div>
            <div class="detail_checkout">
               <table>
                  <tr>
                     <th>Status</th>
                     <th>Tanggal</th>
                  </tr>
                  <?php
                     foreach (@$manifest as $outbond) {
                         echo "<tr>";
                         echo "<td>{$outbond['manifest_date']} ( {$outbond['manifest_time']} )</td>";
                         echo "<td>{$outbond['manifest_description']}</td>";
                         echo "</tr>";
                     }
                     ?>
               </table>
            </div>
            <?php
               } else {
                   echo "<h4>Silakan masukkan nomor resi dan pilih kurir yang ingin dicek.</h4>";
               }
               }
               ?>
         </div>
      </div>
      <style>
         table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
         }
         th, td {
         padding: 15px 15px 15px 0px;
         text-align: left;
         border-bottom: 1px solid #ddd;
         }
         th {
         background-color:none;
         }
         @media screen and (max-width: 600px) {
         table {
         font-size: 14px;
         }
         th, td {
         padding: 6px 8px;
         }
         }
      </style>
      <div class='clear'>
         <!-- FOOTER --> 
         <?php include '../partials/footer.php'; ?>
         <?php include '../partials/bottom-navigation.php'; ?>
         <!-- FOOTER -->
      </div>
   </body>
</html>
