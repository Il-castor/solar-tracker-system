<?php
  //require_once("db_conn.php");
    //connessione al db
    $conn = new mysqli("localhost", "castor1", "") or die("Errore di connessione ");
    mysqli_select_db($conn, "my_castor1") or die ("Errore selezione db");
    
    //esecuzione delle query
    $sql = "SELECT misurazioni.data,misurazioni.potenza FROM misurazioni";
    $result = mysqli_query($conn, $sql);   

    $sql2 = "SELECT misurazioni.potenza FROM misurazioni 
    WHERE misurazioni.data = CURDATE()";
    $potenza_oggi = mysqli_query($conn, $sql2);
	$sql3 ="SELECT * FROM misurazioni WHERE data >= (CURDATE() - INTERVAL 7 DAY)
AND data <=CURDATE();" //query per sapere i dati tra oggi e una settimana fa
    
    
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produzione</title>
    <meta name="description" content="Pagina per mostrare la produzione "/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;amp;lang=it" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css" rel="stylesheet">
    <link href="../styles/main.css" rel="stylesheet">
    <link rel="icon" href="../img/favicon1.ico" />

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['data', 'potenza'],
          
          <?php
            if(mysqli_num_rows($result) > 0){
              while($row = mysqli_fetch_array($result)){
                echo "['".$row['data']."', ".$row['potenza']."],";
              }
            }
          
          ?>
          
        ]);

        var options = { //oggetto js
          chart: {
            format: 'decimal',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
       } 
    </script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          //['Potenza', 40],
          ['Potenza', <?php
            if (mysqli_num_rows($potenza_oggi) == 0)
              echo 0;
            else if (mysqli_num_rows($potenza_oggi) > 0){
              $potenza = array();
              while ($r = mysqli_fetch_array($potenza_oggi)){
                  $potenza[] = $r['potenza'];
              }
              $media_pot = array_sum($potenza) / count($potenza); //media di un array
              round($media_pot, 0);
              echo "$media_pot";
            }

        ?>]
        
        ]);

        var options = {
          width: 200, height: 120,
          redFrom: 9, redTo: 10,
          yellowFrom: 7, yellowTo: 9,
          minorTicks: 2,
          max: 10
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          //['Potenza', 40],
          ['Temperatura', <?php
            $sql3 = "SELECT misurazioni.temperatura FROM misurazioni WHERE misurazioni.data = CURDATE()";
            $temperatura = mysqli_query($conn, $sql3);
           if (mysqli_num_rows($temperatura) == 0)
              echo 0;
            else if (mysqli_num_rows($temperatura) > 0){
              $temp = array();
              while ($r = mysqli_fetch_array($temperatura)){
                  $temp[] = $r['temperatura'];
              }
              $media_temp = array_sum($temp) / count($temp); //media di un array
              round($media_temp, 0);
              echo $media_temp;
            }

        ?>]
        
        ]);

        var options = {
          width: 200, height: 120,
          redFrom: 40, redTo: 50,
          yellowFrom: 30, yellowTo: 40,
          minorTicks: 5,
          max: 50
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div1'));

        chart.draw(data, options);

        
      }
    </script>
    


  </head>
  <body id="top">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header"><a href="download.php" id="contact-button" class="mdl-button mdl-button--fab mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast mdl-shadow--4dp"><i class="material-icons">
