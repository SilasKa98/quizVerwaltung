var toastMsgBody = document.getElementById("toastMsgBody");

async function changeQuestionTags(id){
    console.log(id);

    $('#changeTagsModal').modal('toggle');
    await submitTagSelection("submitTagSelectionBtn");

    //select the choosen tags when the save button is pressed and procceed with the steps to save them
    var selectedTags = [];
    let allBtnTags = document.querySelectorAll(".tagBtn");
    console.log(allBtnTags);
    for(let i=0;i<allBtnTags.length;i++){
        if(allBtnTags[i].checked){
            let selectedTag = (allBtnTags[i].name).toString();
            selectedTags.push(selectedTag);
        }
    }

    sendAjax("editQuestionTags", selectedTags, id);
}


async function changeQuestion(id){
    console.log(id);

    $('#changeQuestionModal').modal('toggle');
    await submitTagSelection("submitQuestionChangeBtn");

    //select the choosen tags when the save button is pressed and procceed with the steps to save them
    let questionText = document.getElementById("changeQuestionTextarea").value;
    let questionLanguage = document.getElementById("changeQuestionLanguage").value;

    let payload = {
        "questionText":questionText,
        "questionLanguage":questionLanguage
    };
    console.log(payload);
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
    console.log(id);

    $('#changeVerificationModal').modal('toggle');
    await submitTagSelection("sumbitVerificationBtn");

    var verificationStatus = [];
    let verificationBtn = document.querySelectorAll(".verificationBtn");
    console.log(verificationBtn);
    for(let i=0;i<verificationBtn.length;i++){
        console.log(verificationBtn[i].id);
        if(verificationBtn[i].id == 1){
            var verificationStatus = verificationBtn[i].name; 
        }
    }

    console.log(verificationStatus);

    sendAjax("editQuestionVerification", verificationStatus, id);
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
            console.log(response);
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