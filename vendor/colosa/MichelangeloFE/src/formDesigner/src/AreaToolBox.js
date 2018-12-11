(function () {
    var AreaToolBox = function () {
        AreaToolBox.prototype.init.call(this);
    };
    AreaToolBox.prototype.init = function () {
        var that = this;
        this.accordion = $("<div></div>");
        this.accordion.accordion({
            heightStyle: "content",
            collapsible: true,
            icons: false,
            event: "mousedown"
        });
        this.body = this.accordion;
    };
    AreaToolBox.prototype.addItem = function (title, item) {
        var name = $("<h3 class='fd-accordion-title' style='margin:0px;'>" + title + "</h3>");
        var body = $("<div style='padding:0px;border:none;border-radius:0px;'></div>");
        body.append(item.body);
        this.accordion.append(name);
        this.accordion.append(body);
        this.accordion.accordion("refresh");
    };
    FormDesigner.extendNamespace('FormDesigner.main.AreaToolBox', AreaToolBox);
}());