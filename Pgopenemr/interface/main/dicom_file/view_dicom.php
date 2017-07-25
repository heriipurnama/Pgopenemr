<?php
require_once("../../globals.php");
$id = $_GET['id'];
//echo $id;

?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative'] ?>/jquery-ui-1-11-4/themes/ui-darkness/jquery-ui.min.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']  ?>/jquery-ui-1-11-4/jquery-ui.min.js"></script>
<title>Dicom Viewer</title>
</head>
<body class="body_top">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="../dicom.php">PACS</a></li>
  <li role="presentation"><a href="../dicom_file/send.php">Patients</a></li>
  <li role="presentation" class="active"><a href="#">Detail</a></li>
</ul>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">PACS : <?php echo $_SESSION["server_pacs"];?></h3>
  </div>
<div class="panel-body">
  <ul class="nav nav-tabs">
    <!-- <li role="presentation"><a href="../dicom.php">PACS</a></li>
    <li role="presentation"><a href="../dicom_file/send.php">Patients</a></li> -->
    <li role="presentation"><a href="../dicom_file/detail.php?id=<?php echo $id; ?>">Detail Patient</a></li>
    <li role="presentation" class="active"><a href="../dicom_file/view_dicom.php?id=<?php echo $id; ?>">Dicom Viewer</a></li>
  </ul>
<iframe src="http://192.168.56.103:8042/osimis-viewer/app/index.html?study=<?php echo $id;?>" frameborder='0' height='100%' width='100%'  >
 <p>Your browser does not support iframes.</p>
</iframe>
</div>
</div>
<!-- <iframe  ></iframe> -->

</body>

<!-- stuff for the popup calendar -->


</html>