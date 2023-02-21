<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Fragenkorb</h5>
    <button type="button" class="btn btn-light clearCart" name="clearCart" style="margin-right: auto;" onclick="emptyQuestionCart()">
      <img src="/quizVerwaltung/media/trash-can.svg" width="20px"/>
    </button>
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
    <button type="button" name="exportButton" class="btn btn-primary" style="width: 40%;" data-bs-toggle="modal" data-bs-target="#catalogOptions" onclick="createCatalog(this)">
      Katalog erstellen
    </button>
    
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exportDownload" aria-expanded="false" style="width: 40%;">
    Exportieren
    </button>

  </div>
</div>

<?php 
  include_once("modal_catalogOptions.php"); 
  include_once("modal_downloadExport.php");  
?>