<div class="modal fade" id="catalogOptions" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Catalog Options</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">  
      
        <input type="text" class="form-control" id="catalogName" placeholder="Please enter a catalog name">
        <hr>
        <p> Change the visibility setting for your catalog </p>
        <div class="btn-group" id="statusSetting" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="radioPublic" autocomplete="off" checked>
            <label class="btn btn-outline-success" for="radioPublic">Public</label>

            <input type="radio" class="btn-check" name="btnradio" id="radioPrivate" autocomplete="off">
            <label class="btn btn-outline-danger" for="radioPrivate">Private</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelCatalogSave">Close</button>
        <button type="button" class="btn btn-primary" id="catalogSave" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>