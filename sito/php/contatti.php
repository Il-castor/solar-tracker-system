<?php

	$nome = $_POST['nome'];
	$email = $_POST['email'];
	$testo = $_POST['textarea'];
  
	  /* destinatari (notare la virgola) */
  $destinatari = ""; //Inserire mail destinatario

  /* oggetto */
  $oggetto = "";

  /* messaggio */
  $messaggio = "
  <html>
  <head>
  <title>Domanda sezioni contatti sito tesina</title>
  </head>
  <body>
  <p>$nome ti ha inviato: <br />
  $testo</p>
  <p>Se vuoi rispondere questo è il suo indirizzo mail <br />
  $email</p>
  </body>
  </html>
  ";
	/* Per inviare email in formato HTML, serve l´intestazione Content-type */
  $intestazioni  = "MIME-Version: 1.0\r\n";
  $intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";

  /* intestazioni addizionali */
  $intestazioni .= "\r\n ";
  $intestazioni .= "From: $mail\r\n";
 /* ed infine l´invio */
 
  mail($destinatari, $oggetto, $messaggio, $intestazioni);
  if (mail)
    header('location: ../html/contact.html'); //rimando alla pagina dei contatti. Mettere percorso completo dentro al sito 
 
 
  
  
?>