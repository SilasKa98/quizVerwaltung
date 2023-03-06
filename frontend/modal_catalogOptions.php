<?php
  //TODO das auslagern damit man ggf. für die sprache nur das includen muss !!!!
  extract($_SESSION["userData"]);

  //get the selected userLanguage to display the system in the right language
  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once($root.'/quizverwaltung/mongoService.php');
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $getAccountInfos= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $getAccountInfos->userLanguage;
  include $root."/quizverwaltung/systemLanguages/text_".$selectedLanguage.".php";
?>

<div class="modal fade" id="catalogOptions" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $catalogOptionsLabel?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  
      
        <input type="text" class="form-control" id="catalogName" placeholder="<?php echo $catalogNamePlaceholder?>">
        <hr>
        <p> <?php echo $catalogVisibilitySetting?> </p>
        <div class="btn-group" id="statusSetting" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="radioPublic" autocomplete="off" checked>
            <label class="btn btn-outline-success" for="radioPublic"><?php echo $publicButton?></label>

            <input type="radio" class="btn-check" name="btnradio" id="radioPrivate" autocomplete="off">
            <label class="btn btn-outline-danger" for="radioPrivate"><?php echo $privateButton?></label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelCatalogSave"><?php echo $optionsCloseButton?></button>
        <button type="button" class="btn btn-primary" id="catalogSave" data-bs-dismiss="modal"><?php echo $optionsSaveButton?></button>
      </div>
    </div>
  </div>
</div>