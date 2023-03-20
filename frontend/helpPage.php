<?php

session_start();
if(!isset($_SESSION["logged_in"])){
  $selectedLanguage = "en-Us";
}else{
    extract($_SESSION["userData"]);
    //get the selected userLanguage to display the system in the right language
    include_once "../services/mongoService.php";
    $mongo = new MongoDBService();
    $filterQuery = (['userId' => $userId]);
    $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
    $selectedLanguage = $getAccountInfos->userLanguage;
}

//can be removed later when es is added.
if($selectedLanguage == "es"){
    echo "Currently Spanish is not supported for the Helppage, please select a different Language.";
    exit();
}

include "../systemLanguages/text_".$selectedLanguage.".php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>

    .exampleImage{
        width: 100%;
    }

    .questionInfoText{
        margin-top: 1%;
        margin-bottom: 8%;
    }

    .importPossibilityText{
        margin-top: 1% !important;
    }

    .questionUl{
        list-style-type: none;
    }

    .questionTypeHeading, .importPossibility{
        font-size: 15pt;
    }
        
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        body {
            scroll-behavior: smooth;
        }

/**
 * Bootstrap "Journal code" icon
 * @link https://icons.getbootstrap.com/icons/journal-code/
 */
.bd-heading a::before {
  display: inline-block;
  width: 1em;
  height: 1em;
  margin-right: .25rem;
  content: "";
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%25230d6efd' viewBox='0 0 16 16'%3E%3Cpath d='M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1H2a2 2 0 0 1 2-2z'/%3E%3Cpath d='M2 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H2z'/%3E%3Cpath fill-rule='evenodd' d='M8.646 5.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 8 8.646 6.354a.5.5 0 0 1 0-.708zm-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 8l1.647-1.646a.5.5 0 0 0 0-.708z'/%3E%3C/svg%3E");
  background-size: 1em;
}

/* stylelint-disable-next-line selector-max-universal */
.bd-heading + div > * + * {
  margin-top: 3rem;
}

/* Table of contents */
.bd-aside a {
  padding: .1875rem .5rem;
  margin-top: .125rem;
  margin-left: .3125rem;
  color: rgba(0, 0, 0, .65);
  text-decoration: none;
}

.bd-aside a:hover,
.bd-aside a:focus {
  color: rgba(0, 0, 0, .85);
  background-color: rgba(121, 82, 179, .1);
}

.bd-aside .active {
  font-weight: 600;
  color: rgba(0, 0, 0, .85);
}

.bd-aside .btn {
  padding: .25rem .5rem;
  font-weight: 600;
  color: rgba(0, 0, 0, .65);
  border: 0;
}

.bd-aside .btn:hover,
.bd-aside .btn:focus {
  color: rgba(0, 0, 0, .85);
  background-color: rgba(121, 82, 179, .1);
}

.bd-aside .btn:focus {
  box-shadow: 0 0 0 1px rgba(121, 82, 179, .7);
}

.bd-aside .btn::before {
  width: 1.25em;
  line-height: 0;
  content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
  transition: transform .35s ease;

  /* rtl:raw:
  transform: rotate(180deg) translateX(-2px);
  */
  transform-origin: .5em 50%;
}

.bd-aside .btn[aria-expanded="true"]::before {
  transform: rotate(90deg)/* rtl:ignore */;
}


/* Examples */
.scrollspy-example {
  position: relative;
  height: 200px;
  margin-top: .5rem;
  overflow: auto;
}

[id="modal"] .bd-example .btn,
[id="buttons"] .bd-example .btn,
[id="tooltips"] .bd-example .btn,
[id="popovers"] .bd-example .btn,
[id="dropdowns"] .bd-example .btn-group,
[id="dropdowns"] .bd-example .dropdown,
[id="dropdowns"] .bd-example .dropup,
[id="dropdowns"] .bd-example .dropend,
[id="dropdowns"] .bd-example .dropstart {
  margin: 0 1rem 1rem 0;
}

