<?php
// unCool Weather App 
// Built in HTML/CSS/PHP and Javascript - BootStrap, Dark Sky API, Font Awesome, Vue.js
// Brandon Ubben 2018
//
require('keys.php');

function get_forecast($string, $key) {
	$location = preg_replace('/[^a-zA-Z0-9_ -]/s','', $string);
	$location = str_replace(' ', '+', $string);
	
	$location_info = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$location"));
	
	$lat = $location_info->results[0]->geometry->location->lat;
	$lng = $location_info->results[0]->geometry->location->lng;
	
	if ($lat == '' || $lng == '') return false;
	else return $forecast = json_decode(file_get_contents("https://api.darksky.net/forecast/$key/$lat,$lng?exclude=minutely,hourly,alerts,flags"));
}

$loading = 'true';
$submitted = 'false';
$error = 'false';
$current_icon = $cool = $daily_forecast_icon_1 = $daily_forecast_icon_2 = $daily_forecast_icon_3 = $daily_forecast_icon_4 = "''";
$current_temp = 0;

if (count($_POST)) {
	$submitted = 'true';
	$location = $_POST["location"];
	$forecast = get_forecast($location, $keys["dark_sky"]);
	$current_forecast = $daily_forecast = $daily_forecast_icons = [];
	$first = true;
	
	if ($forecast === false) $error = 'true';
	else {
			$current_temp = $forecast->currently->temperature;
			$current_forecast = [
				'temperature' => round($forecast->currently->temperature) . '&deg;',
				'humidity' => $forecast->currently->humidity * 100 . '&percnt;',
				'summary' => $forecast->currently->summary,
				'icon' => $forecast->currently->icon,
				'time' => date('l', $forecast->currently->time)
			];
			foreach ($forecast->daily->data as $day) {
				if ($first === false) {
					$daily_forecast[] = [
						'temperature_high' => round($day->temperatureHigh) . '&deg;',
						'temperature_low' => round($day->temperatureLow) . '&deg;',
						'humidity' => $day->humidity * 100 . '&percnt;',
						'icon' => $day->icon,
						'time' => date('l', $day->time)
					];
				} else {
					$current_forecast["temperature_high"] = round($day->temperatureHigh) . '&deg;';
					$current_forecast["temperature_low"] = round($day->temperatureLow) . '&deg;';
				}
				
				if ($first === true) $first = false;
			}
	}
	
	$current_icon = "'" . $current_forecast["icon"] . "'";
	
	if ($current_temp <= 76 && $current_temp > 0) $cool = "It's actually cool outside. Enjoy it.";
	elseif ($current_temp > 76 && $current_temp < 81) $cool ="It's almost cool outside. Better luck next time.";
	else $cool = "It's not cool outside. Sorry.";
	
	$loading = 'false';
}

