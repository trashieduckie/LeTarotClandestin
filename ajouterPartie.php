<?php
session_start();
?>

<html>
<head> <title> Ajout d'une partie </title> </head>
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
<form action = "ajouterPartie.php" method="post">
  <p>
     	<BR>
     	Session :
	<BR>
	<SELECT name="NomSession">
	<?php
		include 'db.php';
		$db = tarotconnect();
		$tab = selectAllSessions();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['NomSession']))
			{
				print("<OPTION>" . $tab[$i]['NomSession'] ."</OPTION>");
				$i++;
			}
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT><BR>
	<BR>
	<table><tr>
	<td>
	
     	Preneur :
	<SELECT name="NomPreneur">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD>
	<TD>
     	Equipier : 
	<SELECT name="NomEquipier">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			print("<OPTION> Aucun </OPTION>");
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD><TD>
     	Défenseur 1 :
	<SELECT name="NomDefenseur1">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD><TD>
     	Défenseur 2 :
	
	<SELECT name="NomDefenseur2">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD><TD>
     	Défenseur 3 :
	
	<SELECT name="NomDefenseur3">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD>
	<TD>
     	Défenseur 4 :
	
	<SELECT name="NomDefenseur4">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			print("<OPTION> Aucun </OPTION>");
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	</TD></TR></TABLE><BR>
     	Contrat :
	<BR>
	<SELECT name="Contrat">
		<OPTION>Petite</OPTION>
		<OPTION>Garde</OPTION>
		<OPTION>GardeSans</OPTION>
		<OPTION>GardeContre</OPTION>
	</SELECT>
	<TABLE><TR><TD>
	<BR>
     	Chelem annoncé :    
	<BR>
	<SELECT name="Chelem">
		<OPTION>Non</OPTION>
		<OPTION>Oui</OPTION>
	</SELECT>
	<BR>
     	Chelem réussi :
	<BR>
	<SELECT name="ChelemOK">
		<OPTION>Non</OPTION>
		<OPTION>Oui</OPTION>
	</SELECT></TD><TD>
	<BR>
     	Petit au bout :
	<BR>
	<SELECT name="NomPetitAuBout">
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			print("<OPTION> Aucun </OPTION>");
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				print("<OPTION>" . $tab[$i]['login'] ."</OPTION>");
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
		
	?>
 	</SELECT>
	<BR>
     	Petit au bout réussi :
	<BR>
	<SELECT name="PetitAuBoutReussi">
		<OPTION>Oui</OPTION>
		<OPTION>Non</OPTION>
 	</SELECT></TR></TABLE>
	<BR>
     	Poignée :
	<BR>
	<table><TR>
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				echo '<TD><INPUT TYPE=CHECKBOX NAME="Poignee[]" value="'.$tab[$i]['login'].'">' . $tab[$i]['login']. '</INPUT></TD>';
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
	?>
	</TR></table>
	<BR>
     	Double poignée :
	<BR>
	<table><TR>
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				echo '<TD><INPUT TYPE=CHECKBOX NAME="PoigneeD[]" value="'.$tab[$i]['login'].'">' . $tab[$i]['login']. '</INPUT></TD>';
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
	?>
	</TR></table>
	<BR>
     	Triple poignée :
	<BR>
	<table><TR>
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				echo '<TD><INPUT TYPE=CHECKBOX NAME="PoigneeT[]" value="'.$tab[$i]['login'].'">' . $tab[$i]['login']. '</INPUT></TD>';
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
	?>
	</TR></table>
	<BR>
     	Zermi :
	<BR>
	<table><TR>
	<?php
		$db = tarotconnect();
		$tab = selectUser();
		if(isset($tab))
		{
			$i = 0;
			while(isset($tab[$i]['login']))
			{
				echo '<TD><INPUT TYPE=CHECKBOX NAME="Misere[]" value="'.$tab[$i]['login'].'">' . $tab[$i]['login']. '</INPUT></TD>';
				$i++;
			}
			
		}
		else
		{
			print("Erreur : pas de session trouvée.");	
		}
		tarotdisconnect($db);
	?>
	</TR></table>
	<BR>
     	Score :
	<BR>
	<INPUT TYPE="text" NAME="scorePartie" ROWS="1" COLS="2">
 	</INPUT>
	<BR>
     	Nombre de bouts :
	<BR>
	<SELECT name="NbBouts">
		<OPTION>0</OPTION>
		<OPTION>1</OPTION>
		<OPTION>2</OPTION>
		<OPTION>3</OPTION>
	</SELECT>
	<BR><BR><BR>
    <input type="submit" name = "Button" value ="Poster">
	<br><a href="./accueil.php"> Retour à l'accueil </a>
  </p>
