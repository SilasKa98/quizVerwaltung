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
?>