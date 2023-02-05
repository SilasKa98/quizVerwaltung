<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/quizVerwaltung/index.php">
            <img src="/quizVerwaltung/media/logoQuizManagerTransparent2.png" alt="Logo" width="80"  class="d-inline-block align-text-top">
            <p id="headerName">Quiz Manager</p>
        </a>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/quizVerwaltung/frontend/frontend_insertQuestion.php"><?php echo $navText_insertQuestion ?></a>
                <a class="nav-link active" href="/quizVerwaltung/frontend/userProfile.php?profileUsername=<?php echo $username; ?>"><?php echo $myProfileLink; ?></a>
                <a class="nav-link" href="#">Help</a>
            </div>
        </div>
    </div>
  </nav>