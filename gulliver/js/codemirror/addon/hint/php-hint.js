(function () {
  var Pos = CodeMirror.Pos;

  function forEach(arr, f) {
    for (var i = 0, e = arr.length; i < e; ++i) f(arr[i]);
  }
  
  function arrayContains(arr, item) {
    if (!Array.prototype.indexOf) {
      var i = arr.length;
      while (i--) {
        if (arr[i] === item) {
          return true;
        }
      }
      return false;
    }
    return arr.indexOf(item) != -1;
  }

  function scriptHint(editor, keywords, getToken, options) {
    // Find the token at the cursor
    var cur = editor.getCursor(), token = getToken(editor, cur), tprop = token;

    if(token.string == "(") {
 	token = tprop = getToken(editor, Pos(cur.line, tprop.start));
        return {list: getCompletions(token.string, keywords, options),
                from: Pos(cur.line, token.start),
                to: Pos(cur.line, token.end + 1)};
    }
    return {list: getCompletions(token.string, keywords, options),
            from: Pos(cur.line, token.start),
            to: Pos(cur.line, token.end)};
  }

  CodeMirror.phpHint = function(editor, options) {
    return scriptHint(editor, phpPMFunctions, function (e, cur) {return e.getTokenAt(cur);}, options);
  };

  var getCurrentTimeFunction = ("getCurrentTime($name,$time) getCurrentTime($name)").split(" ");
  var PMFNewCaseImpersonateFunction = ("PMFNewCaseImpersonate($date)").split(" ");

  var phpPMFunctions = ("formatDate getCurrentDate getCurrentTime literalDate capitalize lowerCase upperCase userInfo executeQuery orderGrid " +
  "evaluateFunction PMFTaskCase PMFTaskList PMFUserList PMFGroupList PMFRoleList PMFCaseList PMFProcessList PMFSendVariables PMFDerivateCase " +
  "PMFNewCaseImpersonate PMFNewCase PMFPauseCase PMFUnpauseCase PMFAssignUserToGroup PMFCreateUser PMFUpdateUser PMFInformationUser " +        
  "generateCode setCaseTrackerCode jumping PMFRedirectToStep pauseCase PMFSendMessage PMFgetLabelOption PMFGenerateOutputDocument " +    
  "PMFGetUserEmailAddress PMFGetNextAssignedUser PMFDeleteCase PMFCancelCase PMFAddInputDocument PMFAddCaseNote PMFGetCaseNotes").split(" ");

  var phpKeywords = ("break case catch continue debugger default delete do else false finally for function " +
                  "if in instanceof new null return switch throw true try typeof var void while with").split(" ");

  function getCompletions(functionName, keywords, options) {
    
    var found = [];

    function maybeAdd(str) {// for keywords ?
      if ( str.indexOf(functionName) == 0 && !arrayContains(found, str)) {
         found.push(str);
      }
    }

    function yesAdd(str) {
      if ( !arrayContains(found, str)) {
         found.push(str);
      }
    }

    if (functionName == "getCurrentTime") {
          forEach( getCurrentTimeFunction, yesAdd);
    } else if (functionName == "PMFNewCaseImpersonate") {
          forEach( PMFNewCaseImpersonateFunction, yesAdd);
    } else {
        for(i=0;i<phpKeywords.length ; i++) {
	    if ( phpKeywords[i].indexOf(functionName) == 0 ) {
		found.push(phpKeywords[i]);
	    }
        } 
        forEach(keywords, maybeAdd);
    }
    return found;
  }
})();
