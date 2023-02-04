<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: loginAccount.php");
    exit();
  }
  extract($_SESSION["userData"]);
  //get the selected userLanguage to display the system in the right language
  include_once "../mongoService.php";
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $selectedLanguage->userLanguage;
  include "../systemLanguages/text_".$selectedLanguage.".php";

  //get all available tags
  $filterQueryTags = (['id' => '1234']);
  $allTagsObj= $mongo->findSingle("tags",$filterQueryTags,[]);
  $allTagsArray = (array)$allTagsObj->allTags;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container-fluid">
        <div class="container">
            <div class="center">
                <form method="post" action="../insertQuestions.php">
                    <input class="inputfile" type="file" name="inputFile" >
                    <input type="hidden" name="allTags" value="<?php echo implode(',',$allTagsArray) ?>">
                    <button class="button-5" type="submit" name="import"><?php echo $text_import_form["import_btn"]?></button>
                    <button class="button-5" type="submit" name="clean" role="button"><?php echo $text_import_form["clean_db_btn"]?></button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>