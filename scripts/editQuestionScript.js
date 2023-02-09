async function changeQuestionTags(id){
    console.log(id);

    $('#changeActionModal').modal('toggle');
    await submitTagSelection();

    //select the choosen tags when the save button is pressed and procceed with the steps to save them
    var selectedTags = [];
    let allBtnTags = document.querySelectorAll(".btn-check");
    console.log(allBtnTags);
    for(let i=0;i<allBtnTags.length;i++){
        if(allBtnTags[i].checked){
            let selectedTag = (allBtnTags[i].name).toString();
            selectedTags.push(selectedTag);
        }
    }

    sendAjax("editQuestionTags", selectedTags, id);
}


function submitTagSelection(){
    subCheck =  new Promise(function (resolve, reject) {
        var submitTagSel = document.getElementById("submitTagSelectionBtn");
        submitTagSel.addEventListener('click', (event) => {
            if(event){
                resolve(event);
            }
            else{
                reject("error ...")
            } 
        });
    })
    return subCheck;
}


function sendAjax(method, selectedTags, id){
    $.ajax({
        type: "POST",
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            selectedTags: selectedTags,
            method: method,
            id: id
        },
        success: function(response) {
            console.log(response);
            location.reload();
        }
    });
}