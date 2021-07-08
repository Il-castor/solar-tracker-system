<?php
	$conn = new mysqli("localhost", "castor1", "") or die("Errore di connessione ");
    mysqli_select_db($conn, "my_castor1") or die ("Errore selezione db");
    $data = date("Y-m-d");
    $filename = "data_".$data.".xls";
	 //$filename="data.xls";
   header ("Content-Type: application/vnd.ms-excel");
   header ("Content-Disposition: inline; filename=$filename");
	
	$sql="SELECT id, tensione, corrente, potenza, temperatura, data, ora FROM misurazioni;";
	$r = mysqli_query($conn, $sql);
	
	$tab = "<table border='1'>";
	$tab .= "<tr><td>id</td><td>Tensione</td><td>Corrente</td><td>Potenza</td><td>Temperatura</td><td>Data</td><td>Ora</td></tr>";
	while ($riga = mysqli_fetch_array($r)){
		$tab.="<tr><td>".$riga['id']."</td><td>".$riga['tensione']."</td><td>".$riga['corrente']."</td><td>".$riga['potenza']."</td><td>".$riga['temperatura']."</td><td>".$riga['data']."</td><td>".$riga['ora']."</td></tr>";
		
	}
	$tab.="</table>";
	echo $tab;

?>