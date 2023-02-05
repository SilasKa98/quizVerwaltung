<div class="modal fade" id="changeLangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $InsertNewLanguageMainHeader; ?></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <?php echo $InsertNewLanguageTitel; ?><br>
            <select class="selLanguageDropDown" id="insertNewLanguageDrpDwn" name="language">
                <option></option>
                <option>de</option>
                <option>en-Us</option>
                <option>es</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="submitNewLanguageInsertBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
        </div>
        </div>
    </div>
</div>