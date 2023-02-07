$(document).ready(function(e) {
    var timeout;
    var delay = 800;   // 800ms

    $('#searchInSystem').keyup(function(e) {
      if(timeout) {
          clearTimeout(timeout);
      }
      timeout = setTimeout(function() {
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
          let searchResultList = document.getElementById("searchResultsFound_questions");
          let allMatchingIdsArray = jsonResponse.allMatchingIds;
          searchResultList.innerHTML = "";
          searchResultList.innerHTML = "<h1 class='list-group-item headerSearchResults'>Questions</h1>";
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
            let searchResultList = document.getElementById("searchResultsFound_users");
            let allMatchingUsers = jsonResponse.allMatchingUsers;
            console.log(allMatchingUsers);
            
            searchResultList.innerHTML = "";
            searchResultList.innerHTML = "<h1 class='list-group-item headerSearchResults'>Users</h1>";
            for(let i=0;i<allMatchingUsers.length;i++){
              searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+allMatchingUsers[i]+'" class="list-group-item list-group-item-action">'+allMatchingUsers[i]+'</a>';
            }
            if(allMatchingUsers.length == 0){
              searchResultList.innerHTML += '<span class="list-group-item list-group-item-action">No Matches found</span>';
            }
          }
        });
      }
  });