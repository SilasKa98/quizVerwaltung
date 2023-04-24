<?php
/**
 * All sorts of calls to the different services get handeled in this file
 * 
 * 
 */

include_once "services/mongoService.php";
include_once "services/translationService.php";
include_once "services/questionService.php";
include_once "services/sanitiseInputService.php";

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
    if(array_key_exists($_POST["selLanguage"],(array)$fetchedQuestion[0]->question)){
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
    include_once "services/karmaService.php";
    $karma = new KarmaService();


    //sanitise id for xss security
    $sanitiser = new SanitiseInputService();
    $id = $sanitiser->sanitiseInput($_POST["id"]);


    $currentKarma = $karma->getCurrentKarma($id);
    session_start();
    $currentUserId = $_SESSION["userData"]["userId"];

    $searchUserFilter = (['userId'=>$currentUserId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);

    //shouldnt be happening/needed, however still checking it for safty
    if(!isset($searchUser)){
        exit();
    }

    $filterQueryQuestions = (['id' => $id]);

    if($_POST["job"] == "increaseKarma"){
        $currentHandle = "up";
        $oppositHandle = "down";
    }else{
        $currentHandle = "down";
        $oppositHandle = "up";
    }


    //search for the questionId in the karma->up array
    $isQuestionVoteExisting = array_search($id,(array)$searchUser["questionsUserGaveKarmaTo"][$currentHandle]);
    
    if($isQuestionVoteExisting === false){
        $newKarmaArray = (array)$searchUser["questionsUserGaveKarmaTo"][$currentHandle];
        array_push($newKarmaArray,$id);
        $fieldQuery = "questionsUserGaveKarmaTo.".$currentHandle;
        $updateUserKarma = ['$set' =>  [$fieldQuery=> $newKarmaArray]];
        $mongo->updateEntry("accounts",$searchUserFilter,$updateUserKarma);

        
        //search in other vote category(up/down) for existence
        $isQuestionExistingInOppositVote = array_search($id,(array)$searchUser["questionsUserGaveKarmaTo"][$oppositHandle]);
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
            $newKarma = $karma->increaseKarma($id);
        }else{
            $newKarma = $karma->decreaseKarma($id);
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
            $newKarma = $karma->decreaseKarma($id);
        }else{
            $newKarma = $karma->increaseKarma($id);
        }
        //echo is needed for displaying the correct Karma
        echo $newKarma;
        $update = ['$set' =>  ['karma'=> $newKarma]];
        $mongo->updateEntry("questions",$filterQueryQuestions,$update); 
    }
}


if(isset($_POST["method"]) && $_POST["method"] == "registerAccount"){
    include_once "services/accountService.php";
    $account = new AccountService();
    $account->register($_POST["username"],$_POST["mail"],$_POST["pwd"],$_POST["pwd_repeat"],$_POST["languageInput"],$_POST["firstname"],$_POST["lastname"], $_POST["requestAdmin"]);
}


if(isset($_POST["method"]) && $_POST["method"] == "loginAccount"){
    include_once "services/accountService.php";
    $account = new AccountService();
    $account->login($_POST["mailuid"],$_POST["pwd"]);
}


if(isset($_POST["logout"])){
    include_once "services/accountService.php";
    $account = new AccountService();
    $account->logout();
}


