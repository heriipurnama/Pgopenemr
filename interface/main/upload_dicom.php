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

$fake_register_globals = false;
$sanitize_all_escapes  = true;

require_once("../globals.php");
?>

<html>
  <head>
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-ui-1-11-4/themes/ui-darkness/jquery-ui.min.css" />
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-2-2-0/index.js" />
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
<!-- 
    <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script> -->
  <!--   <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css"> -->

    <title>Upload</title>
    <style>

        #selectedFiles img {
          max-width: 125px;
          max-height: 125px;
          float: left; 
          margin-bottom:10px;
        }

        img{
          border-radius: 20px;
        }
      /* skin.css Style progressBar*/
        #myProgress {
          width: 100%;
          background-color: #ddd;
        }

        #myBar {
          width: 0%;
          height: 30px;
          background-color: #4CAF50;
          text-align: center;
          line-height: 30px;
          color: white;
        }
        body{
          background-color: #fff;
        }

          /* Dragdrop */
          .content{
            border: 0px solid black;
            padding: 5px;
            margin-bottom: 5px;
            margin: 0 auto;
          }

          .content span{
            width: 250px;
          }

          .content span:hover{
            cursor: pointer;
          }

         .dropzone{
            border: 21px dashed #0087F7;
            border-radius: 5px;
            background: rgba(0,0,0,0.02);
            padding: 50px;
            height: auto;
         }

         .dropzone:hover{
            cursor: pointer;
         }

         

         @media (max-width:800px){
          .container{
              width:9%;
           }
         }
          .dz-message{
            text-align: center;
            font-size: 28px;
         }
          .dropzone .dz-message {
            margin:2em 0;
            padding-top:10px;
            text-align:center;
          }
          
    </style>
    <script src="<?php echo $GLOBALS['assets_static_relative']; ?>/dropzone/dropzone.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['assets_static_relative']; ?>/dropzone/dropzone.css">
    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/dropzone/jquery-1.12.0.min.js"></script>   
  </head>
    <body>
      <!-- <span class="help-block">
         &nbsp;&nbsp;&nbsp; <i class="fa fa-upload" style="font-size:16px;"></i>
         &nbsp;&nbsp;Upload Dicom Files
      </span> -->
    
      <div class="panel-body">
      <!--  form  -->
        <!-- <form action="./dicom_file/send_dicom.php" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data">
          <fieldset align="left">
            <div class="form-inline">
                <div class="form-group">
                  <label class="input">
                    <table border="0">
                      <tr>
                        <td><label style="font-size: 13px">UPLOAD:&nbsp;</label></td>
                        <td>
                          <div class="input-group">
                            <label class="input-group-btn">
                              <span class="btn btn-primary">
                                Browse&hellip;<input type="file" id="files" name="image" style="display: none;" id="fUpload" multiple="multiple" accept="application/*">
                              </span>
                            </label>
                              <input type="text" class="form-control" readonly style="right:3px;margin-top: 0px;" >     
                          </div>
                          <td>
                            <p>&nbsp;</p>
                          </td>
                        </td>
                        <td>
                          <button class="btn btn-primary pull-left" align="left" type="submit" id="input">
                            <i class="fa fa-save" align="left"></i>
                              <?php echo 'Send';?>
                          </button>     
                        </td>
                        <td>
                          <input type="reset" value="Reset" class="btn btn-danger" onclick="window.location.href='upload_dicom.php';"/> 
                        </td>
                      </tr>
                    </table> 
                  </label>
               </div>
            </div> 
          </fieldset>
          <script>
            $(function() {
              $(document).on('change', ':file', function() {
                var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
              });

              $(document).ready( function() {
                $(':file').on('fileselect', function(event, numFiles, label) {
                  var input = $(this).parents('.input-group').find(':text'),
                  log = numFiles > 1 ? numFiles + ' files selected' : label;
                  if( input.length ) {
                    input.val(log);
                  } else {
                    if( log ) alert(log);
                  }
                });
              });
            });
          </script>
        </form>
        <!-- end form  -->
        <!-- form dragDrop -->
        <form id="dropzonewidget" class="dropzone" action="./dicom_file/send_dicom.php">
         <!--  <div class="dz-message1">Click or Drop files here to upload</div> -->
          <div class="dz-message">Click or Drop files here to upload</div>
         <!--  <div id="s"></div> -->
        </form>
        <!-- end form dragDrop -->
      </div>
      <script>
      //clear chache setelah 9M 
       // $(document).ready(function(){
       //    setInterval(function(){cache_clear()},4500);
       //  });
       //  function cache_clear(){
       //    //window.location.reload(true);
       //    //$(".dz-message").fadeIn();
       //   }
       
        // if (document.querySelector('.frameDisplay') !== null) {
        //     //alert('It exists');
        //     document.getElementById("selectedFiles").innerHTML = "dz dz-preview ada";
        //     //$('.selectedFiles').hide();
        // }
        // else{
        //     //alert('hhaha');
        //     //document.getElementById("selectedFiles").innerHTML = "heriipurnama";
        //     document.getElementById("selectedFiles").innerHTML = "dz-preview kosong";
        // }
        //var x = document.getElementsByClassName("dz-preview");
        // if ($(".dz-preview").length>0){ 
        // //it exists 
        //    window.location.reload(true);
  
        // } 
        // else{
        //   //document.getElementById("selectedFiles").innerHTML = "dz-preview kosong";
        //   //window.location.reload(true);
        //   //$(".dz-message").fadeIn();
        // }
        

        //var parent = document.querySelector('.dropzone.dz-started');
        //parent.querySelector('.dz-message');
        var products = document.querySelectorAll(".dz-preview");

        if(products.length>0){
          //do-something
          //window.location.reload(true);
          //$(".dz-message1").fadeIn();
        }
        else{
          //something else
          //$(".dz-message1").fadeOut();
        
          //window.location.reload(true);
        }


        //dz-preview dz-processing dz-image-preview dz-success dz-complete
        // if ( $( ".dz-preview" ).length>0 ) {
        //    //$( "#myDiv" ).show();
        //    window.location.reload(true);
        // }
        // else{
        //   //window.location.reload(true);
        //   //document.getElementById("s").innerHTML = "dz-preview kosong"; 
        // }
      </script>
    </body>
</html>
