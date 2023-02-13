<?php
/**
 * All sorts of calls to the different services get handeled in this file
 * 
 * 
 */

include_once "mongoService.php";
include_once "translationService.php";
include_once "questionService.php";


$question = new QuestionService();
$mongo = new MongoDBService();

//hardcoding "de" as targetLanguage, because its not needed for this case..but it must be set
$getQuestionsTranslator = new TranslationService("de");


if(isset($_POST["method"]) && $_POST["method"] == "insertNewLanguage"){

    //maybe later fetch it from somewhere?
    #$allSupportedLanguages = ["de","en-Us","es"];

    $allSupportedLanguages = $getQuestionsTranslator->getAllTargetLanguageCodes();

    //catching falsly given values from the user 
    if(!in_array($_POST["selLanguage"],$allSupportedLanguages)){
        echo "Illegal target language!";
        exit();
    }

    $filterQuery = (['id' => $_POST["id"]]);
    $options = [];
    $sourceLanguage = $_POST["sourceLanguage"];
    $targetLanguage = $_POST["selLanguage"];
    $objToTranslate = $mongo->read("questions",$filterQuery,$options);
    foreach ($objToTranslate as $doc) {
        $fetchedQuestion = $question -> parseReadInQuestion($doc);
    }
    
    //check if question is already translated in this language
    if(array_key_exists($_POST["selLanguage"],$fetchedQuestion[0]->question)){
        echo "Translation already exists!";
        exit();
    }

    if(isset($fetchedQuestion[0]->question)){
        $newQuestion = [$sourceLanguage => $fetchedQuestion[0]->question->$sourceLanguage];
        $translation = new TranslationService($_POST["selLanguage"]);
        $translatedQuestion = $translation->translateObject($fetchedQuestion,$sourceLanguage);
        $newLangQuestion = $translatedQuestion[0]->question->$sourceLanguage;

        //first get all question versions that exist
        $searchDbEntry = $mongo->findSingle("questions",$filterQuery,[]);
        //cast it to array so the new field can get added
        $searchDbEntry = (array)$searchDbEntry["question"];
        //and then add the new language version
        $searchDbEntry[$targetLanguage] = $newLangQuestion;
        $update = ['$set' =>  ['question'=> $searchDbEntry]];
        $mongo->updateEntry("questions",$filterQuery,$update);
    }

    if(isset($fetchedQuestion[0]->options)){
        print_r($translatedQuestion);
        $newOptions = (array)$translatedQuestion[0]->options->$sourceLanguage;

        //first get all question versions that exist
        $searchDbEntryOptions = $mongo->findSingle("questions",$filterQuery,[]);
        //cast it to array so the new field can get added
        $searchDbEntryOptions = (array)$searchDbEntryOptions["options"];
        //and then add the new language version
        $searchDbEntryOptions[$targetLanguage] = $newOptions;
        $updateOptions = ['$set' =>  ['options'=> $searchDbEntryOptions]];
        $mongo->updateEntry("questions",$filterQuery,$updateOptions);
    }

    //at this point the whole object is translated, now it can be taken apart and only the needed parts can be inserted into the database into the right object (object with id xxxx)
    //same as done obove with the question

    //TODO options field 
    exit();
}


