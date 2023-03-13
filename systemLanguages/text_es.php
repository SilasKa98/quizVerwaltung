<?php
    //general --> needs to be set in every text_xx.php
    $all_languages=[
        "de"=>"German",
        "en-Us"=>"English",
        "es"=>"Spanish"
    ];

    //frontend_insertQuestion.php
    $text_import_form = [
        "select_file"=> "Seleccione Archivo",
        "import_btn"=> "Importar",
        "clean_db_btn"=> "Limpiar base de datos (dev only)"
    ];

    $upload_headingCreateQuestions = "Cree una pregunta directamente con nuestro formulario";
    $upload_headingImportQuestions = "Cargue una o varias preguntas con nuestra carga de archivos";

    $upload_questionChoose = "¿Qué tipo de pregunta quiere crear?";
    $upload_createQuestionText = "Texto de la pregunta";
    $upload_createQuestionAnswer= "Respuesta a la pregunta";
    $upload_addOption = "Añadir opción (marque las respuestas correctas)";
    $upload_questionTags = "Preguntas Etiquetas";

    //index.php
    $text_logout_btn = "Cerrar sesión";
    $welcomeTitel = "Hola";
    $navText_insertQuestion = "Insertar pregunta";
    $profileInfoText = "Elija sus categorías favoritas y vea siempre lo que le interesa.";
    $myProfileLink = "Mi Perfil";
    $latestQuestionsFollowing = "Últimas preguntas de las personas a las que sigues";
    $personsYouMayKnow = "Personas que podrían interesarle:";

    //Toast (index.php)
    $toastTimeDisplay = "justo ahora";

    //questionSection.php
    $InsertNewLanguageMainHeader = "Elegir un nuevo idioma para la pregunta";
    $InsertNewLanguageTitel = "Seleccione su idioma deseado";

    //insertQuestions.php
    $importCheckPageTitel = "Descripción general de la importación";
    $selectYourTagsHeader = "Seleccione las etiquetas adecuadas para la pregunta";
    $adjustButton = "Ajustar";
    $finalizeImportButton = "Preguntas frecuentes";
    $giveTagsReminderAlert = "¡Atención! Si no asigna etiquetas a las preguntas, a otros usuarios les resultará difícil encontrar sus preguntas.";
    $checkHelpPageAlertText = "Antes de importar cualquier cosa, consulte nuestra ";
    $checkHelpPageBtn = "Página de ayuda";

    //userProfile.php
    $userHasNoQuestionsYet = "todavía no ha creado ninguna pregunta.";
    $userHasNoCatalogsYet = "aún no tiene catálogos";
    $fullNameField = "Nombre";
    $languageField = "Idioma";
    $userTotalKarmaOwned = "Karma ganada:";
    $userTotalQuestionsSubmitted = "Preguntas creadas:";
    $userJoinDateInfo = "Se unió a";
    $followedBtnText = "Seguida";
    $notFollowedBtnText = "Seguir";
    $showFollowerText = "Seguidor";
    $showFollowingText = "Siguiente";
    $questionsTabText = "Pregunta";
    $catalogsTabText = "Catálogos";

    //navbar.php
    $searchResultUsersHeader = "Usuarias";
    $searchResultQuestionsHeader = "Preguntas";

    //searchResult (navbar.php) but used in a script
    $noSearchMatches = "Ninguna coincidencia adecuada";

    //editQuestion.php
    $writeNewQuestionHeader = "Cambiar el texto de la pregunta";

     //usersSettings.php
     $deleteAccountText = "Eliminar permanentemente tu cuenta";
     $adminAccessRequestText = "Solicitar cuenta de administrador";
     $adminAccountHeader = "Cuenta de administrador";
     $adminAccountExampleText = "Con una cuenta de administrador, puede editar o eliminar todas las preguntas. También puede verificar las preguntas para confirmar su exactitud.";
     $adminAccessRequestButton = "Solicitar";
     $deleteAccountHeader = "Borrar cuenta";
     $deleteAccountExampleText = "¡Atención! Al realizar esta acción, se eliminará su cuenta y todos los datos asociados. Tus preguntas y todas las interacciones también se perderán.";
     $deleteAccountButton = "Borrar";

    //catalogCart.php
    $cartNameText = "Cesta de preguntas";
    $createCatalogButton = "Crear catálogo";
    $exportButton = "Exportar";
    //cartService.php
    $cartInfoText = "   Actualmente no tiene preguntas en su cesta.
                        Simplemente agregue una pregunta abriendo el menú desplegable junto a una pregunta y haciendo clic en el carrito de compras.
                    ";

    //modal_catalogOptions.php
    $catalogOptionsLabel = "Opciones de catálogo";
    $catalogNamePlaceholder = "Por favor, introduzca un nombre de catálogo";
    $catalogVisibilitySetting = "Cambia la configuración de visibilidad de tu catálogo";
    $publicButton = "Público";
    $privateButton = "Privado";
    $optionsCloseButton = "Cerca";
    $optionsSaveButton = "Ahorrar";

    //modal_downloadExport.php
    $downloadExportLabel = "Cree y descargue su exportación aquí";
    $exportTypeOptions = "Tipo de exportación (predeterminado 'Standard')";
    $exportName = "Asigne un nombre a su exportación (predeterminado 'newCatalog')";
    $downloadButton = "Descargar";

    //printService.php & cartService.php Question prints
    $answerField = "Respuesta";
    $questionTypeField = "Tipo";
    $optionsField = "Opciones";
    $creationDateField = "Fecha de creación";
    $lastChangeField = "Última actualización";
    $versionField = "Versión";
    $tagsField = "Etiquetas";
    $downloadsNumberField = "Descargas";
    $authorField = "Autor";



     //helpPage.php
     //TODO Translate into spanish !!!
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
 
     $liYesNoQuestion = "Sí/No Pregunta";
     $liYesNoQuestionExample = "This type of question is a simple question, 
     which can only be answered with Yes or No. Therefore, the answer in the Quiz Manager system to such a question is always True (Yes) or False (No).";
 
     $liOpenQuestion = "Pregunta abierta";
     $liOpenQuestionExample = "This type of question is an open-ended question. 
     This means that there are no fixed answer options for the user. Instead, the user can answer freely.
     The question type is nevertheless given an answer as a \"sample solution\".";
 
     $liOptionsQuestion = "Opciones Pregunta";
     $liOptionsQuestionExample = "This question type is a question with fixed answer options. 
     However, only one of these answers is correct. The correct answer is marked in green.";
 
     $liMultiOptionsQuestion = "Multiopciones Pregunta";
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
?>