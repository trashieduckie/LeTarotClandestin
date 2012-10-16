<?php
session_start();
?>
<html>
<head>
<title>Login</title>
</head>
<body>
<form action = "login.php" method="post">
  <p>
     	<BR>
     	Nom d'utilisateur :
	<BR>
	<INPUT TYPE="text" NAME="login" ROWS="1" COLS="20">
 	</INPUT>
	<BR>
	Mot de passe :
	<BR>
	<INPUT TYPE="password" NAME="pass" ROWS="1" COLS="20">
	</INPUT> 


        <input type="submit" name = "Button" value ="Poster">
  </p>
</form>
<p>&nbsp;</p>
<?php
	include 'db.php';
	if(isset($_POST['Button']))
	{
		$db = tarotconnect();
		$log = connectUser($_POST['login'], $_POST['pass']);
		if($log == 1)
		{
			$login = $_POST['login'];
			$_SESSION['nom'] = $_POST['login'];	
			echo '<meta http-equiv="refresh" content="0;URL=./accueil.php">';
		}
		else
		{
			unset($_SESSION['nom']);
			echo "<BR>Mot de passe ou nom d'utilisateur invalide";
		}
		tarotdisconnect($db);
	}
	

	
?>
</body>

</html>