/* Layout */
@media (min-width: 1200px) {
  body {
    display: grid;
    gap: 1rem;
    grid-template-columns: 1fr 4fr 1fr;
    grid-template-rows: auto;
  }

  .bd-header {
    position: fixed;
    top: 0;
    /* rtl:begin:ignore */
    right: 0;
    left: 0;
    /* rtl:end:ignore */
    z-index: 1030;
    grid-column: 1 / span 3;
  }

  .bd-aside,
  .bd-cheatsheet {
    padding-top: 4rem;
  }

  /**
   * 1. Too bad only Firefox supports subgrids ATM
   */
  .bd-cheatsheet,
  .bd-cheatsheet section,
  .bd-cheatsheet article {
    display: inherit; /* 1 */
    gap: inherit; /* 1 */
    grid-template-columns: 1fr 4fr;
    grid-column: 1 / span 2;
    grid-template-rows: auto;
  }

  .bd-aside {
    grid-area: 1 / 3;
    scroll-margin-top: 4rem;
  }

  .bd-cheatsheet section,
  .bd-cheatsheet section > h2 {
    top: 2rem;
    scroll-margin-top: 2rem;
  }

  .bd-cheatsheet section > h2::before {
    position: absolute;
    /* rtl:begin:ignore */
    top: 0;
    right: 0;
    bottom: -2rem;
    left: 0;
    /* rtl:end:ignore */
    z-index: -1;
    content: "";
    background-image: linear-gradient(to bottom, rgba(255, 255, 255, 1) calc(100% - 3rem), rgba(255, 255, 255, .01));
  }

  .bd-cheatsheet article,
  .bd-cheatsheet .bd-heading {
    top: 8rem;
    scroll-margin-top: 8rem;
  }

  .bd-cheatsheet .bd-heading {
    z-index: 1;
  }
}

    
    </style>

</head>
<body>

<body class="bg-light">
    
<header class="bd-header bg-dark py-3 d-flex align-items-stretch border-bottom border-dark">
  <div class="container-fluid d-flex align-items-center">
    <a href="/quizVerwaltung/index.php" style="text-decoration:none;">
        <h1 class="d-flex align-items-center fs-4 text-white mb-0">
            <img src="/quizVerwaltung/media/logoQuizManagerTransparent2.png" width="50"  class="me-3" alt="Bootstrap">
            <?php echo $quizManagerHelpHeading;?>
        </h1>
    </a>
  </div>
</header>
<aside class="bd-aside sticky-xl-top text-muted align-self-start mb-3 mb-xl-5 px-2">
  <h2 class="h6 pt-4 pb-3 mb-4 border-bottom"><?php echo $helpContentsHeading;?></h2>
  <nav class="small" id="toc">
    <ul class="list-unstyled">
      <li class="my-2">
        <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#contents-collapse" aria-controls="contents-collapse"><?php echo $helpMenuImport;?></button>
        <ul class="list-unstyled ps-3 collapse" id="contents-collapse" style="">
          <li><a class="d-inline-flex align-items-center rounded" href="#importPosibilitys"><?php echo $helpMenuSubImportPossibilitys;?></a></li>
          <li><a class="d-inline-flex align-items-center rounded" href="#questionTypes"><?php echo $helpMenuSubQuestionTypes;?></a></li>
          <li><a class="d-inline-flex align-items-center rounded" href="#Importstructure"><?php echo $helpMenuSubImportStructure;?></a></li>
        </ul>
      </li>
      <li class="my-2">
        <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#forms-collapse" aria-controls="forms-collapse"><?php echo $helpMenuExport;?></button>
        <ul class="list-unstyled ps-3 collapse" id="forms-collapse" style="">
          <li><a class="d-inline-flex align-items-center rounded" href="#exportPossibilitys"><?php echo $helpMenuSubExportPossibilitys;?></a></li>
          <li><a class="d-inline-flex align-items-center rounded" href="#exportTypes"><?php echo $helpMenuSubExportTypes;?></a></li>
        </ul>
      </li>
      <li class="my-2">
        <button class="btn d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#components-collapse" aria-controls="components-collapse"><?php echo $helpMenuTranslation;?></button>
        <ul class="list-unstyled ps-3 collapse" id="components-collapse">
          <li><a class="d-inline-flex align-items-center rounded" href="#foo2">coming soon</a></li>
          <li><a class="d-inline-flex align-items-center rounded" href="#bar2">coming soon</a></li>
          <li><a class="d-inline-flex align-items-center rounded" href="#buz2">coming soon</a></li>
        </ul>
      </li>
    </ul>
  </nav>
