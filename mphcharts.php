<?php
//error_reporting(E_ERROR);
//ini_set('display_errors', 1);

/*
 * Copyright (C) 2017  M. Kubik (emx0r)
 * This script is a free software, feel free
 * to modify/share it
 */


$api_key = $_GET['api_key'];
$coin    = $_GET['coin'];

if ($api_key == null) {
	die('Error:  enter API key and (optionally) coin: ' . ($_SERVER['HTTP_HOST']) . ($_SERVER['PHP_SELF']).'&api_key=YOUR_API_KEY&coin=YOUR_COIN/BITCOIN'); 
}

if ($coin == null) {
	$coin = 'BITCOIN';
}

function mph_get($url)
{
	$cn = curl_init($url);
        curl_setopt($cn, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($cn);
	curl_close($cn);
	return json_decode($output, true);
}
$result = mph_get("https://"."$coin".".miningpoolhub.com/index.php?page=api&action=getdashboarddata&api_key="."$api_key");

if (sizeof($result) == 0) {
 die("Error:  I could not fetch the data");
}

$balArray = array_reverse($result['getdashboarddata']['data']['recent_credits']);
?>


 <html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
 	['DATE', '<?php echo "$coin" ?>'],
	
	<?php
	foreach($balArray as $item) {
            
        echo "['";
        echo ($item['date']);
        echo "', ";
        echo ($item['amount']);
        echo "], ";
        
        }
 
	?>        
	]);

          var options = {
          title: '<?php echo "$coin"; ?> Balance',
          curveType: 'function',
          legend: { position: 'none' },
	  crosshair: { trigger: 'both' },
	  vAxis: {
  		viewWindow: {
     			 min: 0
  	        }
	}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>
