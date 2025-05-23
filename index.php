<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: frontend/loginAccount.php");
    exit();
  }
  extract($_SESSION["userData"]);


  //get the selected userLanguage to display the system in the right language
  include_once "services/mongoService.php";
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $getAccountInfos->userLanguage;
  include "systemLanguages/text_".$selectedLanguage.".php";

  //get all available Tags
  $allTagsObj = $mongo->findSingle("tags",[]);
  $allTagsArray = (array)$allTagsObj->allTags;

  //get the user FavoritTags
  $usersFavTagsArray = (array)$getAccountInfos->favoritTags;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/quizVerwaltung/stylesheets/general.css">
    <title>Quiz Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</head>
<body onload="getLatestQuestionsOfFollowedUsers();getPersonRecommendations()">

  <?php include_once "frontend/navbar.php";?>
  <div class="container-fluid">
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="card mb-3" id="profileCard" style="min-height: 200px;">
          <div class="row g-0 innerProfileDiv">
              <div class="col-md-4">
                  <img src="media/defaultAvatar.png" class="img-fluid rounded-start" id="profileAvatar" alt="Your Avatar">
              </div>
              <div class="col-md-8">
                  <div class="card-body">
                      <h5 class="card-title"><?php echo $welcomeTitel." ".$username ?></h5>
                      <p class="card-text infoAboutTags"><?php echo $profileInfoText?></p>
                      <div class="tagsWrapper">
                        <div class="btn-group" role="group" id="tags_holder" aria-label="Basic checkbox toggle button group">
                          <?php 
                            $counter = 0;
                            foreach($allTagsArray as $item){ 
                          ?>
                            <input type="checkbox" name="<?php echo $item;?>" class="btn-check" onclick="changeTagFilter(this)" id="btncheck<?php echo $counter;?>">
                            <label class="btn btn-outline-secondary tagsBtn <?php if(in_array($item,$usersFavTagsArray)){ echo "tagIsSelected" ;} ?> " for="btncheck<?php echo $counter;?>"><?php echo $item;?></label>
                          <?php
                            $counter++;
                            } 
                          ?>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
      
      <?php if(!isset($_SESSION["user_is_guest"])){?>
        <div class="col">
          <div class="card mb-3" id="profileCard" style="min-height: 267px;">
            <div class="row g-0 innerProfileDiv">         
              <h5 class="card-title"><?php echo $latestQuestionsFollowing; ?></h5>
                <div class="card-body" id="showRecentBody">
                  <div id="cardHolder">
                  </div>
                  <div id="personsRecommendation">
                    <br><h5><?php echo $personsYouMayKnow; ?></h5>
                    <div id="recommendationPersonHolder">
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      <?php }else{?>

        <div class="col">
          <div class="card mb-3" id="profileCard" style="min-height: 267px;">
            <div class="row g-0 innerProfileDiv">         
              <p style="text-align:center;margin-top: 8%; font-size: 14pt;">You are currently using a Guest account. With a Guest account you can view and browse our platform. 
                However we highly recommend to create your own account so you have full access to all our features. </p>
            </div>
          </div>
        </div>

      <?php }?>
    </div>
  </div>
 

<?php include_once "frontend/questionSection.php";?>


  </div>
<!--end if container-fluid-->


<?php include_once "frontend/notificationToast.php";?>



<script type="text/javascript">
    //script to track if the user is active atm, then inserts the current timestamp in the database     
    var timeout;
    var delay = 2000;   // 2s
    document.addEventListener("mousemove", function(e) {
        if(timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function() {
            <?php
                //update the last user activity time
                $currentTimestamp = time();
                $currentUserFilter = (['userId' => $_SESSION["userData"]["userId"]]);
                $updateLastActivityTimestamp = ['$set' =>  ['lastActivityTimestamp'=> $currentTimestamp]];
                $mongo->updateEntry("accounts",$currentUserFilter,$updateLastActivityTimestamp); 
            ?>
        }, delay);
    });

    document.addEventListener("keypress", function(e) {
        if(timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function() {
            <?php
                //update the last user activity time
                $currentTimestamp = time();
                $currentUserFilter = (['userId' => $_SESSION["userData"]["userId"]]);
                $updateLastActivityTimestamp = ['$set' =>  ['lastActivityTimestamp'=> $currentTimestamp]];
                $mongo->updateEntry("accounts",$currentUserFilter,$updateLastActivityTimestamp); 
            ?>
        }, delay);
    });

  </script>

  <!-- fly to card animation scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="/quizVerwaltung/scripts/flyToCartAnimation.js"></script>
  <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
  <script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>

</body>

</html>


