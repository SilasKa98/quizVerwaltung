<div class="modal fade" id="exportDownload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create & download your export here</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  

        <form action="/quizverwaltung/doTransaction.php" method="post" value="download">
          <select class="form-select" aria-label="Default select example" id="exportTypesList" name="exportTypes">
            <option value="" disabled selected>Export type (default 'Standard')</option>
            <option value="Moodle">Moodle XML</option>
            <option value="TODO">TODO</option>
            <option value="Standard">Standard</option>
          </select>
          <br>
          <input type="text" class="form-control" id="catalogName" placeholder="Please name your export (default 'newCatalog')">
          <hr>
          <button class="btn btn-outline-primary"
            data-bs-dismiss="modal" id="download" style="width: 100%">
            Download
          </button>
        <form>
        
        </div>
      </div>
    </div>
  </div>
</div>