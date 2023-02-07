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

  //get all available Tags
  $allTagsObj = $mongo->findSingle("tags",[],[]);
  $allTagsArray = (array)$allTagsObj->allTags;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/general.css">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
<?php include_once "frontend/navbar.php";?>
  <!--//TODO vom letzen merge konflikt hier nochmal genauer anschauen wie wir das machen !!!!-->
  <div class="container-fluid">
      
    <div class="row row-cols-1 row-cols-md-3 g-4">

      <div class="col">
        <div class="card mb-3" id="profileCard" style="max-width: 540px;min-height: 200px;">
          <div class="row g-0 innerProfileDiv">
              <div class="col-md-4">
                  <img src="media/defaultAvatar.png" class="img-fluid rounded-start" id="profileAvatar" alt="Your Avatar">
              </div>
              <div class="col-md-8">
                  <div class="card-body">
                      <h5 class="card-title"><?php echo $welcomeTitel." ".$username ?></h5>
                      <p class="card-text"><?php echo $profileInfoText?></p>
                      <p class="card-text"><small class="text-muted" id="smallProfileCardText"><?php echo $profileMiniText?></small></p>
                  </div>
              </div>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card mb-3" id="profileCard" style="max-width: 540px;min-height: 200px;">
          <div class="row g-0">
              <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">Adjust Landingpage Filters</h5>
                    <div class="tagsWrapper">
                      <?php foreach($allTagsArray as $item){ ?>
                        <span class="badge rounded-pill text-bg-secondary"><?php echo $item;?></span>
                      <?php } ?>
                    </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
      
    </div>

<?php include "frontend/questionSection.php";?>
  </div>
<!--end if container-fluid-->



<?php include_once "frontend/notificationToast.php";?>


<!-- fly to card animation scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="/quizVerwaltung/scripts/flyToCartAnimation.js"></script>
<script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>
</body>

<script>
  /*
  $(document).ready(function(e) {
    var timeout;
    var delay = 800;   // 800ms

    $('#searchInSystem').keyup(function(e) {
      if(timeout) {
          clearTimeout(timeout);
      }
      timeout = setTimeout(function() {
          searchInSystem(e);
      }, delay);
    });

    function searchInSystem(e) {
      let method = "searchInSystem";
      $.ajax({
        type: "POST",
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            value: e.target.value,
            method: method
        },
        success: function(response) {
          console.log("response:");
          console.log(response);
          let jsonResponse = JSON.parse(response);
          console.log(jsonResponse);
          console.log("save successfull");
          let searchResultList = document.getElementById("searchResultsFound");
          let allMatchingIdsArray = jsonResponse.allMatchingIds;
          console.log(allMatchingIdsArray);
          searchResultList.innerHTML = "";
          for(let i=0;i<allMatchingIdsArray.length;i++){
            searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+jsonResponse.authorsOfTheMatches[i]+'&searchedQuestionId='+jsonResponse.allMatchingIds[i]+'#searchFocus" class="list-group-item list-group-item-action">'+jsonResponse.allMatchingQuestionStrings[i]+'<span class="badge rounded-pill bg-primary searchInnerKarmaPill">'+jsonResponse.KarmaOfTheMatches[i]+'</span></a>';
          }
          if(allMatchingIdsArray.length == 0){
            searchResultList.innerHTML += '<span class="list-group-item list-group-item-action"><?php echo json_encode($noSearchMatches); ?></span>';
          }
        }
      });
    }
  });
  */
</script>


</html>


