<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
        }

        h1,
        h2 {
            color: #333;
            margin: 0;
            padding: 10px;
        }

        p {
            font-size: 1.2em;
            color: #555;
            margin: 10px 0;
        }

        .weather-info {
            margin: 20px 0;
        }

        .weather-icon {
            width: 80px;
            height: 80px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-size: 1em;
        }

        a:hover {
            text-decoration: underline;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            width: 250px;
            max-width: 100%;
        }

        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Weather Information</h1>
    <form id="weatherForm">
        <input type="text" id="city" name="city" placeholder="Enter city" required>
        <button type="submit">Get Weather</button>
    </form>
    <div id="weatherResult"></div>

    <script>
        document.getElementById('weatherForm').addEventListener('submit', function (event)
        {
            event.preventDefault(); // Prevent the form from submitting the traditional way

            var city = document.getElementById('city').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'weather.php?city=' + encodeURIComponent(city), true);
            xhr.onload = function ()
            {
                if (xhr.status === 200) {
                    document.getElementById('weatherResult').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('weatherResult').innerHTML = '<p>Unable to fetch weather data. Please try again later.</p>';
                }
            };
            xhr.send();
        });
    </script>
    <?php
    if (isset($_GET['city'])) {
        $city = htmlspecialchars($_GET['city']);
        $apiKey = '9d7208e2e850eda8f60d01bda3cf7406'; // Your provided API key
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey . "&units=metric";

        $weatherData = @file_get_contents($apiUrl);

        if ($weatherData === FALSE) {
            echo "<p>Unable to fetch weather data. Please try again later.</p>";
        } else {
            $weatherArray = json_decode($weatherData, true);
            if (isset($weatherArray['cod']) && $weatherArray['cod'] === 200) {
                $temperature = $weatherArray['main']['temp'];
                $humidity = $weatherArray['main']['humidity'];
                $windSpeed = $weatherArray['wind']['speed'];
                $weatherDescription = ucfirst($weatherArray['weather'][0]['description']);
                $icon = $weatherArray['weather'][0]['icon'];
                $iconUrl = "http://openweathermap.org/img/wn/" . $icon . "@2x.png";

                echo "<h2>Weather for <span style='color:royalblue;'>" . ucfirst($city) . "</span></h2>";
                echo "<div class='weather-info'>";
                echo "<p><img class='weather-icon' src='" . $iconUrl . "' alt='Weather Icon'></p>";
                echo "<p><span style='color:crimson;'>Temperature:</span> " . $temperature . "°C</p>";
                echo "<p><span style='color:crimson;'>Condition:</span> " . $weatherDescription . "</p>";
                echo "<p><span style='color:crimson;'>Humidity:</span> " . $humidity . "%</p>";
                echo "<p><span style='color:crimson;'>Wind Speed:</span> " . $windSpeed . " m/s</p>";
                echo "</div>";
            } else {
                echo "<p>City not found or invalid response from the API.</p>";
            }
        }
    } else {
        echo "<p>No city entered. Please enter a city name and submit the form.</p>";
    }
    ?>
</body>

</html>
