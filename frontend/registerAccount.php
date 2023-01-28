<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
</head>
<body>
    <form action="../doTransaction.php" method="post">
        <input type="text" name="username" placeholder="Benutzername..."><br><br>
        <input type="text" name="mail" placeholder="E-mail..."><br><br>
        <input type="password" name="pwd" placeholder="Passwort..."><br><br>
        <input type="password" name="pwd_repeat" placeholder="Passewort wiederholen..."><br><br>
        <input type="hidden" name="method" value="registerAccount"><br><br>
        <input type="submit" name="signup_submit" value="Registrieren"><br><br>
    </form>
</body>
</html>