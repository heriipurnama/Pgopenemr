<html> 
 <title>Chanthel</title>
 <body>
  <?php
	$username = 'openemr';  //username chanthel
 	$cauth    = base64_encode(serialize('openemr123')); //password chanthel
 	/*echo "  ".$cauth;
 	exit();*/
  ?>
  <IFRAME src="<?php echo 'http://192.168.56.101:1314/chanthel/login/auth?key=login&u='.$username.'&p='.$cauth ?>" 
  	   height=100% width=100% frameborder=no>
  </IFRAME>
 </body>
 </html>
