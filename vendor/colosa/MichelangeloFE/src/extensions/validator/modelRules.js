/**
 * @class ModelRules
 * @param object
 * @constructor
 */
var ModelRules = function (object, status) {
    /**
     * Status Rules
     * @type {Boolean}
     */
    this.enable = true;
    /**
     * Items collection
     * @type {PMUI.util.ArrayList}
     */
    this.itemsCollection = new PMUI.util.ArrayList();
    this.init(object, status);
};
/**
 * Inicialize Model Rules
 * @param object
 */
ModelRules.prototype.init = function (object, status) {
    var prop;
    this.setStatus(status);
    if (object && typeof object === "object") {
        for (prop in object) {
            this.enable
            this.loadCollection(prop, object[prop]);
        }
    }
};
/**
 * Load Collection Rules
 * @param type
 * @param arrRules
 * @returns {ModelRules}
 */
ModelRules.prototype.loadCollection = function (type, arrRules) {
    var collectionObject;
    if (type && arrRules) {
        if (typeof type === "string" && Array.isArray(arrRules)) {
            collectionObject = new CollectionRules({
                'typeShape': type,
                'rules': arrRules
            });
            this.itemsCollection.insert(collectionObject);
        }
    }
    return this;
};
/**
 * Get the status of the enable property (true | false)
 * @returns {Boolean}
 */
ModelRules.prototype.getStatus = function () {
    return this.enable;
};
/**
 * Get Items Collection Arralist
 * @returns {PMUI.util.ArrayList}
 */
ModelRules.prototype.getItemsCollection = function () {
    return this.itemsCollection;
};
/**
 * Set the status of the enable property
 * @param status
 * @returns {ModelRules}
 */
ModelRules.prototype.setStatus = function (status) {
    this.enable = (typeof status === "boolean") ? status : this.enable;
    return this;
};

/**
 * Get Collection Rules for type Bpmn Element
 * Example:
 *          PMDesigner.modelRules.getCollectionType('bpmnActivity');
 * @param type {String}
 * @returns CollectionRules {PMUI.util.ArrayList}
 */
ModelRules.prototype.getCollectionType = function (type) {
    var collection,
        i,
        itemCollection,
        max;
    if (type && typeof type === "string") {
        max = this.itemsCollection.getSize();
        for (i = 0; i < max; i += 1) {
            itemCollection = this.itemsCollection.get(i);
            if (itemCollection.isTypeShape(type)) {
                collection = itemCollection.getRules();
                break;
            }
        }
    }
    return collection;
};
/**
 * Add Item Rule
 * Example:
 *          PMDesigner.modelRules.addItemRuleToCollection('bpmnActivity', {
 *                  code: '123456',
 *                  description: 'This is a Message of Error'.translate(),
 *                  type: 'bpmnActivity',
 *                  severity: 'Warning', {'Warning | Error'}
 *                  criteria: function (shape, error) {
 *                       //Validation Rule
*                        shape.addErrorLog(error);
 *                  }
 *          });
 * @param type {String}
 * @param item {Object}
 * @returns {ModelRules}
 */
ModelRules.prototype.addItemRuleToCollection = function (type, item) {
    var itemCollection,
        itemRule,
        max,
        index = 0;
    if (type && item) {
        max = this.itemsCollection.getSize();
        while (index < max) {
            itemCollection = this.itemsCollection.get(index);
            if (itemCollection.isTypeShape(type)) {
                itemRule = new ItemRule(item);
                itemCollection.addItemRule(itemRule);
                index = max;
            }
            index += 1;
        }
    }
    return this;
};
/**
 * Remove Item Rule
 * Example:
 *          PMDesigner.modelRules.removeItemRuleFromCollection('123465');
 * @param code {String}
 * @returns {ModelRules}
 */
ModelRules.prototype.removeItemRuleFromCollection = function (code) {
    var itemCollection,
        itemRule,
        max,
        index = 0;
    if (code && typeof code === "string") {
        max = this.itemsCollection.getSize();
        while (index < max) {
            itemCollection = this.itemsCollection.get(index);
            itemRule = itemCollection.getItemRule(code);
            if (itemRule) {
                itemCollection.removeItemRule(itemRule);
                index = max;
            }
            index += 1;
        }
    }
    return this;
};
/**
 * Add Collection Rules
 * Example:
 *          PMDesigner.modelRules.addItemRuleToCollection('bpmnActivity'
 *              [
 *                  {Object ItemRule},
 *                  {Object ItemRule},
 *                  {Object ItemRule}
 *              ]
 *          );
 * @param typeShape {String}
 * @param collectionRules {Array}
 * @returns {ModelRules}
 */
ModelRules.prototype.addCollectionRules = function (typeShape, arrayRules) {
    var max,
        i;
    if (type && arrayRules && Array.isArray(arrayRules)) {
        max = arrayRules.length;
        for (i = 0; i < max; i += 1) {
            this.addItemRuleToCollection(typeShape, arrayRules[i]);
        }
    }
    return this;
};

