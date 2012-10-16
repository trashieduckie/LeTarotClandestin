<?php
session_start();
?>

<html>
<head> <title> Infos sur une partie </title> </head>
<body>
<?php
	if(!isset($_SESSION['nom']))
	{
		echo '<meta http-equiv="refresh" content="0;URL=./login.php">';
	}
?>

<form action = "resultatsSession.php" method="post">
  <p>
	<?php
		include 'db.php';
		$db = tarotconnect();
		$tab = SelectPartie($_GET['p']);
		if(isset($tab))
		{
			$IdPartieCourante = $_GET['p'];
			print("<p><B><U>Partie (<a href=\"./editerPartie.php?idpartie=$IdPartieCourante\">Editer</a>)</B></U><BR>");
			print("Identifiant : " . $tab['IDPartie'] . "<BR>");
			print("Date : " . $tab['DatePartie'] . "<BR>");
			print("Session : " . $tab['NomSession'] . "<BR></p>");
			
			print("<p><B><U>Annonces</B></U><BR>");
			$res = SelectParticipations($_GET['p']);
			foreach($res as $r)
			{
				$nomJoueur = $r['LoginParticipant'];
				if($r['Poignee'] == 1)
				{
					echo "$nomJoueur a annonc� une poign�e.<BR>";
				}
				if($r['DoublePoignee'] == 1)
				{
					echo "$nomJoueur a annonc� une double poign�e.<BR>";
				}
				if($r['TriplePoignee'] == 1)
				{
					echo "$nomJoueur a annonc� une triple poign�e.<BR>";
				}
				if($r['Misere'] == 1)
				{
					echo "$nomJoueur a annonc� une mis�re.<BR>";
				}
			}
			if($tab['ChelemAnnonce'] == 1)
			{
				print("L'attaque avait annonc� un chelem sur cette partie... ");
				if($tab['ChelemReussi'] == 1)
				{
					print("Et il a �t� r�ussi !");
				}
				else
				{
					print("Mais c'est un �chec total.");
				}
			}
			print("</p>");
			print("<p><B><U>D�roulement :</B></U><BR>");
			print("Preneur : " . $tab['LoginPreneur'] . "<BR>");
			print("Equipier : " . $tab['LoginEquipier'] . "<BR>");
			print("Contrat : " . $tab['ContratPartie'] . "<BR>");
			if(strcmp($tab['LoginPetitAuBout'],"Aucun") != 0)
			{
				print($tab['LoginPetitAuBout'] . " a pos� le petit au bout et... ");
				if($tab['PetitAuBoutReussi'] == 1)
				{
					print(" Il l'a sauv�.");
				}
				else
				{
					print(" Il l'a perdu !!");
				}
				print("<BR>");
			}
			if($tab['ChelemReussi'] == 1)
			{
				print("Il y a eu grand chelem sur cette partie !<BR>");
			}
			print("</p>");
			print("<p><B><U>Score :</B></U><BR>");
			print("Calcul(".$tab['LoginPreneur'].") :<BR>");
			ComputeScoreForGame($tab['LoginPreneur'],$_GET['p'],1);
			
			print("R�sultat par joueur : <BR><table><TR><TH>Joueur</TH><TH>Score</TH></TR>");
			$res = SelectParticipations($_GET['p']);
			foreach($res as $r)
			{
				print("<TR><TD>".$r['LoginParticipant']."</TD><TD>".ComputeScoreForGame($r['LoginParticipant'],$_GET['p'],0)."</TD></TR>");
			}
			print("</TABLE>");
			print("</p>");
		}
		else
		{
			print("Erreur : pas de partie trouv�e.");	
		}
		tarotdisconnect($db);
		$nomSession = $tab['NomSession'];
		echo "<br><a href=\"./resultatsSession.php?s=$nomSession\"> Retour aux r�sultats de la session </a>";
	?>
	<br><a href="./accueil.php"> Retour � l'accueil </a>
  </p>
</form>
</body>
</html>