(function () {
    var TabsForm = function () {
        TabsForm.prototype.init.call(this);
    };
    TabsForm.prototype.init = function () {
        this.count = -1;
        this.body = $("<div style='border:none;padding:0px;'></div>");
        this.head = $("<ul style='border:none;background:white;padding:0px;border-bottom: 1px solid #DADADA;border-radius:0px;'></ul>");
        this.body.append(this.head);
        this.body.tabs({
            heightStyle: "content",
            active: 0
        });
    };
    TabsForm.prototype.addItem = function (name, gridForm) {
        this.count = this.count + 1;
        var head = $("<li style='background:white;border-left:1px solid #DADADA;border-top:1px solid #DADADA;border-right:1px solid #DADADA;'><a href='#tabs-" + this.count + "'>" + name + "</a></li>");
        var body = $("<div id='tabs-" + this.count + "' style='padding:0px;'></div>");
        body.append(gridForm.body);
        this.head.append(head);
        this.body.append(body);
        this.body.tabs("destroy");
        this.body.tabs({
            heightStyle: "content",
            active: this.count
        });

        if (this.body.find(".ui-tabs-panel").length === 1)
            this.head.hide();
        else
            this.head.show();

        return {head: head, body: body};
    };
    FormDesigner.extendNamespace('FormDesigner.main.TabsForm', TabsForm);
}());