?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=0">
		<meta name="keywords" content="Vue.js, Bootstrap, Dark Sky API, weather, weather web application, ubben.co">
		<meta name="description" content="A simple weather web application built to be as minimal as possible to demonstrate some of the abilities of its creator, Brandon Ubben.">
		
		<title>unCool Weather | Is it cool outside?</title>
		
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
		<link rel="icon" type="image/x-icon" href="https://images.ubben.co/ubben_favicon.png">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
		<style>
			body {
				background-color: #00b7ff;
				background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='572' height='476.7' viewBox='0 0 1080 900'%3E%3Cg fill-opacity='0.19'%3E%3Cpolygon fill='%23444' points='90 150 0 300 180 300'/%3E%3Cpolygon points='90 150 180 0 0 0'/%3E%3Cpolygon fill='%23AAA' points='270 150 360 0 180 0'/%3E%3Cpolygon fill='%23DDD' points='450 150 360 300 540 300'/%3E%3Cpolygon fill='%23999' points='450 150 540 0 360 0'/%3E%3Cpolygon points='630 150 540 300 720 300'/%3E%3Cpolygon fill='%23DDD' points='630 150 720 0 540 0'/%3E%3Cpolygon fill='%23444' points='810 150 720 300 900 300'/%3E%3Cpolygon fill='%23FFF' points='810 150 900 0 720 0'/%3E%3Cpolygon fill='%23DDD' points='990 150 900 300 1080 300'/%3E%3Cpolygon fill='%23444' points='990 150 1080 0 900 0'/%3E%3Cpolygon fill='%23DDD' points='90 450 0 600 180 600'/%3E%3Cpolygon points='90 450 180 300 0 300'/%3E%3Cpolygon fill='%23666' points='270 450 180 600 360 600'/%3E%3Cpolygon fill='%23AAA' points='270 450 360 300 180 300'/%3E%3Cpolygon fill='%23DDD' points='450 450 360 600 540 600'/%3E%3Cpolygon fill='%23999' points='450 450 540 300 360 300'/%3E%3Cpolygon fill='%23999' points='630 450 540 600 720 600'/%3E%3Cpolygon fill='%23FFF' points='630 450 720 300 540 300'/%3E%3Cpolygon points='810 450 720 600 900 600'/%3E%3Cpolygon fill='%23DDD' points='810 450 900 300 720 300'/%3E%3Cpolygon fill='%23AAA' points='990 450 900 600 1080 600'/%3E%3Cpolygon fill='%23444' points='990 450 1080 300 900 300'/%3E%3Cpolygon fill='%23222' points='90 750 0 900 180 900'/%3E%3Cpolygon points='270 750 180 900 360 900'/%3E%3Cpolygon fill='%23DDD' points='270 750 360 600 180 600'/%3E%3Cpolygon points='450 750 540 600 360 600'/%3E%3Cpolygon points='630 750 540 900 720 900'/%3E%3Cpolygon fill='%23444' points='630 750 720 600 540 600'/%3E%3Cpolygon fill='%23AAA' points='810 750 720 900 900 900'/%3E%3Cpolygon fill='%23666' points='810 750 900 600 720 600'/%3E%3Cpolygon fill='%23999' points='990 750 900 900 1080 900'/%3E%3Cpolygon fill='%23999' points='180 0 90 150 270 150'/%3E%3Cpolygon fill='%23444' points='360 0 270 150 450 150'/%3E%3Cpolygon fill='%23FFF' points='540 0 450 150 630 150'/%3E%3Cpolygon points='900 0 810 150 990 150'/%3E%3Cpolygon fill='%23222' points='0 300 -90 450 90 450'/%3E%3Cpolygon fill='%23FFF' points='0 300 90 150 -90 150'/%3E%3Cpolygon fill='%23FFF' points='180 300 90 450 270 450'/%3E%3Cpolygon fill='%23666' points='180 300 270 150 90 150'/%3E%3Cpolygon fill='%23222' points='360 300 270 450 450 450'/%3E%3Cpolygon fill='%23FFF' points='360 300 450 150 270 150'/%3E%3Cpolygon fill='%23444' points='540 300 450 450 630 450'/%3E%3Cpolygon fill='%23222' points='540 300 630 150 450 150'/%3E%3Cpolygon fill='%23AAA' points='720 300 630 450 810 450'/%3E%3Cpolygon fill='%23666' points='720 300 810 150 630 150'/%3E%3Cpolygon fill='%23FFF' points='900 300 810 450 990 450'/%3E%3Cpolygon fill='%23999' points='900 300 990 150 810 150'/%3E%3Cpolygon points='0 600 -90 750 90 750'/%3E%3Cpolygon fill='%23666' points='0 600 90 450 -90 450'/%3E%3Cpolygon fill='%23AAA' points='180 600 90 750 270 750'/%3E%3Cpolygon fill='%23444' points='180 600 270 450 90 450'/%3E%3Cpolygon fill='%23444' points='360 600 270 750 450 750'/%3E%3Cpolygon fill='%23999' points='360 600 450 450 270 450'/%3E%3Cpolygon fill='%23666' points='540 600 630 450 450 450'/%3E%3Cpolygon fill='%23222' points='720 600 630 750 810 750'/%3E%3Cpolygon fill='%23FFF' points='900 600 810 750 990 750'/%3E%3Cpolygon fill='%23222' points='900 600 990 450 810 450'/%3E%3Cpolygon fill='%23DDD' points='0 900 90 750 -90 750'/%3E%3Cpolygon fill='%23444' points='180 900 270 750 90 750'/%3E%3Cpolygon fill='%23FFF' points='360 900 450 750 270 750'/%3E%3Cpolygon fill='%23AAA' points='540 900 630 750 450 750'/%3E%3Cpolygon fill='%23FFF' points='720 900 810 750 630 750'/%3E%3Cpolygon fill='%23222' points='900 900 990 750 810 750'/%3E%3Cpolygon fill='%23222' points='1080 300 990 450 1170 450'/%3E%3Cpolygon fill='%23FFF' points='1080 300 1170 150 990 150'/%3E%3Cpolygon points='1080 600 990 750 1170 750'/%3E%3Cpolygon fill='%23666' points='1080 600 1170 450 990 450'/%3E%3Cpolygon fill='%23DDD' points='1080 900 1170 750 990 750'/%3E%3C/g%3E%3C/svg%3E");
				background-attachment: fixed;
				font-family: 'Fira Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
				margin: 0 auto;
				padding: 0;
				width: 100%;
			}
			
			footer {
				animation: appear 100ms linear;
				background: rgba(0, 0, 0, 0.4);
			}
			
			.forecast-container {
				display: grid;
					grid-gap: 0.5em;
					grid-template-columns: 0.75fr 1.5fr;
					grid-auto-rows: 100%;
			}
			
			.weekly-forecast {
				display: grid;
					grid-gap: 0.5em;
					grid-template-columns: 1fr 1fr 1fr 1fr;
					grid-auto-rows: 50%;
					justify-content: center;
			}
			
			@media only screen and (max-width: 960px) {
				.forecast-container {
					grid-template-columns: 1fr;
				}
				
				.weekly-forecast {
					grid-template-columns: 1fr 1fr 1fr;
					grid-auto-rows: 25%;
				}
				
				#info {
					grid-column: span 2;
				}
			}
			
			@media only screen and (max-width: 640px) {
				.forecast-container {
					grid-template-columns: 1fr;
				}
				
				.weekly-forecast {
					grid-template-columns: 1fr 1fr;
				}
				
				#info {
					grid-column: span 1;
				}
			}
			
			@media only screen and (max-width: 320px) {
				.weekly-forecast {
					grid-template-columns: 1fr;
				}
			}
			
			#app {
				animation: appear 1150ms linear;
				background: rgba(0, 0, 0, 0.4);
				min-width: 80%;
				width: 100%;
				z-index: 0;
			}
			
			#app nav a,
			#app nav a:visited {
				color: inherit;
				text-decoration: none;
			}
			
			@keyframes appear {
				0% { opacity: 0.1; }
				25% { opacity: 0.25; }
				50% { opacity: 0.5; }
				75% { opacity: 0.75; }
				100% { opacity: 1; }
			}
		</style>
	</head>
	
	<body>
		<div id="app" class="container-fluid m-0 p-0 text-white">
			<nav class="bg-transparent navbar navbar-expand-xl navbar-dark text-white">
				<span class="font-weight-bold navbar-brand" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);"><i class="fas fa-cloud"></i> unCool weather</span>
			</nav>
			
			<template v-if="submitted">
				<template v-if="loading">
					<main class="align-items-center d-flex justify-content-center text-white" style="min-height: 90%;">
						<i class="fas fa-spin fa-spinner fa-3x"></i> 
					</main>
				</template>
				
				<template v-else>
					<template v-if="error">
						<main class="align-items-center d-flex flex-column justify-content-center" style="min-height: 90%;">
							<h2 class="p-2 text-center">There was an error processing your request! Please try again later</h2>
						</main>
					</template>
					
					<template v-else>
						<main class="container-fluid">
							<div class="forecast-container p-1 text-dark">
								<section class="card shadow text-center">
									<div class="card-body">
										<ul class="list-group list-group-flush">
											<li class="list-group-item">
												<i v-if="currentIcon === 'clear-day' || currentIcon === 'partly-cloudy-day'" class="fas fa-sun fa-7x">
												</i>
												<i v-if="currentIcon === 'clear-night' || currentIcon === 'partly-cloudy-night'" class="fas fa-moon fa-7x"></i>
												<i v-if="currentIcon === 'rain'" class="fas fa-tint fa-7x"></i>
												<i v-if="currentIcon === 'snow' || currentIcon === 'sleet'" class="fas fa-snowflake fa-7x"></i>
												<i v-if="currentIcon === 'wind'" class="fas fa-leaf fa-7x"></i>
												<i v-if="currentIcon === 'cloudy'" class="fas fa-cloud fa-7x"></i>
												<i v-if="icons.indexOf(currentIcon) === -1" class="fas fa-question fa-7x"></i>
											</li>
											<li class="list-group-item">
												Temperature: <?php echo $current_forecast["temperature"]; ?>
											</li>
											<li class="list-group-item">
												Humidity: <?php echo $current_forecast["humidity"]; ?>
											</li>
											<li class="list-group-item">
												<?php echo $cool; ?>
											</li>
											<li class="list-group-item">
												<?php echo $current_forecast["temperature_high"]; ?>/<?php echo $current_forecast["temperature_low"]; ?>
											</li>
											<li class="list-group-item px-0 pt-4">
												<a href="/">
													<button class="btn btn-warning container-fluid">
														Search Again
													</button>
												</a>
											</li>
										</ul>
									</div>
								</section>
								
								<section class="weekly-forecast">
								<?php foreach ($daily_forecast as $this_day) { ?>
									<div class="card shadow text-center">
										<div class="card-body small">
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
												<?php echo $this_day["time"]; ?>
												</li>
												<li class="list-group-item">
												<?php if ($this_day["icon"] == 'clear-day') { ?>
													<i class="fas fa-sun fa-5x"></i>
												<?php } elseif ($this_day["icon"] == 'clear-night') { ?>
													<i class="fas fa-moon fa-5x"></i>
												<?php } elseif ($this_day["icon"] == 'rain') { ?>
													<i class="fas fa-tint fa-5x"></i>
												<?php } elseif ($this_day["icon"] == 'snow' || $this_day["icon"] == 'sleet') { ?>
													<i class="fas fa-snowflake fa-5x"></i>
												<?php } elseif ($this_day["icon"] == 'wind') { ?> 
													<i class="fas fa-leaf fa-5x"></i>
												<?php } elseif ($this_day["icon"] == 'partly-cloudy-day' || $this_day["icon"] == 'partly-cloudy-night' || $this_day["icon"] == 'cloudy') { ?>
													<i class="fas fa-cloud fa-5x"></i>
												<?php } else { ?>
													<i class="fas fa-question fa-5x"></i>
												<?php } ?>
												</li>
												<li class="bg-transparent list-group-item">
													Temperature: <?php echo $this_day["temperature_high"]; ?>/<?php echo $this_day["temperature_low"]; ?>
												</li>
												<li class="bg-transparent list-group-item">
													Humidity: <?php echo $this_day["humidity"]; ?>
												</li>
											</ul>
										</div>
									</div>
								<?php } ?>
									<div class="card shadow text-center" id="info">
										<div class="card-body">
											<ul class="list-group list-group-flush">
												<li class="list-group-item">
													<i class="fas fa-info fa-5x"></i>
												</li>
												<li class="list-group-item small">
													Forecast data <br>
													<a href="https://darksky.net/poweredby/" target="_blank" rel="nofollow noreferrer">Powered by Dark Sky</a>
												</li>
												<li class="list-group-item small">
													Background <br>
													<a href="https://www.svgbackgrounds.com" target="_blank" rel="nofollow noreferrer">SVG Backgrounds</a>
												</li>
											</ul>
										</div>
									</div>
								</section>
							</div>
						</main>
					</template>
				</template>
			</template>
			
			<template v-else>
				<main class="align-items-center d-flex flex-column justify-content-center" style="min-height: 90%;">
					<div class="p-2 text-center">
						<h1 class="display-3">Is it cool outside?</h1>
						<p>Please enter your zip code or city (e.g. Los Angeles, CA) to find out!</p>
					</div>
					<form name="search" method="post">
						<div class="form-group bg-transparent">
							<input type="text" class="form-control shadow" name="location" v-model="location" style="background: rgba(255, 255, 255, 0.9); min-width: 300px;">
						</div>
						
						<button type="submit" class="btn btn-warning shadow w-100" v-bind:disabled="location === ''" v-on:click="submitted = true" v-on:touch="submitted = true"> 
							<i class="fas fa-search"></i>
							Search
						</button>
					</form>
				</main>
			</template>
		</div>
		
		<footer class="p-4 pt-5 small text-center text-white">
			<p>
				Built using Bootstrap, Dark Sky API, Font Awesome, PHP and Vue
				<br>
				Check out the <a href="https://github.com/b-ubben/uncool_weather" target="_blank" rel="nofollow noreferrer" class="text-warning">GitHub repo</a> for this project
				<br>
				Brandon Ubben &copy; 2018
				<br>
				<a href="https://ubben.co" target="_blank" rel="nofollow noreferrer" class="text-warning">ubben.co</a>
			</p>
		</footer>
		
		<script>
			var app = new Vue({
				el: '#app',
				data: {
					currentIcon: <?php echo $current_icon; ?>,
					error: <?php echo $error; ?>,
					loading: <?php echo $loading; ?>,
					location: '',
					icons: ['clear-day', 'clear-night', 'rain', 'snow', 'sleet', 'wind', 'cloudy', 'partly-cloudy-day', 'partly-cloudy-night'],
					submitted: <?php echo $submitted; ?>
				}
			});
		</script>
	</body>
</html>