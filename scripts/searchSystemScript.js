$(document).ready(function(e) {
    var timeout;
    var delay = 800;   // 800ms

    $('#searchInSystem').keyup(function(e) {
      if(timeout) {
          clearTimeout(timeout);
      }
      timeout = setTimeout(function() {
        //show the div where the results accordion is rendered in
        document.getElementById("searchResultsWrapper").style.display = "block";
        searchInSystemForQuestions(e);
        searchInSystemForUsers(e);
      }, delay);
    });

    function searchInSystemForQuestions(e) {
      let method = "searchInSystemForQuestions";
      $.ajax({
        type: "POST",
        url: '/quizVerwaltung/doTransaction.php',
        data: {
            value: e.target.value,
            method: method
        },
        success: function(response) {
          console.log("response:");
          console.log(response);
          let jsonResponse = JSON.parse(response);
          console.log(jsonResponse);
          console.log("save successfull");

          //Handle Search result for questions
          let searchResultList = document.getElementById("searchResults_questions_body");
          console.log(searchResultList);
          let allMatchingIdsArray = jsonResponse.allMatchingIds;
          searchResultList.innerHTML = "";
          for(let i=0;i<allMatchingIdsArray.length;i++){
            searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+jsonResponse.authorsOfTheMatches[i]+'&searchedQuestionId='+jsonResponse.allMatchingIds[i]+'#searchFocus" class="list-group-item list-group-item-action">'+jsonResponse.allMatchingQuestionStrings[i]+'<span class="badge rounded-pill bg-primary searchInnerKarmaPill">'+jsonResponse.KarmaOfTheMatches[i]+'</span></a>';
          }
          if(allMatchingIdsArray.length == 0){
            searchResultList.innerHTML += '<span class="list-group-item list-group-item-action">No Matches found</span>';
          }
        }
      });
    }

    function searchInSystemForUsers(e) {
        let method = "searchInSystemForUsers";
        $.ajax({
          type: "POST",
          url: '/quizVerwaltung/doTransaction.php',
          data: {
              value: e.target.value,
              method: method
          },
          success: function(response) {
            console.log("response:");
            console.log(response);
            let jsonResponse = JSON.parse(response);
            console.log(jsonResponse);
            console.log("save successfull");

            //Handle Search result for questions
            let searchResultList = document.getElementById("searchResults_users_body");
            let allMatchingUsers = jsonResponse.allMatchingUsers;
            console.log(allMatchingUsers);
            
            searchResultList.innerHTML = "";
            for(let i=0;i<allMatchingUsers.length;i++){
              searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+allMatchingUsers[i]+'" class="list-group-item list-group-item-action">'+allMatchingUsers[i]+'</a>';
            }
            if(allMatchingUsers.length == 0){
              searchResultList.innerHTML += '<span class="list-group-item list-group-item-action">No Matches found</span>';
            }
          }
        });
      }


      $('#searchInSystem').on('blur', function() {
        $(document).click(function(event) {
          const illegalStrings = ["searchResultAccordion","headingOne","searchResults_questions_header","searchResults_users_header","headingTwo"];
          if($.inArray(event.target.id, illegalStrings) === -1) {
           $("#searchResultsWrapper").css({
              "display": "none"
            });
          }
        });
      });
        
  });