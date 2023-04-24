
    var toastMsgBody = document.getElementById("toastMsgBody");

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
                if(response == "Translation already exists!" || response == "Illegal target language!"){
                    toastMsgBody.innerHTML = response;
                    $(".toast").toast('show');
                    return;
                }
                console.log(response);
                console.log("save successfull");
                toastMsgBody.innerHTML = "New Language added successfully!";
                $(".toast").toast('show');
                //currently just reloading the site to display new things, maybe can also be added with js
                setTimeout(function() {
                    location.reload();
                }, 2000);
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
                if(response == "Illegal language given!"){
                    toastMsgBody.innerHTML = response;
                    $(".toast").toast('show');
                    return;
                }
                console.log(response);
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

    //TODO Fehler mit Open questions diese werden hier irgendwie nicht aktualisiert --> nur wenn die seite geladen wird 
    //cart wird jedoch nach hinzufügen eines anderen Fragentyps dann wíeder aktualisiert etc. !!!!
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

                let questionId = jsonResponse.questionObject.id;
                let author = jsonResponse.questionObject.author;
                let tags = jsonResponse.questionObject.tags;
                let tagBadges = "";

                let answerType;
                let answer;

                if (jsonResponse.questionObject.questionType === "Options" || jsonResponse.questionObject.questionType === "MultiOptions") {
                    answerType = jsonResponse.optionsField;
                    answer = createOptionsBubbles(jsonResponse.questionObject.options[jsonResponse.lang], jsonResponse.questionObject.answer);
                } else {
                    answerType = jsonResponse.answerField;
                    answer = jsonResponse.questionObject.answer;
                }

                if (tags.length != 0){
                    tags.forEach((tag) => {
                        if (tag != ""){
                            tagBadges += "<span class='badge rounded-pill text-bg-secondary' style='margin-right: 2px;'>" + tag + "</span>";
                        }
                    });
                }
               
                //TODO hier muss noch irgednwie festgestellt werden welche sprache der user ausgewählt hat damit dann auch die sprachdatei zugegriffen werden kann
                canvasBody.innerHTML += "<div class='card' id=" + questionId + " style='margin: .5rem; --bs-card-spacer-y: .5rem;'> " +
                                            "<div class='card-body'>" + 
                                                "<div class='row'>" + 
                                                    "<div class='col question' name=" + questionId + ">" +
                                                        "<a class='collapsable_questionText' data-bs-toggle='collapse' href='#collabsable_" + questionId + "'>" + 
                                                            jsonResponse.questionObject.question[jsonResponse.lang] + 
                                                        "</a>" + 
                                                    "</div>" + 
                                                "<div class='col-1 d-flex flex-column cancel' style='justify-content: center;'>" + 
                                                    "<button type='button' class='btn-close' aria-label='Close' name=" + questionId + " " + 
                                                        "onclick='removeCartItem(this)'" + 
                                                        "style='width: .4rem; height: .4rem; float: right;'>" +
                                                    "</button>" + 
                                                "</div>" + 
                                            "</div>" + 
                                            "</div>" +
                                            "<div class='collapse' id='collabsable_"+ questionId + "'>" + 
                                                "<div class=card questionCartCard' style='margin: .5rem; --bs-card-spacer-y: .5rem;'>" + 
                                                    "<div class=card-body questionCartCard>" + 
                                                        "<p 'question-text'> " + answerType + ": " + answer + "</p>" + 
                                                        "<p 'question-text'>" + jsonResponse.tagsField + ": " + tagBadges + "</p>" + 
                                                        "<p 'question-text'>" + jsonResponse.authorField + ": " + 
                                                            "<a href='/quizVerwaltung/frontend/userProfile.php?profileUsername="+author+"&section=questions'>" + 
                                                                "<span class='badge rounded-pill bg-primary authorPill' style='margin-right: 2px;'>"+ author + "</span>" + 
                                                            "</a>" + 
                                                        "</p>" + 
                                                    "</div>" + 
                                                "</div>" + 
                                            "</div>" + 
                                        "</div>";

                cartCount.innerHTML = jsonResponse.cartLength;

                toastMsgBody.innerHTML = "Question added to cart";
                $(".toast").toast('show');
            }
        });
    }

    //TODO hier vllt noch etwas mehr formatierung machen damit das nicht so überlappt ?????!?!??
    function createOptionsBubbles(options, answers) {
        let answerPills = "";
        answers = answers.split(",");
        console.log(answers);
        options.forEach((option, index) => {
            if (answers.includes(index.toString())) {
                answerPills += `<span class='badge rounded-pill text-bg-success' style='margin-right: 2px;'> ${option} </span>`;
            } else {
                answerPills += `<span class='badge rounded-pill text-bg-secondary' style='margin-right: 2px;'> ${option} </span>`;
            }
        });
        return answerPills;
    }
    
    //TODO logs herausnehmen !!!!
    //check if save button clicked!!!!
    async function submitCatalog(){
        console.log("in submitCatalog");
        buttonCheck = new Promise(function (resolve, reject){
            var submit = document.getElementById("catalogSave");
            
            submit.addEventListener('click', (event) => {
                if(event){
                    var buttonStatus = "save";
                    resolve(buttonStatus);
                }
            });
            
            var window = document.getElementById("catalogOptions");
            window.addEventListener("hidden.bs.modal", (event) => {
                if (event) {
                    var closeStatus = true;
                    resolve(closeStatus);
                }
            })            
        });
        return buttonCheck;
    }
    
    //TODO logs herausnehmen !!!!
    //get all neccesary infos from frontend
    async function getCatalogSettings(){
        console.log("in getCatalogSettings");
        
        var buttonPressed = await submitCatalog();
        console.log(buttonPressed)
        
        p = new Promise(function (resolve, reject){
            var name = document.getElementById("catalogName").value;
            var publicStatus = document.getElementById("radioPublic").checked;

            if(name != "" || buttonPressed == "save"){
                result = {
                    name: name,
                    publicStatus: publicStatus
                }
                resolve (result);
            }else{
                reject ("error bei keine ahnung was ------");
            }
        });
        return p;
    }

    //TODO logs herausnehmen !!!!
    async function createCatalog(e){
        console.log("befor await");
        
        let catalogSettings;
        try {
            catalogSettings = await getCatalogSettings();
        } catch (error) {
            console.log("Hier sollte das aufgerufen werden !!!!");
            return ;
        }
        
        console.log("after await");
        let method = "createCatalog";
        console.log(catalogSettings);
        //TODO wie bekomme ich hier attribute!!!
        let name = catalogSettings.name;
        console.log("name: " + name);
        let status = "";
        if (catalogSettings.publicStatus == true){
            status = "public";
        }else{
            status = "private";
        }

        $.ajax({

            type: 'post',
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                method: method,
                name: name,
                status: status
            },
            success: function(response){
                let jsonResponse = JSON.parse(response);
                document.getElementById("catalogName").value = "";

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

    function removeCartItem(e){
        let method = "removeCartItem";
        let id = e.getAttribute("name");

        $.ajax({
            type: 'post',
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                method: method,
                id: id
            },
            success: function(response){
                let jsonResponse = JSON.parse(response);
                if(jsonResponse.removeResult == "successfullRemove"){
                    toastMsgBody.innerHTML = "Item removed successfully!";
                    $(".toast").toast('show');
                }

                let question = document.getElementById(jsonResponse.id);
                let cartCount = document.getElementById("cartCount");

                cartCount.innerHTML = jsonResponse.cartLength;
                question.remove();
            }
        });
    }

    function emptyQuestionCart(e){
        let method = "emptyQuestionCart";
        
        $.ajax({
            type: 'post',
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                method: method
            },
            success: function(response){
                let jsonResponse = JSON.parse(response);
                
                let canvasBody = document.getElementById("canvas-body");
                let cartCount = document.getElementById("cartCount");

                cartCount.innerHTML = 0;
                canvasBody.innerHTML = "";
            }
        });
    }


    function editQuestion(e){
        //submitting the form to redirect with the id given in the form
        e.children[0].submit();
    }

    function changeTagFilter(e){
        console.log(e);
        console.log(e.checked);
        let selectedTag = e.name;
        let method = "changeFavoritTags";
        $.ajax({
          type: "POST",
          url: '/quizVerwaltung/doTransaction.php',
          data: {
              selectedTag: selectedTag,
              method: method
          },
          success: function(response) {
            //reload needed to refresh the filter
            if(response == "Illegal chars detected!"){
                toastMsgBody.innerHTML = response;
                $(".toast").toast('show');
                return;
            }
            location.reload();
          }
        });
    }

    //get latest question of the people the user is following
    function getLatestQuestionsOfFollowedUsers(){
        let method = "getLatestQuestionsOfFollowedUsers";
        $.ajax({
            type: "POST",
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                method: method
            },
            success: function(response) {
              let jsonResponse = JSON.parse(response);
              let followedQuestionsArray = jsonResponse.followedQuestionsArray;
              let followedUsersArray = jsonResponse.followedUsersArray;
              let followedCreationDateArray = jsonResponse.followedCreationDateArray;
              let cardHolder = document.getElementById("cardHolder");
              cardHolder.innerHTML = "";
              if(followedQuestionsArray.length == 0){
                cardHolder.innerHTML +=
                    '<div class="card recentQuestionWrapper">'+
                        '<div class="card-body recentQuestion" style="--bs-card-spacer-y: 0.5rem;">'+
                            '<p class="recentQuestionText"><br><br><br><br></p>'+
                        '</div>'+
                    '</div>'
              }
              for(let i=0;i<followedQuestionsArray.length;i++){
                    cardHolder.innerHTML +=
                    '<div class="card recentQuestionWrapper">'+
                        '<div class="card-body">'+
                            '<p class="recentQuestionText">'+followedQuestionsArray[i]+'</p>'+
                            '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+followedUsersArray[i]+'&section=questions"><span class="badge rounded-pill text-bg-primary recentUserPill">@'+followedUsersArray[i]+'</span></a>'+
                            '<span class="badge text-bg-secondary recentDatePill">'+followedCreationDateArray[i]+'</span>'+
                        '</div>'+
                    '</div>'
              }
            }
        });
    }

    function getPersonRecommendations(){
        let method = "getPersonRecommendations";
        $.ajax({
            type: "POST",
            url: '/quizVerwaltung/doTransaction.php',
            data: {
                method: method
            },
            success: function(response) {
              console.log(response);
              let jsonResponse = JSON.parse(response);
              console.log(jsonResponse);
              let usernames = jsonResponse.matchingUsernames;
              let firstnames = jsonResponse.matchingFirstnames;
              let lastnames = jsonResponse.matchingLastnames;
              let recommendationHolder = document.getElementById("recommendationPersonHolder");
              console.log(usernames.length);
              if(usernames.length == 0){
                document.getElementById("personsRecommendation").style.display = "none";;
              }
              for(let i=0;i<usernames.length;i++){
                recommendationHolder.innerHTML += 
                '<div class="card personRecommendationCard">'+
                    '<div class="card-body personRecommendationCardBody">'+
                        '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+usernames[i]+'&section=questions" class="list-group-item list-group-item-action">'+
                            firstnames[i]+' '+lastnames[i]+
                            '<span class="text-muted"> @'+usernames[i]+'</span>'+
                            '<img class="searchResultMiniPicture" style="margin-left: 1%;" src="/quizVerwaltung/media/defaultAvatar.png" width="20px">'+
                        '</a>'+
                    '</div>'+
                '</div>';
              }
            }
        });
    }