/**
* Insert + increase/decrease Karma and also check+handling if and how user already voted for this question is checked in here
*/
if(isset($_POST["method"]) && $_POST["method"] == "changeKarma"){
    include_once "karmaService.php";
    $karma = new KarmaService();
    $currentKarma = $karma->getCurrentKarma($_POST["id"]);
    session_start();
    $currentUserId = $_SESSION["userData"]["userId"];

    $searchUserFilter = (['userId'=>$currentUserId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);

    //shouldnt be happening/needed, however still checking it for safty
    if(!isset($searchUser)){
        exit();
    }

    $filterQueryQuestions = (['id' => $_POST["id"]]);

    if($_POST["job"] == "increaseKarma"){
        $currentHandle = "up";
        $oppositHandle = "down";
    }else{
        $currentHandle = "down";
        $oppositHandle = "up";
    }


    //search for the questionId in the karma->up array
    $isQuestionVoteExisting = array_search($_POST["id"],(array)$searchUser["questionsUserGaveKarmaTo"][$currentHandle]);
    
    if($isQuestionVoteExisting === false){
        $newKarmaArray = (array)$searchUser["questionsUserGaveKarmaTo"][$currentHandle];
        array_push($newKarmaArray,$_POST["id"]);
        $fieldQuery = "questionsUserGaveKarmaTo.".$currentHandle;
        $updateUserKarma = ['$set' =>  [$fieldQuery=> $newKarmaArray]];
        $mongo->updateEntry("accounts",$searchUserFilter,$updateUserKarma);

        
        //search in other vote category(up/down) for existence
        $isQuestionExistingInOppositVote = array_search($_POST["id"],(array)$searchUser["questionsUserGaveKarmaTo"][$oppositHandle]);
        //if found delete it and give a additional upvote to correct the previous entry
        $correctPreviousSelection = false;
        if($isQuestionExistingInOppositVote !== false){
            $oppositKarmaArray = (array)$searchUser["questionsUserGaveKarmaTo"][$oppositHandle];
            unset($oppositKarmaArray[$isQuestionExistingInOppositVote]);
            $fieldQueryOppositDel = "questionsUserGaveKarmaTo.".$oppositHandle;
            $updateUserKarmaOpposit = ['$set' =>  [$fieldQueryOppositDel=> $oppositKarmaArray]];
            $mongo->updateEntry("accounts",$searchUserFilter,$updateUserKarmaOpposit);

            //example: if the question already got upvoted from the user and the he downvotes, there is needed a additional -1 to neutralize the previous upvote 
            //karmaAddition is applied below, before the new karma value gets inserted
            if($_POST["job"] == "increaseKarma"){
                $karmaAddition = 1;
            }else{
                $karmaAddition = -1;
            }
        }


        //increase/decrease the Karma int of the quesition (karma of the question in the questions collection)
        if($_POST["job"] == "increaseKarma"){
            $newKarma = $karma->increaseKarma($_POST["id"]);
        }else{
            $newKarma = $karma->decreaseKarma($_POST["id"]);
        }
        

        if(isset($karmaAddition)){
            $newKarma = $newKarma + $karmaAddition;
        }

        //echo is needed for displaying the increase
        echo $newKarma;
        $update = ['$set' =>  ['karma'=> $newKarma]];
        $mongo->updateEntry("questions",$filterQueryQuestions,$update); 
    }else{
        $neutralizeKarmaArray = (array)$searchUser["questionsUserGaveKarmaTo"][$currentHandle];
        unset($neutralizeKarmaArray[$isQuestionVoteExisting]);
        $fieldQueryNeutralize = "questionsUserGaveKarmaTo.".$currentHandle;
        $updateUserKarmaNeutralize = ['$set' =>  [$fieldQueryNeutralize=> $neutralizeKarmaArray]];
        $mongo->updateEntry("accounts",$searchUserFilter,$updateUserKarmaNeutralize);

        if($_POST["job"] == "increaseKarma"){
            $newKarma = $karma->decreaseKarma($_POST["id"]);
        }else{
            $newKarma = $karma->increaseKarma($_POST["id"]);
        }
        //echo is needed for displaying the correct Karma
        echo $newKarma;
        $update = ['$set' =>  ['karma'=> $newKarma]];
        $mongo->updateEntry("questions",$filterQueryQuestions,$update); 
    }
}


if(isset($_POST["method"]) && $_POST["method"] == "registerAccount"){
    include_once "accountService.php";
    $account = new AccountService();
    $account->register($_POST["username"],$_POST["mail"],$_POST["pwd"],$_POST["pwd_repeat"],$_POST["languageInput"],$_POST["firstname"],$_POST["lastname"], $_POST["requestAdmin"]);
}


if(isset($_POST["method"]) && $_POST["method"] == "loginAccount"){
    include_once "accountService.php";
    $account = new AccountService();
    $account->login($_POST["mailuid"],$_POST["pwd"]);
}


if(isset($_POST["logout"])){
    include_once "accountService.php";
    $account = new AccountService();
    $account->logout();
}


if(isset($_POST["language"])){
    include_once "accountService.php";
    $account = new AccountService();
    session_start();
    $account->changeLanguage($_POST["language"], $_SESSION["userData"]["userId"]);
    header("Location: ".$_POST["destination"]);
}


if(isset($_POST["method"]) && $_POST["method"] == "changeQuestionLanguageRelation"){
    session_start();
    $currentUserId = $_SESSION["userData"]["userId"];
    $searchUserFilter = (['userId'=>$currentUserId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);


    /*
    *search the given inputs in the db to check if they are valid
    *START OF VALIDATION
    */
        $searchQuestionIdFilter = (['id'=>$_POST["questionId"]]);
        $searchQuestionId = $mongo->findSingle("questions",$searchQuestionIdFilter,[]);
        if(!isset($searchQuestionId)){
            echo "Illegal Id given!";
            exit();
        }

        //maybe later fetch it from somewhere?
        #$allSupportedLanguages = ["de","en-Us","es"];

        $allSupportedLanguages = $getQuestionsTranslator->getAllTargetLanguageCodes();

        if(!in_array($_POST["questionLang"],$allSupportedLanguages)){
            echo "Illegal language given!";
            exit();
        }
    /*
    *END OF VALIDATION
    */


    $questionLangUserRelation = (array)$searchUser["questionLangUserRelation"];

    $isQuestionPresent = array_search($_POST["questionId"],$questionLangUserRelation);

    if($isQuestionPresent !== false){
        unset($questionLangUserRelation[$isQuestionPresent]);
    }

    //first get all current relations that exist
    $checkRelationEntrys = $searchUser;
    //cast it to array and select the field of the object so the new field can get added
    $checkRelationEntrys = $questionLangUserRelation;
    // add the new id lang relation
    $checkRelationEntrys[$_POST["questionLang"]] = $_POST["questionId"];
    $update = ['$set' =>  ['questionLangUserRelation'=> $checkRelationEntrys]];
    $mongo->updateEntry("accounts",$searchUserFilter,$update);

    if(isset($searchQuestionId["options"][$_POST["questionLang"]])){
        $ajaxResponse = [
            "question"=>$searchQuestionId["question"][$_POST["questionLang"]],
            "options"=>(array)$searchQuestionId["options"][$_POST["questionLang"]]
        ];
    }else{
        $ajaxResponse = [
            "question"=>$searchQuestionId["question"][$_POST["questionLang"]]
        ];
    }
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "finalizeImport"){
    include_once "versionService.php";
    $version = new VersionService();
    $version->setVersion("1.0");
    $version = $version->version;
    session_start();
    $fetchedData = $_POST["allQuestions"];
    foreach($fetchedData as $key => $value){
        $question = $value["question"];
        $language = $value["language"];
        if(isset($value["options"])){
            $options = explode(",",$value["options"]);
            unset($value["options"]);
            $value["options"] = [$language=>$options];
        }
        if(isset($value["tags"])){
            $tags = explode(",",$value["tags"]);
            $value["tags"] = $tags;
        }
        unset($value["question"]);
        unset($value["language"]);
        $value["question"] = [$language=>$question];
        $value["id"] = uniqid();
        $value["creationDate"] = date("Y-m-d");
        $value["modificationDate"] = "";
        $value["version"] = $version;
        $value["karma"] = 0;
        $value["author"] = $_SESSION["userData"]["username"];
        $value["verification"] = "";
        print_r($value);
        $mongo->insertMultiple("questions",[$value]);
    }
}


if(isset($_POST["method"]) && $_POST["method"] == "changeFollower"){
    session_start();
    $userThatHasBeenFollowed = $_POST["followedUserId"];
    $currentUserId = $_SESSION["userData"]["userId"];

    //check for security reasons if the followedUserId contains any illegal chars or if it is existing at all
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($userThatHasBeenFollowed))){
        echo "Illegal chars detected!";
        exit();
    }
    $searchUserThatHasBeenFollowed = (['userId'=>$userThatHasBeenFollowed]);
    $searchUserThatHasBeenFollowed = $mongo->findSingle("accounts",$searchUserThatHasBeenFollowed,[]);
    if(!isset($searchUserThatHasBeenFollowed)){
        echo "User is not existing!";
        exit();
    }
    /**
     * Handle the "following" proccess
     */
        $searchCurrentUserFilter = (['userId'=>$currentUserId]);
        $searchCurrentUser = $mongo->findSingle("accounts",$searchCurrentUserFilter,[]);

        $currentUserFollowing = (array)$searchCurrentUser->following;

        //check if user already following, if so delete him to unfollow, if not push the new follower in the array
        $alreadyFollowing = array_search($userThatHasBeenFollowed,$currentUserFollowing);
        if($alreadyFollowing === false){
            $currentUserIsFollowing = true;
            array_push($currentUserFollowing, $userThatHasBeenFollowed);
        }else{
            unset($currentUserFollowing[$alreadyFollowing]);
        }

        $update = ['$set' =>  ['following'=> $currentUserFollowing]];
        $mongo->updateEntry("accounts",$searchCurrentUserFilter,$update); 
    /**
     * end of handling the "following" proccess
     */

     

    /**
     * Handle the "follower" proccess
     */
        $searchFollowedUserFilter = (['userId'=>$userThatHasBeenFollowed]);
        $searchFollowedUser = $mongo->findSingle("accounts",$searchFollowedUserFilter,[]);

        $follwerOftheFollowedUser = (array)$searchFollowedUser->follower;

        //check if user currentUser is already follower of the followedUser, if so delete him to unfollow, if not push the new follower (currentUser) in the array
        $alreadyFollower = array_search($currentUserId,$follwerOftheFollowedUser);
        if($alreadyFollower === false){
            array_push($follwerOftheFollowedUser, $currentUserId);
        }else{
            unset($follwerOftheFollowedUser[$alreadyFollower]);
        }


        $update = ['$set' =>  ['follower'=> $follwerOftheFollowedUser]];
        $mongo->updateEntry("accounts",$searchFollowedUserFilter,$update); 
    /**
     * end of handling the "follower" proccess
     */
        //echo out following status for frontend update
        if(isset($currentUserIsFollowing) && $currentUserIsFollowing == true){
            echo "isFollowing";
        }else{
            echo "notFollowing";
        }
}


