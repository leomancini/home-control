<?php
	$outlet = $_GET['outlet'];
	
	if(preg_match("/on/i", $_GET['command'], $match)) {
		$command = "on";
	} elseif(preg_match("/off/i", $_GET['command'], $match)) {
		$command = "off";
	}
			
	switch ($outlet) {
		case 1:
			$code = Array("on" => 1381683, "off" => 1381692);
		break;
		
		case 2:
			$code = Array("on" => 1381827, "off" => 1381836);
		break;
		
		case 3:
			$code = Array("on" => 1382147, "off" => 1382156);
		break;
		
		case 4:
			$code = Array("on" => 1383683, "off" => 1383692);
		break;
		
		case 5:
			$code = Array("on" => 1389827, "off" => 1389836);
		break;
	}

	if($outlet && $command) {       
		exec("../433Utils/RPi_utils/codesend ".$code[$command]." 0 150");
	}
?>
