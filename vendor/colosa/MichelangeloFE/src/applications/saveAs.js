SaveAsForm = function (settings) {
    Mafe.Form.call(this, settings);
    SaveAsForm.prototype.init.call(this, settings);
};
SaveAsForm.prototype = new Mafe.Form();
SaveAsForm.prototype.init = function () {
    var that = this;
    that.onSave = new Function();
    that.onCancel = new Function();
    that.setID("SaveAsForm");
    that.setTitle("Save as".translate());
    that.setItems([{
        id: "prj_name",
        name: "pro_title",
        pmType: "text",
        label: "Title".translate(),
        placeholder: "Title".translate(),
        maxLength: 100,
        required: true
    }, {
        id: "prj_description",
        pmType: "textarea",
        name: "pro_description",
        label: "Description".translate(),
        placeholder: "Description".translate(),
        rows: 200
    }, {
        id: "pro_category",
        name: "pro_category",
        pmType: "dropdown",
        label: "Category".translate(),
        options: [
            {value: "", label: "No Category".translate()}
        ]
    }
    ]);
    that.setButtons([{
        id: "idCancel",
        text: "Cancel".translate(),
        buttonType: "error",
        handler: function () {
            that.onCancel();
        }
    }, {
        id: "idSave",
        text: "Save".translate(),
        buttonType: "success",
        handler: function () {
            that.onSave();
        }
    }
    ]);
    that.loadCategory();
};
SaveAsForm.prototype.loadCategory = function () {
    var that = this,
        titleProcess;
    var a = new PMRestClient({
        typeRequest: "post",
        multipart: true,
        data: {
            calls: [{
                url: "project/categories",
                method: "GET"
            }, {
                url: "project/" + PMDesigner.project.projectId + "/process",
                method: "GET"
            }
            ]
        },
        functionSuccess: function (xhr, response) {
            var i, dt, category = that.getField("pro_category");
            dt = response[0].response;
            for (i = 0; i < dt.length; i++) {
                category.addOption({
                    value: dt[i].cat_uid,
                    label: dt[i].cat_name
                });
            }
            //load data
            dt = response[1].response;
            titleProcess = "Copy of".translate() + " [" + dt.pro_title + "]";
            that.getField("pro_title").setValue(titleProcess.substring(0, 100));
            that.getField("pro_description").setValue(dt.pro_description);
            that.getField("pro_category").setValue(dt.pro_category);
        },
        functionFailure: function (xhr, response) {
        }
    });
    a.setBaseEndPoint("");
    a.executeRestClient();
};

SaveAs = function (settings) {
    Mafe.Window.call(this, settings);
    SaveAs.prototype.init.call(this, settings);
};
SaveAs.prototype = new Mafe.Window();
SaveAs.prototype.init = function () {
    var that = this;
    that.saveAsForm = new SaveAsForm();
    that.saveAsForm.onYesConfirmCancellation = function () {
        that.close();
    };
    that.saveAsForm.onCancel = function () {
        that.saveAsForm.loseChanges({title: that.title});
    };
    that.saveAsForm.onSave = function () {
        that.saveAsForm.getField("pro_title").setReadOnly(true);
        that.saveAsForm.getField("pro_description").setReadOnly(true);
        PMUI.getPMUIObject($(that.saveAsForm.html).find("#idSave")[0]).setDisabled(true);
        var a = new PMRestClient({
            typeRequest: "post",
            multipart: true,
            data: {
                calls: [{
                    url: "project/save-as",
                    method: "POST",
                    data: {
                        prj_uid: PMDesigner.project.projectId,
                        prj_name: that.saveAsForm.getField("pro_title").getValue(),
                        prj_description: that.saveAsForm.getField("pro_description").getValue(),
                        prj_category: that.saveAsForm.getField("pro_category").getValue()
                    }
                }
                ]
            },
            functionSuccess: function (xhr, response) {
                if (response[0].response.prj_uid) {
                    that.close();
                    window.location.href = "designer?prj_uid=" + response[0].response.prj_uid;
                } else {
                    that.saveAsForm.getField("pro_title").setReadOnly(false);
                    that.saveAsForm.getField("pro_description").setReadOnly(false);
                    PMUI.getPMUIObject($(that.saveAsForm.html).find("#idSave")[0]).setDisabled(false);
                    var field = that.saveAsForm.getField("pro_title");
                    $(field.html).find(".pmui-textlabel").text(response[0].response);
                    field.showMessage();
                }
            },
            functionFailure: function (xhr, response) {
            }
        });
        a.setBaseEndPoint("");
        a.executeRestClient();
    };

    that.setTitle("Save as".translate());
    that.addItem(that.saveAsForm);
};