if(isset($_POST["language"])){
    include_once "services/accountService.php";
    $account = new AccountService();
    session_start();

    //sanitise language for xss security
    $sanitiser = new SanitiseInputService();
    $language = $sanitiser->sanitiseInput($_POST["language"]);

    $account->changeLanguage($language, $_SESSION["userData"]["userId"]);

    //TODO maybe also sanitise the destionation? needs to be checked what happens to the string
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

    #$isQuestionPresent = array_search($_POST["questionId"],$questionLangUserRelation);
    $isQuestionPresent = array_key_exists($_POST["questionId"],$questionLangUserRelation);

    if($isQuestionPresent !== false){
        unset($questionLangUserRelation[$_POST["questionId"]]);
    }

    //first get all current relations that exist
    #$checkRelationEntrys = $searchUser;
    //cast it to array and select the field of the object so the new field can get added
    $checkRelationEntrys = $questionLangUserRelation;
    // add the new id lang relation
    $checkRelationEntrys[$_POST["questionId"]] = $_POST["questionLang"];
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
    include_once "services/versionService.php";
    $sanitiser = new SanitiseInputService();
    $version = new VersionService();
    $version->setVersion("1.0");
    $version = $version->version;
    session_start();
    $fetchedData = $_POST["allQuestions"];
    foreach($fetchedData as $key => $value){

        //sanitise the values to prevent possible XSS attacks (output should be encoded with htmlspecialchars before outputting! strip_tags isnt 100%save, just a first step!)
        $sanitisedValue =[];
        foreach($value as $innerKey => $innerVal){
            $sanitisedValue[$innerKey] = $sanitiser->sanitiseInput($innerVal);
        }
        $value = $sanitisedValue;
        
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
        $value["verification"] = "not verified";
        $value["downloadCount"] = 0;
        #print_r($value);
        $mongo->insertMultiple("questions",[$value]);
    }

    unlink("topics/".$_POST["inputFilename"]);
}


