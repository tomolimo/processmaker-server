'use strict';

/**
 * An empty collection stub. Use {@link RefsCollection.extend} to extend a
 * collection with ref semantics.
 *
 * @classdesc A change and inverse-reference aware collection with set semantics.
 *
 * @class RefsCollection
 */
function RefsCollection() { }

/**
 * Extends a collection with {@link Refs} aware methods
 *
 * @memberof RefsCollection
 * @static
 *
 * @param  {Array<Object>} collection
 * @param  {Refs} refs instance
 * @param  {Object} property represented by the collection
 * @param  {Object} target object the collection is attached to
 *
 * @return {RefsCollection<Object>} the extended array
 */
function extend(collection, refs, property, target) {

    var inverseProperty = property.inverse;

    /**
     * Removes the given element from the array and returns it.
     *
     * @method RefsCollection#remove
     *
     * @param {Object} element the element to remove
     */
    collection.remove = function(element) {
        var idx = this.indexOf(element);
        if (idx !== -1) {
            this.splice(idx, 1);

            // unset inverse
            refs.unset(element, inverseProperty, target);
        }

        return element;
    };

    /**
     * Returns true if the collection contains the given element
     *
     * @method RefsCollection#contains
     *
     * @param {Object} element the element to check for
     */
    collection.contains = function(element) {
        return this.indexOf(element) !== -1;
    };

    /**
     * Adds an element to the array, unless it exists already (set semantics).
     *
     * @method RefsCollection#add
     *
     * @param {Object} element the element to add
     */
    collection.add = function(element) {

        if (!this.contains(element)) {
            this.push(element);

            // set inverse
            refs.set(element, inverseProperty, target);
        }
    };

    return collection;
}


//module.exports.extend = extend;