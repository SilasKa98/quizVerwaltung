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

    $upload_headingCreateQuestions = "Erstellen Sie direkt eine Frage mit unserem Formular";
    $upload_headingImportQuestions = "Laden Sie eine oder mehrer Fragen mit unserem Datei-Upload hoch";

    $upload_questionChoose = "Welche art von Frage wollen Sie erstellen?";
    $upload_createQuestionText = "Fragentext";
    $upload_createQuestionAnswer= "Antwort auf die Frage";
    $upload_addOption = "Option hinzufügen (richtige Antworten ankreuzen)";
    $upload_questionTags = "Fragen Tags";

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
    $changeVerificationModalHeader = "Ändern Sie den Verifikationsstatus";
    $currentVerificationStatusText = "Aktueller Status: ";
    $verifiedStatus = "Verifiziert";
    $notVerifiedStatus = "Nicht verifiziert";

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
    $questionVerifiedStatusDisplay = " Die Frage wurde von einem Admin verifiziert.";
    $questionNotVerifiedStatusDisplay = " Die Frage wurde noch nicht von einem Admin verifiziert.";


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
    $importPossibility2 = "Erstellen von Fragen über das Webformular";
    $importPossibility1Description = "Mit dieser Funktion können Sie eine oder mehrere Fragen gleichzeitig importieren. 
    Welches format unterstützt ist und auf was Sie genau achten müssen, wird in dem Kapitel <a href='#Importstructure'>Import Struktur</a> erklärt";

    $importPossibility2Description = "Mit dieser Funktion können Sie eine Frage direkt in unserem Webformular erstellen. Füllen Sie dazu einfach die angegeben Felder aus.";

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

    $liStructureCode = "Strukturierung von Code in den Fragen";
    $liStructureCodeExample = "Um Code in einer Frage besser zu strukturieren, kann der Code einfach in einen <b>".htmlspecialchars('<span></span>')."</b> HTML-Tag gesetzt werden. 
    Für Zeilenumbrüche kann der HTML-Tag <b>".htmlspecialchars('<br>')."</b> verwendet werden. Im oberen Bild wird ein Beispiel für den Import mit dem daraus resultierenden Code gezeigt.";




    $helpMenuSubExportPossibilitys = "Export Möglichkeiten";
    $helpTextExportPossibilitys = "Alle Fragen die Sie in Ihrem FragenKorb gesammelt haben oder die Sie als Fragenkatalog abgespeichert haben, können exportiert werden.";
    
    $exportPossibility1 = "Direkter Export aus dem Fragenkorb";
    $exportPossibility1Description = "Für den direkten Export aus dem Fragenkorb, verwenden Sie einfach den 
    <button type='button' class='btn btn-danger' disabled>Exportieren</button> Knopf.";

    $exportPossibility2 = "Export eines erstellten Fragenkatalogs";
    $exportPossibility2Description = "Für den Export eines Fragenkatalogs von Ihnen oder auch von anderen Nutzern, navigieren Sie im ersten Schritt auf das entsprechende Profil und klicken auf den Reiter \"Kataloge\". 
    Hinter jedem Katalog finden Sie einen <button type='button' class='btn btn-secondary' disabled>Export</button> Knopf, mit welchem der gesamte Katalog exportiert werden kann.";

    $exportModalExplanationHeader = "Erklärung des Exportfenster";
    $exportModalExplanationDescription  = "Im gezeigten Fenster können Sie mit dem Ersten Feld auswählen, in welches Format Sie Ihre Fragen exportieren wollen. 
    Mit dem zweite Freitextfeld können Sie den Namen festlegen, welchen Ihre Exportdatei erhalten soll.";


    $helpMenuSubExportTypes = "Export Typen";
    $helpTextExportTypes = "Für den Export stehen verschiedene Formate bereit, welche im Folgenden aufgezeigt und beschrieben werden.";

    $exportType1 = "Moodle XML";
    $moodleDocLink = "Moodle Dokumentation";
    $exportType1Description = "Es besteht die Möglichkeit Ihre ausgewählten Fragen in das gängige Moodle XML Format zu exportieren. 
    Die erstellte XML Datei, kann ohne weitere Schritte direkt in Moodle importiert werden.";

    $exportType2 = "Latex";
    $latexDocLink = "Latex Dokumentation";
    $exportType2Description = "Die Fragen können auch in das Latex Format übersetzt bzw. umgewandelt werden. 
    Bei dieser Exportform werden allerdings nicht alle Werte der Frage in das Latexformat umgewandelt. 
    Es wird lediglich die Frage mit allen Antworten umgewandelt. Durch diese Art des Exports, können Sie die Fragen direkt in eine Art \"Klausurform\" bringen.
    Die entstandene Latex-Datei können Sie anschließend von jedem gängigen Latex-compiler übersetzen lassen.";

    $exportType3 = "JSON";
    $jsonDocLink = "JSON Dokumentation";
    $exportType3Description = "Da JSON ein sehr gängiges Format im Webumweld ist, wird dieses auch als mögliches Exportformat unterstützt. 
    Beim Export erhalten Sie die gesamte Frage mit all ihren Werten als JSON-Object.";


    $exportType4 = "SimpQui/Standard";
    $simpQuiDocLink = "SimpQui Dokumentation";
    $exportType4Description = "Unser Standard Format, welches auch als SimpQui Format bekannt ist, wird ebenfalls als Exportformat unterstützt. 
    Es handelt sich um das selbe Format wie beim Import. Als Referenz zu diesem Format verweisen wir auf die Quiz Anwendung <a href='https://hosting.iem.thm.de/user/euler/quiz/index.php?inhalt=home' target='_blank'>SimpQui</a>";



    $helpMenuSubTranslationInfos = "Infos zur Übersetzung";
    $helpTextTranslationInfos = "Die Plattform Quiz Manager unterstützt grundsätzlich drei verschiedene Sprachen (Deutsch, Englisch, Spanisch) für die ganze Plattform.<br><br> 
    Da die Kernfunktionalität dieser Plattform das Teilen und Verwenden von Quizfragen ist, werden für die Quizfragen auch deutlich mehr Sprachen unterstützt. 
    Insgesamt können alle Fragen, welche auf die Plattform hochgeladen wurden, in 31 verschiedene Sprachen übersetzt werden.";

    $headerTranslationDeepl = "DeepL als Tool zur Übersetzung";
    $descriptionDeepL = "Zur Übersetung der Fragen wird die DeepL-API verwendet. DeepL verwendet maschinelle Übersetzungstechnologien,
     wodurch alle Fragen auf der Plattform mit hoher Präzision in eine gute sprachliche Form übersetzt werden können.";
    $deepLDocuLink = "DeepL Dokumentation";


    $helpMenuSubNewTranslation = "Neue Übersetzung";
    $helpTextCreateNewTranslation = "Jeder Nutzer kann für jede Frage eine neue Übersetzung erstellen.";
    $headerCreateNewTranslation = "Eine neue Übersetzung für eine Frage erstellen";
    $descriptionCreateNewTranslation = "Neben jeder Frage im System befindet sich ein Dropdown Menü, mit welchem verschiedenen Aktionen durchgeführt werden können. 
    Unter anderem gibt es die Möglichkeit für die ausgewählte Frage eine neue Übersetzung hinzuzufügen. 
    Diese Funktion kann durch Klick auf den  <img src=\"/quizVerwaltung/media/language.svg\" width=\"20px\">-Knopf aufgerufen werden. 
    Es öffnet sich ein Pop-up Fenster (siehe nachfolgender Screenshot), in welchem dann einfach die gewünschte Sprache ausgewählt werden kann.";


    $helpMenuSubExistingTranslation = "Vorhandene Übersetzung";
    $helpTextExistingTranslation = "Bereits vorhandene Übersetzungen können von allen Nutzern verwendet werden.";
    $headerExistingTranslation = "Eine vorhandene Übersetzung für eine Frage verwenden.";
    $descriptionExistingTranslation = "Sollte am rechten Rand einer Frage ein Drop-Down Menü zu sehen sein, sind für diese Frage bereits Übersetzungen vorhanden. 
    Andere Nutzer haben die Frage schon in verschiedene Sprachen übersetzt. Alle Übersetzungen werden gespeichert, um diese anschließend noch schneller für andere Nutzer verfügbar zu machen. 
    Durch aufklappen des Drop-Down Menüs kann überprüft werden, ob die gewünschte Übersetzung bereits existiert. 
    Falls ja, kann die gewünschte Sprache gewählt werden, um die Frage direkt zu übersetzen.";
?>