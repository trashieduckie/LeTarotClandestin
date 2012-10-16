<?php
function tarotconnect()
{
	// FIXME A ADAPTER
	$host = "???";
	$log = "???";
	$pass = "???";
	$db = mysql_connect($host,$log,$pass)
	or exit("Could not connect to localhost: " . mysql_error());
	mysql_select_db("???", $db) or exit("Could not select database ???");
	return $db;
}



function connectUser($nom,$password)
{
	$tabuser = selectUser();
	$i = 0;
	$trouve = 0;
	while(isset($tabuser[$i]) && $trouve != 1)
	{
		if(strcmp($tabuser[$i]['login'],$nom) == 0)
		{
			if(strcmp($tabuser[$i]['password'],$password) == 0)
			{
				$trouve = 1;
			}
		}

		$i++;
	}
	return $trouve;
		
}

function selectUser()
{

	$query = "select * from joueur order by login";
	$res = mysql_query($query);
	$i = 0;
	if(!$res)
	{
		printf("echec query: ". mysql_error());
	}
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$tabres[$i]['login'] = $row['login'];
		$tabres[$i]['password'] = $row['password'];
		$i++;
	}
	if(isset($tabres))
	{
		return $tabres;
	}
}

function randomPhraseDuJour()
{
	$query = "select * from phrasedujour";
	$res = mysql_query($query);
	$i = 0;
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$tabres[$i]['Phrase'] = $row['Phrase'];
		$i++;
	}
	$index = rand (0, $i-1);
	return $tabres[$index]['Phrase'];
}

function selectAllSessions()
{
	$query = "select * from session order by DateSession DESC";
	$res = mysql_query($query);
	$i = 0;
	if(!$res)
	{
		printf("echec query: ". mysql_error());
	}
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$tabres[$i]['NomSession'] = $row['NomSession'];
		$tabres[$i]['DateSession'] = $row['DateSession'];
		$i++;
	}
	if(isset($tabres))
	{
		return $tabres;
	}
}

function insertSession($name)
{
	$query = "insert into session values('".$name."',NOW(),0)";
	$res = mysql_query($query);
}

function insertPartie($NomSession,$ScorePartie,$NbBoutsPartie,$NomPreneur,$NomEqupier,$Contrat,$Chelem,$ChelemOK,$NomPetitAuBout,$PetitAuBoutReussi)
{
	$query = "insert into partie values(NULL,NOW(),
			'".$NomSession."',
			$ScorePartie,
			$NbBoutsPartie,
			'".$NomPreneur."',
			'".$NomEqupier."',
			'".$Contrat."',
			$Chelem,
			$ChelemOK,
			'".$NomPetitAuBout."',
			$PetitAuBoutReussi)";
	$res = mysql_query($query);
	
	// On renvoie l'ID de la partie créée
	$query = "select IDPartie from partie order by IDPartie DESC LIMIT 1";
	$res = mysql_query($query);
	$i = 0;
	if(!$res)
	{
		print("echec query: ". mysql_error());
	}
	if($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$res = $row['IDPartie'];
	}
	SetCacheSessionAJour($NomSession,0);
	return $res;
}

function insertPartieWithID($IDPartie,$NomSession,$ScorePartie,$NbBoutsPartie,$NomPreneur,$NomEqupier,$Contrat,$Chelem,$ChelemOK,$NomPetitAuBout,$PetitAuBoutReussi)
{
	$query = "insert into partie values($IDPartie,NOW(),
			'".$NomSession."',
			$ScorePartie,
			$NbBoutsPartie,
			'".$NomPreneur."',
			'".$NomEqupier."',
			'".$Contrat."',
			$Chelem,
			$ChelemOK,
			'".$NomPetitAuBout."',
			$PetitAuBoutReussi)";
	$res = mysql_query($query);
	SetCacheSessionAJour($NomSession,0);
	return $res;
}

function BelongsTo($Name,$Set)
{
	foreach ($Set as $s) {
		if(strcmp($s,$Name) == 0)
		{
			return 1;
		}
	}
	return 0;
}

