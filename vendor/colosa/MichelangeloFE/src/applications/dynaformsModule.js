ListDynaform = function () {
    this.superTitle = 'Dynaforms'.translate();
    this.tableContainerHeight = 374;
    this.buttonCreate = null;
    this.onCreate = new Function();
    this.onShowId = new Function();
    this.onEdit = new Function();
    this.onDel = new Function();
    this.loaded = false;
    this.clickedClose = true;
    Mafe.Grid.call(this);
    ListDynaform.prototype.init.call(this);
};
ListDynaform.prototype = new Mafe.Grid();
ListDynaform.prototype.init = function () {
    var that = this;
    that.buttonCreate = new PMUI.ui.Button({
        id: 'dynaformButtonNew',
        text: 'Create'.translate(),
        height: '36px',
        width: 100,
        style: {cssClasses: ['mafe-button-create']},
        handler: function (event) {
            that.onCreate(event);
        }
    });
    that.buttonCreate.defineEvents();
    that.setID('idListDynaform');
    that.setColumns([{
        id: 'copyuid',
        title: 'Show ID'.translate(),
        dataType: 'button',
        buttonLabel: 'Show ID'.translate(),
        buttonStyle: {cssClasses: ['mafe-button-show']},
        onButtonClick: function (row, grid) {
            that.onShowId(row, grid);
        }
    }, {
        id: 'dynaformGridPanelTitle',
        title: 'Title'.translate(),
        dataType: 'string',
        width: '460px',
        alignmentCell: 'left',
        sortable: true,
        columnData: 'dyn_title'
    }, {
        id: 'dynaformGridPanelEdit',
        title: '',
        dataType: 'button',
        buttonStyle: {cssClasses: ['mafe-button-edit']},
        buttonLabel: function (row, data) {
            return 'Edit'.translate();
        },
        onButtonClick: function (row, grid) {
            that.onEdit(row, grid);
        }
    }, {
        id: 'dynaformGridPanelDelete',
        title: '',
        dataType: 'button',
        buttonStyle: {cssClasses: ['mafe-button-delete']},
        buttonLabel: function (row, data) {
            return 'Delete'.translate();
        },
        onButtonClick: function (row, grid) {
            that.onDel(row, grid);
        }
    }
    ]);
};
ListDynaform.prototype.createHTML = function () {
    Mafe.Grid.prototype.createHTML.call(this);
    this.dom.toolbar.appendChild(this.buttonCreate.getHTML());
    return this.html;
};
ListDynaform.prototype.load = function () {
    var that = this;
    that.clearItems();
    (new PMRestClient({
        endpoint: 'dynaforms',
        typeRequest: 'get',
        functionSuccess: function (xhr, response) {
            that.setDataItems(response);
            that.sort('dyn_title', 'asc');
            that.loaded = true;
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);

            that.loaded = false;
        },
        messageError: 'There are problems getting the list of dynaforms, please try again.'.translate()
    })).executeRestClient();
};
ListDynaform.prototype.getSuperTitle = function () {
    return this.superTitle;
};

BlankDynaform = function () {
    this.superTitle = 'Create Blank Dynaform'.translate();
    this.onSave = new Function();
    this.onSaveOpen = new Function();
    this.onCancel = new Function();
    this.buttons = null;
    Mafe.Form.call(this);
    BlankDynaform.prototype.init.call(this);
};
BlankDynaform.prototype = new Mafe.Form();
BlankDynaform.prototype.init = function () {
    var that = this;
    that.setID('formDynaformInformation');
    that.setTitle('Dynaform Information'.translate());
    that.setItems([{
        id: 'formDynaformInformationTitle',
        pmType: 'text',
        label: 'Title'.translate(),
        value: '',
        maxLength: 255,
        placeholder: 'The Dynaform title'.translate(),
        name: 'dyn_title',
        required: true,
        controlsWidth: 303
    }, {
        id: 'formDynaformInformationDescription',
        pmType: 'textarea',
        rows: 200,
        name: 'dyn_description',
        width: 200,
        label: 'Description'.translate(),
        style: {cssClasses: ['mafe-textarea-resize']}
    }
    ]);
    that.buttons = [
        new PMUI.ui.Button({
            id: 'btnClose',
            text: 'Cancel'.translate(),
            buttonType: 'error',
            handler: function () {
                that.onCancel();
            }
        }),
        new PMUI.ui.Button({
            id: 'windowDynaformInformationSaveOpen',
            text: 'Save & Open'.translate(),
            buttonType: 'success',
            handler: function () {
                that.onSaveOpen();
            }
        }),
        new PMUI.ui.Button({
            id: 'windowDynaformInformationSave',
            text: 'Save'.translate(),
            buttonType: 'success',
            handler: function () {
                that.onSave();
            }
        })
    ];
};
BlankDynaform.prototype.getButtons = function () {
    return this.buttons;
};
BlankDynaform.prototype.getSuperTitle = function () {
    return this.superTitle;
};

