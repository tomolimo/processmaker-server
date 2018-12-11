function defaultContentControlMenus() {
    var variableCreate = {
            id: 'variableCreate',
            name: 'Variable',
            htmlProperty: {
                id: 'variableCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-variable'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-variable-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-variable",
                label: {
                    text: "Variables".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.variables();
                }
            },
            createAction: {
                selector: ".mafe-menu-variable-create",
                label: {
                    selector: ".mafe-menu-variable-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.variables.create();
                }
            }
        },
        messageType = {
            id: 'messageTypeCreate',
            name: 'MessageType',
            htmlProperty: {
                id: 'messageTypeCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-messagetype'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-messagetype-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-messagetype",
                label: {
                    text: "Message Types".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.messageType();
                }
            },
            createAction: {
                selector: ".mafe-menu-messagetype-create",
                label: {
                    selector: ".mafe-menu-messagetype-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.messageType.create();
                }
            }
        },
        dynaformCreate = {
            id: 'dynaformCreate',
            name: 'Dynaform',
            htmlProperty: {
                id: 'dynaformCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-dynaform'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-dynaform-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-dynaform",
                label: {
                    text: "Dynaforms".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.dynaform();
                }
            },
            createAction: {
                selector: ".mafe-menu-dynaform-create",
                label: {
                    selector: ".mafe-menu-dynaform-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.dynaform.create();
                }
            }
        },
        inputDocumentCreate = {
            id: 'inputDocumentCreate',
            name: 'InputDocument',
            htmlProperty: {
                id: 'inputDocumentCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-inputdocuments'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-inputdocuments-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-inputdocuments",
                label: {
                    text: "Input Documents".translate()
                },
                execute: true,
                handler: function () {
                    var inputDocument = new InputDocument();
                    inputDocument.build();
                }
            },
            createAction: {
                selector: ".mafe-menu-inputdocuments-create",
                label: {
                    selector: ".mafe-menu-inputdocuments-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    var inputDocument = new InputDocument();
                    inputDocument.build();
                    inputDocument.openFormInMainWindow();
                    inputDocument.method = "POST";
                }
            }
        },
        outputDocumentCreate = {
            id: 'outputDocumentCreate',
            name: 'OutputDocument',
            htmlProperty: {
                id: 'outputDocumentCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-outputdocuments'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-outputdocuments-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-outputdocuments",
                label: {
                    text: "Output Documents".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.output();
                }
            },
            createAction: {
                selector: ".mafe-menu-outputdocuments-create",
                label: {
                    selector: ".mafe-menu-outputdocuments-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.output();
                    PMDesigner.output.create();
                }
            }
        },
        triggerCreate = {
            id: 'triggerCreate',
            name: 'Trigger',
            htmlProperty: {
                id: 'triggerCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-triggers'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-triggers-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-triggers",
                label: {
                    text: "Triggers".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.trigger();
                }
            },
            createAction: {
                selector: ".mafe-menu-triggers-create",
                label: {
                    selector: ".mafe-menu-triggers-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.trigger();
                    PMDesigner.trigger.create();
                }
            }
        },
        reportTableCreate = {
            id: 'reportTableCreate',
            name: 'ReportTable',
            htmlProperty: {
                id: 'reportTableCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-reporttables'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-reporttables-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-reporttables",
                label: {
                    text: "Report Tables".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.reporttable();
                }
            },
            createAction: {
                selector: ".mafe-menu-reporttables-create",
                label: {
                    selector: ".mafe-menu-reporttables-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.reporttable();
                    PMDesigner.reporttable.create();
                }
            }
        },
        databaseCreate = {
            id: 'databaseCreate',
            name: 'Database',
            htmlProperty: {
                id: 'databaseCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-databaseconnections'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-databaseconnections-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-databaseconnections",
                label: {
                    text: "Database Connections".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.database();
                }
            },
            createAction: {
                selector: ".mafe-menu-databaseconnections-create",
                label: {
                    selector: ".mafe-menu-databaseconnections-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.database.create();
                }
            }
        },
        templateCreate = {
            id: 'templateCreate',
            name: 'TemplateCreate',
            htmlProperty: {
                id: 'templateCreate',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-templates'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-templates-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-templates",
                label: {
                    text: "Templates".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.ProcessFilesManager("templates", "CREATION_NORMAL");
                }
            },
            createAction: {
                selector: ".mafe-menu-templates-create",
                label: {
                    selector: ".mafe-menu-templates-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.ProcessFilesManager.createFirst("templates", "CREATION_MORE");
                }
            }
        },
        menuPublic = {
            id: 'menuPublic',
            name: 'MenuPublic',
            htmlProperty: {
                id: 'menuPublic',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-public'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-public-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-public",
                label: {
                    text: "Public Files".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.ProcessFilesManager("public", "CREATION_NORMAL");
                }
            },
            createAction: {
                selector: ".mafe-menu-public-create",
                label: {
                    selector: ".mafe-menu-public-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.ProcessFilesManager.createFirst("public", "CREATION_MORE");
                }
            }
        },
        menuPermission = {
            id: 'menuPermission',
            name: 'MenuPermission',
            htmlProperty: {
                id: 'menuPermission',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-permissions'
                    },
                    {
                        element: 'a',
                        class: 'btn_create mafe-menu-permissions-create',
                        child: [
                            {
                                element: 'span'
                            }
                        ]
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-permissions",
                label: {
                    text: "Permissions".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.processPermissions();
                }
            },
            createAction: {
                selector: ".mafe-menu-permissions-create",
                label: {
                    selector: ".mafe-menu-permissions-create span",
                    text: "Create".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.processPermissions.create();
                }
            }
        },
        menuCaseTracker = {
            id: 'menuCaseTracker',
            name: 'CaseTracker',
            htmlProperty: {
                id: 'menuCaseTracker',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-casetracker'
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-casetracker",
                label: {
                    text: "Case Tracker".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.caseTracker();
                }
            }
        },
        menuSupervisor = {
            id: 'menuSupervisor',
            name: 'MenuSupervisor',
            htmlProperty: {
                id: 'menuSupervisor',
                element: 'li',
                child: [
                    {
                        element: 'a',
                        class: 'mafe-menu-supervisors'
                    }
                ]
            },
            actions: {
                type: 'button',
                selector: ".mafe-menu-supervisors",
                label: {
                    text: "Supervisors".translate()
                },
                execute: true,
                handler: function () {
                    PMDesigner.assigmentSupervisors();
                }
            }
        };

    return [
        variableCreate,
        messageType,
        dynaformCreate,
        inputDocumentCreate,
        outputDocumentCreate,
        triggerCreate,
        reportTableCreate,
        databaseCreate,
        templateCreate,
        menuPublic,
        menuPermission,
        menuCaseTracker,
        menuSupervisor
    ];
};