if(isset($_POST["method"]) && $_POST["method"] == "showFollower"){
    $profileUserId = $_POST["profileUserId"];

    //check for security reasons if the id contains any illegal chars or if it is existing at all
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($profileUserId))){
        echo "Illegal chars detected!";
        exit();
    }
    
    $searchUserFilter = (['userId'=>$profileUserId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
    $usersFollower = (array)$searchUser->follower;

    $correspondingUsernames = [];
    $correspondingFirstname = [];
    $correspondingLastname = [];
    foreach($usersFollower as $foundId){
        $searchUserFilterFollower = (['userId'=>$foundId]);
        $searchUserFollower = $mongo->findSingle("accounts",$searchUserFilterFollower,[]);
        array_push($correspondingUsernames, $searchUserFollower->username);
        array_push($correspondingFirstname, $searchUserFollower->firstname);
        array_push($correspondingLastname, $searchUserFollower->lastname);
    }

    $ajaxResponse = [
        "followerUsernames"=> $correspondingUsernames,
        "followerFirstnames"=> $correspondingFirstname,
        "followerLastnames"=> $correspondingLastname
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "showFollowing"){
    $profileUserId = $_POST["profileUserId"];

    //check for security reasons if the id contains any illegal chars or if it is existing at all
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($profileUserId))){
        echo "Illegal chars detected!";
        exit();
    }

    $searchUserFilter = (['userId'=>$profileUserId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
    $usersFollowing = (array)$searchUser->following;

    $correspondingUsernames = [];
    $correspondingFirstname = [];
    $correspondingLastname = [];
    foreach($usersFollowing as $foundId){
        $searchUserFilterFollowing = (['userId'=>$foundId]);
        $searchUserFollowing = $mongo->findSingle("accounts",$searchUserFilterFollowing,[]);
        array_push($correspondingUsernames, $searchUserFollowing->username);
        array_push($correspondingFirstname, $searchUserFollowing->firstname);
        array_push($correspondingLastname, $searchUserFollowing->lastname);
    }

    $ajaxResponse = [
        "followingUsernames"=> $correspondingUsernames,
        "followingFirstnames"=> $correspondingFirstname,
        "followingLastnames"=> $correspondingLastname
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "searchInSystemForQuestions"){
    $userEntry = $_POST["value"];

     //check for security reasons if the followedUserId contains any illegal chars or if it is existing at all
     if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($userEntry))){
        echo "Illegal chars detected!";
        exit();
    }

    $options = ['sort' => ['karma' => -1]];
    $getAllQuestions = $mongo->read("questions",[], $options);
    $getAllQuestions = (array)$getAllQuestions;
    $allMatchingIds = [];
    $authorsOfTheMatches = [];
    $KarmaOfTheMatches = [];
    $allMatchingQuestionStrings = [];
    for($i=0;$i<count($getAllQuestions);$i++){
        $questionStringsArray = (array)$getAllQuestions[$i]["question"];
        $questionId = $getAllQuestions[$i]->id;
        $questionAuthor = $getAllQuestions[$i]->author;
        $questionKarma = $getAllQuestions[$i]->karma;
        $questionTags= $getAllQuestions[$i]->tags;
        foreach($questionStringsArray as $questionString){

            //Filter for tags
            foreach($questionTags as $tag){
                $foundMatch_tag = str_contains(strtolower($tag), strtolower($userEntry));
                if($foundMatch_tag && $userEntry!= "" && $userEntry != " "){
                    if (!in_array($questionId, $allMatchingIds)){
                        array_push($allMatchingQuestionStrings, $questionString);
                        array_push($allMatchingIds, $questionId);
                        array_push($authorsOfTheMatches, $questionAuthor);
                        array_push($KarmaOfTheMatches, $questionKarma);
                    }
                }
            }
            //filter for question titel
            $foundMatch = str_contains(strtolower($questionString), strtolower($userEntry));
            if($foundMatch && $userEntry!= "" && $userEntry != " "){
                if (!in_array($questionId, $allMatchingIds)){
                    array_push($allMatchingQuestionStrings, $questionString);
                    array_push($allMatchingIds, $questionId);
                    array_push($authorsOfTheMatches, $questionAuthor);
                    array_push($KarmaOfTheMatches, $questionKarma);
                }
            }

            //maybe later also filter for answers and options? 

        }
    }

    $ajaxResponse = [
        "allMatchingIds"=> $allMatchingIds,
        "allMatchingQuestionStrings"=> $allMatchingQuestionStrings,
        "authorsOfTheMatches"=> $authorsOfTheMatches,
        "KarmaOfTheMatches"=> $KarmaOfTheMatches
    ];
    echo json_encode($ajaxResponse);
}



if(isset($_POST["method"]) && $_POST["method"] == "searchInSystemForUsers"){
    $userEntry = $_POST["value"];

    //check for security reasons if the followedUserId contains any illegal chars or if it is existing at all
    if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($userEntry))){
       echo "Illegal chars detected!";
       exit();
   }

   $getAllUsers= $mongo->read("accounts",[]);
   $getAllUsersArray = (array)$getAllUsers;
   $allMatchingUsers = [];
   $allMatchingFirstnames= [];
   $allMatchingLastnames= [];
   for($i=0;$i<count($getAllUsersArray);$i++){
        $username = $getAllUsersArray[$i]["username"];
        $firstname = $getAllUsersArray[$i]["firstname"];
        $lastname = $getAllUsersArray[$i]["lastname"];

        $foundMatch = str_contains(strtolower($username), strtolower($userEntry));
        $foundMatch2 = str_contains(strtolower($firstname), strtolower($userEntry));
        $foundMatch3 = str_contains(strtolower($lastname), strtolower($userEntry));
        if(($foundMatch || $foundMatch2 || $foundMatch3) && $userEntry!= "" && $userEntry != " "){
            if (!in_array($username, $allMatchingUsers)){
                array_push($allMatchingUsers, $username);
                array_push($allMatchingFirstnames, $firstname);
                array_push($allMatchingLastnames, $lastname);
            }
        }
   }

   $ajaxResponse = [
       "allMatchingUsers"=> $allMatchingUsers,
       "allMatchingFirstnames"=> $allMatchingFirstnames,
       "allMatchingLastnames"=> $allMatchingLastnames
   ];
   echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "changeFavoritTags"){

    //check if the given tag isnt containing any illegal chars (catch exploits)
    if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($_POST["selectedTag"]))){
        echo "Illegal chars detected!";
        exit();
    }

    //check if the given tag is even existing in the system (catch faulty given inputs)
    session_start();
    $searchUserFilter = (['userId'=>$_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);
    $userTags = (array)$searchUser->favoritTags;

    $checkIfTagExists = array_search($_POST["selectedTag"],$userTags);

    //if it doesnt exist push it and insert it else drop it
    if($checkIfTagExists === false){
        array_push($userTags, $_POST["selectedTag"]);
    }else{
        unset($userTags[$checkIfTagExists]);
    }

    //reindexing the array so its always starting from 0. Thats importent for further usage of the filters
    $userTags = array_values($userTags);

    //after editing the array $set it to the mongodb
    $update = ['$set' =>  ['favoritTags'=> $userTags]];
    $mongo->updateEntry("accounts",$searchUserFilter,$update); 
}