if(isset($_POST["method"]) && $_POST["method"] == "changeFollower"){
    session_start();
    $userThatHasBeenFollowed = $_POST["followedUserId"];

    //sanitise userThatHasBeenFollowed for xss security
    $sanitiser = new SanitiseInputService();
    $userThatHasBeenFollowed = $sanitiser->sanitiseInput($userThatHasBeenFollowed);

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

    //sanitise profileUserId for xss security
    $sanitiser = new SanitiseInputService();
    $profileUserId = $sanitiser->sanitiseInput($profileUserId);

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

    //sanitise profileUserId for xss security
    $sanitiser = new SanitiseInputService();
    $profileUserId = $sanitiser->sanitiseInput($profileUserId);

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

    // sanitise value to prevent XSS
    $sanitiser = new SanitiseInputService();
    $userEntry = $sanitiser->sanitiseInput($userEntry);


    //check for security reasons if the userEntry contains any illegal chars or if it is existing at all
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

    // sanitise value to prevent XSS
    $sanitiser = new SanitiseInputService();
    $userEntry = $sanitiser->sanitiseInput($userEntry);

    //check for security reasons if the userEntry contains any illegal chars or if it is existing at all
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

    $selectedTags = $_POST["selectedTag"];

    // sanitise selectedTags to prevent XSS
    $sanitiser = new SanitiseInputService();
    $selectedTags = $sanitiser->sanitiseInput($selectedTags);


    //check if the given tag isnt containing any illegal chars (catch exploits)
    if(!preg_match("([A-Za-z0-9\-\_]+)", strval($selectedTags))){
        echo "Illegal chars detected!";
        exit();
    }

    //check if the given tag is even existing in the system (catch faulty given inputs)
    session_start();
    $searchUserFilter = (['userId'=>$_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);
    $userTags = (array)$searchUser->favoritTags;

    $checkIfTagExists = array_search($selectedTags,$userTags);

    //if it doesnt exist push it and insert it else drop it
    if($checkIfTagExists === false){
        array_push($userTags, $selectedTags);
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
    include_once "services/cartService.php";
    include_once "services/accountService.php";
    session_start();
    $id = $_POST["questionId"];

    // sanitise id to prevent XSS
    $sanitiser = new SanitiseInputService();
    $id = $sanitiser->sanitiseInput($id);

    $cart = new CartService();
    $account = new accountService();
    $addResult = $cart->addItem($id);

    $questionFilter = (['id'=>$id]);
    $questionObject = $mongo->findSingle("questions",$questionFilter);

    $searchUserFilter = (['userId'=> $_SESSION["userData"]["userId"]]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter);

    $cartLength = count((array)$searchUser->questionCart);

    $lang = $account->getUserQuestionLangRelation($_SESSION["userData"]["userId"],$id);


    $filterQuery = (['userId' => $_SESSION["userData"]["userId"]]);
    $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
    $selectedLanguage = $getAccountInfos->userLanguage;
    include "systemLanguages/text_".$selectedLanguage.".php";

    $ajaxResponse = [
        "addResult"=> $addResult,
        "questionObject"=> $questionObject,
        "lang"=> $lang,
        "cartLength" => $cartLength,
        "answerField" => $answerField,
        "optionsField" => $optionsField,
        "tagsField" => $tagsField,
        "authorField" => $authorField
    ];
    echo json_encode($ajaxResponse);
}

if(isset($_POST["method"]) && $_POST["method"] == "emptyQuestionCart"){
    include_once "services/cartService.php";
    session_start();
    $cartService = new CartService();
    $clearResult = $cartService->emptyQuestionCart();
    
    $ajaxResponse = [
        "clearResult" => $clearResult
    ];
    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "createCatalog"){
    include_once "services/cartService.php";
    session_start();
    $cartService = new CartService();
    $name = $_POST["name"];
    $status = $_POST["status"];

    //sanitise name and status for xss security
    $sanitiser = new SanitiseInputService();
    $name = $sanitiser->sanitiseInput($name);
    $status = $sanitiser->sanitiseInput($status);


    $createResult = $cartService->createCatalog($name, $status);

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
    include_once "services/cartService.php";
    session_start();
    $cartService = new CartService();
    $id = $_POST["id"];

    //sanitise id for xss security
    $sanitiser = new SanitiseInputService();
    $id = $sanitiser->sanitiseInput($id);

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
    
    //check if one of the give tags or the id contains illegal chars (catch exploits) --> also use the SanitiseInputService for XSS safty
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);

    foreach($selectedTags as $tag){
        $tag = $sanitiser->sanitiseInput($tag);
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
    $filterQueryCurrentUser = (['userId' => $_SESSION["userData"]["userId"]]);
    $userInfoCurrentUser= $mongo->findSingle("accounts",$filterQueryCurrentUser);

    $isCurrentUserAdmin = $userInfoCurrentUser["isAdmin"];

    if($questionAuthor != $_SESSION["userData"]["username"] && !$isCurrentUserAdmin){
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

    /**
     * sanitise the give values for XSS exploits
     */
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);
    $questionText = $sanitiser->sanitiseInput($questionText);

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
    include_once "services/versionService.php";
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

    //sanitise id for xss security
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);

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
    $deleteFilter = ["id" => $questionId];
    $mongo->deleteByUid("questions", $deleteFilter);

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


if(isset($_POST["method"]) && $_POST["method"] == "requestAdminAccount"){

    session_start();
    $userId = $_SESSION["userData"]["userId"];
    $searchUserFilter = (['userId'=>$userId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
    $username = $searchUser->username;
    $firstname = $searchUser->firstname;
    $lastname = $searchUser->lastname;
    $userId = $searchUser->userId;
    $mailAdress = $searchUser->mail;
    $joinDate = $searchUser->joinDate;

    include_once "services/accountService.php";
    $account = new AccountService();
    $account->sendAdminRequestMail($username, $firstname, $lastname, $userId, $mailAdress, $joinDate);
}


if(isset($_POST["method"]) && $_POST["method"] == "deleteUserAccount"){

    session_start();
    $userId = $_SESSION["userData"]["userId"];
    $username = $_SESSION["userData"]["username"];

    
    //deleting the id from all follower-Arrays its found in
    $searchUserFollowerFilter = (['follower'=>$userId]);
    $searchUserFollower = $mongo->read("accounts",$searchUserFollowerFilter,[]);

    foreach((array)$searchUserFollower as $item){
        $followerArray = (array)$item->follower;
        $foundId = array_search($userId, $followerArray);
        unset($followerArray[$foundId]);
        $followerArray = array_values($followerArray);
        $searchUserFilter = (['userId'=>$item->userId]);
        $updateUser = ['$set' =>  ['follower' => $followerArray]];
        $mongo->updateEntry("accounts",$searchUserFilter,$updateUser);      
    }


    //deleting the id from all following-Arrays its found in
    $searchUserFollowingFilter = (['following'=>$userId]);
    $searchUserFollowing = $mongo->read("accounts",$searchUserFollowingFilter,[]);

    foreach((array)$searchUserFollowing as $item){
        $followingArray = (array)$item->following;
        $foundId = array_search($userId, $followingArray);
        unset($followingArray[$foundId]);
        $followingArray = array_values($followingArray);
        $searchUserFilter = (['userId'=>$item->userId]);
        $updateUser = ['$set' =>  ['following' => $followingArray]];
        $mongo->updateEntry("accounts",$searchUserFilter,$updateUser);      
    }


    //remove all questions the user created and also save the ids of this questions for deleting them from catalogs later
    $allQuestionIdsBelongingToUser = [];
    $searchUserAuthorFilter = (['author'=>$username]);
    $searchUserQuestions = $mongo->read("questions",$searchUserAuthorFilter);
    foreach((array)$searchUserQuestions as $question){
        array_push($allQuestionIdsBelongingToUser, $question->id);
        $deleteFilter = ["id" => $question->id];
        $mongo->deleteByUid("questions", $deleteFilter);
    }


    //remove all questions from catalogs if they are created by the user which gets deleted
    //get all Catalogs
    $searchUserQuestionInCatalog = $mongo->read("catalog",[]);

    foreach($searchUserQuestionInCatalog as $catalog){
        $catalogQuestionsArray = (array)$catalog->questions;
        //check for matches in two arrays (all QuestionIds of the user & the current catalog questions array of the loop)
        $questionsArrayMatches = array_intersect($catalogQuestionsArray, $allQuestionIdsBelongingToUser);
        //if there are questions of the user found remove them and update the array
        if(!empty($questionsArrayMatches)){
            foreach($questionsArrayMatches as $key =>$questionsToDelete){
                unset($catalogQuestionsArray[$key]);
                $catalogQuestionsArray = array_values($catalogQuestionsArray);
                $catalogIdFilter = (['id'=>$catalog->id]);
                $updateCatalog = ['$set' =>  ['questions' => $catalogQuestionsArray]];
                $mongo->updateEntry("catalog",$catalogIdFilter,$updateCatalog);      
            }
        }
    }


    //remove all catalogs the user created
    $searchUserCatalogs= $mongo->read("catalog",$searchUserAuthorFilter);
    foreach((array)$searchUserCatalogs as $catalog){
        $deleteFilter = ["id" => $catalog->id];
        $mongo->deleteByUid("catalog", $deleteFilter);
    }


    //remove the user account
    $deleteUserFilter= ["userId" => $userId];
    $mongo->deleteByUid("accounts", $deleteUserFilter);


    //destroy session to log off the user
    session_destroy();

}

if(isset($_POST["method"]) && $_POST["method"] == "getPersonRecommendations"){
    session_start();
    $userId = $_SESSION["userData"]["userId"];
    $currentUsername = $_SESSION["userData"]["username"];

    $searchUserFilter = (['userId'=>$userId]);
    $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
    $userFavoritTags = (array)$searchUser->favoritTags;
    $userFollowings = (array)$searchUser->following;

    $matchingUsernames = [];
    $matchingFirstnames = [];
    $matchingLastnames = [];
    $matchingIds = [];

    //get users by matching tags
    foreach($userFavoritTags as $item){
        $similarityFilter = (['favoritTags'=>$item]);
        $searchUsersWithSimilarTags= $mongo->read("accounts",$similarityFilter);
        foreach((array)$searchUsersWithSimilarTags as $foundUser){
            if($foundUser->username != $currentUsername){
                if (!in_array($foundUser->username, $matchingUsernames) && !in_array($foundUser->userId, $userFollowings)){
                    array_push($matchingUsernames,$foundUser->username);
                    array_push($matchingFirstnames,$foundUser->firstname);
                    array_push($matchingLastnames,$foundUser->lastname);
                    array_push($matchingIds,$foundUser->userId);
                }
            } 
        }
    }

    //cut all Arrays at 7 to limit the amount of users found by tags (logic means from index 0 to 7)
    $matchingUsernames =array_slice($matchingUsernames, 0, 7); 
    $matchingFirstnames = array_slice($matchingFirstnames, 0, 7); 
    $matchingLastnames = array_slice($matchingLastnames, 0, 7); 
    


    //get users by followings of followings
    foreach($userFollowings as $item){
        $searchUserFollowingFilter = (['userId'=>$item]);
        $searchUserFollowing = $mongo->findSingle("accounts",$searchUserFollowingFilter);
        foreach((array)$searchUserFollowing->following as $foundUser){
            if($foundUser != $userId && !in_array($foundUser, $userFollowings) && !in_array($foundUser, $matchingIds)){
                $searchFoundUserFilter = (['userId'=>$foundUser]);
                $searchFoundUser = $mongo->findSingle("accounts",$searchFoundUserFilter);
                array_push($matchingUsernames, $searchFoundUser->username);
                array_push($matchingFirstnames, $searchFoundUser->firstname);
                array_push($matchingLastnames, $searchFoundUser->lastname);
            }
        }
    }
    

    //cut all Arrays at 15 to avoid too many stuff in the view field... from index 8 to 15 there are users found by following (logic means from index 0 to 15)
    $matchingUsernames =array_slice($matchingUsernames, 0, 15); 
    $matchingFirstnames = array_slice($matchingFirstnames, 0, 15); 
    $matchingLastnames = array_slice($matchingLastnames, 0, 15); 


    $ajaxResponse = [
        "matchingUsernames" => $matchingUsernames,
        "matchingFirstnames" => $matchingFirstnames,
        "matchingLastnames" => $matchingLastnames
    ];

    echo json_encode($ajaxResponse);
}


if(isset($_POST["method"]) && $_POST["method"] == "downloadCart"){
    session_start();
    $questionService = new QuestionService();
    $userId = $_SESSION["userData"]["userId"];

    if(isset($_POST["exportName"])){
        $exportName = $_POST["exportName"];
    }else{
        $exportName = "newCatalog";
    }


     //get catalog or user cart
     if(isset($_POST["downloadCatalogId"])){
        $catalogId = $_POST["downloadCatalogId"];
        $filterQuery = (['id' => $catalogId]);
        $searchCatalog = $mongo->findSingle('catalog', $filterQuery);
        if(!isset($searchCatalog)){
            //if id isnt existing, go exit. Id got illegally changed by user
            header("LOCATION: index.php?error=illegalId");
            exit();
        }
        $userCart = (array)$searchCatalog->questions;
    }else{
        //get the users question cart
        $filterQuery = (['userId' => $userId]);
        $searchUser = $mongo->findSingle('accounts', $filterQuery);
        $userCart = (array)$searchUser->questionCart;
    }


    //initalize the moodle export
    if($_POST["exportType"] == "Moodle"){
        include_once "moodleXMLParser.php";
        $exportParser = new MoodleXMLParser($exportName);
    
        foreach($userCart as $cartQuestionId){
            $filterQueryQuestion = (['id' => $cartQuestionId]);
            $searchQuestion = $mongo->findSingle('questions', $filterQueryQuestion);
            $exportParser->parseQuestionObject($searchQuestion);
            $questionService->increaseDownloadCount($cartQuestionId);
        }

        $exportParser->saveXML();
    }

    //initalize the json export
    if($_POST["exportType"] == "JSON"){
        include_once "exportParser.php";
        $jsonParser = new ExportParser();

        $allQuestionsInCard = [];
        foreach($userCart as $cartQuestionId){
            $filterQueryQuestion = (['id' => $cartQuestionId]);
            $searchQuestion = $mongo->findSingle('questions', $filterQueryQuestion);
            array_push($allQuestionsInCard, $searchQuestion);
            $questionService->increaseDownloadCount($cartQuestionId);
        }
        $serializedJsonObject = $jsonParser->serializeQuestion($allQuestionsInCard);
        $jsonParser->downloadFile($serializedJsonObject, $exportName, $userId, ".json");
    }


    //initalize the latex export
    if($_POST["exportType"] == "Latex"){
        include_once "exportParser.php";
        $latexParser = new ExportParser();

        $allQuestionsInCard = [];
        foreach($userCart as $cartQuestionId){
            $filterQueryQuestion = (['id' => $cartQuestionId]);
            $searchQuestion = $mongo->findSingle('questions', $filterQueryQuestion);
            array_push($allQuestionsInCard, $searchQuestion);
            $questionService->increaseDownloadCount($cartQuestionId);
        }
        $parsedLatexObj = $latexParser->convertToLatex($allQuestionsInCard, $userId, $exportName);
        $latexParser->downloadFile($parsedLatexObj, $exportName, $userId, ".tex");
    }


    //initalize the standard/simpQui export
    if($_POST["exportType"] == "Standard"){
        include_once "exportParser.php";
        $stdParser = new ExportParser();

        $allQuestionsInCard = [];
        foreach($userCart as $cartQuestionId){
            $filterQueryQuestion = (['id' => $cartQuestionId]);
            $searchQuestion = $mongo->findSingle('questions', $filterQueryQuestion);
            array_push($allQuestionsInCard, $searchQuestion);
            $questionService->increaseDownloadCount($cartQuestionId);
        }
        $parsedStdObj = $stdParser->convertToStandard($allQuestionsInCard, $userId, $exportName);
        $stdParser->downloadFile($parsedStdObj, $exportName, $userId, ".txt");
    }
}


if(isset($_POST["method"]) && $_POST["method"] == "createQuestionWithForm"){

    include_once "services/versionService.php";
    $sanitiser = new SanitiseInputService();
    $version = new VersionService();
    $version->setVersion("1.0");
    $version = $version->version;
    session_start();

    $questionText = $sanitiser->sanitiseInput($_POST["questionText"]);
    $questionType = $sanitiser->sanitiseInput($_POST["questionType"]);
    if(isset($_POST["questionAnswer"])){
       $questionAnswer = $sanitiser->sanitiseInput($_POST["questionAnswer"]); 
    }
    

    //check if the question type is allowed to dectect illegally user modified types
    $legalQuestionTypes = ["YesNo", "Order", "Open", "Options", "MultiOptions"];
    if(!in_array($questionType, $legalQuestionTypes)){
        header("Location: frontend/frontend_insertQuestion.php?illegalQuestionType");
        exit();
    }

    //check for yes no question if the given answer is true or false --> catch ilegally modified answers
    if($questionType == "YesNo"){
        $legalAnswers = ["True", "False"];
        if(!in_array($questionAnswer, $legalAnswers)){
            header("Location: frontend/frontend_insertQuestion.php?illegalQuestionAnswer");
            exit();
        }
    }

    $questionTags = [];
    if(isset($_POST["tags"])){
        $givenTags = $_POST["tags"];
    }else{
        $givenTags = [];
    }
    //tag names are handed over in html with tags["tagname"] thats why $key is used
    foreach($givenTags as $key => $tag){
        array_push($questionTags, $sanitiser->sanitiseInput($key));
    }


    if($questionType == "Options" || $questionType == "MultiOptions"){
        $questionAnswer = "";
        $questionOptions = [];
        foreach($_POST["options"] as $key => $option){
            array_push($questionOptions, $sanitiser->sanitiseInput($option));
            if($questionType == "Options"){
                if($_POST["correctOptions"] == $option){
                    $questionAnswer = strval($key);
                }
            }
            elseif($questionType == "MultiOptions"){
                if(in_array($option, $_POST["correctOptions"])){
                    if($questionAnswer == ""){
                        $questionAnswer .= $key;
                    }else{
                        $questionAnswer .= ",".$key;
                    }
                }
            }
        }
    }

    if($questionAnswer == ""){
        header("Location: frontend/frontend_insertQuestion.php?noAnswerGiven");
        exit();
    }

    //language is automatically detected with the deepL Api
    $deepLDetectLanguage = new TranslationService("de");
    $language = $deepLDetectLanguage->detectLanguage($questionText);

    if($language == "en"){
        $language = "en-us";
    }

  
    //check the inserted questions for illegal stuff and escape chars that need to be escaped
    $questionText = str_replace("<span>", "<span class='displayCodeInQuestion' translate=\"no\">", $questionText);
    $questionText = str_replace("\"", "'", $questionText);


    $questionToInsert = [
        "answer" => $questionAnswer,
        "tags" => $questionTags,
        "questionType" => $questionType,
        "question" => [$language=>$questionText],
        "id" => uniqid(),
        "creationDate" => date("Y-m-d"),
        "modificationDate" => "",
        "version" => $version,
        "karma" => 0,
        "author" => $_SESSION["userData"]["username"],
        "verification" => "not verified",
        "downloadCount" => 0
    ];

    if($questionType == "Options" || $questionType == "MultiOptions"){
        $questionToInsert["options"] = [$language=>$questionOptions];
    }

    $mongo->insertSingle("questions",$questionToInsert);
    header("Location: frontend/frontend_insertQuestion.php?import=success");
}


if(isset($_POST["method"]) && $_POST["method"] == "editQuestionVerification"){

    $verificationStatus = $_POST["payload"];
    $questionId = $_POST["id"];

    /**
     * Catching all possible exploit values to protect the database
     */
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }

    /**
     * sanitise the give values for XSS exploits
     */
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);
    $verificationStatus = $sanitiser->sanitiseInput($verificationStatus);


    $allowedVerificationStatus = ["verified", "not verified"];
    if(!in_array($verificationStatus,$allowedVerificationStatus)){
        echo "Illegal verification status given!";
        exit();
    }

    $searchQuestionFilter = (['id'=>$questionId]);
    $searchQuestion = $mongo->findSingle("questions",$searchQuestionFilter);
    $questionTags = (array)$searchQuestion->tags;
    $questionAuthor = $searchQuestion->author;

    session_start();
    $filterQueryCurrentUser = (['userId' => $_SESSION["userData"]["userId"]]);
    $userInfoCurrentUser= $mongo->findSingle("accounts",$filterQueryCurrentUser);

    $isCurrentUserAdmin = $userInfoCurrentUser["isAdmin"];

    if($questionAuthor != $_SESSION["userData"]["username"] && !$isCurrentUserAdmin){
        echo "You are not allowed to edit this question!";
        exit();
    } 

    $update = ['$set' =>  ['verification'=> $verificationStatus]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$update); 

    echo "Edit successfull!";
}

if(isset($_POST["method"]) && $_POST["method"] == "editQuestionAnswer"){
    $answerPayload = $_POST["payload"];
    $questionId = $_POST["id"];
    $answerType = $answerPayload["answerType"];
    $answerText = $answerPayload["answerText"];

    /**
     * Catching all possible exploit values to protect the database
     */
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }


    /**
     * sanitise the give values for XSS exploits
     */
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);
    $answerText = $sanitiser->sanitiseInput($answerText);
    $answerType = $sanitiser->sanitiseInput($answerType);


    //check if the answer type is allowed to dectect illegally user modified types
    $legalAnswerTypes = ["YesNo", "Open"];
    if(!in_array($answerType, $legalAnswerTypes)){
        echo "Illegal answerType given!";
        exit();
    }

    if($answerType == "YesNo"){
        $legalAnswers = ["true", "false"];
        if(!in_array($answerText, $legalAnswers)){
            echo "Illegal answerText given!";
            exit();
        }
    }


    //check if the user is allowed to edit this question
    $searchQuestionFilter = (['id'=>$questionId]);
    $searchQuestion = $mongo->findSingle("questions",$searchQuestionFilter);
    $questionTags = (array)$searchQuestion->tags;
    $questionAuthor = $searchQuestion->author;

    session_start();
    $filterQueryCurrentUser = (['userId' => $_SESSION["userData"]["userId"]]);
    $userInfoCurrentUser= $mongo->findSingle("accounts",$filterQueryCurrentUser);

    $isCurrentUserAdmin = $userInfoCurrentUser["isAdmin"];

    if($questionAuthor != $_SESSION["userData"]["username"] && !$isCurrentUserAdmin){
        echo "You are not allowed to edit this question!";
        exit();
    } 
  
    #####end of exploit catching

    $update = ['$set' =>  ['answer'=> $answerText]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$update); 

    echo "Edit successfull!";
}

