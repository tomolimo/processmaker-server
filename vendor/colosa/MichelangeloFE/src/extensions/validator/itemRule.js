/**
 * @class ItemRule
 * @param object
 * @constructor
 */
var ItemRule = function (object) {
    /**
     * Code of the Rule
     * @type {String}
     */
    this.code = null;
    /**
     * Type Shape
     * @type {String}
     */
    this.type = null;
    /**
     * Category of the Rule (BPMN | ENGINE)
     * @type {String}
     */
    this.category = null;
    /**
     * Type of alert (Error | Warning)
     * @type {String}
     */
    this.severity = null;
    /**
     * Description rule
     * @type {String}
     */
    this.description = null;
    /**
     * Criteria of validation
     * @type {Function}
     */
    this.criteria = null;

    this.init(object);
};
/**
 * Inicialize ItemRules.
 * @param object
 */
ItemRule.prototype.init = function (object) {
    if (typeof object === "object") {
        this.setCode(object.code);
        this.setTypeElement(object.type);
        this.setCategory("BPMN");
        this.setSeverity(object.severity);
        this.setDescription(object.description);
        this.setCriteria(object.criteria);
    }
};
/**
 * Get code rule
 * @returns {String}
 */
ItemRule.prototype.getCode = function () {
    return this.code;
};
/**
 * Get type rule
 * @returns {String}
 */
ItemRule.prototype.getTypeElement = function () {
    return this.type;
};
/**
 * Get category
 * @returns {String}
 */
ItemRule.prototype.getCategory = function () {
    return this.category;
};
/**
 * Get severity
 * @returns {String}
 */
ItemRule.prototype.getSeverity = function () {
    return this.severity;
};
/**
 * Get description
 * @returns {String}
 */
ItemRule.prototype.getDescription = function () {
    return this.description;
};
/**
 * Get criteria
 * @returns {function}
 */
ItemRule.prototype.getCriteria = function () {
    return this.criteria;
};
/**
 * Validate code
 * @param codeRule {String}
 * @returns {Boolean}
 */
ItemRule.prototype.isCodeRule = function (codeRule) {
    var isCode = false;
    if (codeRule === this.code) {
        isCode = true;
    }
    return isCode;
};
/**
 * Set code
 * @param code
 * @returns {ItemRule}
 */
ItemRule.prototype.setCode = function (code) {
    if (code && typeof code === "string") {
        this.code = code;
    }
    return this;
};
/**
 * Set type element
 * @param typeElement {String}
 * @returns {ItemRule}
 */
ItemRule.prototype.setTypeElement = function (typeElement) {
    if (typeElement && typeof typeElement === "string") {
        this.type = typeElement;
    }
    return this;
};
/**
 * Set type element
 * @param typeElement {String}
 * @returns {ItemRule}
 */
ItemRule.prototype.setCategory = function (category) {
    if (category && typeof category === "string") {
        this.category = category;
    }
    return this;
};
/**
 * Set severity
 * @param severity {String}
 * @returns {ItemRule}
 */
ItemRule.prototype.setSeverity = function (severity) {
    if (severity && typeof severity === "string") {
        this.severity = severity;
    }
    return this;
};
/**
 * Set description
 * @param description {String}
 * @returns {ItemRule}
 */
ItemRule.prototype.setDescription = function (description) {
    if (description && typeof description === "string") {
        this.description = description;
    }
    return this;
};
/**
 * Set criteria
 * @param criteria {Function}
 * @returns {ItemRule}
 */
ItemRule.prototype.setCriteria = function (criteria) {
    if (criteria && typeof criteria === "function") {
        this.criteria = criteria;
    }
    return this;
};