</form>
<?php

	if(isset($_POST['Button']) && isset($_SESSION['nom']))
	{
		if(	isset($_POST['NomSession']) && strcmp($_POST['NomSession'],"") != 0
			&& isset($_POST['NomPreneur']) && strcmp($_POST['NomPreneur'],"")
			&& isset($_POST['NomEquipier']) && strcmp($_POST['NomEquipier'],"")
			&& isset($_POST['NomDefenseur1']) && strcmp($_POST['NomDefenseur1'],"")
			&& isset($_POST['NomDefenseur2']) && strcmp($_POST['NomDefenseur2'],"")
			&& isset($_POST['NomDefenseur3']) && strcmp($_POST['NomDefenseur3'],"")
			&& isset($_POST['Contrat']) && strcmp($_POST['Contrat'],"")
			&& isset($_POST['Chelem']) && strcmp($_POST['Chelem'],"")
			&& isset($_POST['ChelemOK']) && strcmp($_POST['ChelemOK'],"")
			&& isset($_POST['NomPetitAuBout']) && strcmp($_POST['NomPetitAuBout'],"")	
			&& isset($_POST['scorePartie']) && strcmp($_POST['scorePartie'],"")				
			&& isset($_POST['NbBouts']) && strcmp($_POST['NbBouts'],"")	
		)
		{
			
			$db = tarotconnect();
			$NomSession = $_POST['NomSession'];
			$NomPreneur = $_POST['NomPreneur'];
			$NomEquipier = $_POST['NomEquipier'];
			$NomDefenseur1 = $_POST['NomDefenseur1'];
			$NomDefenseur2 = $_POST['NomDefenseur2'];
			$NomDefenseur3 = $_POST['NomDefenseur3'];
			$NomDefenseur4 = $_POST['NomDefenseur4'];
			$Contrat = $_POST['Contrat'];
			if(strcmp($_POST['Chelem'],"Oui") == 0)
			{
				$Chelem = 1;
			}
			else
			{
				$Chelem = 0;
			}
			if(strcmp($_POST['ChelemOK'],"Oui") == 0)
			{
				$ChelemOK = 1;
			}
			else
			{
				$ChelemOK = 0;
			}
			if(strcmp($_POST['PetitAuBoutReussi'],"Oui") == 0)
			{
				$PetitAuBoutReussi= 1;
			}
			else
			{
				$PetitAuBoutReussi= 0;
			}
			$NomPetitAuBout = $_POST['NomPetitAuBout'];
			$ScorePartie = $_POST['scorePartie'];
			$NbBoutsPartie = $_POST['NbBouts'];
			if(isset($_POST['Poignee']))
				$Poignee = $_POST['Poignee'];
			if(isset($_POST['PoigneeD']))
				$DoublePoignee = $_POST['PoigneeD'];
			if(isset($_POST['PoigneeT']))
				$TriplePoignee = $_POST['PoigneeT'];
			if(isset($_POST['Misere']))
				$Misere = $_POST['Misere'];

			// On vérifie que c'est cohérent
			if(strcmp($NomPreneur,$NomEquipier) == 0
			|| strcmp($NomPreneur,$NomDefenseur1) == 0
			|| strcmp($NomPreneur,$NomDefenseur2) == 0
			|| strcmp($NomPreneur,$NomDefenseur3) == 0
			|| strcmp($NomPreneur,$NomDefenseur4) == 0
			|| strcmp($NomEquipier,$NomDefenseur1) == 0
			|| strcmp($NomEquipier,$NomDefenseur2) == 0
			|| strcmp($NomEquipier,$NomDefenseur3) == 0
			|| (strcmp($NomEquipier,"Aucun") != 0 && strcmp($NomDefenseur4,"Aucun") != 0)
			|| strcmp($NomDefenseur1,$NomDefenseur2) == 0
			|| strcmp($NomDefenseur1,$NomDefenseur3) == 0
			|| strcmp($NomDefenseur1,$NomDefenseur4) == 0
			|| strcmp($NomDefenseur3,$NomDefenseur2) == 0
			|| strcmp($NomDefenseur3,$NomDefenseur4) == 0
			|| strcmp($NomDefenseur2,$NomDefenseur4) == 0
			)
			
			{
				print("<b>Erreur</b>: Incoherence dans les noms de joueur. Possibilités :
						<ul>
						<li> Dédoublement de personne ce qui n'est pas autorisé par la physique en l'état actuel des connaissances humaines.</li>
						<li> Vous avez essayé de jouer à 3.</li>
						<li> Vous avez essayé de jouer à 6.</li>
						</ul>
						Votre partie n'a pas pu être enregistrée. Batard va.");
			}
			else
			{
				// On vérifie que le petit au bout fait partie des joueurs
				if(strcmp($NomPetitAuBout,"Aucun") != 0
				&& strcmp($NomPetitAuBout,$NomPreneur) == 0
				&& strcmp($NomPetitAuBout,$NomEquipier) == 0
				&& strcmp($NomPetitAuBout,$NomDefenseur1) == 0
				&& strcmp($NomPetitAuBout,$NomDefenseur2) == 0
				&& strcmp($NomPetitAuBout,$NomDefenseur3) == 0
				&& strcmp($NomPetitAuBout,$NomDefenseur4) == 0
				)
				{
					print("<b>Erreur</b>: Le petit au bout est attribué à un joueur n'ayant pas disputé la partie. Votre partie n'a pas pu être enregistrée. Batard va.");
				}
				else
				{
					// On commence les insertions, motherfucker
					$IDPartie = InsertPartie($NomSession,$ScorePartie,$NbBoutsPartie,$NomPreneur,$NomEquipier,$Contrat,$Chelem,$ChelemOK,$NomPetitAuBout,$PetitAuBoutReussi);
					
					// On insère les points du preneur
					$PoigneeParticipant = 0;
					if(isset($Poignee) && BelongsTo($NomPreneur,$Poignee))
						$PoigneeParticipant = 1;
					$DoublePoigneeParticipant = 0;
					if(isset($DoublePoignee) && BelongsTo($NomPreneur,$DoublePoignee))
						$DoublePoigneeParticipant = 1;
					$TriplePoigneeParticipant = 0;
					if(isset($TriplePoignee) && BelongsTo($NomPreneur,$TriplePoignee))
						$TriplePoigneeParticipant = 1;
					$MisereParticipant = 0;
					if(isset($Misere) && BelongsTo($NomPreneur,$Misere))
						$MisereParticipant = 1;
					$PositionParticipant = "Preneur";

					InsertParticipation($NomPreneur,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					
					// On insère les points de l'équipier s'il existe
					if(strcmp($NomEquipier,"Aucun") != 0)
					{
						$PoigneeParticipant = 0;
						if(isset($Poignee) && BelongsTo($NomEquipier,$Poignee))
							$PoigneeParticipant = 1;
						$DoublePoigneeParticipant = 0;
						if(isset($DoublePoignee) && BelongsTo($NomEquipier,$DoublePoignee))
							$DoublePoigneeParticipant = 1;
						$TriplePoigneeParticipant = 0;
						if(isset($TriplePoignee) && BelongsTo($NomEquipier,$TriplePoignee))
							$TriplePoigneeParticipant = 1;
						$MisereParticipant = 0;
						if(isset($Misere) && BelongsTo($NomEquipier,$Misere))
							$MisereParticipant = 1;
						$PositionParticipant = "Equipier";
						InsertParticipation($NomEquipier,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					}
					
					// Défenseur 1
					$PoigneeParticipant = 0;
					if(isset($Poignee) && BelongsTo($NomDefenseur1,$Poignee))
						$PoigneeParticipant = 1;
					$DoublePoigneeParticipant = 0;
					if(isset($DoublePoignee) && BelongsTo($NomDefenseur1,$DoublePoignee))
						$DoublePoigneeParticipant = 1;
					$TriplePoigneeParticipant = 0;
					if(isset($TriplePoignee) && BelongsTo($NomDefenseur1,$TriplePoignee))
						$TriplePoigneeParticipant = 1;
					$MisereParticipant = 0;
					if(isset($Misere) && BelongsTo($NomDefenseur1,$Misere))
						$MisereParticipant = 1;
					$PositionParticipant = "Defenseur";
					InsertParticipation($NomDefenseur1,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					
					// Défenseur 2
					$PoigneeParticipant = 0;
					if(isset($Poignee) && BelongsTo($NomDefenseur2,$Poignee))
						$PoigneeParticipant = 1;
					$DoublePoigneeParticipant = 0;
					if(isset($DoublePoignee) && BelongsTo($NomDefenseur2,$DoublePoignee))
						$DoublePoigneeParticipant = 1;
					$TriplePoigneeParticipant = 0;
					if(isset($TriplePoignee) && BelongsTo($NomDefenseur2,$TriplePoignee))
						$TriplePoigneeParticipant = 1;
					$MisereParticipant = 0;
					if(isset($Misere) && BelongsTo($NomDefenseur2,$Misere))
						$MisereParticipant = 1;
					$PositionParticipant = "Defenseur";
					InsertParticipation($NomDefenseur2,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					
					// Défenseur 3
					$PoigneeParticipant = 0;
					if(isset($Poignee) && BelongsTo($NomDefenseur3,$Poignee))
						$PoigneeParticipant = 1;
					$DoublePoigneeParticipant = 0;
					if(isset($DoublePoignee) && BelongsTo($NomDefenseur3,$DoublePoignee))
						$DoublePoigneeParticipant = 1;
					$TriplePoigneeParticipant = 0;
					if(isset($TriplePoignee) && BelongsTo($NomDefenseur3,$TriplePoignee))
						$TriplePoigneeParticipant = 1;
					$MisereParticipant = 0;
					if(isset($Misere) && BelongsTo($NomDefenseur3,$Misere))
						$MisereParticipant = 1;
					$PositionParticipant = "Defenseur";
					InsertParticipation($NomDefenseur3,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					
					// Défenseur 4 (s'il existe)
					if(strcmp($NomDefenseur4,"Aucun") != 0)
					{
						$PoigneeParticipant = 0;
						if(isset($Poignee) && BelongsTo($NomDefenseur4,$Poignee))
							$PoigneeParticipant = 1;
						$DoublePoigneeParticipant = 0;
						if(isset($DoublePoignee) && BelongsTo($NomDefenseur4,$DoublePoignee))
							$DoublePoigneeParticipant = 1;
						$TriplePoigneeParticipant = 0;
						if(isset($TriplePoignee) && BelongsTo($NomDefenseur4,$TriplePoignee))
							$TriplePoigneeParticipant = 1;
						$MisereParticipant = 0;
						if(isset($Misere) && BelongsTo($NomDefenseur4,$Misere))
							$MisereParticipant = 1;
						$PositionParticipant = "Defenseur";
						InsertParticipation($NomDefenseur4,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant);
					}

				}
				
				echo "<BR>Partie enregistrée. Bravo.<BR>";
			}
			
		}
		else
		{
			print("<b>Erreur</b>: Certains champs n'étaient pas remplis. Votre session n'a pas pu être enregistrée. Batard va.");
		}	
	}
?>
</body>
</html>