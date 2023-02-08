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

if(isset($_POST["method"]) && $_POST["method"] == "insertNewLanguage"){

    //maybe later fetch it from somewhere?
    $allSupportedLanguages = ["de","en-Us","es"];

    //catching falsly given values from the user 
    if(!in_array($_POST["selLanguage"],$allSupportedLanguages)){
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
    $account->register($_POST["username"],$_POST["mail"],$_POST["pwd"],$_POST["pwd_repeat"],$_POST["languageInput"],$_POST["firstname"],$_POST["lastname"]);
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
            exit();
        }

        //maybe later fetch it from somewhere?
        $allSupportedLanguages = ["de","en-Us","es"];
        if(!in_array($_POST["questionLang"],$allSupportedLanguages)){
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
        echo "illegal chars";
        exit();
    }
    $searchUserThatHasBeenFollowed = (['userId'=>$userThatHasBeenFollowed]);
    $searchUserThatHasBeenFollowed = $mongo->findSingle("accounts",$searchUserThatHasBeenFollowed,[]);
    if(!isset($searchUserThatHasBeenFollowed)){
        echo "user is not existing!";
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

if(isset($_POST["method"]) && $_POST["method"] == "addToCart"){
    include_once "cartService.php";
    session_start();
    $id = $_POST["questionId"];
    $cart = new CartService();
    $cart->addItem($id);

    $questionFilter = (['id'=>$id]);
    $questionObject = $mongo->findSingle("question",$questionFilter);
}


if(isset($_POST["method"]) && $_POST["method"] == "searchInSystemForQuestions"){
    $userEntry = $_POST["value"];

     //check for security reasons if the followedUserId contains any illegal chars or if it is existing at all
     if(!preg_match("/^[a-zA-ZäöüÄÖÜß0-9 ]*$/", strval($userEntry))){
        echo "illegal chars";
        exit();
    }

    $getAllQuestions = $mongo->read("questions",[]);
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
       echo "illegal chars";
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
        echo "illegal chars";
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

?>