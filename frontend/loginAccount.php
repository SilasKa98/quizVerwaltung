<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <style>
        .wrapperCard{
            width: 99%;
            border-radius: 10px;
            margin: 0 auto;
            margin-bottom: 1%;
        }
        .menuBtn{
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .exampleImg{
            border-radius: 7px;
        }

        .wrapp2{
            background-color: #e3e3e3;
        }

        .contactFormLabel{
            float:left;
            margin-top: 10px;
        }

        #submitContactFormular{
            width: 100%;
            margin-top: 20px;
        }

        .menuBar{
            position: relative; /* sorgt dafür, dass der Bereich an der ursprünglichen Stelle bleibt */
            top: 0;
        }

        .menuBar.fixed {
            position: fixed;
            z-index: 9999;
            background-color: #585858;
            padding-top: 12px;
            width: 100%;
            left: 0;
        }
        .menuBar.fixed a {
            color: white;
            border: 1px solid white;
        }

        #impressumLink{
            float:right;
            text-decoration: none;
            color: black;
            margin-right: 1%;
        }

        @media only screen and (max-width: 800px) {
            .menuBar.fixed {
                position: relative;
                top: 0;
                z-index: inherit;
                width: inherit;
                background-color: inherit;
                margin-top: inherit;
                padding-top: inherit;
                left: inherit;
            }
            .menuBar.fixed a {
                color: inherit;
                border: inherit;
            }
        }
    </style>

