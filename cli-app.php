<?php
// include class
require('phpMQTT.php');


// set configuration values
$config = array(
  'org_id' => 'IOTF-ORG-ID',
  'port' => '1883',
  'app_id' => 'phpmqtt',
  'iotf_api_key' => 'IOTF-API-KEY',
  'iotf_api_secret' => 'IOTF-API-TOKEN',
  'device_id' => 'DEVICE-_ID'
);


$config['server'] = $config['org_id'] . '.messaging.internetofthings.ibmcloud.com';
$config['client_id'] = 'a:' . $config['org_id'] . ':' . $config['app_id'];
$location = array();

// initialize client
$mqtt = new phpMQTT($config['server'], $config['port'], $config['client_id']); 
$mqtt->debug = false;

// connect to broker
if(!$mqtt->connect(true, null, $config['iotf_api_key'], $config['iotf_api_secret'])){
  echo 'ERROR: Could not connect to IoT cloud';
	exit();
} 

// subscribe to topics
$topics['iot-2/type/+/id/' . $config['device_id'] . '/evt/accel/fmt/json'] = 
  array('qos' => 0, 'function' => 'getLocation');
$mqtt->subscribe($topics, 0);

// process messages
while ($mqtt->proc(true)) { 
}

// disconnect
$mqtt->close();

function getLocation($topic, $msg) {
  $json = json_decode($msg);
  echo date('d-m-y h:i:s') . " Device located at (51.5081, 0.1281)" . PHP_EOL;
}
?>