function SelectPlayerNames($NomSession)
{
	$query = "SELECT DISTINCT LoginParticipant
				FROM partie p, participation pa
				WHERE p.NomSession = '$NomSession'
				AND p.IDPartie = pa.IDPartie";
				$res = mysql_query($query);
	$i = 0;
	if(!$res)
	{
		printf("echec query: ". mysql_error());
	}
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$tabres[$i] = $row['LoginParticipant'];
		$i++;
	}
	if(isset($tabres))
	{
		return $tabres;
	}
}

function SelectGamesIDs($NomSession)
{
	$query = "SELECT IDPartie, DatePartie
				FROM partie
				WHERE NomSession = '$NomSession'
				ORDER BY IDPartie";
	$res = mysql_query($query);
	$i = 0;
	if(!$res)
	{
		printf("echec query: ". mysql_error());
	}
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$tabres[$i] = $row;
		$i++;
	}
	if(isset($tabres))
	{
		return $tabres;
	}
}

$ResultCache = array();
$ScoresComputedForGame = array();
function EmptyCache()
{
	unset($ResultCache);
	unset($ScoresComputedForGame);
}

function ComputeScoreForGame($player,$game,$printDetail)
{

	// On vérifie d'abord qu'il a participé...
	global $ResultCache;
	global $ScoresComputedForGame;
	if(isset($ResultCache["$player;$game"]))
	{

		return $ResultCache["$player;$game"];
	}
	$toReturn = 0;
	$i = 0;
	if(!isset($ScoresComputedForGame[$game]))
	{
		$query = "SELECT * FROM participation where IDPartie = '$game'";/*	AND LoginParticipant = '$player'";*/
		$ScoresComputedForGame[$game] = TRUE;
	
		$resParticipation = mysql_query($query);
	

		$query = "SELECT * from partie where IDPartie = '$game'";
	
	
		$res = mysql_query($query);
		$row=mysql_fetch_array($res,MYSQL_ASSOC);
//		$query = "SELECT * FROM participation where IDPartie = '$game'";
//		$resToutesParticipation = mysql_query($query);
		$NbPlayers = mysql_num_rows($resParticipation);
		while($rowParticipation = mysql_fetch_array($resParticipation,MYSQL_ASSOC))
		{	
			
			
			$playerCourant = $rowParticipation['LoginParticipant'];
			$nbBouts = $row['NbBoutsPartie'];
			$score = $row['ScorePartie'];
			if($nbBouts == 0)
				$score = $score - 56;
			if($nbBouts == 1)
				$score = $score - 51;
			if($nbBouts == 2)
				$score = $score - 41;
			if($nbBouts == 3)
				$score = $score - 36;
				
			// On ajoute les points normaux
			$base = 25;
			
			
			$NomPreneur = $row['LoginPreneur'];
			$NomEquipier = $row['LoginEquipier'];
			
				
			$Multiplier = 1; 
			// Multiplier =
			//1 pour l'équipier en 2 vs 3
			//2 pour le preneur en 2 vs 3
			//3 pour le preneur en 1 vs 3
			//4 pour le preneur en 1 vs 4
			// 1 pour le défenseur en 2 vs 3
			// 1 pour le défenseur en 1 vs 3
			$CampJoueur = 1; // attaque
			if(strcmp($playerCourant,$NomEquipier) == 0)
			{	
				// Joueur courant = l'équipier
				$Multiplier = 1;
			}
			else
			{
				if(strcmp($playerCourant,$NomPreneur) != 0)
				{
					// Joueur courant = défenseur
					$CampJoueur = 0; // défense
					$Multiplier = 1;
				}
				else
				{
					// Joueur courant = preneur, reste à calculer si son multiplier est 2, 3 ou 4
					if(strcmp($NomEquipier,"Aucun") == 0)
					{
						
						if($NbPlayers == 4)
						{
							$Multiplier = 3;
						}
						else
						{
							$Multiplier = 4;
						}
					}
					else
					{
						// 2 vs 3
						$Multiplier = 2;
					}
				}
			}
			$MultiplierVictoire = 1;
			if($CampJoueur == 1 && $score < 0)
			{
				$MultiplierVictoire = -1;
			}
			if($CampJoueur == 0 && $score >= 0)
			{
				$MultiplierVictoire = -1;
			}
			$PtsPetitAuBout = 0;
			if(strcmp($row['LoginPetitAuBout'],"Aucun") != 0)
			{
				// Il y a eu petit au bout
				$PtsPetitAuBout = 10;
				if($CampJoueur == 1)
				{
					// Le joueur est dans l'attaque
					if(strcmp($row['LoginPetitAuBout'],$NomPreneur)== 0
					|| strcmp($row['LoginPetitAuBout'],$NomEquipier)== 0)
					{
						// Le petit au bout aussi
						if($row['PetitAuBoutReussi'] == 0)
							$PtsPetitAuBout = $PtsPetitAuBout * -1;
					}
					else
					{
						// Le petit au bout est en défense
						if($row['PetitAuBoutReussi'] == 1)
							$PtsPetitAuBout = $PtsPetitAuBout * -1;
					}
				}
				else
				{
					// Le joueur est en défense
					if(strcmp($row['LoginPetitAuBout'],$NomPreneur)== 0
					|| strcmp($row['LoginPetitAuBout'],$NomEquipier)== 0)
					{
						// Le petit au bout est dans l'attaque
						if($row['PetitAuBoutReussi'] == 1)
							$PtsPetitAuBout = $PtsPetitAuBout * -1;
					}
					else
					{
						// le petit au bout est en défense
						if($row['PetitAuBoutReussi'] == 0)
							$PtsPetitAuBout = $PtsPetitAuBout * -1;
					}
				}
			}
			
			// On regarde les misères, poignées...
			$PtsPoignee = 0;
			$PtsDoublePoignee = 0;
			$PtsTriplePoignee = 0;
			$PtsMisere = 0;
			mysql_data_seek($resParticipation,0);
			while($rowParticipationCourante=mysql_fetch_array($resParticipation,MYSQL_ASSOC))
			{
				$LoginParticipantCourant = $rowParticipationCourante['LoginParticipant'];
				if($rowParticipationCourante['Poignee'] == 1)
				{
					// il y a poignée
					if(strcmp($LoginParticipantCourant,$playerCourant) == 0)
					{
						// C'est le joueur courant qui l'a !
						$PtsPoignee = $PtsPoignee + 10;
					}
					else
					{
						// C'est un autre, on regarde le camp
						if($CampJoueur == 1)
						{
							if(strcmp($LoginParticipantCourant,$NomEquipier)== 0
							|| strcmp($LoginParticipantCourant,$NomPreneur)== 0)
							{
								// Coequipier en attaque, c'est bon.
								$PtsPoignee = $PtsPoignee + 10;
							}
							else
							{
								// Tu payes mon pote
								$PtsPoignee = $PtsPoignee + 10 * -1;
							}
						}
						else
						{
							if(strcmp($rowParticipationCourante['Position'],"Defenseur")== 0)
							{
								// Coequipier en défense, c'est bon.
								$PtsPoignee = $PtsPoignee + 10;
							}
							else
							{
								// Tu payes mon pote
								$PtsPoignee = $PtsPoignee + 10 * -1;
							}
							
						}
					}
				}
				if($rowParticipationCourante['DoublePoignee'] == 1)
				{
					// il y a poignée
					if(strcmp($LoginParticipantCourant,$playerCourant) == 0)
					{
						// C'est le joueur courant qui l'a !
						$PtsDoublePoignee = $PtsDoublePoignee + 20;
					}
					else
					{
						// C'est un autre, on regarde le camp
						if($CampJoueur == 1)
						{
							if(strcmp($LoginParticipantCourant,$NomEquipier)== 0
							|| strcmp($LoginParticipantCourant,$NomPreneur)== 0)
							{
								// Coequipier en attaque, c'est bon.
								$PtsDoublePoignee = $PtsDoublePoignee + 20;
							}
							else
							{
								// Tu payes mon pote
								$PtsDoublePoignee = $PtsDoublePoignee + 20 * -1;
							}
						}
						else
						{
							if(strcmp($rowParticipationCourante['Position'],"Defenseur")== 0)
							{
								// Coequipier en défense, c'est bon.
								$PtsDoublePoignee = $PtsDoublePoignee + 20;
							}
							else
							{
								// Tu payes mon pote
								$PtsDoublePoignee = $PtsDoublePoignee + 20 * -1;
							}
							
						}
					}
				}
				if($rowParticipationCourante['TriplePoignee'] == 1)
				{
					// il y a poignée
					if(strcmp($LoginParticipantCourant,$playerCourant) == 0)
					{
						// C'est le joueur courant qui l'a !
						$PtsTriplePoignee = $PtsTriplePoignee + 30;
					}
					else
					{
						// C'est un autre, on regarde le camp
						if($CampJoueur == 1)
						{
							if(strcmp($LoginParticipantCourant,$NomEquipier)== 0
							|| strcmp($LoginParticipantCourant,$NomPreneur)== 0)
							{
								// Coequipier en attaque, c'est bon.
								$PtsTriplePoignee = $PtsTriplePoignee + 30;
							}
							else
							{
								// Tu payes mon pote
								$PtsTriplePoignee = $PtsTriplePoignee + 30 * -1;
							}
						}
						else
						{
							if(strcmp($rowParticipationCourante['Position'],"Defenseur")== 0)
							{
								// Coequipier en défense, c'est bon.
								$PtsTriplePoignee = $PtsTriplePoignee + 30 ;
							}
							else
							{
								// Tu payes mon pote
								$PtsTriplePoignee = $PtsTriplePoignee + 30 * -1;
							}
							
						}
					}
				}
				if($rowParticipationCourante['Misere'] == 1)
				{
					// il y a poignée
					if(strcmp($LoginParticipantCourant,$playerCourant) == 0)
					{
						// C'est le joueur courant qui l'a !
						$PtsMisere = $PtsMisere + 10;
					}
					else
					{
						// C'est un autre, on regarde le camp
						if($CampJoueur == 1)
						{
							if(strcmp($LoginParticipantCourant,$NomEquipier) == 0
							|| strcmp($LoginParticipantCourant,$NomPreneur) == 0)
							{
								// Coequipier en attaque, c'est bon.
								$PtsMisere = $PtsMisere + 10;
							}
							else
							{
								// Tu payes mon pote
								$PtsMisere = $PtsMisere + 10 * -1;
							}
						}
						else
						{
							if(strcmp($rowParticipationCourante['Position'],"Defenseur") == 0)
							{
								// Coequipier en défense, c'est bon.
								$PtsMisere = $PtsMisere + 10;
							}
							else
							{
								// Tu payes mon pote
								$PtsMisere = $PtsMisere + 10 * -1;
							}
							
						}
					}
				}
			}
			if($i < $NbPlayers)
				mysql_data_seek($resParticipation,$i);
			$i = $i + 1;
			// On regarde le chelem
			$PtsChelem = 0;
			if($row['ChelemReussi'] == 1)
			{
				// On regarde s'il est pour l'attaque ou la défense
				if($score >= 0)
				{
					// Il est pour l'attaque
					// On regarde s'il était annoncé
					if($row['ChelemAnnonce'] == 1)
					{
						$PtsChelem = 400;			
					}
					else
					{
						$PtsChelem = 200;
					}
					if($CampJoueur == 0)
						$PtsChelem = $PtsChelem * (-1);
				}
			}
			else
			{
				if($row['ChelemAnnonce'] == 1)
				{
					// On suppose que c'est l'attaque qui l'a annoncé et qui s'est viandé..
					$PtsChelem = 200;
					if($CampJoueur == 1)
						$PtsChelem = $PtsChelem * (-1);
				}
			}
			
			// On a tous les éléments, il nous manque le contrat
			$MultiplierContrat = 1;
			if(strcmp($row['ContratPartie'],"Garde") == 0)
			{
				$MultiplierContrat = 2;
			}
			if(strcmp($row['ContratPartie'],"GardeSans") == 0)
			{
				$MultiplierContrat = 4;
			}
			if(strcmp($row['ContratPartie'],"GardeContre") == 0)
			{
				$MultiplierContrat = 6;
			}
			$reuss = "Chute";
			if($score >= 0)
			{
				$reuss = "Faite";
			}
			$scoreInit = $score;
			
			$score = (((abs($score) + $base) * $MultiplierContrat * $MultiplierVictoire ) + $PtsPetitAuBout * $MultiplierContrat + $PtsChelem + $PtsMisere + $PtsPoignee + $PtsDoublePoignee + $PtsTriplePoignee) * $Multiplier ;
			if($printDetail)
			{
				$contrat = $row['ContratPartie'];
				echo "<table><tr><td>score($playerCourant)</td><td>	=</td><td align=\"center\">	((abs($scoreInit)	+	$base)</td><TD>points de base</TD></TR>";
				echo "<TR><TD></TD> 		<TD>+</TD>	<TD  align=\"center\">$PtsPetitAuBout</td><TD>Petit au bout</TD></TR>";
				echo "<TR><TD></TD>		<TD>*</TD>	<TD align=\"center\">$MultiplierContrat</td><TD>$contrat</TD></TR>";
				echo "<TR><TD></TD> 		<TD>*</TD>  	<TD align=\"center\">$MultiplierVictoire)</td><TD>$reuss</TD></TR>";
				echo "<TR><TD></TD>		<TD>+</TD>	<TD align=\"center\">$PtsMisere</td><TD>Misère(s)</TD></TR>";
				echo "<TR><TD></TD> 		<TD>+</TD>	<TD align=\"center\">$PtsPoignee</td><TD>Poignée(s)</TD></TR>";
				echo "<TR><TD></TD>		<TD>+</TD>	<TD align=\"center\">$PtsDoublePoignee</td><TD>Double poignée(s)</TD></TR>";
				echo "<TR><TD></TD>		<TD>+</TD>	<TD align=\"center\">$PtsTriplePoignee)</td><TD>Triple poignée(s)</TD></TR>";
				echo "<TR><TD></TD>		<TD>*</TD> 	<TD align=\"center\">$Multiplier</td><TD>Multiplicateur preneur</TD></TR>";
				echo "<TR><TD></TD><TD>=</TD><TD align=\"center\">$score</TD></TR></TABLE>";
			}
			$ResultCache["$playerCourant;$game"] = $score;
			if(strcmp($playerCourant,$player)==0)
				$toReturn = $score;
		}
	}
	else
	{
	}
	return $toReturn;
}

