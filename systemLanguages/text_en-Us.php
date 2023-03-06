<?php
    //general --> needs to be set in every text_xx.php
    $all_languages=[
        "de"=>"German",
        "en-Us"=>"English",
        "es"=>"Spanish"
    ];

    //frontend_insertQuestion.php
    $text_import_form = [
        "select_file"=> "Select file",
        "import_btn"=> "Import",
        "clean_db_btn"=> "Clean Database (dev only)"
    ];


    //index.php
    $text_logout_btn = "Logout";
    $welcomeTitel = "Hello";
    $navText_insertQuestion = "Insert Question";
    $profileInfoText = "Choose your favorite categories and always see what interests you.";
    $myProfileLink = "My Profile";
    $latestQuestionsFollowing = "Latest Questions of the People you are Following";
    $personsYouMayKnow = "People who might interest you:";


    //Toast (index.php)
    $toastTimeDisplay = "just Now";

    //questionSection.php
    $InsertNewLanguageMainHeader = "Pick up a new language for the question";
    $InsertNewLanguageTitel = "Select your disired Language";

    //insertQuestions.php
    $importCheckPageTitel = "Import overview";
    $selectYourTagsHeader = "Select the appropriate tags for the question";
    $adjustButton = "Adjust";
    $finalizeImportButton = "Import questions";
    $giveTagsReminderAlert = "Attention! If you do not assign tags to the questions, it will be difficult for other users to find your questions.";


    //userProfile.php
    $userHasNoQuestionsYet = "hasn't created any questions yet.";
    $userHasNoCatalogsYet = "has no catalogs yet";
    $fullNameField = "Name";
    $languageField = "Language";
    $userTotalKarmaOwned = "Karma Earned:";
    $userTotalQuestionsSubmitted = "Created Questions:";
    $userJoinDateInfo = "Joined on";
    $followedBtnText = "Followed";
    $notFollowedBtnText = "Follow";
    $showFollowerText = "Follower";
    $showFollowingText = "Following";
    $questionsTabText = "Questions";
    $catalogsTabText = "Catalogs";

    //navbar.php
    $searchResultUsersHeader = "Users";
    $searchResultQuestionsHeader = "Questions";

    //searchResult (navbar.php) but used in a script
    $noSearchMatches = "No matches";

    //editQuestion.php
    $writeNewQuestionHeader = "Change the question text";


     //usersSettings.php
     $deleteAccountText = "Permanently delete your account";
     $adminAccessRequestText = "Request Admin-Account";
     $adminAccountHeader = "Admin-Account";
     $adminAccountExampleText = "With an admin account you can edit or delete all questions. Also, you can verify questions to confirm their accuracy.";
     $adminAccessRequestButton = "Request";
     $deleteAccountHeader = "Delete Account";
     $deleteAccountExampleText = "Attention! By performing this action, your account and all associated data will be deleted! Also your created questions and all interactions will be lost.";
     $deleteAccountButton = "Delete";

    //catalogCart.php
    $cartNameText = "Question cart";
    $createCatalogButton = "Create catalog";
    $exportButton = "Export";
    //cartService.php
    $cartInfoText = "   You currently have no questions in your basket.
                        Simply add a question by opening the dropdown menu next to a question and clicking on the shopping cart.
                    ";

    //modal_catalogOptions.php
    $catalogOptionsLabel = "Catalog Options";
    $catalogNamePlaceholder = "Please enter a catalog name";
    $catalogVisibilitySetting = "Change the visibility setting for your catalog";
    $publicButton = "Public";
    $privateButton = "Private";
    $optionsCloseButton = "Close";
    $optionsSaveButton = "Save";

    //modal_downloadExport.php
    $downloadExportLabel = "Create & download your export here";
    $exportTypeOptions = "Export type (default 'Standard')";
    $exportName = "Please name your export (default 'newCatalog')";
    $downloadButton = "Download";

    //printService.php & cartService.php Question prints
    $answerField = "Answer";
    $questionTypeField = "Type";
    $optionsField = "Options";
    $creationDateField = "Creation Date";
    $lastChangeField = "Last updated";
    $versionField = "Version";
    $tagsField = "Tags";
    $downloadsNumberField = "Downloads";
    $authorField = "Author";
?>