/**
 * loads the ImbaAdmin module in the IMBAdmin window
 */
function loadImbaAdminModule(moduleName, moduleDo, payLoad){
    currentModule = moduleName;
    currentModuleDo = moduleDo;

    /**
     * fill currentUserName and currentUserOpenid
     */
    loadMyImbaUser();
    
    /**
     * Remove all tabs
     */   
    $("#imbaContentNav").tabs("destroy");  
    
    /**
     * Create new tabs on element
     */
    $("#imbaContentNav").tabs();
    
    /**
     * Set the window title
     */
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminModules",
        request: "name",
        module: moduleName
    }, function (response){
        tmpTitle  = "<div onclick='javascript:loadImbaAdminDefaultModule();' style='float: left; cursor: pointer;'>";
        tmpTitle += "<span class='ui-icon ui-icon-home' style='float: left;' /></div>";
        tmpTitle += "<div style='float: left;'>&nbsp;&nbsp;&nbsp;</div>";

        if (currentUserName != null) {
            tmpTitle += "<div style='float: left;'>" + currentUserName + "&nbsp;&nbsp;&nbsp;@&nbsp;&nbsp;&nbsp;</div>";
        }
        
        tmpTitle += "<div style='float: left; cursor: pointer;' onclick='javascript:loadImbaAdminDefaultModule();'>IMBAdmin&nbsp;&nbsp;&nbsp;</div>";

        if (response) {
            tmpTitle += "<div style='float: left;'><span class='ui-icon ui-icon-triangle-1-e' style='float: left;' />&nbsp;&nbsp;&nbsp;</div>";
            tmpTitle += "<div onclick='javascript:loadImbaAdminModule(\"" + moduleName + "\");' style='float: left; cursor: pointer;'>" + response + "</div>";
        }
        $("#imbaContentDialog").dialog({
            title: tmpTitle
        });
    });

    /**
     * get and render tabs
     */
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminModules",
        request: "nav",
        module: moduleName
    }, function (response){
        $.each($.parseJSON(response), function(key, value){
            $("#imbaContentNav").tabs("add", "#" + value.id, value.name);
            if ((key == 0) && (moduleDo == null || moduleDo == "")) {
                loadImbaAdminTabContent({
                    module: moduleName,
                    request: value.id,
                    payLoad: payLoad
                });
            } else if ((moduleDo != null) && (moduleDo != "")) {
                loadImbaAdminTabContent({
                    module: moduleName,
                    request: moduleDo,
                    payLoad: payLoad
                });
            }
        });
    });
    
    $("#imbaContentDialog").dialog("open");
}

/**
 * loads the ImbaAdminTab content, depending on the data for the post request
 */
function loadImbaAdminTabContent(data, myTabId) {
    var targetIabId = null;
    if (myTabId == null) {
        targetIabId = getImbaAdminTabIdFromTabIndex(getSelectedImbaAdminTabIndex());
    } else {
        targetIabId = myTabId;
    }

    data.action = "module";

    $.post(ajaxEntry, data, function (response){
        if (response != ""){
            $(targetIabId).html(response);
        }
    });
}


/**
 * Support functions
 * 
 */

/**
 * Show the User Profile in IMBAdmin window
 */
function showUserProfile(openid) {
    loadImbaAdminModule("User", "viewprofile", openid);
}

/**
 * Loads the default ImbaAdminTab
 */
function loadImbaAdminDefaultModule(){
    // Get the default module
    $.post(ajaxEntry, {
        action: "navigation",
        context: "IMBAdminModules",
        request: "getDefault"
    }, function (response){
        // Call the loadImbaAdminModule to open the dialog         
        loadImbaAdminModule(response.toString());
    });
}

/**
 * Returns the current selected tab index
 */
function getSelectedImbaAdminTabIndex(){
    return $('#imbaContentNav').tabs('option', 'selected');
}

/**
 * Return the Id of a tab from a tabIndex
 */
function getImbaAdminTabIdFromTabIndex(tabIndex){
    var result = "";
    $.each($("#imbaContentNav a"), function (k, v) {
        if (k == tabIndex){
            var tmp = v.toString().split("#");
            result = "#" + tmp[1];
        }
    });

    return result;
}