<?php
$url = $_SESSION["server_pacs"];
//echo $url;die();
$curl = curl_init();
  curl_setopt_array($curl, array( 
  CURLOPT_URL => $url.'studies',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
  "cache-control: no-cache",
  ),
  ));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
//print_r($response);die();
  if ($err) {
  echo "cURL Error #:" . $err;
   } else {
  $res = explode(",",$response);
  $temp = "";
    for ($i = 0; $i < count($res); $i++) {
    $std = student($res[$i]);
    $temp[$i] = json_decode($std);
  }
           
  $result = json_decode(json_encode($temp), true);
  echo $result;
  }

function student($id){
  $url = $_SESSION["server_pacs"];
  $replace = str_replace('[', '', $id);
  $fix = str_replace('"', '', $replace);
  $stId = str_replace(']', '', $fix);
  $a = trim($stId);
  $url = $url.'/studies/'.$a;
  $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
     "cache-control: no-cache",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
      return $response;
    }

?>
