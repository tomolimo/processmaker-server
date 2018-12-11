/**
 * Class ModelCrown
 * @param options
 * @constructor
 */
var ModelCrown = function (options) {
    this.itemsDefault = PMUI.util.ArrayList();
    this.init(options);
};
/**
 * Initialize ModelCrown
 * @param options
 */
ModelCrown.prototype.init = function (options) {
    if (typeof options === "object" && Object.keys(options).length > 0) {
        this.setItemsDefault(options.items);
    }
    return this;
};
/**
 * Get Items ModelCrown
 * @returns {*|Array}
 */
ModelCrown.prototype.getItemsDefault = function () {
    return this.itemsDefault;
};
/**
 * Set Items ModelCrown
 * @param items {Array of the Objects}
 * @returns {ModelCrown}
 */
ModelCrown.prototype.setItemsDefault = function (items) {
    var itemCrown,
        max,
        i;
    if (items && Array.isArray(items) && items.length > 0) {
        max = items.length;
        for (i = 0; i < max; i += 1) {
            this.itemsDefault.insert(items[i]);
        }
    }
    return this;
};
/**
 * Add New Item Crown
 * Example:
 *          PMDesigner.modelCrown.addItemToCrown({
 *              id: "example-id",
 *              name: "exmaple-name".translate(),
 *              className: "name-class-css",
 *              eventOnClick: function(item) {
 *                  --Your code goes here
 *              },
 *              eventOnMouseDown: function(item) {
 *                  --Your code goes here
 *              }
 *          });
 *
 * @param itemObject {Object}
 * @returns {ModelCrown}
 */
ModelCrown.prototype.addItemToCrown = function (itemObject) {
    if (typeof itemObject === "object" && Object.keys(itemObject).length > 0) {
        this.itemsDefault.insert(itemObject);
    }
    return this;
};
/**
 * Remove ItemCrown
 * Example:
 *          PMDesigner.modelCrown.removeItemFromCrown(idItem);
 *
 * @param idItem {string}
 * @returns {ModelCrown}
 */
ModelCrown.prototype.removeItemFromCrown = function (idItem) {
    var itemCrown;
    if (idItem && typeof idItem === "string") {
        itemCrown = this.itemsDefault.find("id", idItem);
        if (itemCrown) {
            this.itemsDefault.remove(itemCrown);
        }
    }
    return this;
};