</aside>
<div class="bd-cheatsheet container-fluid bg-body">
  <section id="content">
    <h2 class="sticky-xl-top fw-bold pt-3 pt-xl-5 pb-2 pb-xl-3"><?php echo $helpContentsHeading;?></h2>


    <article class="my-3" id="importPosibilitys">
        <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
            <h3><?php echo $helpMenuSubImportPossibilitys;?></h3>
            <a class="d-flex align-items-center" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion;?></a>
        </div>

        <div>
            <br>
            <h2 class="importPossibility"><?php echo $importPossibility1;?></h2>
            <div class="alert alert-dark importPossibilityText" role="alert">
                <p>
                    <?php echo $importPossibility1Description;?>
                </p>
            </div>
            <h2 class="importPossibility"><?php echo $importPossibility2;?></h2>
            <div class="alert alert-dark importPossibilityText" role="alert">
                <p>
                    <?php echo $importPossibility2Description;?>
                </p>
            </div>
        </div>

    </article>


    <article class="my-3" id="questionTypes">
        <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
            <h3><?php echo $helpMenuSubQuestionTypes;?></h3>
            <a class="d-flex align-items-center" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion;?></a>
        </div>

        <div>
            <br>
            <h2 class="questionTypeHeading"><?php echo $questionTypesText;?></h2>
            <ul class="questionUl">
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liYesNoQuestion;?></h2>
                    <img src="/quizVerwaltung/media/yesNoQuestionExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liYesNoQuestionExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liOpenQuestion;?></h2>
                    <img src="/quizVerwaltung/media/openQuestionExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liOpenQuestionExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liOptionsQuestion;?></h2>
                    <img src="/quizVerwaltung/media/optionsQuestionExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liOptionsQuestionExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liMultiOptionsQuestion;?></h2>
                    <img src="/quizVerwaltung/media/multiOptionsQuestionExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liMultiOptionsQuestionExample;?></p>
                    </div>
                </li>
            </ul>
        </div>

    </article>


    <article class="my-3" id="Importstructure">
        <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
            <h3><?php echo $helpMenuSubImportStructure;?></h3>
            <a class="d-flex align-items-center" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion;?></a>
        </div>

        <div>
            <br>
            <h2 class="questionTypeHeading"><?php echo $helpTextImportStructure;?></h2>
            <div class="alert alert-dark" role="alert">
                <p><?php echo $generalQuestionStructureExample;?></p>
            </div>
            <ul class="questionUl">
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liYesNoQuestion;?></h2>
                    <img src="/quizVerwaltung/media/yesNoQuestionStructure.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liYesNoQuestionStructureExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liOpenQuestion;?></h2>
                    <img src="/quizVerwaltung/media/openQuestionStructure.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liOpenQuestionStructureExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liOptionsQuestion;?></h2>
                    <img src="/quizVerwaltung/media/optionsQuestionStructure.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liOptionsQuestionStructureExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liMultiOptionsQuestion;?></h2>
                    <img src="/quizVerwaltung/media/multiOptionsQuestionStructure.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liMultiOptionsQuestionStructureExample;?></p>
                    </div>
                </li>
                <li>
                    <h2 class="questionTypeHeading"><?php echo $liStructureCode;?></h2>
                    <img src="/quizVerwaltung/media/codeStructureImportExample.PNG" class="exampleImage">
                    <img src="/quizVerwaltung/media/codeStructureResultExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $liStructureCodeExample;?></p>
                    </div>
                </li>
        </div>

    </article>



    <article class="my-3" id="exportPossibilitys">
        <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
            <h3><?php echo $helpMenuSubExportPossibilitys;?></h3>
        </div>

        <div>
            <br>
            <h2 class="importPossibility"><?php echo $helpTextExportPossibilitys;?></h2>
            <h2 class="importPossibility"><?php echo $exportPossibility1;?></h2>
            <div class="alert alert-dark importPossibilityText" role="alert">
                <p>
                    <?php echo $exportPossibility1Description;?>
                </p>
            </div>
            <h2 class="importPossibility"><?php echo $exportPossibility2;?></h2>
            <div class="alert alert-dark importPossibilityText" role="alert">
                <p>
                    <?php echo $exportPossibility2Description;?>
                </p>
            </div>

            <h2 class="importPossibility"><?php echo $exportModalExplanationHeader;?></h2>
            <img src="/quizVerwaltung/media/exportModalExample.PNG"  style="width: 40%; margin-top: 1%;">
            <div class="alert alert-dark importPossibilityText" role="alert">
                <p>
                    <?php echo $exportModalExplanationDescription;?>
                </p>
            </div>
        </div>

    </article>
    
    <article class="my-3" id="exportTypes">
        <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
            <h3><?php echo $helpMenuSubExportTypes;?></h3>
        </div>

        <div>
            <br>
            <h2 class="questionTypeHeading"><?php echo $helpTextExportTypes;?></h2>

            <ul class="questionUl">
                <li>
                    <h2 class="questionTypeHeading"><?php echo $exportType1;?></h2>
                    <div class="bd-heading mt-5 mb-3 mt-xl-0 mb-xl-2">
                      <a class="d-flex align-items-center" target="_blank" href="https://docs.moodle.org/401/de/Moodle_XML-Format"><?php echo $moodleDocLink;?></a>
                    </div>
                    <img src="/quizVerwaltung/media/moodleExportExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $exportType1Description;?></p>
                    </div>
                </li>

                <li>
                    <h2 class="questionTypeHeading"><?php echo $exportType2;?></h2>
                    <div class="bd-heading mt-5 mb-3 mt-xl-0 mb-xl-2">
                      <a class="d-flex align-items-center" target="_blank" href="https://www.latex-project.org/help/documentation/"><?php echo $latexDocLink;?></a>
                    </div>
                    <img src="/quizVerwaltung/media/latexExportExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $exportType2Description;?></p>
                    </div>
                </li>

                <li>
                    <h2 class="questionTypeHeading"><?php echo $exportType3;?></h2>
                    <div class="bd-heading mt-5 mb-3 mt-xl-0 mb-xl-2">
                      <a class="d-flex align-items-center" target="_blank" href="https://www.json.org/json-en.html"><?php echo $jsonDocLink;?></a>
                    </div>
                    <img src="/quizVerwaltung/media/jsonExportExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $exportType3Description;?></p>
                    </div>
                </li>

                <li>
                    <h2 class="questionTypeHeading"><?php echo $exportType4;?></h2>
                    <div class="bd-heading mt-5 mb-3 mt-xl-0 mb-xl-2">
                      <a class="d-flex align-items-center" target="_blank" href="https://hosting.iem.thm.de/user/euler/quiz/index.php?inhalt=info"><?php echo $simpQuiDocLink;?></a>
                    </div>
                    <img src="/quizVerwaltung/media/simpQuiExportExample.PNG" class="exampleImage">
                    <div class="alert alert-dark questionInfoText" role="alert">
                        <p><?php echo $exportType4Description;?></p>
                    </div>
                </li>
            </ul>
        </div>

    </article>

</body>
</html>