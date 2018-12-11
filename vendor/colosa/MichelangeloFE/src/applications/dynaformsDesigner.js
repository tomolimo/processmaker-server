PMDesigner.dynaformDesigner = function (data) {
    var old = PMUI.activeCanvas, a;
    PMUI.activeCanvas = false;
    a = new FormDesigner.main.Designer(data);
    a.show();
    a.onHide = function () {
        PMUI.activeCanvas = old;
    };
    return a;
};
