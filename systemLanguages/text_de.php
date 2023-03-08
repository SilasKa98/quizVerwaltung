<?php
    //general --> needs to be set in every text_xx.php
    $all_languages=[
        "de"=>"German",
        "en-Us"=>"English",
        "es"=>"Spanish"
    ];


    //frontend_insertQuestion.php
    $text_import_form = [
        "select_file"=> "Datei auswählen",
        "import_btn"=> "Importieren",
        "clean_db_btn"=> "Datenbank leeren (dev only)"
    ];


    //index.php
    $text_logout_btn = "Abmelden";
    $welcomeTitel = "Hallo";
    $navText_insertQuestion = "Frage Hinzufügen";
    $profileInfoText = "Wähle deine lieblings Kategorien und sehe immer was dich interessiert.";
    $myProfileLink = "Mein Profil";
    $latestQuestionsFollowing = "Neueste Fragen der Personen, denen Sie folgen";
    $personsYouMayKnow = "Personen die Sie interessieren könnten:";

    //Toast (index.php)
    $toastTimeDisplay = "gerade eben";

    //questionSection.php
    $InsertNewLanguageMainHeader = "Eine neue Sprache für die Frage aufnehmen";
    $InsertNewLanguageTitel = "Wählen Sie die gewünschte Sprache";

    //insertQuestions.php
    $importCheckPageTitel = "Import Übersicht";
    $selectYourTagsHeader = "Wählen Sie die passenden Tags für die Frage";
    $adjustButton = "Anpassen";
    $finalizeImportButton = "Fragen Importieren";
    $giveTagsReminderAlert = "Achtung! Wenn Sie den Fragen keine Tags zuordenen, können andere Nutzer Ihre Fragen nur schwer finden.";
    $checkHelpPageAlertText = "Bevor Sie etwas importieren, überprüfen Sie bitte unsere ";
    $checkHelpPageBtn = "Hilfeseite";

    //userProfile.php
    $userHasNoQuestionsYet = "hat noch keine Fragen erstellt.";
    $userHasNoCatalogsYet = "hat noch keine Kataloge";
    $fullNameField = "Name";
    $languageField = "Sprache";
    $userTotalKarmaOwned = "Verdientes Karma:";
    $userTotalQuestionsSubmitted = "Erstellte Fragen:";
    $userJoinDateInfo = "Beigetreten am";
    $followedBtnText = "Gefolgt";
    $notFollowedBtnText = "Folgen";
    $showFollowerText = "Follower";
    $showFollowingText = "Gefolgt";
    $questionsTabText = "Fragen";
    $catalogsTabText = "Kataloge";

    //navbar.php
    $searchResultUsersHeader = "Nutzer";
    $searchResultQuestionsHeader = "Fragen";

    //searchResult (navbar.php) but used in a script
    $noSearchMatches = "Keine passenden Treffer";

    //editQuestion.php
    $writeNewQuestionHeader = "Ändern Sie den Fragentext";


    //usersSettings.php
    $deleteAccountText = "Ihren Account permanent löschen";
    $adminAccessRequestText = "Admin-Account anfragen";
    $adminAccountHeader = "Admin-Account";
    $adminAccountExampleText = "Mit einem Admin-Account können Sie alle Fragen bearbeiten oder löschen. Außerdem können Sie fragen verifizieren um Ihre Richtigkeit zu bestätigen.";
    $adminAccessRequestButton = "Anfragen";
    $deleteAccountHeader = "Account löschen";
    $deleteAccountExampleText = "Achtung! Durch ausführen dieser Aktion wird Ihr Account und alle damit verbundenen Daten gelöscht! Auch Ihre erstellten Fragen sowie sämtliche Interaktionen gehen verloren.";
    $deleteAccountButton = "Löschen";

    //catalogCart.php
    $cartNameText = "Fragenkorb";
    $createCatalogButton = "Katalog erstellen";
    $exportButton = "Exportieren";
    //cartService.php
    $cartInfoText = "   Du hast aktuell keine Fragen in deinem Korb.
                        Füge einfach eine Frage hinzu indem du neben einer Frage das Dropdown Menü öffnest und den Warenkorb anklicks.
                    ";

    //modal_catalogOptions.php
    $catalogOptionsLabel = "Katalog Optionen";
    $catalogNamePlaceholder = "Bitte geben Sie einen Katalognamen ein";
    $catalogVisibilitySetting = "Ändern Sie die Sichtbarkeit für Ihren Katalog";
    $publicButton = "Öffentlich";
    $privateButton = "Privat";
    $optionsCloseButton = "Abbrechen";
    $optionsSaveButton = "Speichern";

    //modal_downloadExport.php
    $downloadExportLabel = "Erstellen und herunterladen des Exports";
    $exportTypeOptions = "Exporttyp (Standard 'Standard')";
    $exportName = "Bitte benennen Sie Ihren Export (Standard 'newCatalog')";
    $downloadButton = "Herunterladen";

    //printService.php & cartService.php Question prints
    $answerField = "Antwort";
    $questionTypeField = "Typ";
    $optionsField = "Optionen";
    $creationDateField = "Erstellungsdatum";
    $lastChangeField = "Letzte Änderung";
    $versionField = "Version";
    $tagsField = "Tags";
    $downloadsNumberField = "Downloads";
    $authorField = "Autor";




    //helpPage.php
    $quizManagerHelpHeading = "QuizManager Hilfe";
    $questionTypesText = "Die Quiz Manager Plattform unterstützt unterschiedliche Frageformate. Im folgenden werden diese genauer gezeigt und beschrieben.";

    $helpMenuImport = "Importieren";
    $helpMenuSubImportPossibilitys = "Import Möglichkeiten";
    $helpMenuSubQuestionTypes = "Frage Typen";
    $helpMenuSubImportStructure = "Import Struktur";

    $helpMenuExport = "Exportieren";

    $helpMenuTranslation = "Übersetzen";


    $helpContentsHeading = "Inhalt";

    $importPossibility1 = "Einfügen von Fragen mittels Datei Import";
    $importPossibility2 = "Einfügen von Fragen über das Webformular (coming soon)";
    $importPossibility1Description = "Mit dieser Funktion können Sie eine oder mehrere Fragen gleichzeitig importieren. 
    Welches format unterstützt ist und auf was Sie genau achten müssen, wird in dem Kapitel <a href='#Importstructure'>Import Struktur</a> erklärt";

    $importPossibility2Description = "Diese Funktion ist noch nicht implementiert, aber in naher Zukunft geplant.";

    $liYesNoQuestion = "Ja/Nein Frage";
    $liYesNoQuestionExample = "Bei diesem Fragetyp handelt es sich um einfach Fragen, 
    welche nur mit Ja oder Nein beantwortet werden können. Die Antwort im Quiz Manager System auf eine Solche Frage heißt deshalb immer True(Ja) oder False(Nein)";

    $liOpenQuestion = "Offene Frage";
    $liOpenQuestionExample = "Bei diesem Fragetyp handelt es sich eine eine offen gestellte Frage. 
    Das heißt es gibt keine festen Antwortmöglichkeiten für den Nutzer. Stattdessen kann der Nutzer selbst frei antworten.
    Dem Fragetyp wird dennoch eine Antwort als \"Musterlösung\" mitgegeben.";

    $liOptionsQuestion = "Auswahl Frage";
    $liOptionsQuestionExample = "Bei diesem Fragetyp handelt es sich um eine Frage mit fest vorgegebenen Antwortmöglichkeiten. 
    Von diesen Antworten ist allerdings zwingend nur eine Richtig. Die richtige Antwort ist grün markiert.";

    $liMultiOptionsQuestion = "Mehrfachauswahl Frage";
    $liMultiOptionsQuestionExample = "Bei diesem Fragetyp handelt es sich um eine Frage mit fest vorgegebenen Antwortmöglichkeiten, wie auch bei der Auswahl Frage. 
    Von diesen Antworten können allerdings mehrere richtig sein. Die richtigen Antworten sind grün markiert.";


    $helpTextImportStructure = "Für das Importieren von Fragen wird ein spezielles Format verwendet. 
    Momentan können nur .txt Dateien importiert werden, welche dieser Struktur entsprechen.";
    $generalQuestionStructureExample = "Jede Frage wird mittels dem \"#\" Zeichen in unterschiedliche Sektionen aufgeteilt. Die erste Sektion stellt den Fragetyp dar. 
    Die zweite Sektion beinhaltet den Fragetext. Alle weiteren Sektionen sind je nach Fragetyp unterschiedlich und werden nachfolgend genauer beschrieben.";
    
    $liYesNoQuestionStructureExample = "Bei einer Ja/Nein Frage beinhaltet die dritte Sektion die Antwort auf die Frage. 
    Diese kann nur einen der folgenden Werte haben: <b>True</b> / <b>False</b> ";
    $liOpenQuestionStructureExample = "Bei einer Offenen Frage beinhaltet die dritte Sektion die Antwort bzw. \"Musterlösung\" auf die Frage. 
    Diese kann jeden beliebigen Wert haben";
    $liOptionsQuestionStructureExample = "Bei einer auswahl Frage beinhaltet die dritte Sektion den Index der korrekten Antwort auf die Frage. 
    Es kann nur eine richtige Antwort geben. 
    Die Sektionen, welche auf die Antwort folgen, stellen jeweils Antwortmöglichkeiten dar. Im oben gezeigten Beispiel gibt es zwei Antwortmöglichkeiten. 
    Die Indizierung startet bei 0, das heißt die Antwort \"answer Option2\" wäre in diesem Beispiel die richtige Antwort.";
    $liMultiOptionsQuestionStructureExample = "Bei einer mehrfachauswahl Frage beinhaltet die dritte Sektion die Indizes der korrekten Antworten auf die Frage. 
    Es gibt also mehere richtige Antworten auf die Frage.
    Die Sektionen, welche auf die Antwort folgen, stellen jeweils Antwortmöglichkeiten dar. Im oben gezeigten Beispiel gibt es drei Antwortmöglichkeiten. 
    Die Indizierung startet bei 0, das heißt die Antworten \"Answer1\" und \"Answer2\" wäre in diesem Beispiel die richtigen Antworten.";


?>