/**
 * @class PMUI.menu.navBarPanel
 * Handles the navbar panel of designer,
 * contains all menus for content elements.
 *
 * @param {array} items Default items
 * @constructor
 */
var NavbarPanel = function (items) {
    NavbarPanel.prototype.init.call(this, items);
};

/**
 * Initializes the object.
 *
 * @param {array} items Array with default values.
 */
NavbarPanel.prototype.init = function (items) {
    var item;
    if (typeof items === 'undefined') {
        items = defaultNavbarPanelMenus.getNavBarPanelMenu();
    }
    this.items = new PMUI.util.ArrayList();
    for (item in items) {
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
NavbarPanel.prototype.show = function () {
    var item = null,
        i,
        max;
    if (PMDesigner.navbarPanel.items instanceof Object) {
        for (i = 0, max = PMDesigner.navbarPanel.items.getSize(); i < max; i += 1) {
            item = PMDesigner.navbarPanel.items.get(i);
            if (typeof item.htmlProperty !== "undefined") {
                PMDesigner.navbarPanel.buildHtmlElement(item.htmlProperty);
            }
            if (typeof item.aditionalAction !== "undefined") {
                item.aditionalAction;
            }
            if (typeof item.actions !== "undefined") {
                new PMAction(item.actions);
            }
        }
    } else {
        throw new Error('cannot show the elements of the List');
    }

};

/**
 * This method creates a html element button into the navBar Panel
 * @param {Object} element
 * @param {HTMLElement} before
 */
NavbarPanel.prototype.buildHtmlElement = function (element, before) {
    var ul = document.getElementById('idNavBarList'),
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
NavbarPanel.prototype.getNodeChild = function (nodeChild, nodePattern) {
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
 * NavBar panel array List and delete the HTML from the designer.
 * @param {String} idButton
 */
NavbarPanel.prototype.deleteHtmlElement = function (idButton) {
    var btn = document.getElementById(idButton),
        element = PMDesigner.contentControl.items.find("id", idButton),
        remove = PMDesigner.contentControl.items.remove(element);
    if (typeof btn !== 'undefined' && remove === true) {
        btn.parentNode.removeChild(btn);
    } else {
        throw new Error('Cannot find the specified button: ' + idButton + '. Please, review this');
    }
};

/**
 * NavbarPanel get an instance
 * @type {NavbarPanel}
 */
PMDesigner.navbarPanel = new NavbarPanel(defaultNavbarPanelMenus.getNavBarPanelMenu());
