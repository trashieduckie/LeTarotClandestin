<?php
session_start();
?>

<html>
<head> <title> Ajout d'une session </title> </head>
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
		
		print("<p>Attention, la base de données est readonly, lorsque vous remplissez ce formulaire vous le remplissez avec votre sang, n'oubliez pas.</p>");
		

	}
?>
<form action = "ajouterSession.php" method="post">
  <p>
     	<BR>
     	Nom :
	<BR>
	<INPUT TYPE="text" NAME="nomSession" ROWS="1" COLS="20">
 	</INPUT>
    <input type="submit" name = "Button" value ="Poster">
	<br><a href="./accueil.php"> Retour à l'accueil </a>
  </p>
</form>
<?php

	if(isset($_POST['Button']) && isset($_SESSION['nom']))
	{
		include 'db.php';
		if(isset($_POST['nomSession']) && strcmp($_POST['nomSession'],"") != 0)
		{
			
			$db = tarotconnect();
			$name = $_POST['nomSession'];
			InsertSession($name);
			print("Session créée !");
			tarotdisconnect($db);
			
		}
		else
		{
			print("Erreur: Certains champs n'étaient pas remplis. Votre session n'a pas pu être enregistrée. Batard va.");
		}	
	}
?>
</body>
</html>