<html>
<head>
	<link href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css" rel="stylesheet">
</head>
</html>
<?php
	
	$euroKW = 0.30;  // Costo indicativo.  Riferimento -> taglialabolletta.it/quanto-costa-un-kwh/
		
	$gco2 = 550; // Grammi medi di CO2 emessi per la produzione di 1kWh di energia elettrica dalle centrali termoelettriche a olio combustibile in italia
	
	$bariliKW = ( (1/1000) * 0.24 ) / 0.146; // Barili di petrolio per kWh
	
	// connessione al database
	$conn = new mysqli("localhost", "castor1", "") or die("Errore di connessione ");
    mysqli_select_db($conn, "my_castor1") or die ("Errore selezione db");
	
	
	
	$sql = "SELECT misurazioni.id AS ordina, misurazioni.potenza FROM misurazioni ORDER BY ordina;";

	$r=mysqli_query($conn, $sql);	
		
	$Wh = 0;
	$n = 0;
	if (mysqli_num_rows($r) > 0){
		while ($row = mysqli_fetch_assoc($r) )
		{
			$Wh = $Wh + ( $row["potenza"] * ((1.5/60)/60) );
			$n+=1;
		}
	}
	//echo $Wh;
		
	if($n==0)
	{
		$stampa.="<p>kWh Prodotti = 0</p>";
	}
	else
	{
		$stampa = "<table style='width: 100%' class='mdl-data-table mdl-js-data-table'>
  <thead>
    <tr>
      <th class='mdl-data-table__cell--non-numeric'>Nome</th>
      <th class='mdl-data-table__cell--non-numeric'>Valore</th>
    </tr>
  </thead>
  <tbody>";
		$iWh = $Wh * 1000;
		$stampa.="<tr><td class='mdl-data-table__cell--non-numeric'>Wh Prodotti </td>"."<td class='mdl-data-table__cell--non-numeric'>".round($iWh, 3)."</td></tr>";
		$grammi = $Wh * $gco2;
		$stampa.="<tr><td class='mdl-data-table__cell--non-numeric'>Emissioni evitate </td>"."<td class='mdl-data-table__cell--non-numeric'>".round($grammi, 2)." gCO<sub>2</sub></td></tr>";
		$bep = $Wh * $bariliKW;
		$stampa.="<tr><td class='mdl-data-table__cell--non-numeric'>Barili petrolio risparmiati </td>"."<td class='mdl-data-table__cell--non-numeric'>".round($bep, 1)." bep</td></tr>";		
		$euror = $Wh * $euroKW;
		$stampa.="<tr><td class='mdl-data-table__cell--non-numeric'>Risparmiati </td>"."<td class='mdl-data-table__cell--non-numeric'>".round($euror, 2)."€</td></tr></tbody>";
		$stampa.="</table>";
	}		
	
	
	echo $stampa;
?>

