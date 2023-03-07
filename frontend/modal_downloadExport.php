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

<div class="modal fade" id="exportDownload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $downloadExportLabel?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  

        <form action="/quizVerwaltung/doTransaction.php" method="post" id="exportDownloadForm">
          <select class="form-select" aria-label="Default select example" id="exportTypesList" name="exportType" required>
            <option value="" disabled selected><?php echo $exportTypeOptions?></option>
            <option value="Moodle">Moodle XML</option>
            <option value="Latex">Latex</option>
            <option value="JSON">JSON</option>
            <option value="Standard">Standard</option>
          </select>
          <input type="hidden" name="method" id="downloadMethod" value="downloadCart">
          <br>
          <input type="text" class="form-control" name="exportName" id="exportName" placeholder="<?php echo $exportName?>" required>
          <hr>
          <button type="submit" class="btn btn-outline-primary" style="width: 100%" onclick="sendDownloadRequest()"><?php echo $downloadButton?></button>
        </form>
        <!--data-bs-dismiss="modal" -->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  //TODO auslagern in js file
  
  function sendDownloadRequest(){

    let exportTypesList = document.getElementById("exportTypesList").value;
    let exportName = document.getElementById("exportName").value;

    if(exportTypesList != "" && exportName != ""){
      setTimeout(function(){
        document.getElementById("exportTypesList").value = "";
        document.getElementById("exportName").value = "";
        $('#exportDownload').modal('hide');
      }, 800);
    }

  }
  
</script>