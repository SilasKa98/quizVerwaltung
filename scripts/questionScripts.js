


    //check if the user presses "save" in the insert new Language modal
    function submitNewLanguageInsert(){

        subCheck =  new Promise(function (resolve, reject) {
            var submitNewLang = document.getElementById("submitNewLanguageInsertBtn");
            submitNewLang.addEventListener('click', (event) => {
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


    //get what language the user wants to translate the question into.. 
    //this function is also asyncrounus so it will wait until the user submits the selection with the save button --> function submitNewLanguageInsert()
    async function getNewQuestionLanguage(){

        p =  new Promise(function (resolve, reject) {
            var newLang = document.getElementById("insertNewLanguageDrpDwn");
            newLang.addEventListener('change', (event) => {
                var result = event;
                if(result != ""){
                    resolve(result);
                }
                else{
                    reject("error ...")
                } 
            });
        })
        
        await submitNewLanguageInsert();
        return p;

    }


    //this function awaits a return from getNewQuestionLanguage(), the getNewQuestionLanguage() only returns if the user submitted his selection.
    //with this structure its secured that the user can change the target language as often as he wants, till he presses submit
    async function insertNewLanguage(e){

        let newLang = await getNewQuestionLanguage();
        let selLanguage = newLang.target.value;
        let id = e.getAttribute("name").split("_")[0];
        let sourceLanguage = e.getAttribute("name").split("_")[1];
        let method = "insertNewLanguage";

        $.ajax({
            type: "POST",
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                selLanguage: selLanguage,
                method: method,
                sourceLanguage: sourceLanguage,
                id: id
            },
            success: function(response) {
                console.log(response);
                console.log("save successfull");
                toastMsgBody.innerHTML = "New Language added successfully!";
                $(".toast").toast('show');
                //currently just reloading the site to display new things, maybe can also be added with js
                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
        });

    }


    function changeKarma(e){

        var job = e.name;
        let id = e.id;
        let method = "changeKarma";
        $.ajax({
            type: "POST",
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                job: job,
                method: method,
                id: id
            },
            success: function(response) {
                console.log(response);
                //instantly shows the changes to the karma without reload
                let karmaId = document.getElementById("karma_"+id);  
                if(job == "increaseKarma"){
                    var otherBtn = e.nextElementSibling;
                }else{
                    var otherBtn = e.previousElementSibling;
                }
                otherBtn.style.background = "none";
                if(e.style.background == "rgb(5, 125, 238)"){
                    e.style.background = "none"; 
                }else{
                    e.style.background = "rgb(5, 125, 238)"; 
                }
                
                
                karmaId.innerHTML = response;
                console.log("karma change successfull");
            }
        });

    }

    
    function changeQuestionLanguage(e){

        let questionId = e.getAttribute("name").split("_")[1];
        let questionLang = e.value;
        const method = "changeQuestionLanguageRelation";
        $.ajax({
            type: "POST",
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                questionId: questionId,
                questionLang: questionLang,
                method: method
            },
            success: function(response) {
                //in the backend a json is created with all needed return values. Here the json needs to be parsed to use it for displaying changes
                let jsonResponse = JSON.parse(response);

                //display the changed values --> question
                let questionText = document.getElementById("headerText_"+questionId);
                questionText.innerHTML = jsonResponse.question;
                
                //display the changed values --> options
                if (typeof jsonResponse.options !== 'undefined') {
                    for(let i=0;i<jsonResponse.options.length;i++){
                        let optionField = document.getElementById("optionField_"+i+"_"+questionId);
                        optionField.innerHTML = jsonResponse.options[i];
                    }
                }

                console.log("language change successfull");
                toastMsgBody.innerHTML = "Changed the question language successfully!";
                $(".toast").toast('show');
            }
        });
    }

    function addToCart(e){
        let questionId = e.getAttribute("name");
        let method = "addToCart";

        $.ajax({
            type: 'post',
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                questionId: questionId,
                method: method
            },
            success: function(response) {
                let jsonResponse = JSON.parse(response);
                
                if (jsonResponse.addResult === "ItemExists"){
                    toastMsgBody.innerHTML = "Question already in cart";
                    $(".toast").toast('show');
                    return;
                }

                let canvasBody = document.getElementById("canvas-body");
                let cartCount = document.getElementById("cartCount");
                let cartInfoText = document.getElementById("cartInfoText");
                if (cartInfoText != undefined){
                    cartInfoText.remove();
                }

                canvasBody.innerHTML += "<div class='card' style='margin: .5rem; --bs-card-spacer-y: .5rem;'> " +
                                            "<div class='card-body'>" + 
                                                jsonResponse.questionObject.question[jsonResponse.lang] + 
                                            "</div>" +
                                        "</div>";
                cartCount.innerHTML = jsonResponse.cartLength;

                toastMsgBody.innerHTML = "Question added to cart";
                $(".toast").toast('show');
            }
        });
    }

    function createCatalog(e){
        let method = "createCatalog";

    $.ajax({

        type: 'post',
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            method: method
        },
        success: function(response){
            let jsonResponse = JSON.parse(response);

            if (jsonResponse.createResult === "cartEmpty"){
                toastMsgBody.innerHTML = "Can't create catalog of Empty Cart. Please add some questions to be able to create a catalog.";
                $(".toast").toast('show');
                return;
            }

            let canvasBody = document.getElementById("canvas-body");
            let cartCount = document.getElementById("cartCount");

            cartCount.innerHTML = jsonResponse.cartLength;
            canvasBody.innerHTML = "";

            toastMsgBody.innerHTML = "Catalog created. See profile to look at your catalogs";
            $(".toast").toast('show');
        }

    });
    }