function PrintBestAndWorstGame($play,$NomSession)
{
	$bestScore = "<UNSET>";
	$worstScore = "<UNSET>";
	$IDGameBestScore = -1;
	$IDGameWorstSCore = -1;
	$query = "SELECT p.IDPartie 
				FROM participation p, partie p2 
				WHERE p.LoginParticipant = '$play' 
				AND p2.NomSession = '$NomSession' 
				AND p.IDPartie = p2.IDPartie";
	$res = mysql_query($query);
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$scoreCourant = ComputeScoreForGame($play,$row['IDPartie'],0);
		if(strcmp($bestScore,"<UNSET>") == 0 || $bestScore < $scoreCourant)
		{
			$bestScore = $scoreCourant;
			$IDGameBestScore = $row['IDPartie'];
		}
		if(strcmp($worstScore,"<UNSET>") == 0 || $worstScore > $scoreCourant)
		{
			$worstScore = $scoreCourant;
			$IDGameWorstScore = $row['IDPartie'];
		}
	}
	if(strcmp($worstScore,"<UNSET>") == 0)
	{
		return "nada";
	}
	else
	{
		return "$bestScore (partie <a href=\"./infosPartie.php?p=$IDGameBestScore\">$IDGameBestScore</a>) / $worstScore  (partie <a href=\"./infosPartie.php?p=$IDGameWorstScore\">$IDGameWorstScore</a>)";
	}
}

