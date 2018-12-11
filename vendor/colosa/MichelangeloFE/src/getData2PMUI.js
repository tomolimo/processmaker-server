var getData2PMUI = function (form) {
    if (form) {
        var input, field, data2 = {};
        for (var i = 0; i < $(form).find(".pmui-field").length; i += 1) {
            input = null;
            field = null;
            field = PMUI.getPMUIObject($(form).find(".pmui-field")[i]);
            var value = null;
            switch (field.type) {
                case "TextField":
                    value = field.controls[0].html.value;
                    break;
                case "TextAreaField":
                    value = field.controls[0].html.value;
                    break;
                case "DropDownListField":
                    value = field.controls[0].html.value;
                    break;
                case "radio":
                    var dataChekbox = [];
                    for (var j = 0; j < $(field.html).find("input").length; j += 1) {
                        if ($(field.html).find("input")[j].selected) {
                            value = $(field.html).find("input")[j].value;
                        }
                    }
                    break;
                case "CheckBoxGroupField":
                    var dataChekbox = [];
                    for (var j = 0; j < $(field.html).find("input").length; j += 1) {
                        if ($(field.html).find("input")[j].checked) {
                            dataChekbox.push($(field.html).find("input")[j].value);
                        }
                    }
                    value = JSON.stringify(dataChekbox);
                    break;
                case "datetime":
                    value = field.controls[0].html.value;
                    break;
                case "PasswordField":
                    value = field.controls[0].html.value;
                    break;
                case "PMCodeMirrorField":
                    value = field.controls[0].value;
                    break;
            }
            data2[field.getName()] = value;
        }
    }
    return data2;
};