if(isset($_POST["method"]) && $_POST["method"] == "editQuestionOptions"){
    $answerPayload = $_POST["payload"];
    $questionId = $_POST["id"];
    $optionValues = $answerPayload["optionValues"];
    $optionChecks = $answerPayload["optionChecks"];
    $questionType = $answerPayload["questionType"];
    $answerOptionsLang = $answerPayload["answerOptionsLang"];

    /**
     * Catching all possible exploit values to protect the database
     */
    if(!preg_match("/^[a-zA-Z0-9]*$/", strval($questionId))){
        echo "Illegal id given!";
        exit();
    }

    /**
     * sanitise the give values for XSS exploits
     */
    $sanitiser = new SanitiseInputService();
    $questionId = $sanitiser->sanitiseInput($questionId);
    $questionType = $sanitiser->sanitiseInput($questionType);
    $sanitisedOptionValues = [];
    foreach($optionValues as $value){
        array_push($sanitisedOptionValues, $sanitiser->sanitiseInput($value));
    }
    $sanitisedOptionChecks = [];
    foreach($optionChecks as $value){
        array_push($sanitisedOptionChecks, $sanitiser->sanitiseInput($value));
    }

    $allSupportedLanguages = $getQuestionsTranslator->getAllTargetLanguageCodes();
    //catching falsly given languages from the user 
    if(!in_array($answerOptionsLang,$allSupportedLanguages)){
        echo "Illegal target language!";
        exit();
    }

    $allowedQuestionTypes = ["Options", "MultiOptions"];
    if(!in_array($questionType,$allowedQuestionTypes)){
        echo "Illegal question Type!";
        exit();
    }

    ###end of checking for illegal values

    //get all currentLanguage versions of the question to create new translation with the new options for each question
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

    //build new Options array with all translations
    $newOptionsArray = [];
    foreach($question_keys as $questionLangVersion){
        $translation = new TranslationService($questionLangVersion);
        $newTranslatedOptions = [];
        foreach($sanitisedOptionValues as $option){
            $newOption = $translation->translateText($option);
            array_push($newTranslatedOptions, $newOption);
        }
        $newQuestionArray[$questionLangVersion] = $newTranslatedOptions;
    }


    //update options
    $update = ['$set' =>  ['options'=> $newQuestionArray]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$update); 


    //build new answer string with the correct indexes
    $finalAnswerString = "";

    $array_keys = array_keys($sanitisedOptionChecks);
    $last_key = end($array_keys);
    reset($sanitisedOptionChecks);

    foreach($sanitisedOptionChecks as $key => $value){
        if($value == "true"){
            if($questionType == "MultiOptions"){
                if($last_key == $key){
                    $finalAnswerString .= $key;
                }else{
                    $finalAnswerString .= $key.",";
                }
            }else{
                $finalAnswerString .= $key;
            }
        }
    }

    //update answer
    $updateAnswer = ['$set' =>  ['answer'=> $finalAnswerString]];
    $mongo->updateEntry("questions",$searchQuestionFilter,$updateAnswer); 


    //update the question Version
    include_once "services/versionService.php";
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


?>