function SelectPartie($IDGame)
{
	$query = "SELECT * FROM partie where IDPartie = '$IDGame'";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	return $row;
}

function SelectParticipations($game)
{
	$query = "SELECT * FROM participation where IDPartie = '$game'";
	$resToutesParticipation = mysql_query($query);
	$i = 0;
	if(!$resToutesParticipation)
	{
		printf("echec query: ". mysql_error());
	}
	while($row=mysql_fetch_array($resToutesParticipation,MYSQL_ASSOC))
	{
		$tabres[$i] = $row;
		$i++;
	}
	if(isset($tabres))
	{
		return $tabres;
	}
}
function GetGameContrat($game)
{
	$query = "SELECT ContratPartie,LoginPreneur,LoginEquipier FROM partie where IDPartie = '$game'";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	if(strcmp($row['LoginEquipier'],"Aucun") == 0)
		return $row['ContratPartie'] . "(" . $row['LoginPreneur'] . ")";
	else
		return $row['ContratPartie'] . "(" . $row['LoginPreneur'] . ",". $row['LoginEquipier'].")";
}
function GameOK($game)
{
	$query = "SELECT NbBoutsPartie,ScorePartie FROM partie where IDPartie = '$game'";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	$score = $row['ScorePartie'];
	$nbBouts = $row['NbBoutsPartie'];
	if($nbBouts == 0)
		$score = $score - 56;
	if($nbBouts == 1)
		$score = $score - 51;
	if($nbBouts == 2)
		$score = $score - 41;
	if($nbBouts == 3)
		$score = $score - 36;
	if($score >= 0)
		return 1; // Reussi
	return 0;
}

