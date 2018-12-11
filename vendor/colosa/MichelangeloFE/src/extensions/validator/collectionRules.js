/**
 * @class CollectionRules
 * @param collectionRules
 * @constructor
 */
var CollectionRules = function (collectionRules) {
    /**
     * Type Shape
     * @type {String}
     */
    this.typeShape = null;
    /**
     * Array of Rules
     * @type {Rules}
     */
    this.rules = new PMUI.util.ArrayList();
    this.init(collectionRules);
};
/**
 * Inicialize CollectionRules
 * @param collectionRules
 */
CollectionRules.prototype.init = function (collectionRules) {
    if (typeof collectionRules === "object") {
        this.setTypeShape(collectionRules.typeShape);
        this.loadRules(collectionRules.rules);
    }
};
/**
 * Get type shape
 * @returns {String}
 */
CollectionRules.prototype.getTypeShape = function () {
    return this.typeShape;
};
/**
 * Validate type shape
 * @param typeShape
 * @returns {Boolean}
 */
CollectionRules.prototype.isTypeShape = function (typeShape) {
    var isType = false;
    if (typeShape === this.getTypeShape()) {
        isType = true;
    }
    return isType;
};
/**
 * Get Array Rules
 * @returns {Array Rules}
 */
CollectionRules.prototype.getRules = function () {
    return this.rules;
};
/**
 * Get Item Rule
 * @returns {ItemRule}
 */
CollectionRules.prototype.getItemRule = function (codeRule) {
    var itemRule,
        itemsRules;
    if (codeRule) {
        itemsRules = this.getRules();
        itemRule = itemsRules.find("code", codeRule);
    }
    return itemRule;
};
/**
 * Set type shape
 * @param typeShape
 * @returns {CollectionRules}
 */
CollectionRules.prototype.setTypeShape = function (typeShape) {
    this.typeShape = (typeShape && typeof typeShape === "string") ? typeShape : this.typeShape;
    return this;
};
/**
 * Set array rules
 * @param arrRules
 * @returns {CollectionRules}
 */
CollectionRules.prototype.setRules = function (arrRules) {
    if (arrRules && Array.isArray(arrRules)) {
        this.rules = arrRules;
    }
    return this;
};
/**
 * Load rules array
 * @param arrRules
 * @returns {CollectionRules}
 */
CollectionRules.prototype.loadRules = function (arrRules) {
    var itemRule,
        i,
        max;
    if (arrRules && Array.isArray(arrRules) && arrRules.length > 0) {
        max = arrRules.length;
        for (i = 0; i < max; i += 1) {
            itemRule = new ItemRule(arrRules[i]);
            this.rules.insert(itemRule);
        }
    }
    return this;
};
/**
 * Add new ItemRule object
 * @param itemRule {Object}
 */
CollectionRules.prototype.addItemRule = function (itemRule) {
    if (itemRule && typeof itemRule === "object") {
        this.rules.insert(itemRule);
    }
    return this;
};
/**
 * Remove ItemRule object
 * @param itemRule
 * @returns {CollectionRules}
 */
CollectionRules.prototype.removeItemRule = function (itemRule) {
    if (itemRule && typeof itemRule === "object") {
        this.rules.remove(itemRule);
    }
    return this;
};