if(isset($_POST["method"]) && $_POST["method"] == "addToCart"){
    include_once "cartService.php";
    session_start();
    $id = $_POST["questionId"];
    $cart = new CartService();
    $addResult = $cart->addItem($id);

    $questionFilter = (['id'=>$id]);
    $questionObject = $mongo->findSingle("questions",$questionFilter);

    $searchUserFilter = (['userId'=> $_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);
    $questionLanguageRelation = (array)$searchUser["questionLangUserRelation"];
    $cartLength = count((array)$searchUser->questionCart);
    $lang = array_search($id,$questionLanguageRelation);

    if(!$lang){
        //get the first key of the question so it can be used to set it as the default language
        $lang = array_key_first((array)$questionObject->question);
    }



    $ajaxResponse = [
        "addResult"=> $addResult,
        "questionObject"=> $questionObject,
        "lang"=> $lang,
        "cartLength" => $cartLength
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "createCatalog"){
    include_once "cartService.php";
    session_start();
    $cartService = new CartService();
    $createResult = $cartService->createCatalog();

    $searchUserFilter = (['userId'=> $_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);
    $cartLength = count((array)$searchUser->questionCart);

    $ajaxResponse = [
        "createResult" => $createResult,
        "cartLength" => $cartLength
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "removeCartItem"){
    include_once "cartService.php";
    session_start();
    $cartService = new CartService();
    $id = $_POST["id"];
    $removeResult = $cartService->removeItem($id);

    $searchUserFilter = (['userId'=> $_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);
    $cartLength = count((array)$searchUser->questionCart);

    $ajaxResponse = [
        "id" => $id,
        "removeResult" => $removeResult,
        "cartLength" => $cartLength
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "editQuestionTags"){

    $selectedTags = $_POST["payload"];
    $questionId = $_POST["id"];

    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }

    //added catch if the question has no tags / if all tags get removed
    if(empty($selectedTags)){
        $selectedTags = [];
    }
    
    //check if one of the give tags contains illegal chars (catch exploits)
    foreach($selectedTags as $tag){
        if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($tag))){
            echo "Illegal chars detected!";
            exit();
        }
    }
    
    $searchQuestionFilter = (['id'=>$questionId]);
    $searchQuestion = $mongo->findSingle("questions",$searchQuestionFilter);
    $questionTags = (array)$searchQuestion->tags;
    $questionAuthor = $searchQuestion->author;
    session_start();
    if($questionAuthor != $_SESSION["userData"]["username"]){
        echo "You are not allowed to edit this question!";
        exit();
    } 

    $update = ['$set' =>  ['tags'=> $selectedTags]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$update); 

    echo "Edit successfull!";
}


if(isset($_POST["method"]) && $_POST["method"] == "editQuestionText"){
    $questionId = $_POST["id"];
    $questionText = $_POST["payload"]["questionText"];
    $questionLanguage = $_POST["payload"]["questionLanguage"];


    /**
     * Catching all possible exploit values to protect the database
     */
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }

    /*
    //maybe the regex needs to be adjusted here to allow more --> its too limiting with that 
    if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($questionText))){
        echo "Illegal chars in Question given!";
        exit();
    }
    */

    $allSupportedLanguages = $getQuestionsTranslator->getAllTargetLanguageCodes();
    //catching falsly given languages from the user 
    if(!in_array($questionLanguage,$allSupportedLanguages)){
        echo "Illegal target language!";
        exit();
    }

    //get all currentLanguage versions of the question to create new translation with the new text for each language
    $searchQuestionFilter = (['id'=>$questionId]);
    $searchQuestion = $mongo->findSingle("questions",$searchQuestionFilter);
    $question_keys = array_keys((array)$searchQuestion->question);
    $questionAuthor = $searchQuestion->author;
    $currentQuestionVersion = $searchQuestion->version;

    session_start();

    $filterQueryCurrentUser = (['userId' => $_SESSION["userData"]["userId"]]);
    $userInfoCurrentUser= $mongo->findSingle("accounts",$filterQueryCurrentUser);

    $isCurrentUserAdmin = $userInfoCurrentUser["isAdmin"];

    if($questionAuthor != $_SESSION["userData"]["username"] && !$isCurrentUserAdmin){
        echo "You are not allowed to edit this question!";
        exit();
    } 

    $newQuestionArray = [];
    foreach($question_keys as $questionLangVersion){
        $translation = new TranslationService($questionLangVersion);
        $newQuestion = $translation->translateText($questionText);
        $newQuestionArray[$questionLangVersion] = $newQuestion;
    }


    $update = ['$set' =>  ['question'=> $newQuestionArray]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$update); 

    //update the question Version
    include_once "versionService.php";
    $version = new VersionService();
    $newVersion = $version->increaseVersion($currentQuestionVersion);

    $updateVersion = ['$set' =>  ['version'=> $newVersion]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$updateVersion); 

    //update the question modificationDate
    $modificationDate = date("Y-m-d");
    $updateModificationDate = ['$set' =>  ['modificationDate'=> $modificationDate]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$updateModificationDate); 

    echo "Edit successfull!";
}


if(isset($_POST["method"]) && $_POST["method"] == "deleteQuestion"){

    $questionId = $_POST["id"];
    //check for illegal chars in id
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }

    //check if id even exists
    $searchQuestionFilter = (['id'=>$questionId]);
    $searchQuestion = $mongo->findSingle("questions",$searchQuestionFilter);

    if(!isset($searchQuestion)){
        echo "No Question found for this Id!";
        exit();
    }

    $questionAuthor = $searchQuestion->author;


    session_start();


    $filterQueryCurrentUser = (['userId' => $_SESSION["userData"]["userId"]]);
    $userInfoCurrentUser= $mongo->findSingle("accounts",$filterQueryCurrentUser);

    $isCurrentUserAdmin = $userInfoCurrentUser["isAdmin"];

    if($questionAuthor != $_SESSION["userData"]["username"] && !$isCurrentUserAdmin){
        echo "You are not allowed to edit this question!";
        exit();
    } 


    //remove the question from all catalogs
    $allCatalogs = $mongo->read("catalog",[]);
    $allQuestionsOfCatalogs = (array)$allCatalogs;
    $allCatalogQuestions =  [];
    $allCatalogIds =  [];
    foreach($allQuestionsOfCatalogs as $catalog){
        array_push($allCatalogQuestions, (array)$catalog->questions);
        array_push($allCatalogIds, $catalog->id);
    }

    foreach($allCatalogQuestions as $catalogKey =>$catalogQuestionsArray){
        $checkIfInCatalog = array_search($questionId, $catalogQuestionsArray);
        if($checkIfInCatalog !== false){
            unset($catalogQuestionsArray[$checkIfInCatalog]);
            $searchCatalogFilter = (['id'=>$allCatalogIds[$catalogKey]]);
            $updateCatalog = ['$set' =>  ['questions'=> $catalogQuestionsArray]];
            $mongo->updateEntry("catalog",$searchCatalogFilter,$updateCatalog);   
        }
    }

    //remove all user relations to this question
    //-->questionLangUserRelation
    $allUsers = $mongo->read("accounts",[]);
    $allUsersArray = (array)$allUsers;
    $questionLangUserRelationsArray =  [];
    $allUserIds =  [];
    $userKarmaArray =  [];
    foreach($allUsersArray as $user){
        array_push($questionLangUserRelationsArray, (array)$user->questionLangUserRelation);
        array_push($allUserIds, $user->userId);
        array_push($userKarmaArray, (array)$user->questionsUserGaveKarmaTo);
    }

    foreach($questionLangUserRelationsArray as $relationKey => $relationArray){
        $checkIfInRelationArray = array_search($questionId, $relationArray);
        if($checkIfInRelationArray !== false){
            unset($relationArray[$checkIfInRelationArray]);
            $searchUserFilter = (['userId'=>$allUserIds[$relationKey]]);
            $updateUser = ['$set' =>  ['questionLangUserRelation'=> $relationArray]];
            $mongo->updateEntry("accounts",$searchUserFilter,$updateUser);      
        }
    }

    //-->questionUserGaveKarmaTo
    $karmaUp = [];
    $karmaDown = [];
    foreach($userKarmaArray as $karma){
        array_push($karmaUp,(array)$karma["up"]);
        array_push($karmaDown,(array)$karma["down"]);
    }

    //up karma
    foreach($karmaUp as $karmaKey => $userKarmaArray){
        $checkIfinArray = array_search($questionId, $userKarmaArray);
        if($checkIfinArray !== false){
            unset($userKarmaArray[$checkIfinArray]);
            $searchUserFilter = (['userId'=>$allUserIds[$karmaKey]]);
            $updateUser = ['$set' =>  ['questionsUserGaveKarmaTo.up' => $userKarmaArray]];
            $mongo->updateEntry("accounts",$searchUserFilter,$updateUser);      
        }
    }

    //down karma
    foreach($karmaDown as $karmaKey => $userKarmaArray){
        $checkIfinArray = array_search($questionId, $userKarmaArray);
        if($checkIfinArray !== false){
            unset($userKarmaArray[$checkIfinArray]);
            $searchUserFilter = (['userId'=>$allUserIds[$karmaKey]]);
            $updateUser = ['$set' =>  ['questionsUserGaveKarmaTo.down' => $userKarmaArray]];
            $mongo->updateEntry("accounts",$searchUserFilter,$updateUser);      
        }
    }


    //then fully remove the question from the system
    $mongo->deleteByUid("questions", $questionId);

    echo "Deleted Question successfully!";
}


if(isset($_POST["method"]) && $_POST["method"] == "getLatestQuestionsOfFollowedUsers"){
    session_start();
    $userId = $_SESSION["userData"]["userId"];

    $searchUserFilter = (['userId'=>$userId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
    $usersFollowing = (array)$searchUser->following;

    $followedQuestionsArray = [];
    $followedUsersArray = [];
    $followedCreationDateArray = [];
    foreach($usersFollowing as $foundId){
        $searchUserFilterFollowing = (['userId'=>$foundId]);
        $searchUserFollowing = $mongo->findSingle("accounts",$searchUserFilterFollowing,[]);
        $followedUser = $searchUserFollowing->username;

        // TODO change from creationDate to modification date
        $searchOption = ['sort' => ['creationDate' => -1] , 'limit' => 3];
        $searchQuestionOfFollowedUserFilter = (['author'=>$followedUser]);
        $searchQuestionOfFollowedUser = $mongo->read("questions",$searchQuestionOfFollowedUserFilter, $searchOption);
        foreach((array)$searchQuestionOfFollowedUser as $innerQuestion){
            //only show questions that arent older than 5 days
            if(round(strtotime(date("Y-m-d")) - strtotime($innerQuestion->creationDate))/(60 * 60 * 24) <= 5){
                array_push($followedQuestionsArray, (array)$innerQuestion->question[array_key_first((array)$innerQuestion->question)]);
                array_push($followedUsersArray, $innerQuestion->author);
                array_push($followedCreationDateArray, $innerQuestion->creationDate);
            }
        }
    }

        //cut all Arrays at 20 to avoid too many stuff in the view field... (logic means from index 0 to 20)
        $followedQuestionsArray =array_slice($followedQuestionsArray, 0, 20); 
        $followedUsersArray = array_slice($followedUsersArray, 0, 20); 
        $followedCreationDateArray = array_slice($followedCreationDateArray, 0, 20); 
    

    $ajaxResponse = [
        "followedQuestionsArray" => $followedQuestionsArray,
        "followedUsersArray" => $followedUsersArray,
        "followedCreationDateArray" => $followedCreationDateArray
    ];
    echo json_encode($ajaxResponse);
}


?>