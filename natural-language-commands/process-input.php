<?php
    include('outlets.php');

    function waitForSend() {
        usleep(250000);
    }

    function setOutletStateByNumber($number, $state) {
        global $outletCodes;

        exec("../433Utils/RPi_utils/codesend ".$outletCodes[$number][$state]." 0 150");
        waitForSend();
    }

    function setOutletStateByLabel($label, $state) {
        global $outletCodes;
        global $outletLabels;

        $outletNumber = $outletLabels[$label];

        exec("../433Utils/RPi_utils/codesend ".$outletCodes[$outletNumber][$state]." 0 150");
        waitForSend();
    }

    function batchSetOutletStateByNumber($command) {
        global $outletCodes;

        $regularLights = Array($outletCodes["4"], $outletCodes["2"], $outletCodes["1"]);

        if($command == "allLightsOn") {
            foreach($outletCodes as $outletNumber => $onOffCodes) {
                setOutletStateByNumber($outletNumber, 'on');
            }
        } elseif($command == "allLightsOff") {
            foreach($outletCodes as $outletNumber => $onOffCodes) {
                setOutletStateByNumber($outletNumber, 'off');
            }
        } elseif($command == "regularLightsOn") {
            foreach($regularLights as $outletNumber => $onOffCodes) {
                setOutletStateByNumber($outletNumber, 'on');
            }
        } elseif($command == "regularLightsOff") {
            foreach($regularLights as $outletNumber => $onOffCodes) {
                setOutletStateByNumber($outletNumber, 'off');
            }
        }
    }

    function sendInfraredCommand($command) {        
        $server = "http://192.168.1.00/";
	
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $server."/".$command
        ));
        
        curl_exec($curl);
        curl_close($curl);
    }

    function inputContains($word) {
        global $input;

        return (strpos($input, $word) !== false);
    }

    $input = strtolower($_GET['input']);

    // All the regular lights
    if (inputContains('regular') && inputContains('on')) {
        batchSetOutletStateByNumber('regularLightsOn');
    } else if (inputContains('regular') && inputContains('off')) {
        batchSetOutletStateByNumber('regularLightsOff');

    // All the lights
    } else if (inputContains('house') && inputContains('on')) {
        batchSetOutletStateByNumber('allLightsOn');
    } else if (inputContains('house') && inputContains('off')) {
        batchSetOutletStateByNumber('allLightsOff');

    // Living room reading light
    } else if (inputContains('reading') && inputContains('on')) {
        setOutletStateByLabel('livingRoomReadingLight', 'on');
    } else if (inputContains('reading') && inputContains('off')) {
        setOutletStateByLabel('livingRoomReadingLight', 'off');
        
    // Living room light
    } else if (inputContains('living room') && inputContains('on')) {
        setOutletStateByLabel('livingRoomLight', 'on');
    } else if (inputContains('living room') && inputContains('off')) {
        setOutletStateByLabel('livingRoomLight', 'off');

    // Dining room light
    } else if (inputContains('dining room') && inputContains('on')) {
        setOutletStateByLabel('diningRoomLight', 'on');
    } else if (inputContains('dining room') && inputContains('off')) {
        setOutletStateByLabel('diningRoomLight', 'off');
    
    // Bedroom desk light
    } else if (inputContains('bedroom desk') && inputContains('on')) {
        setOutletStateByLabel('bedroomDeskLight', 'on');
    } else if (inputContains('bedroom desk') && inputContains('off')) {
        setOutletStateByLabel('bedroomDeskLight', 'off');

    // Bedroom light
    } else if (inputContains('bedroom') && inputContains('on')) {
        setOutletStateByLabel('bedroomLight', 'on');
    } else if (inputContains('bedroom') && inputContains('off')) {
        setOutletStateByLabel('bedroomLight', 'off');

    // Projector
    } else if (inputContains('projector') && inputContains('on')) {
        sendInfraredCommand('projectorOn');
    } else if (inputContains('projector') && inputContains('off')) {
        sendInfraredCommand('projectorOff');
    } else if (inputContains('tv') && inputContains('on')) {
        sendInfraredCommand('projectorOn');
    } else if (inputContains('tv') && inputContains('off')) {
        sendInfraredCommand('projectorOff');

    // Space Heater
    } else if (inputContains('heat') && inputContains('on')) {
        sendInfraredCommand('heaterTogglePower');
    } else if (inputContains('heat') && inputContains('off')) {
        sendInfraredCommand('heaterTogglePower');
    } else if (inputContains('heat') && (inputContains('up') || inputContains('increase'))) {
        if(preg_match('!\d+!', $input, $numbers)) {
            $count = intval($numbers[0]);

            for ($i = 0; $i < $count; $i++) {
                sendInfraredCommand('heaterIncreaseTemp');
                waitForSend();
            }
        } else {
            sendInfraredCommand('heaterIncreaseTemp');
        }
    } else if (inputContains('heat') && (inputContains('down') || inputContains('decrease'))) {
        if(preg_match('!\d+!', $input, $numbers)) {
            $count = intval($numbers[0]);

            for ($i = 0; $i < $count; $i++) {
                sendInfraredCommand('heaterDecreaseTemp');
                waitForSend();
            }
        } else {
            sendInfraredCommand('heaterDecreaseTemp');
        }
    } else if (inputContains('heat') && (inputContains('rotate') || inputContains('rotation'))) {
        sendInfraredCommand('heaterToggleRotation');


    // Sleep mode: turn off projector, turn off living room lights and turn on bedroom light
    } else if (inputContains('sleep')) {
        sendInfraredCommand('projectorOff');
        waitForSend();

        setOutletStateByLabel('bedroomLight', 'on');
        waitForSend();

        setOutletStateByLabel('diningRoomLight', 'off');
        waitForSend();
        setOutletStateByLabel('livingRoomReadingLight', 'off');
        waitForSend();
        setOutletStateByLabel('livingRoomLight', 'off');
        waitForSend();
        setOutletStateByLabel('bedroomDeskLight', 'off');

    // Eating mode: turn on projector, turn all lights off except dining room light
    } else if (inputContains('eat') && inputContains('on')) {
        sendInfraredCommand('projectorOn');
        waitForSend();

        setOutletStateByLabel('diningRoomLight', 'on');
        waitForSend();

        setOutletStateByLabel('livingRoomReadingLight', 'off');
        waitForSend();
        setOutletStateByLabel('livingRoomLight', 'off');
        waitForSend();
        setOutletStateByLabel('bedroomDeskLight', 'off');
        waitForSend();
        setOutletStateByLabel('bedroomLight', 'off');
    
    // Watching mode: turn on projector and turn all lights off
    } else if (inputContains('watch')) {
        sendInfraredCommand('projectorOn');
        waitForSend();

        setOutletStateByLabel('diningRoomLight', 'off');
        waitForSend();

        setOutletStateByLabel('livingRoomReadingLight', 'off');
        waitForSend();
        setOutletStateByLabel('livingRoomLight', 'off');
        waitForSend();
        setOutletStateByLabel('bedroomDeskLight', 'off');
        waitForSend();
        setOutletStateByLabel('bedroomLight', 'off');
    }
?>