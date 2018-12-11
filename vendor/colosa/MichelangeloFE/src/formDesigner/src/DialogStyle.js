(function () {
    var DialogStyle = function (dialog, type) {
        var bo = dialog[0].parentNode;
        bo.style.border = '1px solid rgba(0, 0, 0, 0.4)';
        bo.style.borderRadius = '0px';
        bo.style.padding = '0px';

        var b = bo.previousSibling.style;
        b.background = 'transparent';
        b.backgroundColor = 'rgba(0, 0, 0, 0.4)';
        b.opacity = '1';

        var titleBar = bo.firstChild;
        titleBar.style.border = 'none';
        titleBar.style.borderRadius = '0px';
        titleBar.style.background = '#3397e1';
        titleBar.style.color = 'white';
        titleBar.style.height = '25px';
        titleBar.style.fontSize = '16px';
        titleBar.style.fontFamily = 'SourceSansPro';
        titleBar.style.fontWeight = 'normal';

        titleBar.firstChild.style.marginTop = '5px';
        titleBar.firstChild.style.textAlign = 'center';

        var button = titleBar.firstChild.nextSibling;
        button.style.background = '#3397e1';
        button.style.border = 'none';
        button.style.marginRight = '4px';
        button.style.width = "18px";
        button.style.height = "18px";
        button.title = "close".translate();

        var icon = button.firstChild;
        icon.style.backgroundImage = 'url(' + $.imgUrl + 'fd-close.png)';
        icon.style.margin = "0px";
        icon.style.backgroundPosition = "0px 0px";
        icon.style.left = "-3px";
        icon.style.top = "-3px";
        icon.style.width = "24px";
        icon.style.height = "24px";
        icon.parentNode.onmouseover = function () {
            icon.style.backgroundImage = 'url(' + $.imgUrl + 'fd-closew.png)';
        };
        icon.parentNode.onmouseout = function () {
            icon.style.backgroundImage = 'url(' + $.imgUrl + 'fd-close.png)';
        };
        icon.nextSibling.style.padding = "0px";

        dialog.removeClass("ui-state-focus");
        dialog.removeClass("ui-state-hover");

        if (type === "alert") {
            //red
            titleBar.style.background = "#e84c3d";
            button.style.background = "#e84c3d";
        }
        if (type === "warning") {
            //yellow
            titleBar.style.background = "#edb60b";
            button.style.background = "#edb60b";
        }
        if (type === "success") {
            //green  
        }
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogStyle', DialogStyle);
}());