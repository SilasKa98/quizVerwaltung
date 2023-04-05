<?php
    //default include text_en to get all_languages array
    include "../systemLanguages/text_en-Us.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <style>
        body{
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <br>
    <h1 style="text-align: center;">Create your Account</h1>
    <br>
    <form action="/quizVerwaltung/doTransaction.php" method="post">
        <div class="container-fluid" style="width: 80%;">

            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username">
                <label for="floatingInput">Username</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="firstname" class="form-control" id="floatingInput" placeholder="Firstname">
                <label for="floatingInput">Firstname</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="lastname" class="form-control" id="floatingInput" placeholder="Lastname">
                <label for="floatingInput">Lastname</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="mail" class="form-control" id="floatingInput" placeholder="E-Mail">
                <label for="floatingInput">E-Mail</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="pwd" class="form-control" id="floatingInput" placeholder="Password">
                <label for="floatingInput">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="pwd_repeat" class="form-control" id="floatingInput" placeholder="Repeat Password">
                <label for="floatingInput">Repeat Password</label>
            </div>
            <div class="form-floating mb-3">
                <select name="languageInput" class="form-select">
                    <?php foreach($all_languages as $key => $value){ ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                </select>
                <label for="floatingInput">Your Language</label>
            </div>
            <br>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="requestAdmin" role="switch" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault">Request Admin Account</label>
            </div>
            <input type="hidden" name="method" value="registerAccount">
            <br><br>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success" name="signup_submit">Sign Up</button><br><br>
            </div>
        </div>
    </form>
</body>
</html>