cloud_download</i></a>
      <header class="mdl-layout__header mdl-layout__header--waterfall site-header">
        <div class="mdl-layout__header-row site-logo-row"><span class="mdl-layout__title">
            <div class="site-logo"></div><span class="site-description">Solar Tracker System</span></span></div>
        <div class="mdl-layout__header-row site-navigation-row mdl-layout--large-screen-only">
          <nav class="mdl-navigation mdl-typography--body-1-force-preferred-font"><a class="mdl-navigation__link" href="../index.html">Home</a><a class="mdl-navigation__link" href="produzione.php">Produzione</a><!--<a class="mdl-navigation__link" href="about.html">About</a>--><a class="mdl-navigation__link" href="../html/contact.html">Contatti</a>
          </nav>
        </div>
      </header>
      <div class="mdl-layout__drawer mdl-layout--small-screen-only">
        <nav class="mdl-navigation mdl-typography--body-1-force-preferred-font"><a class="mdl-navigation__link" href="../index.html">Home</a><a class="mdl-navigation__link" href="produzione.php">Produzione</a><!--<a class="mdl-navigation__link" href="about.html">About</a>--><a class="mdl-navigation__link" href="../html/contact.html">Contatti</a>
        </nav>
      </div>
      <main class="mdl-layout__content">
        <div class="site-content">
          <div class="container"><div class="mdl-grid site-max-width">
    <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card" id="grafico-tensione">
        
        <div class="mdl-card__title" id="titolo-grafico">
            <h2 class="mdl-card__title-text">Grafico Potenza</h2>
        </div>
        <div id="columnchart_material" style="width: 500px; height: 300px; padding-left:1%;"></div>

        <div class="mdl-card__supporting-text" style="text-align: center;">
            Potenza prodotta nell'ultima settimana.
        </div>

    </div>
    <!--<div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card">
        <div class="mdl-card__media">
            <img class="article-image" src="img/portfolio2.jpg" border="0" alt="">
        </div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Night Shadow</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Enim labore aliqua consequat ut quis ad occaecat aliquip incididunt. Sunt nulla eu enim irure enim nostrud aliqua consectetur ad consectetur sunt..
        </div><br>        <div class="mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="portfolio-page.html">
            Learn More
          </a>
        </div>
    </div>
    <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card">
        <div class="mdl-card__media">
            <img class="article-image" src="img/portfolio3.jpg" border="0" alt="">
        </div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Sky Reach</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Enim labore aliqua consequat ut quis ad occaecat aliquip incididunt. Sunt nulla eu enim irure enim nostrud aliqua consectetur ad consectetur sunt..
        </div><br>        <div class="mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="portfolio-page.html">
            Learn More
          </a>
        </div>
    </div><br>  -->  
    <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card" id="grafico-tensione">
        
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text" style="margin: auto;">Produzione attuale</h2>
        </div>
          <div id="chart_div" style="width: 400px; height: 120px; padding-left: 30%;"></div>
        <div class="mdl-card__supporting-text" style="text-align: center;">
            Grafico che mostra la potenza attuale.
        </div>

        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text" style="margin: auto;">Temperatura  attuale</h2>
        </div>
          <div id="chart_div1" style="width: 400px; height: 120px; padding-left: 30%;"></div>
        <div class="mdl-card__supporting-text" style="text-align: center;">
            Grafico che mostra la temperatura attuale.
        </div>




    </div> 
      <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card" id="grafico-tensione">
        
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text" style="margin: auto;"><img src="../img/emission.svg">Emissioni evitate</h2>
        </div>
        <div class="mdl-card__supporting-text">
            <?php
              require_once("valori.php");
            ?>
        </div>
    </div><!--  <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card">
        <div class="mdl-card__media">
            <img class="article-image" src="img/portfolio6.jpg" border="0" alt="">
        </div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Water Flow</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Enim labore aliqua consequat ut quis ad occaecat aliquip incididunt. Sunt nulla eu enim irure enim nostrud aliqua consectetur ad consectetur sunt..
        </div><br>        <div class="mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="portfolio-page.html">
            Learn More
          </a>
        </div>
    </div><br>    <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card">
        <div class="mdl-card__media">
            <img class="article-image" src="img/portfolio7.jpg" border="0" alt="">
        </div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Lonely Bridge</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Enim labore aliqua consequat ut quis ad occaecat aliquip incididunt. Sunt nulla eu enim irure enim nostrud aliqua consectetur ad consectetur sunt..
        </div><br>        <div class="mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="portfolio-page.html">
            Learn More
          </a>
        </div>
    </div><br>    <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card">
        <div class="mdl-card__media">
            <img class="article-image" src="img/portfolio8.jpg" border="0" alt="">
        </div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Busy Street</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Enim labore aliqua consequat ut quis ad occaecat aliquip incididunt. Sunt nulla eu enim irure enim nostrud aliqua consectetur ad consectetur sunt..
        </div><br>        <div class="mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--accent" href="portfolio-page.html">
            Learn More
          </a>
        </div>
    </div><br>-->  
      <div class="mdl-cell mdl-card mdl-shadow--4dp portfolio-card" id="grafico-tensione">
        
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text" style="margin: auto;" >Meteo</h2>
        </div>
        <div class="mdl-card__supporting-text">
            <a class="weatherwidget-io" href="https://forecast7.com/it/44d7010d63/reggio-emilia/" data-label_1="REGGIO EMILIA" data-days="3" data-theme="original" id="meteo" style="cursor: default;">REGGIO EMILIA</a>
        </div><br>        
    </div>
</div></div>
        </div>
        <footer class="mdl-mini-footer">
          <div class="footer-container">
            <div class="mdl-logo">&copy; Castorini Francesco </div>
            
          </div>
        </footer>
      </main>
      <script src="https://code.getmdl.io/1.3.0/material.min.js" defer></script>
      <script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>
<script type="text/javascript">document.getElementById("meteo").disabled=true;</script>
    </div>
  </body>
</html>