function InsertParticipation($NomParticipant,$IDPartie,$PositionParticipant,$PoigneeParticipant,$DoublePoigneeParticipant,$TriplePoigneeParticipant,$MisereParticipant)
{
	$query = "insert into participation values (
		$PoigneeParticipant,
		$MisereParticipant,
		$DoublePoigneeParticipant,
		$TriplePoigneeParticipant,
		'$PositionParticipant',
		'$NomParticipant',
		$IDPartie)";
	$res = mysql_query($query);
}

function DeletePartie($IDPartie)
{
	$query = "delete from partie where IDPartie = $IDPartie";
	$res = mysql_query($query);
	
	$query = "delete from participation where IDPartie = $IDPartie";
	$res = mysql_query($query);
}

function ComputeNombrePrises($joueur,$session)
{
	$query = "select count(*) as nbPrises
			  from partie
			  where NomSession = '$session'
			  AND LoginPreneur = '$joueur'";
	$res = mysql_query($query);	
	$row = mysql_fetch_array($res);
	return $row['nbPrises'];
}

function ComputeNombreChutes($joueur,$session)
{
	$query = "select ScorePartie,NbBoutsPartie
			  from partie
			  where NomSession = '$session'
			  AND LoginPreneur = '$joueur'";
	$res = mysql_query($query);	
	$nbChutes = 0;
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$nbBouts = $row['NbBoutsPartie'];
		$score = $row['ScorePartie'];
		if($nbBouts == 0 && $score < 56
		|| $nbBouts == 1 && $score < 51
		|| $nbBouts == 2 && $score < 41
		|| $nbBouts == 3 && $score < 36)
			$nbChutes = $nbChutes+1;
	}
	return $nbChutes;
}

