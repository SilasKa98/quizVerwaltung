<?php

$newLanguageModal = $_SERVER['DOCUMENT_ROOT'];
include_once ($newLanguageModal."/quizVerwaltung/translationService.php");

//hardcoding "de" as targetLanguage, because its not needed for this case..but it must be set
$translator = new TranslationService("de");
$allLanguages = $translator->getAllTargetLanguages();

?>


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
                    <?php foreach($allLanguages as $language){ ?>
                        <option value="<?php echo strtolower($language->code); ?>"><?php echo $language->name;?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitNewLanguageInsertBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>