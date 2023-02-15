<?php
    session_start();
    if(!$_SESSION["logged_in"]){
        header("Location: loginAccount.php");
        exit();
    }
    extract($_SESSION["userData"]);


    include_once "../mongoService.php";
    include_once "../questionService.php";
    include_once "../printService.php";

    

    $mongo = new MongoDBService();
    $question = new QuestionService();
    $printer = new Printer();

    //get the selected userLanguage to display the system in the right language
    $filterQuery = (['userId' => $userId]);
    $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
    $selectedLanguage = $selectedLanguage->userLanguage;
    include "../systemLanguages/text_".$selectedLanguage.".php";


    //get the current/visited profile
    $visitedUserprofile = $_GET["profileUsername"];
    $filterQueryUserprofile = (['username' => $visitedUserprofile]);
    $foundProfile= $mongo->findSingle("accounts",$filterQueryUserprofile,[]);


    //get all catalogs of this user
    $filterQueryCatalogs = ['author' => $foundProfile->username];
    $usersCatalogs = $mongo->read("catalog", $filterQueryCatalogs);
    $readyForPrintCatalogs = [];
    foreach ($usersCatalogs as $doc) {
        array_push($readyForPrintCatalogs,$doc);
    }

    //get all questions of this user
    $filterQuery = ['author' => $foundProfile->username];
    $usersQuestions = $mongo->read("questions", $filterQuery);

    $readyForPrintQuestions = [];
    $totalKarmaEarned = 0;
    $totalQuestionsSubmitted = 0;
    foreach ($usersQuestions as $doc) {
        $fetchedQuestion = $question->parseReadInQuestion($doc);
        array_push($readyForPrintQuestions,$fetchedQuestion);

        //total Karma calculation
        $totalKarmaEarned += $doc->karma;

        //total Questions calculation
        $totalQuestionsSubmitted++;
    }

    //check if currentUser is following the visited user
    $followerOfVisitedProfile = (array)$foundProfile->follower;
    $currentUserIsFollower = array_search($userId,$followerOfVisitedProfile);
    $userIsFollowing = false;
    if($currentUserIsFollower !== false){
        //currentUser is follower of the visited profile/user
        $userIsFollowing = true;
    }

    //get count of the follower/following of the visited Profile
    $visitedProfileFollowerCount = count((array)$foundProfile->follower);
    $visitedProfileFollowingCount = count((array)$foundProfile->following);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $foundProfile->username;?></title>

    <link rel="stylesheet" href="/quizVerwaltung/stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>

    <?php include_once "navbar.php";?>


    <?php include_once "modal_insertNewQuestionLang.php";?>
    <?php include_once "modal_showFollower.php";?>
    <?php include_once "modal_showFollowing.php";?>
  

    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="../media/defaultAvatar.png" alt="avatar"
                    class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-3"><?php echo $foundProfile->username;?></h5>
                    <p class="text-muted mb-1">Full Stack Developer</p>
                    <p class="text-muted mb-4"><?php echo $userJoinDateInfo." ".$foundProfile->joinDate; ?></p>
                    <div class="d-flex justify-content-center mb-2">
                    <?php if($foundProfile->userId != $userId){ ?>
                        <?php if($userIsFollowing){ ?>
                            <button type="button" id="followBtn" class="btn btn-success" onclick="follow('<?php echo $foundProfile->userId;?>')"><?php echo $followedBtnText;?></button>
                        <?php }else{ ?>
                            <button type="button" id="followBtn" class="btn btn-primary" onclick="follow('<?php echo $foundProfile->userId;?>')"><?php echo $notFollowedBtnText;?></button>
                        <?php } ?>
                        <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                    <?php } ?>
                    </div>
                </div>
                </div>
                <div class="card mb-4 mb-lg-0">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush rounded-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="/quizVerwaltung/media/arrows-up-down.svg" width="10px">
                        <p class="mb-0"><?php echo $userTotalKarmaOwned." <b>".$totalKarmaEarned; ?></b></p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="/quizVerwaltung/media/comments.svg" width="23px">
                        <p class="mb-0"><?php echo $userTotalQuestionsSubmitted." <b>".$totalQuestionsSubmitted; ?></b></p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="/quizVerwaltung/media/users.svg" width="23px" >
                        <button id="showFollowerBtn" onclick="showFollower('<?php echo $foundProfile->userId;?>')">
                            <p class="mb-0">
                                <?php echo $showFollowerText." ".$visitedProfileFollowerCount; ?>
                            </p>
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="/quizVerwaltung/media/users-rays.svg" width="23px" > 
                        <?php if($foundProfile->userId == $userId){?>
                            <button id="showFollowingBtn" onclick="showFollowing('<?php echo $foundProfile->userId;?>')">
                        <?php }?>
                            <p class="mb-0">
                                <?php echo $showFollowingText." ".$visitedProfileFollowingCount;?>
                            </p>
                        <?php if($foundProfile->userId == $userId){?>
                            </button>
                        <?php }?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="/quizVerwaltung/media/lightbulb.svg" width="18px" > 
                        <p class="mb-0">Beste Frage</p>
                    </li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo $fullNameField; ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $foundProfile->firstname." ".$foundProfile->lastname;?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">E-mail</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $foundProfile->mail; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo $languageField; ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $foundProfile->userLanguage; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card mb-4">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link <?php if($_GET["section"] == "questions"){ echo "active";}?>" aria-current="page" href="userProfile.php?profileUsername=<?php echo $visitedUserprofile;?>&section=questions"><?php echo $questionsTabText;?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if($_GET["section"] == "catalog"){ echo "active";}?>" href="userProfile.php?profileUsername=<?php echo $visitedUserprofile;?>&section=catalog"><?php echo $catalogsTabText;?></a>
                            </li>
                        </ul>
                        <div class="card-body" id="userprofileQuestionWrapper">
                            <?php
                            if($_GET["section"] == "questions"){
                                foreach ($readyForPrintQuestions as $doc) {
                                    $printer->printQuestion($doc);
                                }

                                if(count($readyForPrintQuestions) == 0){
                                    echo"<p id='noQuestionsYetText'>".$foundProfile->username." ".$userHasNoQuestionsYet."</p>";
                                }
                            }
                            if($_GET["section"] == "catalog"){
                                foreach ($readyForPrintCatalogs as $doc) {
                                    $printer->printCatalog($doc);
                                }

                                if(count($readyForPrintCatalogs) == 0){
                                    echo"<p id='noQuestionsYetText'>".$foundProfile->username." ".$userHasNoCatalogsYet."</p>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </div>
    </section>


    <?php include_once "notificationToast.php";?>

    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
    <script>

        function follow(followedUserId){
            let method = "changeFollower";
            $.ajax({
                type: "POST",
                url: '/quizVerwaltung/doTransaction.php',
                data: {
                    followedUserId: followedUserId,
                    method: method
                },
                success: function(response) {
                    if(response == "Illegal chars detected!" || response == "User is not existing!"){
                        toastMsgBody.innerHTML = response;
                        $(".toast").toast('show');
                        return;
                    }
                    console.log(response);
                    //change the color of the button without reload for the moment...
                    //the permanent display handling is done above with php inside of the html
                    if(response == "isFollowing"){
                       document.getElementById("followBtn").style.backgroundColor = "#157347"; 
                       document.getElementById("followBtn").style.borderColor = "#264026"; 
                       document.getElementById("followBtn").innerHTML = <?php echo json_encode($followedBtnText); ?>; 
                    }else{
                        document.getElementById("followBtn").style.backgroundColor = "#0d6efd"; 
                        document.getElementById("followBtn").style.borderColor = "#0d6efd"; 
                        document.getElementById("followBtn").innerHTML = <?php echo json_encode($notFollowedBtnText); ?>; 
                    }
                    
                }
            });
        }

        function showFollower(profileUserId){
            let method = "showFollower";
            $.ajax({
                type: "POST",
                url: '/quizVerwaltung/doTransaction.php',
                data: {
                    profileUserId: profileUserId,
                    method: method
                },
                success: function(response) {
                    $('#showFollower').modal('toggle');
                    let jsonResponse = JSON.parse(response);   
                    let followerModalBody = document.getElementById("followerModalBody");
                    let followerUsernames = jsonResponse.followerUsernames;
                    let followerFirstnames = jsonResponse.followerFirstnames;
                    let followerLastnames = jsonResponse.followerLastnames;
                    followerModalBody.innerHTML = "";
                    for(let i=0;i<followerUsernames.length;i++){
                        followerModalBody.innerHTML += "<a class='followerLink' href='/quizVerwaltung/frontend/userProfile.php?profileUsername="+followerUsernames[i]+"&section=questions'><p>"+
                                                            followerFirstnames[i]+" "+followerLastnames[i]+" @"+followerUsernames[i]+
                                                            "<img class='searchResultMiniPicture' src='/quizVerwaltung/media/defaultAvatar.png' width=20px>"
                                                        "</p></a>";
                    }
                }
            });
        }

        function showFollowing(profileUserId){
            let method = "showFollowing";
            $.ajax({
                type: "POST",
                url: '/quizVerwaltung/doTransaction.php',
                data: {
                    profileUserId: profileUserId,
                    method: method
                },
                success: function(response) {
                    $('#showFollowing').modal('toggle');
                    let jsonResponse = JSON.parse(response);   
                    console.log(jsonResponse);
                    let followingModalBody = document.getElementById("followingModalBody");
                    let followingUsernames = jsonResponse.followingUsernames;
                    let followingFirstnames = jsonResponse.followingFirstnames;
                    let followingLastnames = jsonResponse.followingLastnames;
                    followingModalBody.innerHTML = "";
                    for(let i=0;i<followingUsernames.length;i++){
                        followingModalBody.innerHTML += "<a class='followerLink' href='/quizVerwaltung/frontend/userProfile.php?profileUsername="+followingUsernames[i]+"&section=questions'><p>"+
                                                            followingFirstnames[i]+" "+followingLastnames[i]+" @"+followingUsernames[i]+
                                                            "<img class='searchResultMiniPicture' src='/quizVerwaltung/media/defaultAvatar.png' width=20px>"
                                                        "</p></a>";
                    }
                }
            });
        }
    </script>

    <!-- fly to card animation scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="/quizVerwaltung/scripts/flyToCartAnimation.js"></script>
<script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>
</body>
</html>