function ComputeNombreParticipation($play,$NomSession)
{
	$query = "select count(*) as nbParticipations
			  from participation p1, partie p2
			  where p2.NomSession = '$NomSession'
			  AND p2.IDPartie = p1.IDPartie
			  AND p1.LoginParticipant = '$play'";
	$res = mysql_query($query);	
	$row = mysql_fetch_array($res);
	return $row['nbParticipations'];
}
function ComputeNombrePetitAuBout($play,$NomSession)
{
	$query = "select count(*) as nbPetitAuBout
			  from partie
			  where NomSession = '$NomSession'
			  AND LoginPetitAuBout = '$play'";
	$res = mysql_query($query);	
	$row = mysql_fetch_array($res);
	return $row['nbPetitAuBout'];
}
function ComputeNombrePetitAuBoutReussi($play,$NomSession)
{
	$query = "select count(*) as nbPetitAuBout
			  from partie
			  where NomSession = '$NomSession'
			  AND LoginPetitAuBout = '$play'
			  AND PetitAuBoutReussi = 1";
	$res = mysql_query($query);	
	$row = mysql_fetch_array($res);
	return $row['nbPetitAuBout'];
}
function ComputeCoupleDeLAnnee($NomSession)
{
	$query = "SELECT LoginPreneur, LoginEquipier, COUNT( * ) AS NbEnsemble
				FROM partie
				WHERE NomSession = '$NomSession'
				GROUP BY LoginPreneur, LoginEquipier";
	$res = mysql_query($query);
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		if(strcmp($row['LoginEquipier'],"Aucun") != 0)
		{
			$key1 = "<B>" .$row['LoginPreneur'] . "</B> et <B>" . $row['LoginEquipier'] ."</B>";
			$key2 = "<B>" .$row['LoginEquipier'] . "</B> et <B>" . $row['LoginPreneur'] ."</B>";
			if(isset($toReturn[$key1]))
			{
				$toReturn[$key1] = $toReturn[$key1] + $row['NbEnsemble'];
			}
			else
			{
				if(isset($toReturn[$key2]))
				{
					$toReturn[$key2] = $toReturn[$key2] + $row['NbEnsemble'];
				}
				else
				{
					$toReturn[$key1] = $row['NbEnsemble'];
				}
			}
		}
	}
	$realToReturn['String'] = "<UNSET>";
	$realToReturn['Nb'] = 0;
	foreach($toReturn as $key => $value)
	{
		if($value > $realToReturn['Nb'])
		{
			$realToReturn['Nb'] = $value;
			$realToReturn['String'] = $key;
		}
	}
	return $realToReturn;
}