/**
 * @class PMUI.menu.contentControl
 * Handles the content control menu of designer,
 * contains all menus for content elements.
 *
 * @param {array} items Default items
 * @constructor
 */
var ContentControl = function (items) {
    ContentControl.prototype.init.call(this, items);
};

/**
 * Initializes the object.
 *
 * @param {array} items Array with default values.
 */
ContentControl.prototype.init = function (items) {
    this.items = new PMUI.util.ArrayList();
    if (typeof items === 'undefined') {
        items = defaultContentControlMenus();
    }
    for (var item in items) {
        if (!items.hasOwnProperty(item)) {
            continue;
        }
        this.items.insert(items[item]);
    }
};

/**
 * This method renders HTML and actions into designer
 *
 */
ContentControl.prototype.show = function () {
    var item = null,
        i,
        max;
    if (this.items instanceof Object) {
        for (i = 0, max = this.items.getSize(); i < max; i += 1) {
            item = this.items.get(i);
            if (typeof item.htmlProperty !== "undefined") {
                this.buildHtmlElement(item.htmlProperty);
            }
            if (typeof item.actions !== "undefined") {
                new PMAction(item.actions);
            }
            if (typeof item.createAction !== "undefined") {
                new PMAction(item.createAction);
            }
        }
    } else {
        throw new Error('Cannot show the elements of the List');
    }
};

/**
 * This method creates a html element button into the content
 * control panel.
 * @param {Object} element
 * @param {HTMLElement} before
 */
ContentControl.prototype.buildHtmlElement = function (element, before) {
    var ul = document.getElementById('contentControlList'),
        htmlElement;
    if ((typeof ul !== undefined) && (ul !== null)) {
        htmlElement = this.getNodeChild(element, ul);
        if (typeof before !== "undefined") {
            before = document.getElementById(before);
            ul.insertBefore(htmlElement, before);
        } else {
            ul.appendChild(htmlElement);
        }
    }

};

/**
 * This method assembling dependent html elements to the button
 * @param {Object} nodeChild
 * @param {HTMLElement} nodePattern
 * @returns {Element}
 */
ContentControl.prototype.getNodeChild = function (nodeChild, nodePattern) {
    var node = document.createElement(nodeChild.element),
        i;
    if (typeof nodeChild.id !== 'undefined') {
        node.setAttribute("id", nodeChild.id);
    }
    if (nodeChild.element === 'a') {
        node.setAttribute("href", "#");
    }
    if (typeof(nodeChild.class) !== 'undefined') {
        node.setAttribute("class", nodeChild.class);
    }
    if (typeof(nodeChild.child) !== 'undefined' && nodeChild.child instanceof Array) {
        for (i = 0; i < nodeChild.child.length; i += 1) {
            this.getNodeChild(nodeChild.child[i], node);
        }
    }
    if (typeof(nodeChild.src) !== 'undefined') {
        node.setAttribute("src", nodeChild.src);
    }
    if ((typeof nodePattern !== undefined) && (nodePattern !== null) && nodePattern.localName !== 'ul') {
        nodePattern.appendChild(node);
    }
    return node;
};

/**
 * This method removes an html element for the
 * Content Control panel array List and delete the HTML from the designer.
 * @param {String} idButton
 */
ContentControl.prototype.deleteHtmlElement = function (idButton) {
    var btn = document.getElementById(idButton),
        element = this.items.find("id", idButton),
        remove = this.items.remove(element);
    if (typeof btn !== 'undefined' && remove === true) {
        btn.parentNode.removeChild(btn);
    } else {
        throw new Error('Cannot find the specified button: ' + idButton + '. Please, review this');
    }
};

/**
 * ContentControl get an instance
 * @type {ContentControl}
 */
PMDesigner.contentControl = new ContentControl(defaultContentControlMenus());
