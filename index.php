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
  <p>hallo <?php echo $username ?></p>
  <form  method="post" action="doTransaction.php">
    <button class="button-5" type="submit" name="logout" role="button" style="float: right;"><?php echo $text_logout_btn?></button>
  </form>
  <form method="post" action="doTransaction.php">
    <select name="language" onchange="this.form.submit()">
      <?php
      echo $selectedLanguage;
        foreach($all_languages as $key => $value){
          echo "<option value='".$key."'"; if($key == $selectedLanguage){echo "selected='selected'";}  echo">".$value."</option>";
        }
      ?>
    </select>
  </form>
  <div class="container">
    <div class="center">
    <form method="post" action="insertQuestions.php">
      <input class="inputfile" type="file" name="inputFile" >
      <button class="button-5" type="submit" name="import"><?php echo $text_import_form["import_btn"]?></button>
      <button class="button-5" type="submit" name="clean" role="button"><?php echo $text_import_form["clean_db_btn"]?></button>
    </form>
    </div>
  </div>



<?php


include "frontend/questionSection.php";


?>


</body>
</html>