<?php
session_start();
?>

<html>
<head> <title> Scores par session </title> </head>
<body>
<?php
	if(!isset($_SESSION['nom']))
	{
		echo '<meta http-equiv="refresh" content="0;URL=./login.php">';
	}
?>

<form action = "resultatsSession.php" method="post">
  <p>
     <BR>
     	Session :
	<BR>
	<SELECT name="NomSession">
	<?php
		include 'db.php'; 
		$db = tarotconnect();
		$tab = selectAllSessions();
		
		$i = 0;
		$SessionParDefaut = $tab[$i]['NomSession'];
		if(isset($tab))
		{
			
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
	//	tarotdisconnect($db);
		echo "</SELECT><input type=\"submit\" name = \"Button\" value =\"Choisir\"><br><a href=\"./accueil.php\"> Retour à l'accueil </a>  </p></form>";
		if((isset($_POST['Button']) || isset($_GET['s']) || isset($SessionParDefaut)) && isset($_SESSION['nom']))
	{
		if((isset($_POST['NomSession']) && strcmp($_POST['NomSession'],"") != 0) || isset($_GET['s']) || isset($SessionParDefaut))
		{
			
			$NomSession = "unset";
			if(isset($_POST['NomSession']))
				$NomSession = $_POST['NomSession'];
			else
				if(isset($_GET['s']))
					$NomSession = $_GET['s'];
				else
					$NomSession = $SessionParDefaut;

			echo "<p><B><U>Tableau des scores pour la session " . $NomSession . " : </B></U><BR><BR>";
			EmptyCache();
			$debut = getmicrotime(); 
			$PartieMinAtteint = "UNSET";
			$PartieMaxAtteint = "UNSET";
			$LoginMinAttient = "UNSET";
			$LoginMaxAtteint = "UNSET";
			$MinAtteint = 1;
			$MaxAtteint = -1;
			
			$PartieMinOneShot = "UNSET";
			$PartieMaxOneShot = "UNSET";
			$LoginMinOneShot = "UNSET";
			$LoginMaxOneShot = "UNSET";
			$MinOneShot = 1;
			$MaxOneShot = -1;
			$Players = SelectPlayerNames($NomSession);
			$sessionAJour = IsCacheSessionAJour($NomSession);
			if(isset($Players))
			{
				echo "<table><td><table><TR><TH>Date</TH><TH>Partie</TH>";
				foreach ($Players as $play)
				{
					echo "<TH>$play</TH>";
					$cumulScore[$play] = 0;
					$tourNegatifs[$play] = 0;
					$defaites[$play] = 0;
					$victoires[$play] = 0;
					$GamesCount[$play] = 0;
					$scoreApresPartie[$play] = array();
					$contrats = array();
					$contrats['Petite'] = 0;
					$contrats['Garde'] = 0;
					$contrats['GardeSans'] = 0;
					$contrats['GardeContre'] = 0;
				}
				echo "<TH>Contrat</TH><TH>Status</TH>";
				echo "</TR>";
				$LineChecksum = 0;
				$Games = SelectGamesIDs($NomSession);
				$lignePaire = "<tr bgcolor='#dddddd'>";
				$ligneImpaire = "<tr bgcolor='#eeeeee'>";
				$i = 0;
				foreach ($Games as $gameRow)
				{
					
					$game = $gameRow['IDPartie'];
					$gameDate = $gameRow['DatePartie'];
					if($i % 2 == 0)
						echo "$lignePaire";
					else
						echo "$ligneImpaire";
					$i++;
					echo "<TD>$gameDate</TD>";
					echo "<TD align =\"center\"><a href=\"./infosPartie.php?p=$game\">$game</a></TD>";
					
					foreach ($Players as $play)
					{
						$CurrentScore = ComputeScoreForGame($play,$game,0);
						if($CurrentScore < 0)
						{
							$defaites[$play] = $defaites[$play] + 1;
							$GamesCount[$play] = $GamesCount[$play] + 1;
						}	
						else
						{
							if($CurrentScore > 0)
							{	
								$victoires[$play] = $victoires[$play] + 1;
								$GamesCount[$play] = $GamesCount[$play] + 1;
							}
						}
						$cumulScore[$play] = $cumulScore[$play] + $CurrentScore;
						$scoreApresPartie[$play][$game] = $cumulScore[$play];
						if($MinAtteint > $cumulScore[$play])
						{
							$MinAtteint = $cumulScore[$play];
							$LoginMinAtteint = $play;
							$PartieMinAtteint = $game;
						}
						if($MaxAtteint < $cumulScore[$play])
						{
							$MaxAtteint = $cumulScore[$play];
							$LoginMaxAtteint = $play;
							$PartieMaxAtteint = $game;
						}
						if($MinOneShot > $CurrentScore)
						{
							$MinOneShot = $CurrentScore;
							$LoginMinOneShot = $play;
							$PartieMinOneShot = $game;
						}
						if($MaxOneShot < $CurrentScore)
						{
							$MaxOneShot = $CurrentScore;
							$LoginMaxOneShot = $play;
							$PartieMaxOneShot = $game;
						}
						if($cumulScore[$play] >= 0)
						{
							echo "<TD><span style=\"color:green\">";
						}
						else
						{
							echo "<TD><span style=\"color:red\">";
							$tourNegatifs[$play]++;
						}
						echo $cumulScore[$play];
						echo "</span></TD>";
						$LineChecksum = $LineChecksum+$CurrentScore;
					}
					if($LineChecksum != 0)
					{
						echo "CHECKSUM ERROR ON GAME $game!!";
					}
					
					$Contrat = GetGameContrat($game);
					$ContratTok = strtok($Contrat,"(");
					$contrats[$ContratTok]++;
					echo "<TD> " . $Contrat . "</TD>";
					if(GameOK($game))
						echo "<TD> <span style=\"color:green\"> Réussite </span></TD>";
					else
						echo "<TD> <span style=\"color:red\"> Chute </span> </TD>";
					echo "</TR>";
				}
		/*		echo "<TR><TD></TD>	<TD>Total : </TD>"; */
				$ChecksumTotal = 0;
				$ScorePremier = "<UNSET>";
				$NomPremier = "<UNSET>";
				$ScoreDernier = "<UNSET>";
				$NomDernier = "<UNSET>";
				foreach ($Players as $play)
				{
					if(strcmp($ScorePremier,"<UNSET>") == 0 || $ScorePremier < $cumulScore[$play])
					{
						$ScorePremier = $cumulScore[$play];
						$NomPremier = $play;
					}
					
					if(strcmp($ScoreDernier,"<UNSET>") == 0 || $ScoreDernier > $cumulScore[$play])
					{
						$ScoreDernier = $cumulScore[$play];
						$NomDernier = $play;
					}
					
			/*		echo "<TD align=\"right\">";
					if($cumulScore[$play] >= 0)
					{
						echo "<span style=\"color:green\">";
					}
					else
					{
						echo "<span style=\"color:red\">";
					}
					echo $cumulScore[$play];
					echo "</span></TD>"; */
					$ChecksumTotal = $ChecksumTotal + $cumulScore[$play];
				}
				if($ChecksumTotal != 0)
				{
					echo "CHECKSUM ERROR DANS LES TOTAUX!";
				}
			/*	echo "</TR>";*/
				echo "<TR><TD></TD><TD></TD>";
				foreach ($Players as $play)
				{
					echo "<TH>$play</TH>";
				}
				echo "</TR>";
				echo "</table></TD>";
				echo "<TD>   </TD>";
				echo "<TD><B>Classement :</B><BR>";
				$cumulScoreSorted = $cumulScore;
				arsort($cumulScoreSorted);
				$i = 1;
				foreach ($cumulScoreSorted as $playSorted => $valueSorted)
				{
					if($i == 1)
						echo "<B><U>1er(<IMG SRC=\"./banane.gif\" ALT=\"BananeDansante\" TITLE=\"Banane qui danse\">)</U></B> ";
					else
						echo "$i" . "ème ";
					echo ": $playSorted ($valueSorted)<BR>";
					$i++;
				}
				echo "</TD><TD><B>Classement alternatif en valeur absolue:</B><BR>";
				
				$i = 1;
				foreach ($cumulScoreSorted as $playSorted => $valueSorted)
				{
					$cumulScoreSorted[$playSorted] = abs($cumulScoreSorted[$playSorted]);
				}				
				arsort($cumulScoreSorted);
				foreach ($cumulScoreSorted as $playSorted => $valueSorted)
				{
					if($i == 1)
						echo "<B><U>1er</U></B> ";
					else
						echo "$i" . "ème ";
					echo ": $playSorted ($valueSorted)<BR>";
					$i++;
				}
				
				$FileName = str_replace(" ","","Scores$NomSession.png");
				$FileNameContrats = str_replace(" ","","Contrats$NomSession.png");
				$FileNamePrises = str_replace(" ","","Prises$NomSession.png");
				if (!$sessionAJour)
				{	
					foreach ($Players as $play)
					{ 
						$PrisesJoueur[$play] = ComputeNombrePrises($play,$NomSession);
					}
					RenderClassement($scoreApresPartie,$FileName);
					RenderContrats($contrats,$FileNameContrats);
					RenderPrises($PrisesJoueur,$FileNamePrises);
					SetCacheSessionAJour($NomSession,1);
				}
				
				echo "</TD></TABLE><IMG SRC=\"./$FileNameContrats\" ALT=\"Scores\" TITLE=\"Contrats\"></TD><TD><IMG SRC=\"./$FileNamePrises\" ALT=\"Scores\" TITLE=\"Prises\"><BR><IMG SRC=\"./$FileName\" ALT=\"Scores\" TITLE=\"Scores\">";
				
				echo "<BR>C'est $NomPremier qui mène la danse pendant que $NomDernier creuse sa tombe tranquillou.</p>";
				echo "<p><B><U>Statistiques de la session :</B></U><BR>";
				echo "<U>Par joueur :</U><BR>";
				echo "<TABLE>";
				$NomMaxPrises = "<UNSET>";
				$RecordMaxPrises = -1;
				$NomMinPrises = "<UNSET>";
				$RecordMinPrises = -1;
				$i = 0;
				foreach ($Players as $play)
				{
					if($i % 2 == 0)
					{
						echo "<TR>";
						if($i != 0)
						{
							echo "</TR>";
						}
					}
					$i++;
					echo "<TD>";
					$NomJoueur = $play;
					echo "<IMG SRC=\"./$play.jpg\" ALT=\"$play\" TITLE=\"$play\" WIDTH=\"50%\"><BR><B>$play</B><BR>";
					$NbPrisesCourant = ComputeNombrePrises($play,$NomSession);
					$TauxPrisesCourant = round($NbPrisesCourant/$GamesCount[$play]*100,1);
					$NbChutes = ComputeNombreChutes($play,$NomSession);
					if($NbPrisesCourant > 0)
						$Taux = round(($NbPrisesCourant-$NbChutes)/$NbPrisesCourant * 100.,2);
					else
						$Taux = "N/A";
					$NombreParticipation = ComputeNombreParticipation($play,$NomSession);
					$TauxPrise = round($NbPrisesCourant / $NombreParticipation * 100.,2);
					$NbPartiesJoueurCourant = $GamesCount[$play];
					echo "Prises : $NbPrisesCourant dont $NbChutes chute(s) en $NbPartiesJoueurCourant parties. <BR>Taux de prises : $TauxPrisesCourant%<BR>Taux de réussite : $Taux";
					if($Taux == "N/A")
						echo ".<BR>";
					else
						echo "%.<BR>";
					echo "Taux de prises / nombre de participation = $TauxPrise%.<BR>";
					$NombrePetitAuBout = ComputeNombrePetitAuBout($play,$NomSession);
					$NombrePetitAuBoutReussi =  ComputeNombrePetitAuBoutReussi($play,$NomSession);
					$TauxPetit = "N/A";
					if($NombrePetitAuBout > 0)
					{
						$TauxPetit = $NombrePetitAuBoutReussi / $NombrePetitAuBout * 100.;
						$TauxPetit = round($TauxPetit,2) . "%";
					}
					echo "Petit au bout amené $NombrePetitAuBout fois dont $NombrePetitAuBoutReussi réussite(s), soit un taux de $TauxPetit.<BR>";
					
					if($TauxPrisesCourant > $RecordMaxPrises)
					{
						$RecordMaxPrises = $TauxPrisesCourant;
						$NomMaxPrises = $play;
					}
					if($RecordMinPrises == -1 || $RecordMinPrises > $TauxPrisesCourant)
					{
						$RecordMinPrises = $TauxPrisesCourant;
						$NomMinPrises = $play;
					}
					echo "Max/Min sur une partie : " . PrintBestAndWorstGame($play,$NomSession) . "<BR>";
					echo "Nombre de tours passés dans le négatif : ".$tourNegatifs[$play]. "<BR>";
					echo "<TD>";
				}
				echo "</TR></TABLE>";
				echo "<BR><U>Générales :</u><BR>";
				echo "<IMG SRC=\"./cojones.jpg\" ALT=\"cojones\" TITLE=\"cojones\" HEIGHT=\"30%\"> <B>$NomMaxPrises</B> a des <b>cojones</b> (ou de la chatte, c'est selon...), il a pris $RecordMaxPrises% du temps où il était présent !<BR>";
				echo "<IMG SRC=\"./couillemolle.jpg\" ALT=\"couille molle\" TITLE=\"couille molle\" HEIGHT=\"30%\"> <B>$NomMinPrises</B> est la <b>couille molle</b> de la session avec $RecordMinPrises% de prises dans les parties qu'il a jouée(s).<BR>";
				echo "<IMG SRC=\"./fond.jpg\" ALT=\"le fond\" TITLE=\"le fond\" HEIGHT=\"30%\"> <B>$LoginMinAtteint</B> a <b>touché le fond</b> avec $MinAtteint à l'issue de la partie <a href=\"./infosPartie.php?p=$PartieMinAtteint\">$PartieMinAtteint</a>.<BR>";
				echo "<IMG SRC=\"./record.jpg\" ALT=\"record\" TITLE=\"record\" HEIGHT=\"30%\"> <B>$LoginMaxAtteint</B> détient le <b>record de points</b> avec $MaxAtteint à l'issue de la partie <a href=\"./infosPartie.php?p=$PartieMaxAtteint\">$PartieMaxAtteint</a>.<BR>";
				echo "<IMG SRC=\"./banque.jpg\" ALT=\"holdup\" TITLE=\"holdup\" HEIGHT=\"30%\"> <B>$LoginMaxOneShot</B> a fait <b>sauter la banque</b> à la partie <a href=\"./infosPartie.php?p=$PartieMaxOneShot\">$PartieMaxOneShot</a> en marquant $MaxOneShot points d'un coup !<BR>";
				echo "<IMG SRC=\"./soumis.jpg\" ALT=\"pwned\" TITLE=\"pwned\" HEIGHT=\"35%\"> <B>$LoginMinOneShot</B> s'est <b>fait soumettre</b> pendant la partie <a href=\"./infosPartie.php?p=$PartieMinOneShot\">$PartieMinOneShot</a> en prenant $MinOneShot dans la tronche.<BR>";
				$LoginPetiteBite = "<UNSET>";
				$PartiePetiteBite = "<UNSET>";
				$PointsPetiteBite = "<UNSET>";
				ComputePetiteBite($NomSession,$LoginPetiteBite,$PartiePetiteBite,$PointsPetiteBite);
				if(strcmp($LoginPetiteBite,"<UNSET>") != 0)
				{
					echo "<IMG SRC=\"./petitebite.jpg\" ALT=\"petite bite\" TITLE=\"petite bite\" HEIGHT=\"25%\"> <B>$LoginPetiteBite</B> a gagné le <b>petite bite award</b> lors de la partie <a href=\"./infosPartie.php?p=$PartiePetiteBite\">$PartiePetiteBite</a> avec une petite faite de $PointsPetiteBite points.<BR>";
				}
				$BestCouple = ComputeCoupleDeLAnnee($NomSession);
				$CoupleString = $BestCouple['String'];
				$CoupleNb = $BestCouple['Nb'];
				if($CoupleNb > 0)
					echo "<IMG SRC=\"./couple.jpg\" ALT=\"trop mignon\" TITLE=\"trop mignon\" HEIGHT=\"30%\"> $CoupleString est le <b>couple de l'année</b>, ils ont été en attaque ensemble $CoupleNb fois.<BR>";
				echo "</p>";
				$Bitch = ComputeBitch($NomSession);
				$LoginAppele = $Bitch['Login'];
				$NbAppel = $Bitch['Nb'];
				echo "<IMG SRC=\"./sexybitch.jpg\" ALT=\"bitch\" TITLE=\"bitch\" HEIGHT=\"30%\"> <B>$LoginAppele</B> est une <b>sexy bitch</b>, il a été appelé $NbAppel fois.<BR>";
				
				foreach ($victoires as $playvictoiresSorted => $valuevictoiresSorted)
				{
					$victoires[$playvictoiresSorted] = round($valuevictoiresSorted / $GamesCount[$playvictoiresSorted] * 100,1);
				}
				arsort($victoires);
				foreach ($victoires as $playvictoiresSorted => $valuevictoiresSorted)
				{
					
					echo "<IMG SRC=\"./winner.jpg\" ALT=\"Winner\" TITLE=\"Winner\" HEIGHT=\"30%\"> <B>$playvictoiresSorted</B> est un <b>winner</b>, il a été $valuevictoiresSorted% du temps dans le camp victorieux.<BR>";
					break;
				}
				foreach ($defaites as $playdefaitesSorted => $valuedefaitesSorted)
				{
					$defaites[$playdefaitesSorted] = round($valuedefaitesSorted / $GamesCount[$playdefaitesSorted] * 100,1);
				}
				arsort($defaites);
				foreach ($defaites as $playdefaitesSorted => $valuedefaitesSorted)
				{
					echo "<IMG SRC=\"./loser.png\" ALT=\"Loser\" TITLE=\"Loser\" HEIGHT=\"30%\"> <B>$playdefaitesSorted</B> is a <b>loser</b> baby, il a été $valuedefaitesSorted% du temps dans le camp des perdants.<BR>";
					break;
				}

				
				$fin = getmicrotime(); 
				echo "<BR>Page générée en ".round( $fin - $debut, 3) ." secondes"; 
			}
			else
			{
				echo "Pas d'infos pour cette session.<BR>";
			}
			tarotdisconnect($db);
			
		}
		else
		{
			print("Erreur: Certains champs n'étaient pas remplis.");
		}	
	}
?></p>
 	

</body>
</html>