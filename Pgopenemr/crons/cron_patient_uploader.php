<?php
/**
Cron patient uploader
File ini digunakan untuk proses upload data pasien dari orthanc ke openemr
Proses yang dilakukan
1. Ambil list data patient dari orthanc,
2. Upload ke openemr
*/
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/adodb.inc.php");
//require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/drivers/adodb-mysqli.inc.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/drivers/adodb-postgres9.inc.php");

function _curl($Url){
  // is cURL installed yet?
  if (!function_exists('curl_init')){
    die('Sorry cURL is not installed!');
  }

  // OK cool - then let's create a new cURL resource handle
  $ch = curl_init();

  // Set URL
  curl_setopt($ch, CURLOPT_URL, $Url);

  // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_HEADER, 0);

  // Should cURL return or print out the data? (true = return, false = print)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Timeout in seconds
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);

  // Download the given URL, and return output
  $output = curl_exec($ch);

  // Close the cURL resource, and free system resources
  curl_close($ch);

  return $output;
}

function connectDB($host, $user, $pass, $dbname){
  $DBType   = 'mysqli';
  $DBServer = $host;
  $DBUser   = $user;
  $DBPass   = rawurlencode($pass);
  $DBName   = $dbname;
   
  // 1=ADODB_FETCH_NUM, 2=ADODB_FETCH_ASSOC, 3=ADODB_FETCH_BOTH
  $dsn_options='?persist=0&fetchmode=2';
   
  $dsn = "$DBType://$DBUser:$DBPass@$DBServer/$DBName$dsn_options";
   
  $conn = NewADOConnection($dsn);

  return $conn;

}

//calling connection
//$db = connectDB($host = 'localhost', $user = 'root', $pass = 'root', $dbname = 'openemr');
$db = connectDB($host = 'localhost', $user = 'postgres', $pass = '', $dbname = 'postgres');


$urlPatients = "http://192.168.56.103:8042/patients";
//$urlPatients = "http://192.168.1.226:9042/studies";
$orthancPatients = _curl($urlPatients);
$patients = json_decode($orthancPatients, true);
// print_r($patients);
// proses data dari orthanc
for($i=0;$i< sizeof($patients); $i++){
	$patientID = $patients[$i];
	// echo $patientID;
	$orthancPatient = _curl($urlPatients.DIRECTORY_SEPARATOR.$patientID);
	$patient = json_decode($orthancPatient);
  // print_r($patient);exit;
	$patientName = $patient->PatientMainDicomTags->PatientName;
	
  //split patientName
  if(strstr($patientName, " ")){
    $patientNameArr = explode(" ", $patientName);
    if(sizeof($patientNameArr) == 2){
      $firstName = $patientNameArr[0];
      $middleName = "";
      $lastName = $patientNameArr[1];
    }else{
      $firstName = $patientNameArr[0];
      $middleName = $patientNameArr[1];
      $lastname = $patientNameArr[2];
    }
  }else{
     if(strstr($patientName, "^")){
      $patientNameArr = explode("^", $patientName);
      if(sizeof($patientNameArr) == 2){
        $firstName = $patientNameArr[0];
        $middleName = "";
        $lastName = $patientNameArr[1];
      }else{
        $firstName = $patientNameArr[0];
        $middleName = $patientNameArr[1];
        $lastname = $patientNameArr[2];
      }
    }else{
      $firstName = $patientName;
      $middleName = "";
      $lastName = $patientName;
    }
  }

	if(strtolower($patientName) != 'anonymized' && strtolower($patientName) != 'anonymize' ){
		if(!empty($patient->PatientMainDicomTags->PatientSex)){
      $patientSex = $patient->PatientMainDicomTags->PatientSex;
      if(strtolower($patientSex) == 'm'){
        $patientTitle = 'Mr.';
      }

      if(strtolower($patientSex) == 'f'){
        $patientTitle = 'Mrs.';
      }

    }else{
      $patientSex = "";
    }

    if(!empty($patient->PatientMainDicomTags->PatientBirthDate)){
			$patientBirthDate = date('Y-m-d', strtotime($patient->PatientMainDicomTags->PatientBirthDate));
		}else{
			$patientBirthDate = "";
		}

    //check data 

    $query = "SELECT id from patient_data WHERE dicom_id = '".$patientID."' ";
    $response = $db->Execute($query);

    if($response === false) {
      trigger_error('Wrong SQL: ' . $query . ' Error: ' . $db->ErrorMsg(), E_USER_ERROR);
    } else {
      $rows_returned = $response->RecordCount();
    }
    if($rows_returned == 0){
      $query = "SELECT pid FROM patient_data ORDER BY pid DESC LIMIT 1";

      $response = $db->Execute($query);
      $arr = $response->GetRows();
      $pid = $arr[0]['pid'] + 1; 
      
      $query = "
                INSERT INTO patient_data (
                              title,
                              fname, 
                              mname, 
                              lname,
                              DOB, 
                              pharmacy_id, 
                              `date`, 
                              sex, 
                              pubpid,
                              pid,
                              providerID, 
                              ref_providerID,
                              dicom_id) 
                        VALUES(
                            '".$patientTitle."',
                            '".$firstName."',
                            '".$middleName."',
                            '".$lastName."',
                            '".$patientBirthDate."',
                            0,
                            '".date('Y-m-d H:i:s')."',
                            '".$patientSex."',
                            ".$pid.",
                            ".$pid.",
                            0,
                            0,
                            '".$patientID."'
                          )
      ";
      
      if($db->Execute($query) === false) {
        trigger_error('Wrong SQL: ' . $query . ' Error: ' . $db->ErrorMsg(), E_USER_ERROR);
      } else {
        $last_inserted_id = $db->Insert_ID();
        $affected_rows = $db->Affected_Rows();
        echo "Success add in OpenEMR the patient name $patientName \n";
      }
      
    }



	}   
	
}


