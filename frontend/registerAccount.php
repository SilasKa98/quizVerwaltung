<?php
    $selectedLanguage = "de";
    include "../systemLanguages/text_".$selectedLanguage.".php";
?>

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
        <input type="text" name="username" placeholder="Username"><br><br>
        <input type="text" name="mail" placeholder="Mail"><br><br>
        <input type="password" name="pwd" placeholder="Password"><br><br>
        <input type="password" name="pwd_repeat" placeholder="Repeat Password"><br><br>
        <select name="language">
            <option value="de">German</option>
            <option value="en">English</option>
            <option value="es">Spanish</option>
        </select>
        <input type="hidden" name="method" value="registerAccount"><br><br>
        <input type="submit" name="signup_submit" value="Sign Up"><br><br>
    </form>
</body>
</html>