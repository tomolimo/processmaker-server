function DVEditor(where,body,oHiddenInput,height,mode,editorId)
{
    var me=this;
    var hiddenInput=oHiddenInput;
    var iframe=$dce("iframe");
    //NOTE: className no funciona en FIREFOX
    iframe.style.width="100%";
    iframe.style.height=height;
    iframe.style.margin="0px";
    iframe.style.padding="0px";
    iframe.style.border="none";
    where.appendChild(iframe);
    var head=document.childNodes[0].childNodes[0];
    var header='';
    if (iframe.contentWindow) {
        var doc = iframe.contentWindow.document;
    } else {
        var doc = iframe.contentDocument;
    }
    var _header = $dce("head");// head.cloneNode(true);
    for (var i=0;i<head.childNodes.length;i++) {
        try {
            if ((head.childNodes[i].tagName==='LINK')&&
                (head.childNodes[i].type="text/css")) {
                _header.appendChild(head.childNodes[i].cloneNode(true));
            }
        } catch (e) {
        }
    }
    header=_header.innerHTML;
    //alert(header);
    doc.open();
    doc.write('<html><head>'+header+'</head><body style="height:100%;padding:0px;margin:0px;border:none;background-color:ThreeDHighlight;cursor:text;">'+body+'</body></html>');
    doc.close();
    doc.designMode="on";
    if (mode=="edit") {
        doc.contentEditable=true;
    } else {
        doc.contentEditable=false;
    }
    this.doc=doc;

    me.insertHTML = function (html)
    {
        var cmd = 'inserthtml';
        var bool = false;
        var value = html;
        try {
            doc.execCommand(cmd,bool,value);
        } catch (e) {
        }
        return false;
    };

    me.command = function()
    {
        var cmd = this.getAttribute('name');
        var bool = false;
        var value = this.getAttribute('cmdValue') || null;
        if (value == 'promptUser') {
            value = prompt(
                (typeof(G_STRINGS[this.getAttribute('promptText')])!=='undefined')?
                    G_STRINGS[this.getAttribute('promptText')]:
                    this.getAttribute('promptText')
            );
        }
        try {
            if (editorId) {
                if (window.ActiveXObject && cmd == 'hilitecolor') {
                    doc.selection.createRange().pasteHTML('<span style ="background-color:' + value + '">' +doc.selection.createRange().text +'</span>');
                } else {
                    eval("window._editor" +editorId+".doc.execCommand(cmd,bool,value);");
                }
            } else {
                doc.execCommand(cmd,bool,value);
            }
        } catch (e) {
        }
        return false;
    };

    me.loadToolBar = function(uri)
    {
        var tb=WebResource(uri);
        if(editorId){   // for multi-edhtmleditor
            tb.innerHTML = tb.innerHTML.replace(/DVEditorMap/g, "DVEditorMap" + editorId);
        }
        iframe.parentNode.insertBefore(tb,iframe);
        me.setToolBar(tb);
    };

    me.setToolBar = function(toolbar)
    {
        var buttons=toolbar.getElementsByTagName('area');
        for (var b=0;b<buttons.length;b++) {
            buttons[b].onclick=me.command;
        }
    };

    me.getHTML = function()
    {
        var body='';
        try {
            body=doc.getElementsByTagName('body')[0];
            body=body.innerHTML;
        } catch (e) {
        }
        return body;
    };
    me.setHTML = function(html)
    {
        try {
            body=doc.getElementsByTagName('body')[0];
            body.innerHTML=html;
        } catch (e) {
        }
        return body;
    };

    me.refreshHidden = function()
    {
        if(hiddenInput)
        {
            var html=me.getHTML();
            var raiseOnChange=hiddenInput.value!==html;
            hiddenInput.value=html;
            if (raiseOnChange && hiddenInput.onchange) hiddenInput.onchange();
        }
    };

    me.syncHidden = function(name)
    {
        me.refreshHidden();
        setTimeout(name+".syncHidden('"+name+"')",500);
    };
}