<?php
$navbar = $_SERVER['DOCUMENT_ROOT'];
include_once ($navbar."/quizVerwaltung/frontend/catalogCart.php");
include_once ($navbar."/quizVerwaltung/services/mongoService.php");
$mongo = new MongoDBService();
$filterQuery = (['userId' => $userId]);
$getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
$cartCount = count((array)$getAccountInfos->questionCart);
if (!isset($cartCount)){$cartCount = 0;}


//check if app is running on http or https
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
?>



<nav class="navbar navbar-expand-xl bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/quizVerwaltung/index.php">
            <img src="/quizVerwaltung/media/logoQuizManagerTransparent2.png" alt="Logo" width="80"  class="d-inline-block align-text-top">
            <p id="headerName">Quiz Manager</p>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRight" aria-controls="navbarRight" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarRight">
            <div class="navbar-nav">
                <?php if(!isset($_SESSION["user_is_guest"])){?>
                    <a class="nav-link" aria-current="page" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion ?></a>
                <?php }?>
                <a class="nav-link" href="/quizVerwaltung/frontend/helpPage.php"><?php echo $helppageNavText;?></a>
                <a class="nav-link" href="https://www.thm.de/site/impressum.html" target="_blank">Impressum</a>
                <a class="nav-link" href="https://www.thm.de/site/datenschutz.html" target="_blank"><?php echo $datenschutzNavbar;?></a>
            </div>
            
            <ul class="navbar-nav ms-auto">
                
                <li class="nav-item">
                    <div class="d-flex" id="searchInSystemWrapper" role="search" style="margin: .5rem">
                        
                            <input class="form-control me-2" id="searchInSystem" type="search" placeholder="Search" aria-label="Search">
                            <div class="list-group" id="searchResultsWrapper">
                                <div class="accordion" id="searchResultAccordion">

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" id="searchResults_questions_header" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuestions" aria-expanded="true" aria-controls="collapseOne">
                                            <?php echo $searchResultQuestionsHeader; ?>
                                            </button>
                                        </h2>
                                        <div id="collapseQuestions" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#searchResultAccordion">
                                            <div class="accordion-body" id="searchResults_questions_body">
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingtwo">
                                            <button class="accordion-button" id="searchResults_users_header" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers" aria-expanded="true" aria-controls="collapseOne">
                                                <?php echo $searchResultUsersHeader; ?>
                                            </button>
                                        </h2>
                                        <div id="collapseUsers" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#searchResultAccordion">
                                            <div class="accordion-body" id="searchResults_users_body">
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
            
                    </div>
                </li>
                <?php if(!isset($_SESSION["user_is_guest"])){ ?>
                <li class="nav-item"> 
                    <button 
                        class="btn btn-outline-light shopping-cart" 
                        type="button"
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#offcanvasRight" 
                        aria-controls="offcanvasRight">
                        <img src="/quizVerwaltung/media/catalogIcon.png" width="40px"/>
                        <span class="badge text-bg-secondary" id="cartCount">
                            <?php echo $cartCount;?>
                        </span>
                    </button>
                </li>
                <?php }?>
                <li class="nav-item dropdown" id="navOpenDrpDwnBtn">
                    <?php if(!isset($_SESSION["user_is_guest"])){?>
                        <a id="ankerWrapUserLink" href="/quizVerwaltung/frontend/userProfile.php?profileUsername=<?php echo $username; ?>&section=questions">
                    <?php }?>  
                            <p id="usernameLink">
                                <?php echo $username; ?>
                            </p>
                            <img class="rounded-circle" src="/quizVerwaltung/media/defaultAvatar.png" alt="mdo" width="40" height="40">
                    <?php if(!isset($_SESSION["user_is_guest"])){?>
                        </a>    
                    <?php }?>
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                        <?php if(!isset($_SESSION["user_is_guest"])){?>
                            <li>
                                <!--dont remove, needed for bugfix-->
                                <form></form>
                            </li>
                            <li>
                            <form class="navbar-form navbar-right" method="post" action="/quizVerwaltung/doTransaction.php">
                                <input type="hidden" name="destination" value="<?php echo $protocol.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>">     
                                <select name="language" class="form-select" id="changeLangSel" onchange="this.form.submit()">
                                    <?php
                                        foreach($all_languages as $key => $value){
                                        echo "<option value='".$key."'"; if($key == $selectedLanguage){echo "selected='selected'";}  echo">".$value."</option>";
                                        }
                                    ?>
                                    </select>
                            </form>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/quizVerwaltung/frontend/userProfile.php?profileUsername=<?php echo $username; ?>&section=questions"><?php echo $myProfileLink; ?></a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/quizVerwaltung/frontend/usersSettings.php">Settings</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php }?>
                            <li>
                                <form class="navbar-form navbar-right" method="post" action="/quizVerwaltung/doTransaction.php">
                                    <button class="dropdown-item" type="submit" name="logout"><?php echo $text_logout_btn?></button>
                                </form>
                            </li>
                        </ul>
                </li>
            </ul> 
        </div>
    </div>
  </nav>
