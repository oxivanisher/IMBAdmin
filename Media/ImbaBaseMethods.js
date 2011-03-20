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
    
    $.post(ajaxEntry, data, function (response){
        if (response != ""){
            $(targetIabId).html(response);
        }
    });
}


/**
 *  Retrievs the Columnhead by index
 *  the <th> element needs to have an "title" Attribute
 */  
function getColumnHeadByIndex(tableId, colIndex){
    var result = null;
    $.each($("#"+tableId+" thead th"), function(index, value) { 
        if (index == colIndex) result = value.getAttribute("title");
    });
        
    return result;
}

/**
 * Creats a cookie, with name, value und days of expire
 */
function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

/**
 * Reads a cookie by name
 */
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

/**
 * Erases a cookie by nane
 */
function eraseCookie(name) {
    createCookie(name,"",-1);
}