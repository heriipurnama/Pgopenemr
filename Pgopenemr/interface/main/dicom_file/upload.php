<?php
// Copyright (C) 2012, 2016 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// Sanitize escapes and stop fake register globals.
//
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// Generate some code based on the list of columns.
//
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
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative'] ?>/jquery-ui-1-11-4/themes/ui-darkness/jquery-ui.min.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']  ?>/jquery-ui-1-11-4/jquery-ui.min.js"></script>
<title>Upload</title>
</head>
<body class="body_top">
<body class="body_top">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="../dicom.php">PACS</a></li>
  <li role="presentation"><a href="../dicom_file/send.php">Patients</a></li>
  <li role="presentation" class="active"><a href="../dicom_file/upload.php">Upload</a></li>
</ul>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">PACS : <?php echo $_SESSION["server_pacs"];?></h3>
  </div>
<div class="panel-body">
            <form action="#" method="post" enctype="multipart/form-data" id="js-upload-form">
              <input type="file" class="btn btn-file" name="image" id="fUpload" onchange="checkextension()"  multiple  > 
                        <div id="myProgress">
                            <div id="myBar">0%</div>
                        </div>
                        <br/>
                        <button type="submit" onclick="move()" class="btn btn-sm btn-primary">Upload files</button>
                        <script>
                          function move() {
                            var elem = document.getElementById("myBar");   
                            var width = 10;
                            var id = setInterval(frame, 10);
                            function frame() {
                              if (width >= 100) {
                                clearInterval(id);
                              } else {
                                width++; 
                                elem.style.width = width + '%'; 
                                elem.innerHTML = width * 1  + '%';
                              }
                            }
                          }
                        </script>
                  <!--     </div> -->
                    </form>
          <!-- widget content -->
          <div class="widget-body">
            
            <div class="row" align="center" style="min-height: 470px;">
                  <article class="col-lg-12">
                  </article>
              </div> 

          </div>
          <!-- end widget content -->

        </div>

</div>

<!-- form used to open a new top level window when a patient row is clicked -->
<form name='fnew' method='post' target='_blank' action='../main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>'>
<input type='hidden' name='patientID'      value='0' />
</form>

</body>
</html>

