<?php
// include class
require('phpMQTT.php');

// set configuration values
$config = array(
  'org_id' => '2lu4fi',
  'port' => '1883',
  'app_id' => 'phpmqtt',
  'iotf_api_key' => 'a-2lu4fi-xp7p4e1lfv',
  'iotf_api_secret' => 'gH?Gr&VX*@ohw6-3Me',
  'maps_api_key' => 'AIzaSyCzwr7HRT5cQHE5SwZ6uMTv8q2tqy4m3n0',
  'device_id' => 'oneplus1',
  'qos' => 1  
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
  array('qos' => $config['qos'], 'function' => 'getLocation');
$mqtt->subscribe($topics, $config['qos']);

// process messages
$elapsedSeconds = 0;
while ($mqtt->proc(true)) { 
  if (count($location) == 2) {
    $latitude = $location[0];
    $longitude = $location[1];
    $mapsApiUrl = 'https://maps.googleapis.com/maps/api/staticmap?key=' . $config['maps_api_key'] . '&size=640x480&format=png&maptype=roadmap&style=element:geometry%7Ccolor:0xf5f5f5&style=element:labels.icon%7Cvisibility:off&style=element:labels.text.fill%7Ccolor:0x616161&style=element:labels.text.stroke%7Ccolor:0xf5f5f5&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xbdbdbd&style=feature:poi%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:poi.park%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:road%7Celement:geometry%7Ccolor:0xffffff&style=feature:road.arterial%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:road.highway%7Celement:geometry%7Ccolor:0xdadada&style=feature:road.highway%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:transit.line%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:transit.station%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:water%7Celement:geometry%7Ccolor:0xc9c9c9&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&scale=2&markers=color:green|' . sprintf('%f,%f', $latitude, $longitude);


    break;
  } 
  
  if ($elapsedSeconds == 5) {
    break;  
  }
  
  sleep(1);
  $elapsedSeconds++;
}

// disconnect
$mqtt->close();

function getLocation($topic, $msg) {
  global $location;
  $json = json_decode($msg);
  $location = array($json->d->lat, $json->d->lon);
  return $location;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPM Misty</title>
    <style>
    html, #content, #map {
      height: 100%;
    }
    #footer {
      text-align: center;
    }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>    
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta http-equiv="refresh" content="10">
  </head>
  <body>
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">UPM Misty</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#download">Orders</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#features">Shipping</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
        <div style="margin-top: 50px;">
            <div class="row bg-stuff">
                <div class="col-sm-7">
                    <div class="header-content">
                        <div class="header-content-inner">
                            
                            <?php if (isset($mapsApiUrl)): ?>
                            <img class="img-responsive" id="mapImage" src="<?php echo $mapsApiUrl; ?>" />  
                            <?php else: ?>
                            No GPS data available.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="device-container">
                        <div class="device-mockup iphone6_plus portrait white">
                            <div class="device">
                                <div class="screen">
                                    <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                                    <h2>Track your deliveries on UPM Misty</h2>
                                </div>
                                <div class="text">
                                    Total <strong>5 deliveries.</strong>
                                    <br />
                                    <strong>100 %</strong> of deliveries on time.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    </section>
    <section id="contact" class="contact bg-primary">
        <div class="container">
            <h2>Follow UPM on social media!</h2>
            <ul class="list-inline list-social">
                <li class="col-sm-4 social-twitter">
                    <a href="#"><i class="fa fa-3x fa-twitter"></i></a>
                </li>
                <li class="col-sm-4 social-facebook">
                    <a href="#"><i class="fa fa-3x fa-facebook"></i></a>
                </li>
                <li class="col-sm-4 social-google-plus">
                    <a href="#"><i class="fa fa-3x fa-google-plus"></i></a>
                </li>
            </ul>
        </div>
    </section>

    <footer>
        <div class="container">
            <p><img src="powered-by-google-on-white.png" /> <br /></p>
            <ul class="list-inline">
                <li>
                    <a href="#">Privacy</a>
                </li>
                <li>
                    <a href="#">Terms</a>
                </li>
                <li>
                    <a href="#">FAQ</a>
                </li>
            </ul>
        </div>
    </footer>
  </body>
</html>
