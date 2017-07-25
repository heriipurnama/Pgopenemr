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
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative'] ?>/jquery-ui-1-11-4/themes/ui-darkness/jquery-ui.min.css" />
    <link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <title>PACS</title>
    <style>
        .donations-needed {
            margin-top: 25px;
            margin-bottom: 25px;
            color: #c9302c;
        }
        .donations-needed a, .donations-needed a:visited,
        .donations-needed a:active {
            color: #c9302c;
        }
        .donations-needed a.btn {
            color: #c9302c;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            animation: all 2s;
        }
        .donations-needed a.btn:hover {
            background-color: #c9302c;
            color: #fff;
        }
        .donations-needed .btn {
            border-radius: 8px;
            border: 2px solid #c9302c;
            color: #c9302c;
            background-color: transparent;
        }
    </style>

    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative'] ?>/jquery-min-2-2-0/index.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']  ?>/jquery-ui-1-11-4/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var registrationTranslations = <?php echo json_encode(array(
            'title' => xla('OpenEMR Product Registration'),
            'pleaseProvideValidEmail' => xla('Please provide a valid email address'),
            'success' => xla('Success'),
            'registeredSuccess' => xla('Your installation of OpenEMR has been registered'),
            'submit' => xla('Submit'),
            'noThanks' => xla('No Thanks'),
            'registeredEmail' => xla('Registered email'),
            'registeredId' => xla('Registered id'),
            'genericError' => xla('Error. Try again later'),
            'closeTooltip' => xla('Close')
        ));
        ?>;

        var registrationConstants = <?php echo json_encode(array(
            'webroot' => $GLOBALS['webroot']
        ))
        ?>;
    </script>

    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/product_registration_service.js?v=<?php echo $v_js_includes; ?>"></script>
    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/product_registration_controller.js?v=<?php echo $v_js_includes; ?>"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            var productRegistrationController = new ProductRegistrationController();
            productRegistrationController.getProductRegistrationStatus(function(err, data) {
                if (err) { return; }

                if (data.status === 'UNREGISTERED') {
                    productRegistrationController.showProductRegistrationModal();
                } else if (data.status === 'REGISTERED') {
                    productRegistrationController.displayRegistrationInformationIfDivExists(data);
                }
            });
        });
    </script>
</head>

<body class="body_top">
   
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Set Server</h3>
  </div>
  <div class="panel-body">
    <form action="./dicom_file/send.php" class="smart-form" novalidate="novalidate" method="POST">
                    <fieldset align="left">
                        <div class="form-inline">
                            <div class="form-group">
                <label class="input"> 
                         <label style="font-size: 13px">Choose Modality : </label>
                    <select name="server" placeholder="Choose Modalities" class="form-titel form-control" id="form-titel" style="width: 300px">
                        <?php
                        // $servers = array('http://192.168.1.226:9042/','http://192.168.1.230:9042/');
                        $servers = array('http://192.168.56.103:8042/','http://192.168.1.226:9042/');
                        foreach ($servers as $k => $val) { ?>
                        <option value="<?php echo $val; ?>"><?php echo $val; ?></option>    
                        <?php } ?>
                    </select>
                    <b class="tooltip tooltip-bottom-left">Choose Server</b> 
                </label>

    <div class="note">
        <i>*must choosen</i>
    </div>
    </div>
    </div> 
    </fieldset>
       <footer align="left" style="left: 0px;">
        <div align="left">
         <button class="btn btn-primary pull-left" align="left" type="submit">
           <i class="fa fa-save" align="left"></i>
             <?php echo 'Send';?>
         </button>
             <a class="btn btn-default  pull-left" href="#Patient" align="left">
           <i class="fa fa-rotate-left" ></i>
             <?php echo 'Back';?>
             </a>
        </div>
    </footer>
        </form>
  </div>
</div>

</body>
</html>
<script type="text/javascript">
    
</script>