</head>
<body style="background-color: #cbc8c8">
<div id="topSection"></div>

    <div class="card wrapperCard" style="margin-top: 10px;">
        <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" style=" padding: 0 !important; margin: 0 !important; border-radius: 10px;">
            <br>
            <img class="d-block mx-auto mb-4" src="/quizVerwaltung/media/logoQuizManagerTransparent2.png" alt="" width="300">
            <div class="col-md-5 p-lg-5 mx-auto my-5" style="padding: 0 !important; width: 78%;">
                <h1 class="display-4 fw-normal">Quiz Manager</h1>
                <p class="lead fw-normal">An application to create, manage and share all kind of quiz questions.</p>
                <div class="menuBar">
                    <a class="btn btn-outline-secondary menuBtn" href="#loginSection">Login</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#uploadSection">Upload Questions</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#translateSection">Translate Questions</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#connectWithOthersSection">Conncect with Others</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#exportSection">Export Questions</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#catalogSection">Create Catalogs</a>
                    <a class="btn btn-outline-secondary menuBtn" href="#contactUs">Contact us</a>
                </div>
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
        </div>
    </div>

    <div class="card wrapperCard wrapp2" id="loginSection">
        <div class="container col-xl-10 col-xxl-8 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bold lh-1 mb-3">Login To Your Account now</h1>
                    <p class="col-lg-10 fs-4">
                        Get access to a variety of different quiz questions from many different categories.
                        Or easily create your own questions and get feedback from other users or even gain fans of your content.
                    </p>
                </div>
                <div class="col-md-10 mx-auto col-lg-5">
                    <form class="p-4 p-md-4 border rounded-3 bg-light" action="/quizVerwaltung/doTransaction.php" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" name="mailuid" class="form-control" id="floatingInput" placeholder="E-mail or Username">
                            <label for="floatingInput">Email or Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" name="pwd" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                            <input type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <input type="hidden" name="method" value="loginAccount">
                        <input  type="submit" class="w-100 btn btn-lg btn-primary" name="login_submit" value="Login">
                        <hr class="my-4">
                    </form>
                    <small class="text-muted">No Account Yet?<a href="registerAccount.php"> Sign up now!</a></small>
                    <small class="text-muted">Or continue as
                            <form action="/quizVerwaltung/doTransaction.php" method="post" style="display:inline;">
                                <input type="hidden" name="method" value="guestLogin">
                                <a href="#" onclick="this.closest('form').submit();return false;">Guest</a>
                            </form>
                        </small>
                </div>
            </div>
        </div>
    </div>


    <div class="card wrapperCard" id="uploadSection">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <!--<img src="/quizVerwaltung/media/insertExamplePicture.png" style="border-radius: 7px;" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">-->
                    <img src="/quizVerwaltung/media/insertOverviewExamplePicture.PNG" class="d-block mx-lg-auto img-fluid exampleImg" alt="Bootstrap Themes"  loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Easily upload new Questions</h1>
                    <p class="lead">
                        Quickly upload new Questions with our intuitive import Format. It even allows you to import multiple questions at once.
                        Our import overview helps you to keep track of the Questions and its values you are about to upload.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a type="button" class="btn btn-primary" href="helpPage.php#importPosibilitys">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card wrapperCard wrapp2" id="translateSection">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Automatically translate questions in whatever language you need </h1>
                    <p class="lead">
                        With our connection to the DeepL Api, we can translate all questions into a variety of languages.
                        Questions that might have been uninteressting for you, are now usable and can also be used by you.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a type="button" class="btn btn-primary" href="helpPage.php#translationInfos">Read more</a>
                    </div>
                </div>
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="/quizVerwaltung/media/translateExamplePicture.PNG" class="d-block mx-lg-auto img-fluid exampleImg" width="500" height="300" alt="Bootstrap Themes" loading="lazy">
                </div>
            </div>
        </div>
    </div>


    <div class="card wrapperCard" id="connectWithOthersSection">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <!--<img src="/quizVerwaltung/media/insertExamplePicture.png" style="border-radius: 7px;" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">-->
                    <img src="/quizVerwaltung/media/ProfileExamplePicture.PNG" class="d-block mx-lg-auto img-fluid exampleImg" alt="Bootstrap Themes"  loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Connect with People and earn Reputation</h1>
                    <p class="lead">
                        Follow people whose content you like or get followers yourself by posting good questions.
                        In addition, questions can be rated by other users, maybe you are the one who uploads the best and most popular questions of the community.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a type="button" class="btn btn-primary" href="helpPage.php">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card wrapperCard wrapp2" id="exportSection">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Choose questions and export them in different Formats </h1>
                    <p class="lead">
                       Every Question can be added to your cart to later Export it in a variety of different Formats.
                       Currently we are Supporting the Moodle XML Format, the Latex format and our own Format we are also using to import questions.
                       We are planning for more in the future to come!
                       Easily select which format you would like to get in the Dropdown-Menu of the Export.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a type="button" class="btn btn-primary" href="helpPage.php#exportPossibilitys">Read more</a>
                    </div>
                </div>
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="/quizVerwaltung/media/exportExamplePicture.PNG" class="d-block mx-lg-auto img-fluid exampleImg" alt="Bootstrap Themes" loading="lazy">
                </div>
            </div>
        </div>
    </div>


    <div class="card wrapperCard" id="catalogSection">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <!--<img src="/quizVerwaltung/media/insertExamplePicture.png" style="border-radius: 7px;" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">-->
                    <img src="/quizVerwaltung/media/catalogExamplePicture.PNG" class="d-block mx-lg-auto img-fluid exampleImg" style="width: 450px" alt="Bootstrap Themes"  loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Create catalogs to group questions</h1>
                    <p class="lead">
                        Do you want to group questions from a similar topic?
                        Simply create a catalog, which can later be exported as a whole. By means of the visibility setting you can determine whether other users can see this catalog or not.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a type="button" class="btn btn-primary" href="helpPage.php">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card wrapperCard wrapp2" id="contactUs">
        <div class="px-4 py-5 my-5 text-center">
                <h1 class="display-5 fw-bold">Contact us</h1>
                <div class="col-lg-6 mx-auto" style="width: 70%;">
                <p class="lead mb-4">
                    Do you have any questions or suggestions for us? Feel free to leave us a message using the form below
                </p>
                <form>
                    <div class="form-group">
                        <label for="FormControlInput1" class="contactFormLabel">Your Name*</label>
                        <input type="email" class="form-control" id="FormControlInput1">
                    </div>
                    <div class="form-group">
                        <label for="FormControlInput2" class="contactFormLabel">Your Email*</label>
                        <input type="email" class="form-control" id="FormControlInput2">
                    </div>
                    <div class="form-group">
                        <label for="FormControlTextarea3" class="contactFormLabel">Your Message*</label>
                        <textarea class="form-control" id="FormControlTextarea3" rows="3"></textarea>
                    </div>
                    <input id="submitContactFormular" type="submit" value="SEND MESSAGE" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>
    <?php include_once "footer.php"; ?>
</body>
<script>
//smooth scroll to section
$(document).ready(function(){
   $('a[href^="#"]').on('click', function(event) {
       var target = $(this.getAttribute('href'));
       if( target.length ) {
           event.preventDefault();
           $('html, body').stop().animate({
               scrollTop: target.offset().top -200
           }, 200);
       }
   });
});


/**
 * Pinn the navbar to the top if out of vision
 */
var fixedBereich = document.querySelector('.menuBar');
// Erstellt einen neuen Observer
var observer = new IntersectionObserver(function(entries) {
  // Durchläuft alle Einträge, die der Observer überwacht
  entries.forEach(function(entry) {
    if(!entry.isIntersecting){
            // Wenn das Element nicht mehr sichtbar ist, füge die CSS-Klasse "fixed" hinzu
      fixedBereich.classList.add('fixed');
    }
  });
});


/**
 * remove the pin when at the top
 */
const distanceToTop = document.querySelector('#topSection').getBoundingClientRect().top;
window.addEventListener('scroll', function() {
  if (window.scrollY === 0) {
    fixedBereich.classList.remove('fixed');
  }
});

// Fügt das Element dem Observer hinzu
observer.observe(fixedBereich);

</script>
</html>