<?php
  //TODO das auslagern damit man ggf. für die sprache nur das includen muss !!!!
  extract($_SESSION["userData"]);

  //get the selected userLanguage to display the system in the right language
  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once($root.'/quizverwaltung/services/mongoService.php');
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $getAccountInfos->userLanguage;
  include $root."/quizverwaltung/systemLanguages/text_".$selectedLanguage.".php";
?>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel"><?php echo $cartNameText?></h5>
    <button type="button" class="btn btn-light clearCart" name="clearCart" style="margin-right: auto;" onclick="emptyQuestionCart()">
      <img src="/quizVerwaltung/media/trash-can.svg" width="20px"/>
    </button>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="canvas-body">
    <?php
    //to declar an absolut path
    $catalogCart = $_SERVER['DOCUMENT_ROOT'];
    $catalogCart .= "/quizVerwaltung/services/cartService.php";
    include_once($catalogCart);
    $service = new CartService();
    $service->printCart();
    ?>
  </div>

  <div class="container-fluid" align="center" style="margin-bottom: 10px;" id=catalogButtons>
  <?php if(!isset($_SESSION["user_is_guest"])){?>
    <button type="button" name="exportButton" class="btn btn-primary" style="width: 40%;" data-bs-toggle="modal" data-bs-target="#catalogOptions" onclick="createCatalog(this)">
      <?php echo $createCatalogButton ?>
    </button>
  <?php }?>
    
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exportDownload" aria-expanded="false" style="width: 40%;">
      <?php echo $exportButton ?>
    </button>

  </div>
</div>

<?php 
  include_once("modal_catalogOptions.php"); 
  include_once("modal_downloadExport.php");  
?>