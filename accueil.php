<?php
session_start();
?>

<html>
<head> <title> Le tarot clandestin </title> </head>
<body>
<?php
	if(!isset($_SESSION['nom']))
	{
		echo '<meta http-equiv="refresh" content="0;URL=./login.php">';
	}
?>
<?php
	if(isset($_SESSION['nom']))
	{
		include 'db.php';
		$db = tarotconnect();	
		echo "<p>Bonjour, ".$_SESSION['nom'].".<br>";
		print("Bienvenue sur le magic tarot interface v0.1. Ce site est optimisé pour Internet Explorer 3 et Lynx.<br>");
		echo 'Merci d\'adresser toute complainte au <a href="mailto:nicolas.sarkozy@gouv.fr">webmaster</a></p>';
		print("<p><b><u>Phrase du jour :</b></u> <i>" . randomPhraseDuJour() . "</i></p>");
		tarotdisconnect($db);

	}
?>

<p> Quelques infos sur le elite data model : <br>
Les <b>parties</b> sont regroupées en <b>sessions</b>. Les scores et statistiques sont calculés pour une session donnée ce qui permet de remettre à zéro pour éviter que Grégoire n'atteigne les -2000.<br>
</p><p><B><U>ATTENTION:</B></U>
Le code est ultra vulnérable aux injections SQL donc merci d'éviter de tricher bande de foutriquets.<br>
La base de données est stockée en RAID -1 sur un amstrad CPC 464, les sauvegardes sont effectuées sur des cassettes qui sont stockées dans mon frigo.<br>
Merci de votre compréhension.<br>
</p>
<p> Actions possibles :
<ul type="circle">
<li> <a href="./ajouterSession.php">Ajouter une session</a> </li>
<li> <a href="./ajouterPartie.php">Ajouter une partie dans une session</a> </li>
<li> <a href="./resultatsSession.php">Voir les scores et statistiques d'une session</a> </li>
</ul>
</p>
</body>
</html>