function ComputeBitch($NomSession)
{
	$query = "SELECT LoginEquipier, COUNT( * ) AS NbAppel
				FROM partie
				WHERE NomSession = '$NomSession'
				AND LoginEquipier <> 'Aucun'
				GROUP BY LoginEquipier
				ORDER BY NbAppel DESC
				LIMIT 0 , 1";
	$res = mysql_query($query);
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		$toReturn['Login'] = $row['LoginEquipier'];
		$toReturn['Nb'] = $row['NbAppel'];
	}
	return $toReturn;
}

function ComputePetiteBite($NomSession,&$LoginPetiteBite,&$PartiePetiteBite,&$ScorePetiteBite)
{
	$query = "SELECT LoginPreneur, ScorePartie, NbBoutsPartie, IDPartie
			FROM partie
			WHERE NomSession = '$NomSession'
			AND	ContratPartie = 'Petite'";
	$res = mysql_query($query);
	$ScorePartie = -1;
	while($row=mysql_fetch_array($res,MYSQL_ASSOC))
	{
		
		$Ecart = $row['ScorePartie'];
		if($row['NbBoutsPartie'] == 0)
			$Ecart = $Ecart - 56;
		else if($row['NbBoutsPartie'] == 1)
			$Ecart = $Ecart - 51;
		else if($row['NbBoutsPartie'] == 2)
			$Ecart = $Ecart - 41;
		else if($row['NbBoutsPartie'] == 3)
			$Ecart = $Ecart - 36;
	//	echo "login ". $row['LoginPreneur'] ." score : $Ecart nbBouts : ". $row['NbBoutsPartie'] . " IDPartie : ".$row['IDPartie']."<BR>";

		if($Ecart > 15 && $Ecart > $ScorePartie)
		{
			$ScorePartie = $Ecart;
			$PartiePetiteBite = $row['IDPartie'];
			$LoginPetiteBite = $row['LoginPreneur'];
			$ScorePetiteBite = $Ecart;
		}
	}
}

function IsCacheSessionAJour($NomSession)
{
	$query = "select CacheSessionAJour from session where NomSession='".$NomSession."'";
	$res = mysql_query($query);
    $row=mysql_fetch_array($res,MYSQL_ASSOC);

	return $row['CacheSessionAJour'] == 1;
}

