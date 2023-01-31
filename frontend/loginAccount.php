<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../doTransaction.php" method="post">
		<input type="text" name="mailuid" placeholder="E-mail/Benutzername...">
		<input type="password" name="pwd" placeholder="Passwort...">
        <input type="hidden" name="method" value="loginAccount">
		<input type="submit" name="login_submit" value="Einloggen">
	</form>
    <br>
    <a href="registerAccount.php">No Account yet? Sign up now!</a>
</body>
</html>