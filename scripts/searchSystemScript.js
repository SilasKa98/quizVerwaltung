$(document).ready(function(e) {


  var timeout;
  var delay = 800;   // 800ms
  var delay2 = 801;   // 810ms


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
    timeout = setTimeout(function() {
      //function to open the accordion area which contains most search hits needs to be delayed with delay2 to get right results
      CollapseAndExpandAccordion();
    }, delay2);
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
        let jsonResponse = JSON.parse(response);

        //Handle Search result for questions
        let searchResultList = document.getElementById("searchResults_questions_body");
        var allMatchingIdsArray = jsonResponse.allMatchingIds;
        searchResultList.innerHTML = "";

        for(let i=0;i<allMatchingIdsArray.length;i++){
          searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+jsonResponse.authorsOfTheMatches[i]+'&searchedQuestionId='+jsonResponse.allMatchingIds[i]+'#searchFocus" class="list-group-item list-group-item-action">'+jsonResponse.allMatchingQuestionStrings[i]+'<span class="badge rounded-pill bg-primary searchInnerKarmaPill">'+jsonResponse.KarmaOfTheMatches[i]+'</span></a>';
        }
        if(allMatchingIdsArray.length == 0){
          searchResultList.innerHTML += '<span id="noMatches" class="list-group-item list-group-item-action">No Matches found</span>';
        }
      }
    });
    return true;
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
          let jsonResponse = JSON.parse(response);

          //Handle Search result for users
          let searchResultList = document.getElementById("searchResults_users_body");
          let allMatchingUsers = jsonResponse.allMatchingUsers;
          searchResultList.innerHTML = "";

          for(let i=0;i<allMatchingUsers.length;i++){
            searchResultList.innerHTML += '<a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='+allMatchingUsers[i]+'" class="list-group-item list-group-item-action">'+allMatchingUsers[i]+'</a>';
          }
          if(allMatchingUsers.length == 0){
            searchResultList.innerHTML += '<span id="noMatches" class="list-group-item list-group-item-action">No Matches found</span>';
          }
        }
      });
      return true;
    }


    //following functions are for handling the showing and hiding of the results under the search input
    $('#searchInSystem').focus(function(){
      let currentWindowWith = $( window ).width();
      if(currentWindowWith > 1199){
        $("#searchInSystem").css({
          "width"      : "425px",
          "transition" : '0.5s ease-in-out'
        });
      }
    });


    $('#searchInSystem').on('blur', function() {
      $(document).click(function(event) {

        const illegalStrings = ["searchResultAccordion","headingOne","searchResults_questions_header","searchResults_users_header","headingTwo"];
        if($.inArray(event.target.id, illegalStrings) === -1) {
          $("#searchResultsWrapper").css({
            "display": "none"
          });
        }

        const illegalStrings_inputTransition = ["searchInSystemWrapper","searchInSystem","searchResultAccordion","headingOne","searchResults_questions_header","searchResults_users_header","headingTwo"];
        if($.inArray(event.target.id, illegalStrings_inputTransition) === -1 && $(window.getSelection().anchorNode).attr('id') !== 'searchInSystemWrapper') {
          $("#searchInSystem").css({
            "width": ""
          });
        }

      });
    });


    //function to determine which accordion body needs to be expanded/collapsed (checked by total findings/category)
    function CollapseAndExpandAccordion(){
      let questionSearchChilds = $("#searchResults_questions_body").children().length;
      let userSearchChilds = $("#searchResults_users_body").children().length;

      //if only one result, check if its the no Matches found one, if so calc -1
      if(questionSearchChilds == 1){
        if($("#searchResults_questions_body").children()[0].id == "noMatches"){
          questionSearchChilds = 0;
        }
      }
      if(userSearchChilds == 1){
        if($("#searchResults_users_body").children()[0].id == "noMatches"){
          userSearchChilds = 0;
        }
      }

      if(questionSearchChilds > userSearchChilds){
        $("#collapseQuestions").addClass("show");
        $("#collapseUsers").removeClass("show");
      }else{
        $("#collapseUsers").addClass("show");
        $("#collapseQuestions").removeClass("show");
      }
    }  
    
    
  });