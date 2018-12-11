(function () {
    var TypesControl = {
        title: "title",
        subtitle: "subtitle",
        label: "label", //deprecated
        link: "link",
        image: "image",
        file: "file",
        multipleFile: "multipleFile",
        submit: "submit",
        button: "button",
        grid: "grid",
        subform: "subform",
        variable: "variable", //only variable
        text: "text",
        textarea: "textarea",
        dropdown: "dropdown",
        checkbox: "checkbox",
        checkgroup: "checkgroup",
        radio: "radio",
        datetime: "datetime",
        suggest: "suggest",
        hidden: "hidden",
        form: "form",
        cell: "cell",
        annotation: "label", //todo
        geomap: "location",
        qrcode: "scannerCode",
        signature: "signature",
        imagem: "imageMobile",
        audiom: "audioMobile",
        videom: "videoMobile",
        panel: "panel",
        msgPanel: "msgPanel"
    };
    FormDesigner.extendNamespace('FormDesigner.main.TypesControl', TypesControl);
}());