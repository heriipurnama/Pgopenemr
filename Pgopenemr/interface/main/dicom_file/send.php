
<?php
// Start the session

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
// Set session variables
$_SESSION["server_pacs"] = $_POST['server'];
//echo $GLOBALS['assets_static_relative'];
//echo $_SESSION["server_pacs"];
$colcount = 0;
$header0 = "";
$header  = "";
$coljson = "";
$res = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
  "list_id = 'ptlistcols' AND activity = 1 ORDER BY seq, title");
while ($row = sqlFetchArray($res)) {
  $colname = $row['option_id'];
  $title = xl_list_label($row['title']);
  $header .= "   <th>";
  $header .= text($title);
  $header .= "</th>\n";
  $header0 .= "   <td align='center'><input type='text' size='10' ";
  $header0 .= "value='' class='search_init' /></td>\n";
  if ($coljson) $coljson .= ", ";
  $coljson .= "{\"sName\": \"" . addcslashes($colname, "\t\r\n\"\\") . "\"}";
  ++$colcount;
}

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
  //print_r($result);die();
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
<!DOCTYPE html>
<html>
<head>
<?php html_header_show(); ?>
    <title><?php echo xlt("Patients") ?></title>
<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">

<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/datatables.net-dt-1-10-13/css/jquery.dataTables.min.css" type="text/css">

<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/datatables.net-colreorder-dt-1-3-2/css/colReorder.dataTables.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative'] ?>/jquery-ui-1-11-4/themes/ui-darkness/jquery-ui.min.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']  ?>/jquery-ui-1-11-4/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-1-10-2/index.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/datatables.net-1-10-13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/datatables.net-colreorder-1-3-2/js/dataTables.colReorder.min.js"></script>

<script language="JavaScript">

$(document).ready(function() {

  var oTable = $('#user_table').DataTable({
            sDom: 'T<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"pull-right"ip>>>',
            PaginationType : "bootstrap",

            oLanguage: { "sSearch": "", 
                "sLengthMenu" : "_MENU_ &nbsp;"},
              });

});

function openNewTopWindow(pid) {
 document.fnew.patientID.value = pid;
 top.restoreSession();
 document.fnew.submit();
}

</script>

</head>
<body class="body_top">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="../dicom.php">PACS</a></li>
  <li role="presentation" class="active"><a href="../dicom_file/patient.php">Patients</a></li>
  <li role="presentation"><a href="../dicom_file/upload.php">Upload</a></li>
</ul>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">PACS : <?php echo $_SESSION["server_pacs"];?></h3>
  </div>
  <div class="panel-body">
     <table class="table table-striped table-bordered table-condensed" id="user_table">
                            <thead>
                                <tr>
                                  <th class="tengah">NO</th>
                                  <th class="tengah">Patient Name</th>
                                  <th class="tengah">Study Date</th>
                                  <th class="tengah">Institution Name</th>
                                  <th class="tengah">Action</th>
                                </tr>
                            </thead>
                           <tbody>
                                <?php $no = 1; foreach ($result as $k => $v) {
                               
                                ?>
                                  <tr>      
                                    <td><?php echo @$no; ?></td>
                                    <td><b><?php echo @$v['PatientMainDicomTags']['PatientName'];?></b></td>
                                    <td><?php echo date('Y-m-d', strtotime(@$v['MainDicomTags']['StudyDate'])) ;?></td>
                                    <td><?php echo @$v['MainDicomTags']['InstitutionName'];?></td>
                                    <td style="text-align:center">
                                     <a href="../dicom_file/detail.php?id=<?php echo $v['ID']; ?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i>Detail </a>
                                      <!-- <a href="#Patient/delete/<?php echo $v['ID']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete patient <?php  echo $v['PatientMainDicomTags']['PatientName']; ?> ?')"><i class="fa fa-trash-o"></i>Delete</a> -->
                                    </td>
                                  </tr>
                                <?php  $no++; }  ?>
                              </tbody>
                        </table>
  </div>
</div>

<form name='fnew' method='post' target='_blank' action='../main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>'>
<input type='hidden' name='patientID'      value='0' />
</form>

</body>
</html>