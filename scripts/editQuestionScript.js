var toastMsgBody = document.getElementById("toastMsgBody");

async function changeQuestionTags(id){

    $('#changeTagsModal').modal('toggle');
    await submitTagSelection("submitTagSelectionBtn");

    //select the choosen tags when the save button is pressed and procceed with the steps to save them
    var selectedTags = [];
    let allBtnTags = document.querySelectorAll(".tagBtn");
    for(let i=0;i<allBtnTags.length;i++){
        if(allBtnTags[i].checked){
            let selectedTag = (allBtnTags[i].name).toString();
            selectedTags.push(selectedTag);
        }
    }

    sendAjax("editQuestionTags", selectedTags, id);
}


async function changeQuestion(id){

    $('#changeQuestionModal').modal('toggle');
    await submitTagSelection("submitQuestionChangeBtn");

    //select the choosen tags when the save button is pressed and procceed with the steps to save them
    let questionText = document.getElementById("changeQuestionTextarea").value;
    let questionLanguage = document.getElementById("changeQuestionLanguage").value;

    let payload = {
        "questionText":questionText,
        "questionLanguage":questionLanguage
    };
    sendAjax("editQuestionText", payload, id);
}

function frontendChangeVerificationDisplay(e){
    e.classList.add("active");
    e.id = "1";
    if(e.nextElementSibling && e.nextElementSibling.tagName == "BUTTON"){
        e.nextElementSibling.classList.remove("active");
        e.nextElementSibling.id = "0";
    }else{
        e.previousElementSibling.classList.remove("active");
        e.previousElementSibling.id = "0";
    }
}

async function changeVerification(id){

    $('#changeVerificationModal').modal('toggle');
    await submitTagSelection("sumbitVerificationBtn");

    var verificationStatus = [];
    let verificationBtn = document.querySelectorAll(".verificationBtn");
    for(let i=0;i<verificationBtn.length;i++){
        if(verificationBtn[i].id == 1){
            var verificationStatus = verificationBtn[i].name; 
        }
    }


    sendAjax("editQuestionVerification", verificationStatus, id);
}


async function changeOptions(id){

    $('#changeOptionsModal').modal('toggle');
    await submitTagSelection("sumbitOptionsBtn");
    let answerOptionsQuestionType = document.getElementById("answerOptionsQuestionType").value;
    let answerOptionsLang = document.getElementById("answerOptionsLang").value;
    let editOptionsValue = document.querySelectorAll(".editOptionsValue");
    let allOptionsValues = [];
    for(let i=0;i<editOptionsValue.length;i++){
        allOptionsValues.push(editOptionsValue[i].value);
    }

    let editOptionsCheck = document.querySelectorAll(".editOptionsCheck");
    let allOptionsChecks = [];
    for(let i=0;i<editOptionsCheck.length;i++){
        allOptionsChecks.push(editOptionsCheck[i].checked);
    }

    let payload = {
        "optionValues": allOptionsValues,
        "optionChecks": allOptionsChecks,
        "questionType": answerOptionsQuestionType,
        "answerOptionsLang": answerOptionsLang
    };

    sendAjax("editQuestionOptions", payload, id);
}

/**
 * 
 * This function only handels answer setting for Open Questions.. Options answers are handled in the changeOptions function
 */
async function changeAnswer(id){

    $('#changeAnswerModal').modal('toggle');
    await submitTagSelection("sumbitAnswerBtn");
    let answerQuestionType = document.getElementById("answerQuestionType").value;
    if(answerQuestionType == "YesNo"){
        let answerTrue = document.getElementById("flexSwitchCheckAnswerTrue").checked;
        let answerFalse = document.getElementById("flexSwitchCheckAnswerFalse").checked;
        var answerText;
        if(answerTrue == true){
            answerText = "true";
        }else if(answerFalse == true){
            answerText = "false";
        }
    }else{
        answerText = document.getElementById("changeAnswerTextarea").value;
    }
    
    let payload = {
        "answerText": answerText,
        "answerType": answerQuestionType
    };

    sendAjax("editQuestionAnswer", payload, id);
}



function submitTagSelection(saveBtn){
    subCheck =  new Promise(function (resolve, reject) {
        var submitTagSel = document.getElementById(saveBtn);
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




function sendAjax(method, payload, id){
    $.ajax({
        type: "POST",
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            payload: payload,
            method: method,
            id: id
        },
        success: function(response) {
            if(response == "Illegal chars detected!" || response == "Illegal id given!" || response == "Illegal target language!" || response == "You are not allowed to edit this question!"){
                toastMsgBody.innerHTML = response;
                $(".toast").toast('show');
                return;
            }

            toastMsgBody.innerHTML = response;
            $(".toast").toast('show');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    });
}



function deleteQuestion(id){
    let text = "Are you sure that you want to delete this question ?";
    if (confirm(text) == false) {
      return;
    }

    let method = "deleteQuestion";
    $.ajax({
        type: "POST",
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            method: method,
            id: id
        },
        success: function(response) {
            if(response == "No Question found for this Id!" || response == "Illegal id given!" || response == "You are not allowed to edit this question!"){
                toastMsgBody.innerHTML = response;
                $(".toast").toast('show');
                return;
            }
            toastMsgBody.innerHTML = response;
            $(".toast").toast('show');
       
            setTimeout(function() {
                //redirect to the url the user came from
                location.href= document.referrer;
            }, 1500);
        }
    });
}