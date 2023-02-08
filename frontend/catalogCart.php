<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <!--<h5 class="offcanvas-title" id="offcanvasRightLabel">Fragenkatalog</h5>-->
    <button type="button" class="btn btn-light" style="--bs-btn-font-size: 1.25rem;">Fragenkorb</button>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="canvas-body">
    <?php
    //to declar an absolut path
    $catalogCart = $_SERVER['DOCUMENT_ROOT'];
    $catalogCart .= "/quizVerwaltung/cartService.php";
    include_once($catalogCart);
    $service = new CartService();
    $service->printCart();
    ?>
  </div>

  <div class="container-fluid" align="center" style="margin-bottom: 10px;" id=catalogButtons>
    <button type="button" class="btn btn-primary" style="width: 40%;">Katalog erstellen</button>
    
    <div class="btn-group">
      <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40%;">
      Exportieren
      </button>
        <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="TODO">Moodle</a></li>
        <li><a class="dropdown-item" href="TODO">TODO</a></li> <!-- //TODO noch weitere Formate dann einfügen -->
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="TODO">Standard</a></li>
      </ul>
    </div>

  </div>
</div>