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

    $upload_headingCreateQuestions = "Create a question directly with our form";
    $upload_headingImportQuestions = "Upload one or more questions with our file upload";

    $upload_questionChoose = "What kind of question do you want to create?";
    $upload_createQuestionText = "Question text";
    $upload_createQuestionAnswer= "Answer to the question";
    $upload_addOption = "Add option (check the correct answers)";
    $upload_questionTags = "Questions tags";


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
    $helppageNavText = "Help";
    $datenschutzNavbar = "Data privacy";

    //searchResult (navbar.php) but used in a script
    $noSearchMatches = "No matches";

    //editQuestion.php
    $writeNewQuestionHeader = "Change the question text";
    $changeVerificationModalHeader = "Change the verification status";
    $currentVerificationStatusText = "Current Status: ";
    $verifiedStatus = "Verified";
    $notVerifiedStatus = "Not verified";
    $changeOptionsModalHeader = "Change the question options and answers";
    $changeAnswerModalHeader = "Change the answer of the question";
    $editTrueAnswer = "True";
    $editFalseAnswer = "False";

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
    $questionVerifiedStatusDisplay = " The question has been verified by an admin.";
    $questionNotVerifiedStatusDisplay = " The question has not yet been verified by an admin.";



       //helpPage.php
       $quizManagerHelpHeading = "QuizManager Help";
       $questionTypesText = "The Quiz Manager platform supports different question formats. In the following these are shown and described in more detail.";
   
       $helpMenuImport = "Import";
       $helpMenuSubImportPossibilitys = "Import Possibilities";
       $helpMenuSubQuestionTypes = "Question types";
       $helpMenuSubImportStructure = "Import structure";
   
       $helpMenuExport = "Export";
   
       $helpMenuTranslation = "Translate";

       $helpMenuAboutTranslation = "About";

       $helpAboutInforamtionMain = "Informations about the Project";
        $helpAboutInformationText = "This project was carried out and implemented as a master's project in the 
        study program Wirtschaftsinformatik at the Technische Hochschule Mittelhessen (THM). It is intended to fill 
        a gap in the quiz industry, since after some research no or not widespread openly accessible quiz question platforms were found.
        So this project wants to be an open-access platform for all kinds of questions, where users can add, edit and export questions in 
        various formats for further use.";
        $helpAboutProjectMemberMain = "Project Member";
        $helpAboutProjectText = "Dennis Zimmer  - Developer <br>
        Silas Kammerer - Developer <br>
        Prof. Dr. Stephan Euler - Project Supervisor";
   
       $helpContentsHeading = "Content";
   
       $importPossibility1 = "Insert questions by file import";
       $importPossibility2 = "Inserting questions via the web form";
       $importPossibility1Description = "With this function you can import one or more questions at the same time.
       Which format is supported and what you have to pay attention to exactly is explained in the chapter <a href='#Importstructure'>Import Structure</a>";
   
       $importPossibility2Description = "With this function you can create a question directly in our web form. To do this, simply fill in the specified fields.";
   
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

       $liStructureCode = "Structuring code in the questions";
       $liStructureCodeExample = "To better structure code in a question, the code can simply be placed in a <b>".htmlspecialchars('<span></span>')."</b> HTML-Tag. 
       For line breaks the HTML-Tag <b>".htmlspecialchars('<br>')."</b> can be used. The image above shows an example of the import with the resulting code.";


        $helpMenuSubExportPossibilitys = "Export Possibilities";
        $helpTextExportPossibilitys = "All questions that you have collected in your question basket or that you have saved as a question catalog can be exported.";
        
        $exportPossibility1 = "Direct export from the question basket";
        $exportPossibility1Description = "For direct export from the question basket, simply use the  
        <button type='button' class='btn btn-danger' disabled>Export</button> button.";

        $exportPossibility2 = "Export of a created question catalog";
        $exportPossibility2Description = "To export a question catalog from you or also from other users, navigate to the corresponding profile in the first step and click on the tab \"Catalogs\". 
        Behind each catalog you will find a <button type='button' class='btn btn-secondary' disabled>Export</button> button, with which the entire catalog can be exported.";

        $exportModalExplanationHeader = "Explanation of the export window";
        $exportModalExplanationDescription  = "In the window shown, you can use the first field to select the format in which you want to export your questions. 
        With the second free text field you can specify the name you want to give to your export file.";


        $helpMenuSubExportTypes = "Export types";
        $helpTextExportTypes = "Various formats are available for the export, which are shown and described below.";

        $exportType1 = "Moodle XML";
        $moodleDocLink = "Moodle Documentation";
        $exportType1Description = "It is possible to export your selected questions into the common Moodle XML format. 
        The created XML file can be imported directly into Moodle without any further steps.";

        $exportType2 = "Latex";
        $latexDocLink = "Latex Documentation";
        $exportType2Description = "The questions can also be translated or converted into the latex format. 
        With this export form, however, not all values of the question are converted into the latex format. 
        Only the question with all answers will be converted. By this kind of export, you can bring the questions directly into a kind of \"exam form\".
        The resulting latex file can then be translated by any common latex compiler.";

        $exportType3 = "JSON";
        $jsonDocLink = "JSON Documentation";
        $exportType3Description = "Since JSON is a very common format in the webumweld, it is also supported as a possible export format. 
        When exporting, you will get the entire question with all its values as a JSON object.";


        $exportType4 = "SimpQui/Standard";
        $simpQuiDocLink = "SimpQui Documentation";
        $exportType4Description = "Our standard format, which is also known as SimpQui format, is also supported as an export format. 
        It is the same format as for import. For reference to this format we refer to the Quiz application <a href='https://hosting.iem.thm.de/user/euler/quiz/index.php?inhalt=home' target='_blank'>SimpQui</a>";



        $helpMenuSubTranslationInfos = "Translation info";
        $helpTextTranslationInfos = "The Quiz Manager platform basically supports three different languages (English, German, Spanish) for the whole platform.<br><br>
        Since the core functionality of this platform is sharing and using quiz questions, significantly more languages are also supported for the quiz questions. 
        In total, all questions uploaded to the platform can be translated into 31 different languages.";

        $headerTranslationDeepl = "DeepL as a tool for translation";
        $descriptionDeepL = "The DeepL API is used to translate the questions. DeepL uses machine translation technologies,
        which allows all questions on the platform to be translated into a good linguistic form with high precision.";
        $deepLDocuLink = "DeepL Documentation";


        $helpMenuSubNewTranslation = "New translation";
        $helpTextCreateNewTranslation = "Each user can create a new translation for each question.";
        $headerCreateNewTranslation = "Create a new translation for a question";
        $descriptionCreateNewTranslation = "Next to each question in the system there is a drop-down menu with which various actions can be performed. 
        Among other things, there is the possibility to add a new translation for the selected question. 
        This function can be called by clicking on the <img src=\"/quizAdministration/media/language.svg\" width=\"20px\"> button. 
        A pop-up window will open (see screenshot below), where you can then simply select the desired language.";


        $helpMenuSubExistingTranslation = "Existing translation";
        $helpTextExistingTranslation = "Existing translations can be used by all users.";
        $headerExistingTranslation = "Use an existing translation for a question.";
        $descriptionExistingTranslation = "If there is a drop-down menu on the right side of a question, there are already translations for this question. 
        Other users have already translated the question into different languages. All translations are saved to make them available to other users even faster afterwards. 
        By opening the drop-down menu you can check if the desired translation already exists. 
        If yes, the desired language can be selected to translate the question directly.";
?>

