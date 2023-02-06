<?php include_once "catalogCart.php";?>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/quizVerwaltung/index.php">
            <img src="/quizVerwaltung/media/logoQuizManagerTransparent2.png" alt="Logo" width="80"  class="d-inline-block align-text-top">
            <p id="headerName">Quiz Manager</p>
        </a>
        
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion ?></a>
                <a class="nav-link" href="#">Help</a>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbarRight">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"> 
                    <button 
                        class="btn btn-outline-light" 
                        type="button"
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#offcanvasRight" 
                        aria-controls="offcanvasRight">
                        <img src="/quizVerwaltung/media/catalogIcon.png" width="40px"/>
                    </button>
                </li>
                
                <li class="nav-item">
                    <form class="d-flex" role="search" style="margin: .5rem"> <!-- //TODO hier irgenwie anders centern -->
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>

                <li class="nav-item dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle" src="/quizVerwaltung/media/defaultAvatar.png" alt="mdo" width="40" height="40">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li>
                                <a class="dropdown-item" href="/quizVerwaltung/frontend/userProfile.php?profileUsername=<?php echo $username; ?>"><?php echo $myProfileLink; ?></a>
                            </li>
                            <li>
                                <form method="post" action="/quizVerwaltung/doTransaction.php"> <!--id="languageForm"-->
                                    <input type="hidden" name="destination" value="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>">
                                    <select name="language" onchange="this.form.submit()">
                                    <?php
                                        foreach($all_languages as $key => $value){
                                        echo "<option value='".$key."'"; if($key == $selectedLanguage){echo "selected='selected'";}  echo">".$value."</option>";
                                        }
                                    ?>
                                    </select>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
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
