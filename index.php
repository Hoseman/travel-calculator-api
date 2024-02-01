<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapQuest Distance Calculator <i class="fas fa-exchange-alt"></i></title>
    <link rel='stylesheet' id='fa-css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' type='text/css' media='all' />
    <link href="css/styles.css" rel="stylesheet">
    
</head>
<body>

<form class="form" method="post" action="">
    <h1 class="form__heading">Get Instant Quote &amp; Book Now</h1>
    <p class="form__intro">This demo uses the Map Quest API. Additionally is uses postcodes to calculate the distance between start and end journey points. It calculates the distance by road and calculates a price based on mileage and a price based on journey time. Each price is also based on vehicle type.</p>
    <p class="form__intro">You can use any UK based postcode.</p>
    <p class="form__intro">Example postcodes to use for the demo:  <span class="form__postcode">MK2 3DG (Bletchley)</span>  <span class="form__postcode">E1 8RU (Central London)</span>  <span class="form__postcode">MK44 3WJ (Bedford)</span></p>

    <div class="form__column">

    <span class="form__exchange-icon"><i class="fas fa-exchange-alt"></i></span>

    <div class="form__col">
        <label class="form__label" for="startPoint"><i class="fas fa-map-marker-alt"></i> Travelling From <span class="form__validation">*</span></label>
        <input class="form__input" type="text" name="startPoint" placeholder="Add Postcode Here" required>
    </div>
    
    <div class="form__col">
        <label class="form__label" for="endPoint"><i class="fas fa-map-marker-alt"></i> Travelling To <span class="form__validation">*</span></label>
        <input class="form__input" type="text" name="endPoint" placeholder="Add Postcode Here" required>
    </div>


    </div>


    <label class="form__label"><i class="fas fa-shuttle-van"></i> Vehicle Type <span class="form__validation">*</span></label>
    <select class="form__select" name="vehicle_rate">
        <option>-- SELECT --</option>
        <option value="1.5">Vehicle A</option>
        <option value="2.5">Vehicle B</option>
    </select>
    <br>
    <button class="form__button" type="submit">Get Distance</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = '62p90WGqktOzplmVtY1MzeYQRNrGmcWm'; // Replace with your MapQuest API key

    $start = $_POST['startPoint'];
    $end = $_POST['endPoint'];

    $endPoint = str_replace(' ', '', $end);
    $startPoint = str_replace(' ', '', $start);

    $vehicle_rate = $_POST['vehicle_rate'];

    $apiUrl = "https://www.mapquestapi.com/directions/v2/route?key=$apiKey&from=$startPoint&to=$endPoint&unit=m";

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data['route']) {
        $distance = $data['route']['distance'];
        $formattedTime = $data['route']['formattedTime'];
        $rate = $distance * $vehicle_rate;

        // Convert formatted time to minutes
        list($hours, $minutes) = explode(':', $formattedTime);
        $totalMinutes = ($hours * 60) + $minutes;



        if($vehicle_rate == "1.5"){
            $minutes_rate = 40 / 60;
            $hourly_rate = $minutes_rate * $totalMinutes;
        } else {
            $minutes_rate = 55 / 60;
            $hourly_rate = $minutes_rate * $totalMinutes;
        }   

        //vehicle A mileage rate = 1.50
        //vehicle A hourly rate = 40
        //vehicle B mileage rate = 2.50
        //vehicle B hourly rate = 55

        if($vehicle_rate == "1.5"){ 
            $vehicle_type = "Small Coach 0-35 passengers"; 
        } else { 
            $vehicle_type = "Big Coach 36-53 passengers"; 
        }

        // Display the result on the page
        echo "<div class='result'>";
        echo "<h3 class='results__title'>Thankyou for your submission</h3>";
        echo "<p>You are travelling from: $start</p>";
        echo "<p>You are travelling to: $end</p>";
        echo "<p>Distance: $distance miles</p>";
        echo "<p>Estimated Travel Time: $formattedTime</p>";
        echo "<p>Vehicle: $vehicle_type</p>";
        echo "<p>Mileage Rate: £$rate</p>";
        echo "<p>Hourly Rate: £$hourly_rate</p>";
        echo "</div>";
    } else {
        echo "<div class='result'>";
        echo "<p>Unable to retrieve distance. Please check your input and try again.</p>";
        echo "</div>";
    }
}
?>

</body>
</html>
