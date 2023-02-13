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
  $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $getAccountInfos->userLanguage;
  include "../systemLanguages/text_".$selectedLanguage.".php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="/quizVerwaltung/stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
    <?php include_once "navbar.php";?>

    <div class="container-fluid" style="width: 80%">

        <h1 id="usersSettingsHeader">User Settings</h1>

        <div class="card border-dark settingsCard" style="margin-top: 5%;">
            <div class="card-header"><?php echo $adminAccessRequestText; ?></div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $adminAccountHeader; ?></h5>
                <p class="card-text"><?php echo $adminAccountExampleText; ?></p>
                <button type="button" class="btn btn-outline-success" onclick="requestAdminAccess()"><?php echo $adminAccessRequestButton; ?></button>
            </div>
        </div>

        <div class="card border-danger settingsCard">
            <div class="card-header"><?php echo $deleteAccountText; ?></div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $deleteAccountHeader; ?></h5>
                <p class="card-text"><?php echo $deleteAccountExampleText; ?></p>
                <button type="button" class="btn btn-outline-danger" onclick="requestAccountDelete()"><?php echo $deleteAccountButton; ?></button>
            </div>
        </div>

    </div>


    <?php include_once "notificationToast.php";?>


    <!-- fly to card animation scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="/quizVerwaltung/scripts/flyToCartAnimation.js"></script>
    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
    <script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>

    <script>
        function requestAdminAccess(){
            let method = "requestAdminAccount";
            $.ajax({
                type: "POST",
                url: '/quizVerwaltung/doTransaction.php',
                data: {
                    method: method
                },
                success: function(response) {
                    toastMsgBody.innerHTML = "Request sent!";
                    $(".toast").toast('show');
                }
            });
        }

        function requestAccountDelete(){
            let text = "Are you sure that you want to permanently delete your Account ?";
            if (confirm(text) == false) {
                return;
            }
            let method = "deleteUserAccount";
            $.ajax({
                type: "POST",
                url: '/quizVerwaltung/doTransaction.php',
                data: {
                    method: method
                },
                success: function(response) {
                    location.href = "loginAccount.php";
                }
            });
        }
    </script>
</body>
</html>