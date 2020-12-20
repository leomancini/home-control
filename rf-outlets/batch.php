<?php
	if(preg_match("/regular_lights/i", $_GET['command'], $match)) {
		if(preg_match("/on/i", $_GET['command'], $match)) {
			$command = "regular_on";
		} elseif(preg_match("/off/i", $_GET['command'], $match)) {
			$command = "regular_off";
		}
	} else {
		if(preg_match("/on/i", $_GET['command'], $match)) {
			$command = "all_on";
		} elseif(preg_match("/off/i", $_GET['command'], $match)) {
			$command = "all_off";
		}
	}
	
	$codes = Array(
		"1" => Array(
			"on" => 1381683,
			"off" => 1381692
		),
		"2" => Array(
			"on" => 1381827,
			"off" => 1381836
		),
		"3" => Array(
			"on" => 1382147,
			"off" => 1382156
		),
		"4" => Array(
			"on" => 1383683,
			"off" => 1383692
		),
		"5" => Array(
			"on" => 1389827,
			"off" => 1389836
		)
	);
	
	$regular_lights = Array($codes["4"], $codes["5"], $codes["1"]);
	
	if($command == "all_on") {
		foreach($codes as $code_pair) {
			exec("../433Utils/RPi_utils/codesend ".$code_pair["on"]." 0 150");
			usleep(250000);
		}
	} elseif($command == "all_off") {
		foreach($codes as $code_pair) {
			exec("../433Utils/RPi_utils/codesend ".$code_pair["off"]." 0 150");
			usleep(250000);
		}
	} elseif($command == "regular_on") {
		foreach($regular_lights as $code_pair) {
			exec("../433Utils/RPi_utils/codesend ".$code_pair["on"]." 0 150");
			usleep(250000);
		}
	} elseif($command == "regular_off") {
		foreach($regular_lights as $code_pair) {
			exec("../433Utils/RPi_utils/codesend ".$code_pair["off"]." 0 150");
			usleep(250000);
		}
	}
?>
