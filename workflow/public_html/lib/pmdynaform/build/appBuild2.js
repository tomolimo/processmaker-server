window.onload = function () {
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        mode: "application/ld+json",
        viewportMargin: Infinity
    });
    editor.setSize(350, 500);
    document.getElementById("file").onchange = function (e) {
        var r = new FileReader();
        r.readAsText(this.files[0], "UTF-8");
        r.onload = function (e) {
            editor.setValue(e.target.result);
            $(".pmdynaform-container").remove();
        };
        this.form.reset();
    };
    document.getElementById("test").onclick = function () {
        $(".pmdynaform-container").remove();
        window.project = new PMDynaform.core.Project({
            data: JSON.parse(editor.getValue()),
            renderTo: document.getElementById("container"),
            submitRest: true,
            keys: {
                server: "http://richard3.pmos.colosa.net/", //"http://michelangelo.pmos3.colosa.net/",
                projectId: "95480800054cb8637e41a83089603369", //"25084755253f3a016907523058545566",
                workspace: "richard3" //"workflow3"
            },
            token: {
                accessToken: "a14763cf7f7d7e837133ce01d1a00161cddc19b5" //"db0498b53483bb840e996a27d23ace1d49f1e35b"
            },
            renderTo: document.body
        });
        $(".pmdynaform-container").css({
            "float":"left",
            "width" : "100%"
        });
    };
    document.getElementById("format").onclick = function () {
        var a = editor.getCursor(true);
        var b = editor.getCursor(false);
        if (a === b) {
            a = {line: 0, ch: 0};
            b = {line: editor.doc.lastLine(), ch: editor.getValue().length};
        }
        editor.autoFormatRange(a, b);
    };

    document.getElementById("desktop").onclick = function () {
        $(".pmdynaform-container").css({"width": "1024px","float" : "left"});
    };
    document.getElementById("tablet").onclick = function () {
        $(".pmdynaform-container").css({"width": "800px", "float" : "left"});
    };
    document.getElementById("smartphone").onclick = function () {
        $(".pmdynaform-container").css({"width": "400px","float" : "left"});
    };
};
data = window.onload = function () {
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        mode: "application/ld+json",
        viewportMargin: Infinity
    });
    editor.setSize(350, 500);
    document.getElementById("file").onchange = function (e) {
        var r = new FileReader();
        r.readAsText(this.files[0], "UTF-8");
        r.onload = function (e) {
            editor.setValue(e.target.result);
            $(".pmdynaform-container").remove();
        };
        this.form.reset();
    };
    document.getElementById("test").onclick = function () {
        $(".pmdynaform-container").remove();
        window.project = new PMDynaform.core.Project({
            data: JSON.parse(editor.getValue()),
            renderTo: document.getElementById("container"),
            submitRest: true,
            keys: {
                server: "http://richard3.pmos.colosa.net/", //"http://michelangelo.pmos3.colosa.net/",
                projectId: "95480800054cb8637e41a83089603369", //"25084755253f3a016907523058545566",
                workspace: "richard3" //"workflow3"
            },
            token: {
                accessToken: "a14763cf7f7d7e837133ce01d1a00161cddc19b5" //"db0498b53483bb840e996a27d23ace1d49f1e35b"
            },
            renderTo: document.body
        });
        $(".pmdynaform-container").css({
            "float":"left",
            "width" : "100%"
        });
    };
    document.getElementById("format").onclick = function () {
        var a = editor.getCursor(true);
        var b = editor.getCursor(false);
        if (a === b) {
            a = {line: 0, ch: 0};
            b = {line: editor.doc.lastLine(), ch: editor.getValue().length};
        }
        editor.autoFormatRange(a, b);
    };

    document.getElementById("desktop").onclick = function () {
        $(".pmdynaform-container").css({"width": "1024px","float" : "left"});
    };
    document.getElementById("tablet").onclick = function () {
        $(".pmdynaform-container").css({"width": "800px", "float" : "left"});
    };
    document.getElementById("smartphone").onclick = function () {
        $(".pmdynaform-container").css({"width": "400px","float" : "left"});
    };
};