function SetCacheSessionAJour($NomSession,$val)
{
	$query = "update session set CacheSessionAJour=$val where NomSession='".$NomSession."'";
	$res = mysql_query($query);
}

include("pChart/pData.class");  
include("pChart/pChart.class"); 

function RenderClassement($tabScores,$FileName)
{
	$DataSet = new pData;
	$i = 0;
	$nbGames = 0;
	foreach($tabScores as $play => $scores)
	{
		$i++;
		
		$DataSet->AddPoint($scores,"Serie".$i);
		$DataSet->AddSerie("Serie" . $i);
		$DataSet->SetSerieName($play,"Serie" . $i);
		$DataSet->SetAbsciseLabelSerie();
		$nbGames = count($scores);
	}
	$largeur = $nbGames * 20;
	if($largeur < 500)
		$largeur = 500;
	if($largeur > 1500)
		$Test = new pChart($largeur+40,530);
	else
		$Test = new pChart($largeur,530);  
	$Test->setFontProperties("Fonts/tahoma.ttf",8);  
	$Test->setGraphArea(40,10,$largeur - 20,530 - 30);  
	$Test->drawGraphArea(252,252,252);  
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);  
	$Test->drawGrid(4,TRUE,230,230,230,255);  
	$Test->setLineStyle(1,0);
	  
	// Draw the line graph  
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());  
	  
	// Finish the graph  
	$Test->setFontProperties("Fonts/tahoma.ttf",8);  
	$Test->drawLegend(45,35,$DataSet->GetDataDescription(),255,255,255);  
	if($largeur > 1500)
		$Test->DrawLegend($largeur-10, 35,$DataSet->GetDataDescription(),255,255,255);
	$Test->setFontProperties("Fonts/tahoma.ttf",10);  
	$Test->drawTitle(60,22,"Evolution des scores pendant la session",50,50,50,585);  
	$Test->Render($FileName);
}
function RenderContrats($NombreContrats,$FileNameContrats)
{
	// Dataset definition 
	$DataSet = new pData;
	$DataSet->AddPoint($NombreContrats,"Serie1");
	$DataSet->AddPoint(array("Petite","Garde","Garde sans","Garde contre"),"Serie2");
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Serie2");

	// Initialise the graph
	$Test = new pChart(420,250);
	$Test->drawFilledRoundedRectangle(7,7,413,243,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,415,245,5,230,230,230);
	$Test->createColorGradientPalette(0,255,0,255,0,0,4);

	// Draw the pie chart
	$Test->setFontProperties("Fonts/tahoma.ttf",8);
	$Test->AntialiasQuality = 0;
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,130,110,PIE_PERCENTAGE_LABEL,FALSE,50,20,5);
	$Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

	// Write the title
	$Test->setFontProperties("Fonts/MankSans.ttf",10);
	$Test->drawTitle(10,20,"Contrats pris",100,100,100);

	$Test->Render($FileNameContrats);
}

function RenderPrises($NbPrises,$FileNamePrises)
{
	// Dataset definition 
	$DataSet = new pData;
	$DataSet->AddPoint($NbPrises,"Serie1");
	$DataSet->AddPoint(array_keys($NbPrises),"Serie2");
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Serie2");

	// Initialise the graph
	$Test = new pChart(420,250);
	$Test->drawFilledRoundedRectangle(7,7,413,243,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,415,245,5,230,230,230);
	$Test->createColorGradientPalette(0,0,255,0,255,0,count($NbPrises));

	// Draw the pie chart
	$Test->setFontProperties("Fonts/tahoma.ttf",8);
	$Test->AntialiasQuality = 0;
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,130,110,PIE_PERCENTAGE_LABEL,FALSE,50,20,5);
	$Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

	// Write the title
	$Test->setFontProperties("Fonts/MankSans.ttf",10);
	$Test->drawTitle(10,20,"Prises par joueur",100,100,100);

	$Test->Render($FileNamePrises);
}

function getmicrotime(){ 
  list($usec, $sec) = explode(" ",microtime()); 
  return ((float)$usec + (float)$sec); 
}
function tarotdisconnect($db)
{
	mysql_close($db);
}

?>