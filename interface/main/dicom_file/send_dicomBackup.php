<?php
/**
 * OpenEMR About Page
 *
 * This Displays an About page for OpenEMR Displaying Version Number, Support Phone Number
 * If it have been entered in Globals along with the Manual and On Line Support Links
 *
 * Copyright (C) 2016 Terry Hill <terry@lillysystems.com>
 * Copyright (C) 2016 Brady Miller <brady.g.miller@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author Terry Hill <terry@lilysystems.com>
 * @author Brady Miller <brady.g.miller@gmail.com>
 * @author heriipurnama <heriipurnama.github.io>
 * @link http://www.open-emr.org
 *
 * Please help the overall project by sending changes you make to the author and to the OpenEMR community.
 *
 */

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");

// ini_set('upload_max_filesize', '128M');
// ini_get_all('post_max_size','128M');
// ini_set('safe_mode ', 'Off');
// ini_set('max_input_time', '2592000');
// ini_set('memory_limit', '3000M');
// ini_set('post_max_size', '200M');
// ini_set('upload_max_filesize', '200M');
// ini_set('max_execution_time', '2592000');
// ini_set('max_file_uploads', '100');
// ini_set('session.gc_maxlifetime', '1200'); 
//echo $GLOBALS['assets_static_relative'];
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

if($_SERVER["REQUEST_METHOD"] == "POST"){

  /*
    Query ambil server orthanc yang aktif dari value dari db
  */
  $sql     = sqlStatement("SELECT title  FROM list_options 
                       where list_id='PACS' and activity=1 and option_id LIKE 'pacs_in%' 
                       ORDER BY seq limit 1");
             $result= sqlFetchArray($sql);
             $url   = $result['title'];
  /**
  skrip upload single upload
*/
  if(isset($_FILES['image'])){
      $errors    = array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp  = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $error     = $_FILES['image']['error'];

        if(empty($errors)==true) {

          /*$url ="http://192.168.56.103:8042"; //server-vm-igor
          rest API ke server orthanc*/
          $a       = shell_exec('curl -H POST '.$url.'/instances --data-binary @'.$file_tmp);

          /*Misalkan nama variabel data json Anda adalah $a lalu dekode 
          dan berikan ke variabel baru $json_array*/
          $json_array = json_decode($a); 
          $data = $json_array->Status;
            //jika data dalam databases
            if ($data == 'AlreadyStored') {
              # code...
                echo "<script type=\"text/javascript\">
                        window.alert('Sent data already exists in the database');
                        window.location.href = '../upload_dicom.php';
                      </script>";
                exit;
            }
            //jika data berhasil dikirim ke databases
            elseif ($data == 'Success') {
              # code...
                echo "<script type=\"text/javascript\">
                        window.alert('Data that was sent was successful');
                        window.location.href = '../upload_dicom.php';
                      </script>";
                exit;
            //jika gagal terkirim
            }else {
              # code...  
                echo "<script type=\"text/javascript\">
                        window.alert('Data sent is failled maybe not in the dicom format');
                        window.location.href = '../upload_dicom.php';
                      </script>";
                exit;
            }
            
        }else{
          print_r($errors);
        }
  
  /**
  skrip upload multiple drag n drop
  */
  }elseif(!empty($_FILES)) {
    # code...
    $tmpFile   = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_name = $_FILES['file']['name'];
    $extensi   = mime_content_type($tmpFile);

      if(empty($errors)==true) {
        /*$url ="http://192.168.56.103:8042"; //server-vm-igor
        rest API ke server orthanc*/
        if ($extensi=="application/dicom") {
          # code...
           try {
             //$a = shell_exec('curl -H POST '.$url.'/instances --data-binary @'.$tmpFile);
      
              $postfields = array(
                'upload_file' => '@'.$tmpFile
              );

              $curl = curl_init();
              curl_setopt($curl, CURLOPT_URL, $url.'/instances');
              curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
              curl_exec($curl);
              curl_close($curl);

           } catch (Exception $e) {
             return false;        
           }
        }
        else{
          try {
               move_uploaded_file($tmpFile,"/var/www/chanthel/upload-data/".$file_name);
          } catch (Exception $e) {
            return false;    
          }
        }
      }else{
        print_r($errors);
      }
  }
}
?>