/**
 * loads the ImbaGame game in the IMBAdmin window
 */
function loadImbaGame(gameName, gameDo, payLoad){
    currentGame = gameName;
    currentGameDo = gameDo;
    
    /**
     * fill currentUserName and currentUserOpenid
     */
    loadMyImbaUser();
    
    /**
     * Remove all tabs
     */   
    $("#imbaGameNav").tabs("destroy");  
    
    /**
     * Create new tabs on element
     */
    $("#imbaGameNav").tabs();
    
    /**
     * Set the window title
     */
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminGames",
        request: "name",
        game: gameName,
        secSession: phpSessionID
    }, function (response){
        if (checkReturn(response) == false) {  
            tmpTitle  = "<div onclick='javascript:loadImbaGameDefaultGame();' style='float: left; cursor: pointer;'>";
            tmpTitle += "<span class='ui-icon ui-icon-home' style='float: left;' /></div>";
            tmpTitle += "<div style='float: left;'>&nbsp;&nbsp;&nbsp;</div>";

            if (currentUserName != null) {
                tmpTitle += "<div style='float: left;'>" + currentUserName + "&nbsp;&nbsp;&nbsp;@&nbsp;&nbsp;&nbsp;</div>";
            }
        
            tmpTitle += "<div style='float: left; cursor: pointer;' onclick='javascript:loadImbaGameDefaultGame();'>IMBA Game&nbsp;&nbsp;&nbsp;</div>";

            if (response) {
                tmpTitle += "<div style='float: left;'><span class='ui-icon ui-icon-triangle-1-e' style='float: left;' />&nbsp;&nbsp;&nbsp;</div>";
                tmpTitle += "<div onclick='javascript:loadImbaGame(\"" + gameName + "\");' style='float: left; cursor: pointer;'>" + response + "</div>";
            }
            $("#imbaGameDialog").dialog({
                title: tmpTitle
            });
        }
    });

    /**
     * get and render tabs
     */
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminGames",
        request: "nav",
        game: gameName,
        secSession: phpSessionID
    }, function (response){
        $.each($.parseJSON(response), function(key, value){
            $("#imbaGameNav").tabs("add", "#" + value.id, value.name);
            if ((key == 0) && (gameDo == null || gameDo == "")) {
                loadImbaGameTabContent({
                    game: gameName,
                    request: value.id,
                    payLoad: payLoad
                });
            } else if ((gameDo != null) && (gameDo != "")) {
                loadImbaGameTabContent({
                    game: gameName,
                    request: gameDo,
                    payLoad: payLoad
                });
            }
        });
    });
    
    if (isSystemInErrorState == false) {
        $("#imbaGameDialog").dialog("open");
    }
}

/**
 * loads the ImbaGameTab content, depending on the data for the post request
 */
function loadImbaGameTabContent(data, myGameTabId) {
    var targetGameIabId = null;
    if (myGameTabId == null) {
        targetGameIabId = getImbaGameTabIdFromTabIndex(getSelectedImbaGameTabIndex());
    } else {
        targetGameIabId = myGameTabId;
    }

    data.action = "game";
    data.secSession = phpSessionID;

    $.post(ajaxEntry, data, function (response){
        if (checkReturn(response) == false) {  
            if (response != ""){
                $(targetGameIabId).html(response);
            }
        }
    });
}


/**
 * Support functions
 * 
 */


/**
 * Loads the default ImbaGameTab
 */
function loadImbaGameDefaultGame(){
    // Get the default game
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminGames",
        request: "getDefault",
        secSession: phpSessionID
    }, function (response){
        if (checkReturn(response) == false) {  
            // Call the loadImbaGame to open the dialog         
            loadImbaGame(response.toString());
        }
    });
}

/**
 * Returns the current selected tab index
 */
function getSelectedImbaGameTabIndex(){
    return $('#imbaGameNav').tabs('option', 'selected');
}

/**
 * Return the Id of a tab from a tabIndex
 */
function getImbaGameTabIdFromTabIndex(tabGameIndex){
    var gameResult = "";
    $.each($("#imbaGameNav a"), function (k, v) {
        if (k == tabGameIndex){
            var gameTmp = v.toString().split("#");
            gameResult = "#" + gameTmp[1];
        }
    });
    return gameResult;
}