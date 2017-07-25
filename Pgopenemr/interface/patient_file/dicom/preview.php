<?php
$id = $_GET['id'];
//echo $id;

?>
<html>
	<head>
		<title>Dicom Viewer</title>
	</head>
<body class="body_top">
	<iframe src="http://192.168.56.103:8042/osimis-viewer/app/index.html?study=<?php echo $id;?>" frameborder='0' height='100%' width='100%'  >
      <p>Your browser does not support iframes.</p>
    </iframe>
<!-- <iframe  ></iframe> -->
</body>
<!-- stuff for the popup calendar -->
</html>