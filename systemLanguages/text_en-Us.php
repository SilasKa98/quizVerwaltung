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
    $checkHelpPageAlertText = "Before importing anything please kindly check our ";
    $checkHelpPageBtn = "Helppage";

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




       //helpPage.php
       $quizManagerHelpHeading = "QuizManager Help";
       $questionTypesText = "The Quiz Manager platform supports different question formats. In the following these are shown and described in more detail.";
   
       $helpMenuImport = "Import";
       $helpMenuSubImportPossibilitys = "Import Possibilities";
       $helpMenuSubQuestionTypes = "Question types";
       $helpMenuSubImportStructure = "Import structure";
   
       $helpMenuExport = "Export";
   
       $helpMenuTranslation = "Translate";
   
   
       $helpContentsHeading = "Content";
   
       $importPossibility1 = "Insert questions by file import";
       $importPossibility2 = "Inserting questions via the web form (coming soon)";
       $importPossibility1Description = "With this function you can import one or more questions at the same time.
       Which format is supported and what you have to pay attention to exactly is explained in the chapter <a href='#Importstructure'>Import Structure</a>";
   
       $importPossibility2Description = "This feature is not implemented yet, but it is planned in the near future.";
   
       $liYesNoQuestion = "Yes/No Question";
       $liYesNoQuestionExample = "This type of question is a simple question, 
       which can only be answered with Yes or No. Therefore, the answer in the Quiz Manager system to such a question is always True (Yes) or False (No).";
   
       $liOpenQuestion = "Open Question";
       $liOpenQuestionExample = "This type of question is an open-ended question. 
       This means that there are no fixed answer options for the user. Instead, the user can answer freely.
       The question type is nevertheless given an answer as a \"sample solution\".";
   
       $liOptionsQuestion = "Options Question";
       $liOptionsQuestionExample = "This question type is a question with fixed answer options. 
       However, only one of these answers is correct. The correct answer is marked in green.";
   
       $liMultiOptionsQuestion = "Multioptions Question";
       $liMultiOptionsQuestionExample = "This type of question is a question with fixed answer options, as in the selection question. 
       However, several of these answers can be correct. The correct answers are marked in green.";
   
   
       $helpTextImportStructure = "A special format is used for importing questions. 
       At the moment only .txt files can be imported, which correspond to this structure.";
       $generalQuestionStructureExample = "Each question is divided into different sections using the \"#\" character. The first section represents the question type. 
       The second section contains the question text. All other sections are different depending on the question type and are described in more detail below.";
       
       $liYesNoQuestionStructureExample = "In the case of a yes/no question, the third section contains the answer to the question. 
       This can have only one of the following values: <b>True</b> / <b>False</b> ";
       $liOpenQuestionStructureExample = "In the case of an Open Question, the third section contains the answer or \"sample solution\" to the question. 
       This can have any value";
       $liOptionsQuestionStructureExample = "In case of a selection question, the third section contains the index of the correct answer to the question. 
       There can be only one correct answer. 
       The sections that follow the answer represent possible answers. In the example shown above, there are two answer choices. 
       The indexing starts at 0, which means that the answer \"answer Option2\" would be the correct answer in this example.";
       $liMultiOptionsQuestionStructureExample = "In case of a multiple choice question, the third section contains the indices of the correct answers to the question. 
       So there are several correct answers to the question.
       The sections that follow each answer represent possible answers. In the example shown above, there are three answer choices. 
       The indexing starts at 0, which means that the answers \"Answer1\" and \"Answer2\" would be the correct answers in this example.";
   
?>