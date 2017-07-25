<?php
  $username = $this->session->userdata("username");
  $has_insert = has_privilege($username, 'User_keyword', '_insert');
  $has_delete = has_privilege($username, 'User_keyword', '_delete');
?>
<script type="text/javascript" src="<?php echo ASSETS_URL.TEMPLATE?>/js/plugin/dropzone/dropzone.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL.TEMPLATE?>/js/report/jquery-1.23.3.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL.TEMPLATE ?>/js/plugin/dropzone/dropzone.min.js">

<section id="widget-grid" class="">
  <!-- row -->
  <div class="row">
    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-12">
      <!-- Widget ID (each widget will need unique ID)-->
      <div class="jarviswidget jarviswidget-color-blue" id="wid-id-1" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-colorbutton="false" >
        <header>
          <style type="text/css">
            .upload-drop-zone {
              height: 200px;
              border-width: 2px;
              margin-bottom: 20px;
            }

            /* skin.css Style*/
            .upload-drop-zone {
              color: #ccc;
              border-style: dashed;
              border-color: #ccc;
              line-height: 200px;
              text-align: center
            }

            .upload-drop-zone.drop {
              color: #222;
              border-color: #222;
            }

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
          </style>
          <span class="widget-icon"> <i class="fa fa-desktop fa-fw"></i> </span>
          <h2>Upload</h2>
          <script type="text/javascript">
            function checkextensiond() {
               var file = document.querySelector("#fUpload");
               if ( /\.(dcm|dicom)$/i.test(file.files[0].name) === false ) { 
                  alert("File not an dcm or dicom !"); 
              }
          }
        </script>
        </header>
        <!-- widget div-->
        <div>
          <!-- widget edit box -->
          <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
          </div>
          <!-- end widget edit box -->
            <form action="<?=BASE_URL?>Upload/add" method="post" enctype="multipart/form-data" id="js-upload-form">
              <?php 
                if ($this->session->userdata("act_msg")) { ?>
                   <div class="alert alert-block alert-success">
                       <a href="#" data-dismiss="alert" class="close">x</a>
                        <h4 class="alert-heading"><i class="fa fa-check-square-o"></i> Data Berhasil Terkirim Sukses!</h4>
                      <p>
                          <?php echo $this->session->userdata("act_msg"); ?>
                      </p>
                   </div>
              <?php
              }
             if ($this->session->userdata("del_msg")) {
                  ?>
                    <div class="alert alert-danger alert-block">
                      <a href="#" data-dismiss="alert" class="close">x</a>
                     <h4 class="alert-heading"><i class="fa fa-warning"></i> Data Yang Dikirim Tidak Sukses Data Sudah Ada !</h4>
                      <p>
                          <?php echo $this->session->userdata("del_msg"); ?>
                      </p>
                  </div>
              <?php
             }

             if ($this->session->userdata("format_msg")) {
                  ?>
                    <div class="alert alert-danger alert-block">
                      <a href="#" data-dismiss="alert" class="close">x</a>
                     <h4 class="alert-heading"><i class="fa fa-warning"></i> Data Yang Dikirim Bukan Dengan Format dicom !</h4>
                      <p>
                          <?php echo $this->session->userdata("format_msg"); ?>
                      </p>
                  </div>
              <?php
             }

            $this->session->set_userdata("act_msg", "");
            $this->session->set_userdata("del_msg", "");
            $this->session->set_userdata("format_msg", "");

            ?>
                   <!-- <div class="form-inline">
                        <div class="form-group"> -->
                          <input type="file" name="image" id="fUpload" onchange="checkextension()" required /> 
                          <!--id="js-upload-files"  id="js-upload-submit" -->                     
                           <!-- <div class="form-group">
                                <div class="col-sm-6 col-md-4">
                                  <select id="company" class="form-control" name="server">
                                     <option value="s1">orthanc-server 1</option>
                                     <option value="s2">orthanc-server 2</option>
                                  </select> 
                                </div>
                           </div> -->
                          <!-- </div>-->
                          <br/><br/>

                        <div id="myProgress">
                            <div id="myBar">0%</div>
                        </div>

                        <br/>
                          <button type="submit" onclick="move()" class="btn btn-primary">Upload files</button>
                          <input type="reset" value="Reset" class="btn btn-warning">
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
                  <!-- </div> -->
            </form>

 <!--  <h1>Upload</h1>
  <form action="<?=BASE_URL?>Upload/add" method="post" enctype="multipart/form-data" class="dropzone" id="my-dropzone" style="width: 700px;" >
     <input type="file" name="image" id="fUpload" onchange="checkextension()" />
     <button type="submit" onclick="move()" class="btn btn-primary" id="submit-all">Upload files</button>
    
     <script type="text/javascript">
            Dropzone.options.myDropzone = {
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                addRemoveLinks: true,
                
                init: function() {
                   var submitButton = document.querySelector("#submit-all")
                   myDropzone = this; // closure
                      submitButton.addEventListener("click", function() {
                        //alert('Terkirim');
                        myDropzone.processQueue();
                      });
                      this.on("addedfile", function() {
                        myDropzone.processQueue();
                      });
                }
            };
     </script>
     <input type="reset" value="Reset" class="btn btn-warning">
  </form> -->

  <form action="<?=BASE_URL?>Upload/add" method="post" enctype="multipart/form-data" class="dropzone" style="height: 30px;"id="image-upload">
    <div>
      <h3>Upload Multiple Image By Click On Box</h3>
    </div>
  </form>
  <script type="text/javascript">
     Dropzone.options.imageUpload = {
        addRemoveLinks: true,
        //acceptedFiles:'.dcm,.dicom',
        maxFilesize:4

    };
 </script>
<!-- <form action="<?=BASE_URL?>Upload/add" method="post" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data"> 
  <div class="dropzone-previews"></div>
    <div class="fallback">
       <input name="file" type="file" multiple/>
    </div> 
       <button type="submit" id="submit-all" class="btn btn-primary btn-xs">Upload the file</button>
       <script type="text/javascript">
          Dropzone.options.myAwesomeDropzone = { 
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            addRemoveLinks: true,
            previewsContainer: ".dropzone-previews",
            dictRemoveFile: "Remove",
            dictCancelUpload: "Cancel",
            dictDefaultMessage: "Drop the images you want to upload here",
            dictFileTooBig: "Image size is too big. Max size: 10mb.",
            dictMaxFilesExceeded: "Only 10 images allowed per upload.",
            //acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",
              init: function() {
                var myDropzone = this;
                //Upload images when submit button is clicked.
                $("#submit-all").click(function (e) {
                  e.preventDefault();
                  e.stopPropagation();
                  myDropzone.processQueue();
                });
                myDropzone.on("complete", function (file) {
                  if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
                    window.location.reload();
                  }
                });
              }
          }
       </script>
</form> -->



          <!-- widget content -->
          <div class="widget-body">
            <div class="row" align="center" style="min-height: 470px;">
                  <article class="col-lg-12">
                      <!-- <img src="<?php //echo ASSETS_URL.TEMPLATE?>/img/kcj.png" alt="KCJ" style="width:30%; padding:150px 10px 10px 10px"> -->
                  </article>
              </div> 
          </div>
          <!-- end widget content -->
        </div>
        <!-- end widget div -->
      </div>
      <!-- end widget -->
    </article>
    <!-- WIDGET END -->
  </div>
  <!-- end row -->
  <!-- row -->
  <div class="row">
  </div>
  <!-- end row -->
</section>
<!-- end widget grid -->