<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: frontend/loginAccount.php");
    exit();
  }
  extract($_SESSION["userData"]);

  //get the selected userLanguage to display the system in the right language
  include_once "mongoService.php";
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $selectedLanguage->userLanguage;
  include "systemLanguages/text_".$selectedLanguage.".php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="general.css">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</head>
<body>

  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="media/logo.jpg" alt="Logo" width="60" height="48" class="d-inline-block align-text-top">
            <p id="headerName">Quiz Verwaltung</p>
        </a>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion ?></a>
                <a class="nav-link" href="#">Bar</a>
                <a class="nav-link" href="#">Help</a>
            </div>
        </div>
    </div>
  </nav>

  <form  method="post" action="doTransaction.php" id="logoutForm">
    <button class="button-5" type="submit" name="logout" role="button" style="float: right;"><?php echo $text_logout_btn?></button>
  </form>
  <form method="post" action="doTransaction.php" id="languageForm">
    <select name="language" onchange="this.form.submit()">
      <?php
      echo $selectedLanguage;
        foreach($all_languages as $key => $value){
          echo "<option value='".$key."'"; if($key == $selectedLanguage){echo "selected='selected'";}  echo">".$value."</option>";
        }
      ?>
    </select>
  </form>
  <div class="container-fluid">

    <div class="card mb-3" id="profileCard" style="max-width: 540px;">
      <div class="row g-0 innerProfileDiv">
          <div class="col-md-4">
              <img src="media/defaultAvatar.png" class="img-fluid rounded-start" id="profileAvatar" alt="Your Avatar">
          </div>
          <div class="col-md-8">
              <div class="card-body">
                  <h5 class="card-title"><?php echo $welcomeTitel." ".$username ?></h5>
                  <p class="card-text">Here could be some information about the user or other links to something.</p>
                  <p class="card-text"><small class="text-muted" id="smallProfileCardText">Last updated 3 mins ago</small></p>
              </div>
          </div>
      </div>
    </div>
    <!--Moved to frontend_insertQuestion.php-->
    <!--
    <div class="container">
      <div class="center">
        <form method="post" action="insertQuestions.php">
          <input class="inputfile" type="file" name="inputFile" >
          <button class="button-5" type="submit" name="import"><?php echo $text_import_form["import_btn"]?></button>
          <button class="button-5" type="submit" name="clean" role="button"><?php echo $text_import_form["clean_db_btn"]?></button>
        </form>
      </div>
    </div>
    -->

<?php
include "frontend/questionSection.php";
?>

  </div>
</body>
</html>