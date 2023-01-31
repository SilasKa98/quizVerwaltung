<?php
    //default include text_en to get all_languages array
    include "../systemLanguages/text_en.php";

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
            <?php foreach($all_languages as $key => $value){ ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="method" value="registerAccount"><br><br>
        <input type="submit" name="signup_submit" value="Sign Up"><br><br>
    </form>
</body>
</html>