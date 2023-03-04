<div class="modal fade" id="exportDownload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create & download your export here</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  

        <form action="/quizVerwaltung/doTransaction.php" method="post" id="exportDownloadForm">
          <select class="form-select" aria-label="Default select example" id="exportTypesList" name="exportType" required>
            <option value="" disabled selected>Export type (default 'Standard')</option>
            <option value="Moodle">Moodle XML</option>
            <option value="TODO">TODO</option>
            <option value="JSON">JSON</option>
            <option value="Standard">Standard</option>
          </select>
          <input type="hidden" name="method" id="downloadMethod" value="downloadCart">
          <br>
          <input type="text" class="form-control" name="exportName" id="exportName" placeholder="Please name your export (default 'newCatalog')" required>
          <hr>
          <button type="submit" class="btn btn-outline-primary" style="width: 100%" onclick="sendDownloadRequest()">Download</button>
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