Dynaform = function (settings) {
    this.listDynaform = null;
    this.blankDynaform = null;
    Mafe.Window.call(this, settings);
    Dynaform.prototype.init.call(this, settings);
};
Dynaform.prototype = new Mafe.Window();
Dynaform.prototype.init = function (settings) {
    var that = this;
    that.listDynaform = new ListDynaform();
    that.setOnBeforeCloseHandler(function () {
        this.clickedClose = true;
        if (!that.blankDynaform.isVisible()) {
            that.close();
        } else {
            that.blankDynaform.onCancel();
        }
    });
    that.listDynaform.load();
    that.listDynaform.onShowId = function (row, grid) {
        showUID(row.getData().dyn_uid);
    };
    that.listDynaform.onCreate = function () {
        that.resetView();
        that.setTitle(that.blankDynaform.getSuperTitle());
        that.blankDynaform.setVisible(true);
        that.setButtons(that.blankDynaform.getButtons());
    };
    that.listDynaform.onEdit = function (row, grid) {
        that.edit(row, grid);
    };
    that.listDynaform.onDel = function (row, grid) {
        that.del(row, grid);
    };

    that.blankDynaform = new BlankDynaform();
    that.blankDynaform.onSave = function () {
        that.saveBlank(false);
    };
    that.blankDynaform.onSaveOpen = function () {
        that.saveBlank(true);
    };
    that.blankDynaform.onCancel = function () {
        $("input,select,textarea").blur();
        var title = "";
        that.blankDynaform.loseChanges(title);
    };
    that.blankDynaform.onYesConfirmCancellation = function () {
        if (that.clickedClose) {
            that.close();
        }
        that.resetView();
        that.setTitle(that.listDynaform.getSuperTitle());
        that.listDynaform.setVisible(true);
    };

    that.addItem(that.listDynaform);
    that.addItem(that.blankDynaform);

    that.open();
    that.resetView();
    that.setTitle(that.listDynaform.getSuperTitle());
    that.listDynaform.setVisible(true);

    // hard coding dyn_title textfield because enter keypress reload the current page
    if (that.blankDynaform.getItems()[0]
        && that.blankDynaform.getItems()[0].controls[0]
        && that.blankDynaform.getItems()[0].controls[0].html) {
        $(that.blankDynaform.getItems()[0].controls[0].html).keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
            }
        });
    }
};
Dynaform.prototype.saveBlank = function (open) {
    var that = this,
        data,
        restClient,
        flagAux;

    if (!that.blankDynaform.isValid()) {
        flagAux = that.blankDynaform.visible;
    } else {
        flagAux = that.blankDynaform.isValid();
    }

    if (flagAux) {
        if (getData2PMUI(that.blankDynaform.html).dyn_title == "") {
            return false;
        }
    }

    data = getData2PMUI(that.blankDynaform.html);
    data['dyn_version'] = 2;
    data['dyn_type'] = 'xmlform';
    restClient = new PMRestClient({
        endpoint: 'dynaform',
        typeRequest: 'post',
        data: data,
        functionSuccess: function (xhr, response) {
            if (open) {
                that.close();
                try {
                    PMUI.getActiveCanvas().emptyCurrentSelection();
                } catch (msg) {
                }
                PMDesigner.dynaformDesigner(response);
            } else {
                that.resetView();
                that.setTitle(that.listDynaform.getSuperTitle());
                that.listDynaform.setVisible(true);
                that.listDynaform.load();
            }
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        },
        messageError: 'There are problems creating the dynaform, please try again.'.translate(),
        messageSuccess: 'Dynaform saved successfully'.translate(),
        flashContainer: that.listDynaform
    });
    restClient.executeRestClient();
};
Dynaform.prototype.edit = function (row, grid) {
    this.close();
    try {
        PMUI.getActiveCanvas().emptyCurrentSelection();
    } catch (msg) {
    }
    PMDesigner.dynaformDesigner(row.getData());
};
Dynaform.prototype.del = function (row, grid) {
    var that = this,
        confirmDeletion = new Mafe.ConfirmDeletion();
    confirmDeletion.setMessage('Do you want to delete this DynaForm?'.translate());
    confirmDeletion.setTitle("Dynaform".translate());
    confirmDeletion.onDelete = function () {
        var restClient = new PMRestClient({
            endpoint: 'dynaform/' + row.getData().dyn_uid,
            typeRequest: 'remove',
            functionSuccess: function (xhr, response) {
                that.listDynaform.load();
            },
            functionFailure: function (xhr, response) {
                PMDesigner.msgWinError(response.error.message);
            },
            messageError: 'There are problems deleting the dynaform, please try again.'.translate(),
            messageSuccess: 'Dynaform deleted successfully'.translate(),
            flashContainer: that.listDynaform
        });
        restClient.executeRestClient();
    };
};

(function () {
    PMDesigner.dynaform = function () {
        var dynaform = new Dynaform();
    };
    PMDesigner.dynaform.create = function () {
        var dynaform = new Dynaform();
        dynaform.resetView();
        dynaform.setTitle(dynaform.blankDynaform.getSuperTitle());
        dynaform.blankDynaform.setVisible(true);
        dynaform.setButtons(dynaform.blankDynaform.getButtons());
    };
}());
