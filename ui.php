<?php
echo "<strong style='display:none;'>" . htmlspecialchars($_GET["site"]) . "'</strong>";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RR Analytics!</title>
    
    <style>
    /* CSS files add styling rules to your content */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;600;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;700&display=swap');

body {
  font-family: "Inter", "Helvetica", "Arial", sans-serif;
  margin: 15vw;
}

h1 {
  font-family: "Roboto Mono", "IBM Plex Mono", "Consolas", monospace;
  padding-bottom: 40px;
  border-bottom: 2px solid lightgray;
}

.cards {
  background-color: lightgray;
  border-radius: 5px;
  padding: 10px 30px;
}

.counts {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(1, 1fr);
  grid-column-gap: 30px;
  grid-row-gap: 30px; 
  word-wrap: break-word;
  height: 200px;
}

@media only screen and (max-width: 600px) {
  .counts {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    grid-template-rows: repeat(1, 1fr);
    grid-column-gap: 30px;
    grid-row-gap: 30px; 
    word-wrap: break-word;
  }
}

.cards > h2 {
  font-size: 4em;
  margin-bottom: 0px;
}

.cards > p {
  font-family: "Roboto Mono", "IBM Plex Mono", "Consolas", monospace;
}
    </style>
  </head>  
  <body>
    <h1 id="website-url">
      hola
    </h1>
    
    <div class="analytics">
      <div class="counts">
        <div class="total-hits cards">
          <p>
            Total Hits
          </p>
          <h2 id="total-hits"></h2>
        </div>
        <div class="unique-hits cards">
          <p>
            Unique Hits
          </p>
          <h2 id="unique-hits"></h2>
        </div>
      </div>
      <p id="time-spent">
        
      </p>
    </div>
    <h1>
      Top 5 Locations
    </h1>
    <p id="start">
      
    </p>
    <script>
let baseUrl = "https://riverside.rocks/apis/data.php?website=";

getElementsByTagName("STRONG")[0].innerText = website;

fetch(`${baseUrl}${website}`)
  .then(res => res.json())
  .then(data => {
    document.getElementById("total-hits").innerText = data.total_hits;
    document.getElementById("unique-hits").innerText = data.unique_hits;

    let { seconds, minutes, hours, days } = data.time_spent;
    let time_string = "";

    if (Math.trunc(hours) > 1) {
      time_string += Math.trunc(hours) + " hours total engagement time";
    } else if (Math.trunc(hours) === 1) {
      time_string += Math.trunc(hours) + " hour total engagement time";
    }

    document.getElementById("time-spent").innerText = time_string;

    var countries = data.verbose.countries;
    var ammounts = data.verbose.hits;
  
    var c
    for (c = 0; c < 5; c++) { 
      var p = document.createElement("P");
      document.body.appendChild(p);
      p.innerHTML = "<img src='https://poptox-3744.kxcdn.com/assets/images/flags/32/"+countries[c].toLowerCase()+".png' />    "+ammounts[c]
      console.log(countries[c]+" ---> "+ammounts[c])
      
    }

    //https://poptox-3744.kxcdn.com/assets/images/flags/32/in.png
  });

    </script>
  </body>
</html>
