/**
 * @class PMActivity
 * @param {Object} options
 */
var PMActivity = function (options) {
    PMShape.call(this, options);

    /**
     * Activity Alphanumeric unique identifier
     * @type {String}
     */
    this.act_uid = null;
    /**
     * Activity name
     * @type {String}
     */
    this.act_name = null;
    /**
     * Activity Type
     * @type {String}
     */
    this.act_type = null;
    /**
     * Define if the task is for compensation (BPMN)
     * @type {Boolean}
     */
    this.act_is_for_compensation = null;
    /**
     * Define the quantity needed to start the activity
     * @type {Number}
     */
    this.act_start_quantity = null;
    /**
     * Define the quantity needed to complete the activity
     * @type {Number}
     */
    this.act_completion_quantity = null;
    /**
     * Define the task type.
     * @type {String}
     */
    this.act_task_type = null;
    /**
     * Define the task loop type.
     * @type {String}
     */
    this.act_loop_type = null;
    /**
     * Define the activity implementation
     * @type {String}
     */
    this.act_implementation = null;
    /**
     * Define the instatiation status
     * @type {Boolean}
     */
    this.act_instantiate = null;
    /**
     * Define the script type supported
     * @type {String}
     */
    this.act_script_type = null;
    /**
     * Define the script
     * @type {String}
     */
    this.act_script = null;
    /**
     * Defines the loop type accepted
     * @type {String}
     */
    this.act_loop_type = null;
    /**
     * Define if the test to complete the loop would be executed before o later
     * @type {Boolean}
     */
    this.act_test_before = null;
    /**
     * Defines the maximum value of loops allowed
     * @type {Number}
     */
    this.act_loop_maximum = null;
    /**
     * Defines the loop condition
     * @type {String}
     */
    this.act_loop_condition = null;
    /**
     * Defines the loop cardinality
     * @type {String}
     */
    this.act_loop_cardinality = null;
    /**
     * Defines the loop behavior
     * @type {String}
     */
    this.act_loop_behavior = null;
    /**
     * Define if the activity has an adhoc behavior
     * @type {Boolean}
     */
    this.act_is_adhoc = null;
    /**
     * Defines if the activity is collapsed
     * @type {Boolean}
     */
    this.act_is_collapsed = null;
    /**
     * Defines the condition needed to complete the activity
     * @type {String}
     */
    this.act_completion_condition = null;
    /**
     * Define the order to be executed when exists several task in parallel mode
     * @type {Number}
     */
    this.act_ordering = null;
    /**
     * Defines if into a loop all instances would be cancelled
     * @type {Boolean}
     */
    this.act_cancel_remaining_instances = null;
    /**
     * Defines the protocol used for the transaction activities
     * @type {String}
     */
    this.act_protocol = null;
    /**
     * Define the method to be used when activity consume/execute a web service
     * @type {String}
     */
    this.act_method = null;
    /**
     * Define the scope of the activity
     * @type {Boolean}
     */
    this.act_is_global = null;
    /**
     * Define the referer to another object (Process, Participant or Another Activity)
     * @type {String}
     */
    this.act_referer = null;
    /**
     * Defines the default flow when activity is related to two or more flows
     * @type {String}
     */
    this.act_default_flow = null;
    /**
     * Defines the diagram related when activity plays as subprocess
     * @type {String}
     */
    this.act_master_diagram = null;
    /**
     * Array of Boundary places created to receive boundary events
     * @type {Array}
     */
    this.boundaryPlaces = new PMUI.util.ArrayList();
    /**
     * Array of Boundary events attached to this activity
     * @type {Array}
     */
    this.boundaryArray = new PMUI.util.ArrayList();
    this.isValidDropArea = true;

    PMActivity.prototype.init.call(this, options);
};

/**
 * Point the prototype to the PMShape Object
 * @type {PMShape}
 */
PMActivity.prototype = new PMShape();
/**
 * Defines the object type
 * @type {String}
 */
PMActivity.prototype.type = 'PMActivity';
/**
 * Points to container behavior object
 * @type {Object}
 */
PMActivity.prototype.activityContainerBehavior = null;
/**
 * Points to the resize behavior object
 * @type {Object}
 */
PMActivity.prototype.activityResizeBehavior = null;

PMActivity.prototype.mapBpmnType = {
    'EMPTY': 'bpmn:Task',
    'SENDTASK': 'bpmn:SendTask',
    'RECEIVETASK': 'bpmn:ReceiveTask',
    'USERTASK': 'bpmn:UserTask',
    'SERVICETASK': 'bpmn:ServiceTask',
    'SCRIPTTASK': 'bpmn:ScriptTask',
    'MANUALTASK': 'bpmn:ManualTask',
    'BUSINESSRULE': 'bpmn:BusinessRuleTask'
};
PMActivity.prototype.supportedArray = [
    'EMPTY',
    'COLLAPSED',
    'SENDTASK',
    'RECEIVETASK',
    'USERTASK',
    'SERVICETASK',
    'SCRIPTTASK',
    'MANUALTASK',
    'BUSINESSRULE'
];

PMActivity.prototype.supportedLoopArray = [
    'EMPTY',
    'NONE',
    'LOOP',
    'PARALLEL',
    'SEQUENTIAL'
];

PMActivity.prototype.mapLoopTypes = {
    'LOOP': 'bpmn:StandardLoopCharacteristics',
    'PARALLEL': 'bpmn:MultiInstanceLoopCharacteristics',
    'SEQUENTIAL': 'bpmn:MultiInstanceLoopCharacteristics'
};
/**
 * Initialize object with default values
 * @param options
 */
PMActivity.prototype.init = function (options) {
    var defaults = {
        act_type: 'TASK',
        act_name: 'Task',
        act_loop_type: 'NONE',
        act_is_for_compensation: false,
        act_task_type: 'EMPTY',
        act_is_collapsed: false,
        act_is_global: false,
        act_loop_cardinality: 0,
        act_loop_maximum: 0,
        act_start_quantity: 1,
        act_is_adhoc: false,
        act_cancel_remaining_instances: true,
        act_instantiate: false,
        act_completion_quantity: 0,
        act_implementation: '',
        act_script: '',
        act_script_type: '',
        act_default_flow: 0,
        minHeight: 50,
        minWidth: 100,
        maxHeight: 500,
        maxWidth: 600
    };
    jQuery.extend(true, defaults, options);
    this.setActivityUid(defaults.act_uid)
        .setActName(defaults.act_name)
        .setActivityType(defaults.act_type)
        .setLoopType(defaults.act_loop_type)
        .setIsForCompensation(defaults.act_is_for_compensation)
        .setTaskType(defaults.act_task_type)
        .setIsCollapsed(defaults.act_is_collapsed)
        .setIsGlobal(defaults.act_is_global)
        .setLoopCardinality(defaults.act_loop_cardinality)
        .setLoopMaximun(defaults.act_loop_maximum)
        .setStartQuantity(defaults.act_start_quantity)
        .setIsAdhoc(defaults.act_is_adhoc)
        .setCancelRemainingInstances(defaults.act_cancel_remaining_instances)
        .setInstantiate(defaults.act_instantiate)
        .setImplementation(defaults.act_implementation)
        .setCompletionQuantity(defaults.act_completion_quantity)
        .setScript(defaults.act_script)
        .setScriptType(defaults.act_script_type)
        .setDefaultFlow(defaults.act_default_flow)
        .setMinHeight(defaults.minHeight)
        .setMinWidth(defaults.minWidth)
        .setMaxHeight(defaults.maxHeight)
        .setMaxWidth(defaults.maxWidth);
    this.setOnBeforeContextMenu(this.beforeContextMenu);
};
/**
 * Sets the label element
 * @param {String} value
 * @return {*}
 */
PMActivity.prototype.setName = function (name) {
    if (typeof name !== 'undefined') {
        this.act_name = name;
        if (this.label) {
            this.label.setMessage(name);
        }
    }
    return this;
};
/**
 * Returns the activity type property
 * @return {String}
 */
PMActivity.prototype.getActivityType = function () {
    return this.act_type;
};

/**
 * Returns the is for compensation property
 * @return {Boolean}
 */
PMActivity.prototype.getIsForCompensation = function () {
    return this.act_is_for_compensation;
};

/**
 * Returns if the activity cancel remaining instances when is cancelled
 * @return {Boolean}
 */
PMActivity.prototype.getCancelRemainingInstances = function () {
    return this.act_cancel_remaining_instances;
};

/**
 * Returns the quantity needed to complete an activity
 * @return {Number}
 */
PMActivity.prototype.getCompletionQuantity = function () {
    return this.act_completion_quantity;
};

/**
 * Set is the activity is global (scope)
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.getIsGlobal = function () {
    return this.act_is_global;
};

/**
 * Returns the start quantity needed to start an activity
 * @return  {Number}
 */
PMActivity.prototype.getStartQuantity = function () {
    return this.act_start_quantity;
};

/**
 * Returns if the instance is active
 * @return {Boolean}
 */
PMActivity.prototype.getInstantiate = function () {
    return this.act_instantiate;
};

/**
 * Returns the implementation property
 * @return {String}
 */
PMActivity.prototype.getImplementation = function () {
    return this.act_implementation;
};

/**
 * Return the Script property
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.getScript = function () {
    return this.act_script;
};

/**
 * Return the Script Type property
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.getScriptType = function () {
    return this.act_script_type;
};

/**
 * Return the minimun height of an activity
 * @return {*}
 */
PMActivity.prototype.getMinHeight = function () {
    return this.minHeight;
};

/**
 * Return the minimun width of an activity
 * @return {*}
 */
PMActivity.prototype.getMinWidth = function () {
    return this.minWidth;
};
/**
 * Return the maximun height of an activity
 * @return {*}
 */
PMActivity.prototype.getMaxHeight = function () {
    return this.maxHeight;
};

/**
 * Return the maximun width of an activity
 * @return {*}
 */
PMActivity.prototype.getMaxWidth = function () {
    return this.maxWidth;
};
/**
 * Sets the act_uid property
 * @param {String} value
 * @return {*}
 */
PMActivity.prototype.setActivityUid = function (value) {
    this.act_uid = value;
    return this;
};

/**
 * Sets the activity type property
 * @param {String} type
 * @return {*}
 */
PMActivity.prototype.setActivityType = function (type) {
    this.act_type = type;
    return this;
};

/**
 * Sets the implementation property
 * @param {String} type
 * @return {*}
 */
PMActivity.prototype.setImplementation = function (type) {
    this.act_implementation = type;
    return this;
};

/**
 * Set the loop type property
 * @param {String} type
 * @return {*}
 */
PMActivity.prototype.setLoopType = function (type) {
    this.act_loop_type = type;
    return this;
};

/**
 * Sets the collapsed property
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setIsCollapsed = function (value) {
    if (typeof value === "boolean") {
        this.act_is_collapsed = value;
    }
    return this;
};

/**
 * Sets the is for compensation property
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setIsForCompensation = function (value) {
    if (typeof value === "boolean") {
        this.act_is_for_compensation = value;
    }
    return this;
};

/**
 * Sets the activity task type
 * @param {String} type
 * @return {*}
 */
PMActivity.prototype.setTaskType = function (type) {
    this.act_task_type = type;
    return this;
};

/**
 * Sets the activity task type
 * @param {String} type
 * @return {*}
 */
PMActivity.prototype.setLoopType = function (type) {
    this.act_loop_type = type;
    return this;
};

/**
 * Set is the activity is global (scope)
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setIsGlobal = function (value) {
    if (typeof value === "boolean") {
        this.act_is_global = value;
    }
    return this;
};

/**
 * Set the loop cardinality of the activity
 * @param {String} value
 * @return {*}
 */
PMActivity.prototype.setLoopCardinality = function (value) {
    this.act_loop_cardinality = value;
    return this;
};

/**
 * Sets the loop maximun value
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setLoopMaximun = function (value) {
    this.act_loop_maximum = value;
    return this;
};

/**
 * Sets the start quantity needed to start an activity
 * @param  {Number} value
 * @return {*}
 */
PMActivity.prototype.setStartQuantity = function (value) {
    this.act_start_quantity = value;
    return this;
};

/**
 * Sets if the activity has an adhoc behavior
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setIsAdhoc = function (value) {
    if (typeof value === "boolean") {
        this.act_is_adhoc = value;
    }
    return this;
};

/**
 * Sets if the activity cancel remaining instances when is cancelled
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setCancelRemainingInstances = function (value) {
    if (typeof value === "boolean") {
        this.act_cancel_remaining_instances = value;
    }
    return this;
};

/**
 * Sets if the instance is active
 * @param {Boolean} value
 * @return {*}
 */
PMActivity.prototype.setInstantiate = function (value) {
    if (typeof value === "boolean") {
        this.act_instantiate = value;
    }
    return this;
};

/**
 * Sets the quantity needed to complete an activity
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setCompletionQuantity = function (value) {
    this.act_completion_quantity = value;
    return this;
};

/**
 * Sets the Script property
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setScript = function (value) {
    this.act_script = value;
    return this;
};

/**
 * Sets the Script Type property
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setScriptType = function (value) {
    this.act_script_type = value;

    return this;
};

/**
 * Sets te default_flow property
 * @param value
 * @return {*}
 */
PMActivity.prototype.setDefaultFlow = function (value) {
    if (this.html) {
        PMShape.prototype.setDefaultFlow.call(this, value);
        this.canvas.triggerCommandAdam(this, ['act_default_flow'], [this.act_default_flow], [value]);
    }
    this.act_default_flow = value;
    return this;
};
/**
 * Sets the minimun height
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setMinHeight = function (value) {
    this.minHeight = value;
    return this;
};

/**
 * Sets the minimun with
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setMinWidth = function (value) {
    this.minWidth = value;

    return this;
};
/**
 * Sets the maximun height
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setMaxHeight = function (value) {
    this.maxHeight = value;
    return this;
};

/**
 * Sets the maximun with
 * @param {Number} value
 * @return {*}
 */
PMActivity.prototype.setMaxWidth = function (value) {
    this.maxWidth = value;

    return this;
};

PMActivity.prototype.setActType = function (type) {
    this.act_type = type;
    return this;
};

PMActivity.prototype.setActName = function (name) {
    this.act_name = name;
    return this;
};
/**
 * Factory of activity behaviors. It uses lazy instantiation to create
 * instances of the different container behaviors
 * @param {String} type An string that specifies the container behavior we want
 * an instance to have, it can be regular or nocontainer
 * @return {ContainerBehavior}
 */
PMActivity.prototype.containerBehaviorFactory = function (type) {
    if (type === 'activity') {
        if (!this.activityContainerBehavior) {
            this.activityContainerBehavior = new ActivityContainerBehavior();
        }
        return this.activityContainerBehavior;
    } else {
        return PMShape.prototype.containerBehaviorFactory.call(this, type);
    }
};

PMActivity.prototype.dropBehaviorFactory = function (type, selectors) {
    if (type === 'pmconnection') {
        if (!this.pmConnectionDropBehavior) {
            this.pmConnectionDropBehavior = new PMConnectionDropBehavior(selectors);
        }
        return this.pmConnectionDropBehavior;
    } else if (type === 'pmcontainer') {
        if (!this.pmContainerDropBehavior) {
            this.pmContainerDropBehavior = new PMContainerDropBehavior(selectors);
        }
        return this.pmContainerDropBehavior;
    } else if (type === 'pmactivitydrop') {
        if (!this.pmContainerDropBehavior) {
            this.pmContainerDropBehavior = new PMActivityDropBehavior(selectors);
        }
        return this.pmContainerDropBehavior;
    } else {
        return PMUI.draw.CustomShape.prototype.dropBehaviorFactory.call(this, type, selectors);
    }
};

PMActivity.prototype.getDataObject = function () {
    var name = this.getName(),
        container,
        element_id;
    switch (this.parent.type) {
        case 'PMCanvas':
            container = 'bpmnDiagram';
            element_id = this.canvas.id;
            break;
        case 'PMPool':
            container = 'bpmnPool';
            element_id = this.parent.id;
            break;
        case 'PMLane':
            container = 'bpmnLane';
            element_id = this.parent.id;
            break;
        default:
            container = 'bpmnDiagram';
            element_id = this.canvas.id;
            break;
    }
    return {
        act_uid: this.id,
        act_name: name,
        act_type: this.act_type,
        act_task_type: this.act_task_type,
        act_loop_type: this.act_loop_type,
        act_is_for_compensation: this.act_is_for_compensation,
        act_start_quantity: this.act_start_quantity,
        act_completion_quantity: this.act_completion_quantity,
        act_implementation: this.act_implementation,
        act_instantiate: this.act_instantiate,
        act_script_type: this.act_script_type,
        act_script: this.act_script,
        act_loop_type: this.act_loop_type,
        act_test_before: this.act_test_before,
        act_loop_maximum: this.act_loop_maximum,
        act_loop_condition: this.act_loop_condition,
        act_loop_cardinality: this.act_loop_cardinality,
        act_loop_behavior: this.act_loop_behavior,
        act_is_adhoc: this.act_is_adhoc,
        act_is_collapsed: this.act_is_collapsed,
        act_completion_condition: this.act_completion_condition,
        act_ordering: this.act_ordering,
        act_cancel_remaining_instances: this.act_cancel_remaining_instances,
        act_protocol: this.act_protocol,
        act_method: this.act_method,
        act_is_global: this.act_is_global,
        act_referer: this.act_referer,
        act_default_flow: this.act_default_flow,
        act_master_diagram: this.act_master_diagram,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: container,
        bou_element: element_id,
        _extended: this.getExtendedObject()
    };
};
/**
 * Create/Initialize the boundary places array
 * @return {*}
 */
PMActivity.prototype.makeBoundaryPlaces = function () {
    var bouX,
        bouY,
        factor = 3,
        space,
        number = 0,
        shape = this.boundaryArray.getFirst(),
        numBottom = 0,
        numLeft = 0,
        numTop = 0,
        numRight = 0;

    //BOTTON
    bouY = shape.parent.getHeight() - shape.getHeight() / 2;
    bouX = shape.parent.getWidth() - (numBottom + 1) * (shape.getWidth() + factor);
    while (bouX + shape.getWidth() / 2 > 0) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'BOTTOM';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numBottom += 1;
        bouX = shape.parent.getWidth() - (numBottom + 1) * (shape.getWidth() + factor);
    }
    //LEFT
    bouY = shape.parent.getHeight() - (numLeft + 1) * (shape.getHeight() + factor);
    bouX = -shape.getHeight() / 2;
    while (bouY + shape.getHeight() / 2 > 0) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'LEFT';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numLeft += 1;
        bouY = shape.parent.getHeight() - (numLeft + 1) * (shape.getHeight() + factor);
    }

    //TOP
    bouY = -shape.getWidth() / 2;
    bouX = numTop * (shape.getWidth() + factor);
    while (bouX + shape.getWidth() / 2 < shape.parent.getWidth()) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'TOP';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numTop += 1;
        bouX = numTop * (shape.getWidth() + factor);
    }
    //RIGHT
    bouY = numRight * (shape.getHeight() + factor);
    bouX = shape.parent.getWidth() - shape.getWidth() / 2;
    while (bouY + shape.getHeight() / 2 < shape.parent.getHeight()) {
        space = {};
        space.x = bouX;
        space.y = bouY;
        space.available = true;
        space.number = number;
        space.location = 'RIGHT';
        shape.parent.boundaryPlaces.insert(space);
        number += 1;
        numRight += 1;
        bouY = numRight * (shape.getHeight() + factor);
    }
    return this;
};

/**
 * Sets the boundary element to a selected boundary place
 * @param {PMEvent} shape
 * @param {Number} number
 * @return {*}
 */
PMActivity.prototype.setBoundary = function (shape, number) {
    var bouPlace = this.boundaryPlaces.get(number);
    bouPlace.available = false;
    shape.setPosition(bouPlace.x, bouPlace.y);
    return this;
};

/**
 * Returns the current place available to attach boundary events.
 * Retuns false if there's not place available
 * @return {Number/Boolean}
 */
PMActivity.prototype.getAvailableBoundaryPlace = function () {
    var place = 0,
        bouPlace,
        sw = true,
        i;
    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        bouPlace = this.boundaryPlaces.get(i);
        if (bouPlace.available && sw) {
            place = bouPlace.number;
            sw = false;
        }
    }
    if (sw) {
        place = false;
    }
    return place;
};

/**
 * Update Boundary Places Array
 * @return {*}
 */
PMActivity.prototype.updateBoundaryPlaces = function () {
    var i,
        aux,
        k = 0;
    aux = new PMUI.util.ArrayList();
    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        aux.insert(this.boundaryPlaces.get(i));
    }

    this.boundaryPlaces.clear();
    this.makeBoundaryPlaces();

    for (i = 0; i < this.boundaryPlaces.getSize(); i += 1) {
        if (k < aux.getSize()) {
            this.boundaryPlaces.get(i).available = aux.get(k).available;
            k += 1;
        }
    }
    return this;
};

/**
 * Returns the number of boundary events attached to this activity
 * @return {Number}
 */
PMActivity.prototype.getNumberOfBoundaries = function () {
    var child,
        i,
        bouNum = 0;

    for (i = 0; i < this.getChildren().getSize(); i += 1) {
        child = this.getChildren().get(i);
        if (child.getType() === 'PMEvent' && child.evn_type === 'BOUNDARY') {
            bouNum = bouNum + 1;
        }
    }
    return bouNum;
};
/**
 * Updates boundary positions when exists a change into the boundary array
 * @param {Boolean} createIntersections
 */
PMActivity.prototype.updateBoundaryPositions = function (createIntersections) {
    var child,
        port,
        i,
        j;

    if (this.getNumberOfBoundaries() > 0) {
        this.updateBoundaryPlaces();
        for (i = 0; i < this.getChildren().getSize(); i += 1) {
            child = this.getChildren().get(i);
            if (child.getType() === 'PMEvent'
                && child.evn_type === 'BOUNDARY') {
                child.setPosition(this.boundaryPlaces.get(child.numberRelativeToActivity).x,
                    this.boundaryPlaces.get(child.numberRelativeToActivity).y
                );
                for (j = 0; j < child.ports.getSize(); j += 1) {
                    port = child.ports.get(j);
                    port.setPosition(port.x, port.y);
                    port.connection.disconnect().connect();
                    if (createIntersections) {
                        port.connection.setSegmentMoveHandlers();
                        port.connection.checkAndCreateIntersectionsWithAll();
                    }
                }
            }
        }
    }
};

PMActivity.prototype.getActivityType = function () {
    return this.act_type;
};

PMActivity.prototype.getContextMenu = function () {
};
PMActivity.prototype.getTaskType = function () {
    return this.act_task_type;
};

PMActivity.prototype.getLoopType = function () {
    return this.act_loop_type;
};

PMActivity.prototype.updateDefaultFlow = function (destID) {
    this.act_default_flow = destID;
    return this;
};

PMActivity.prototype.updateTaskType = function (newType) {
    return this;
};

PMActivity.prototype.updateScriptType = function (newType) {
    return this;
};

PMActivity.prototype.changeColor = function (newTheme) {
    switch (newTheme) {
        case 'red':
            newClass = 'mafe-activity-task-' + newTheme;
            break;
        case 'green':
            newClass = 'mafe-activity-task-' + newTheme;
            break;
        case 'orange':
            newClass = 'mafe-activity-task-' + newTheme;
            break;
        case 'silver':
            newClass = 'mafe-activity-task-' + newTheme;
            break;
        default:
            newClass = 'mafe-activity-task';
            break;

    }
    var firstLayer = this.getLayers().asArray()[0];
    //remove custom clasess
    firstLayer.style.removeClasses(['mafe-activity-task', 'mafe-activity-task-red', 'mafe-activity-task-green', 'mafe-activity-task-orange', 'mafe-activity-task-silver']);
    //add the new class
    firstLayer.style.addClasses([newClass]);
    return this;
};

PMActivity.prototype.setResizeBehavior = function (behavior) {
    var factory = new PMUI.behavior.BehaviorFactory({
        products: {
            "regularresize": PMUI.behavior.RegularResizeBehavior,
            "Resize": PMUI.behavior.RegularResizeBehavior,
            "yes": PMUI.behavior.RegularResizeBehavior,
            "resize": PMUI.behavior.RegularResizeBehavior,
            "noresize": PMUI.behavior.NoResizeBehavior,
            "NoResize": PMUI.behavior.NoResizeBehavior,
            "no": PMUI.behavior.NoResizeBehavior,
            "activityResize": PMActivityResizeBehavior
        },
        defaultProduct: "noresize"
    });
    this.resizeBehavior = factory.make(behavior);
    if (this.html) {
        this.resize.init(this);
    }
    return this;
};
/**
 * Change task type
 * @param {String} type
 * @returns {*}
 */
PMActivity.prototype.switchTaskType = function (type) {
    var marker = this.markersArray.get(0),
        lowerType = type.toLowerCase();
    marker.removeAllClasses();
    marker.setMarkerZoomClasses([
        "mafe-" + lowerType + "-marker-10",
        "mafe-" + lowerType + "-marker-15",
        "mafe-" + lowerType + "-marker-21",
        "mafe-" + lowerType + "-marker-26",
        "mafe-" + lowerType + "-marker-31"
    ]);
    marker.paint(true);
    this.setTaskType(type);
    this.paint();
    this.updateBpmnTaskType(this.mapBpmnType[this.getTaskType()]);
    PMDesigner.connectValidator.bpmnValidator();
    return this;
};

/**
 * Change subprocess type
 * @param {String} type
 * @returns {*}
 */
PMActivity.prototype.switchSubProcessType = function (type) {
    var marker = this.markersArray.get(0),
        lowerType = type.toLowerCase();
    marker.removeAllClasses();
    marker.setMarkerZoomClasses([
        "mafe-" + lowerType + "-marker-10",
        "mafe-" + lowerType + "-marker-15",
        "mafe-" + lowerType + "-marker-21",
        "mafe-" + lowerType + "-marker-26",
        "mafe-" + lowerType + "-marker-31"
    ]);
    marker.paint(true);
    this.setTaskType(type);
    this.paint();
    return this;
};

PMActivity.prototype.executeLoopType = function (type) {
    var marker = this.markersArray.get(1),
        lowerType = type.toLowerCase();
    marker.removeAllClasses();
    marker.setMarkerZoomClasses([
        "mafe-" + lowerType + "-marker-10",
        "mafe-" + lowerType + "-marker-15",
        "mafe-" + lowerType + "-marker-21",
        "mafe-" + lowerType + "-marker-26",
        "mafe-" + lowerType + "-marker-31"
    ]);
    marker.paint(true);
    this.setLoopType(type);
    this.paint();
    this.updateBpmnTaskType(this.mapBpmnType[this.getTaskType()], this.getLoopType());
    PMDesigner.connectValidator.bpmnValidator();
    return this;
};
/**
 * this method admin thw activity loop types
 * @param type
 * @param shape
 */
PMActivity.prototype.switchLoopType = function (type) {
    var data = {
            act_uid: this.getID()
        },
        self = this,
        url = '/project/' + PMDesigner.project.id + '/activity/validate-active-cases';

    PMDesigner.restApi.execute({
        data: JSON.stringify(data),
        method: "update",
        url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + url,
        success: function (data, textStatus, xhr) {
            if (data.result) {
                self.executeLoopType(type);
            } else {
                PMDesigner.msgFlash(data.message, document.body, 'error', 3000, 5);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            PMDesigner.msgFlash('There are problems updating the Loop Marker'.translate(), document.body, 'error', 3000, 5);
        }
    });

};

PMActivity.prototype.beforeContextMenu = function () {
    var items, i,
        menuItem,
        hasMarker = false;
    this.canvas.hideAllCoronas();
    if (this.canvas.readOnly) {
        return;
    }
    if (this.getActivityType() === 'TASK') {
        items = this.menu.items.find('id', 'taskType').childMenu.items;
        for (i = 0; i < items.getSize(); i += 1) {
            menuItem = items.get(i);
            if (menuItem.id === this.getTaskType().toLowerCase()) {
                menuItem.disable();
                hasMarker = true;
            } else {
                menuItem.enable();
            }
        }

        items = this.menu.items.find('id', 'loopType').childMenu.items;
        for (i = 0; i < items.getSize(); i += 1) {
            menuItem = items.get(i);
            if (menuItem.id === this.getLoopType().toLowerCase()) {
                menuItem.disable();
                hasMarker = true;
            } else {
                menuItem.enable();
            }
        }
    }

};
/**
 * updates XML task type tag, removes the current tag and create a new updated
 * @param newBpmnType
 * @param loopType
 */
PMActivity.prototype.updateBpmnTaskType = function (newBpmnType, loopType) {
    var tempID = this.businessObject.elem.id,
        outgoing,
        incoming;

    outgoing = (this.businessObject.elem && this.businessObject.elem.outgoing) ?
        this.businessObject.elem.outgoing : null;
    incoming = (this.businessObject.elem && this.businessObject.elem.incoming) ?
        this.businessObject.elem.incoming : null;
    this.removeBpmn();

    this.businessObject.elem = null;
    this.createBpmn(newBpmnType);
    this.businessObject.elem.id = tempID;
    this.businessObject.elem.incoming = (incoming) ? incoming : null;
    this.businessObject.elem.outgoing = (outgoing) ? outgoing : null;
    if (loopType && typeof loopType !== 'undefined' && loopType !== 'EMPTY') {
        this.createLoopCharacteristics(this.businessObject.elem, loopType);
    }


};

PMActivity.prototype.getBpmnElementType = function () {
    if (this.extendedType === 'SUB_PROCESS') {
        return 'bpmn:SubProcess';
    } else {
        return this.mapBpmnType[this.getTaskType()];
    }

};
PMActivity.prototype.isSupported = function () {
    var isSupported = false;
    if (this.supportedArray.indexOf(this.getTaskType()) !== -1) {
        isSupported = true;
        if (this.getTaskType() != "COLLAPSED") {
            if (this.supportedLoopArray.indexOf(this.getLoopType()) !== -1) {
                isSupported = true;
            } else {
                isSupported = false;
            }
        }
    }
    return isSupported;
};
/**
 * Creates XML Loop task tag to export.
 * @param element
 * @param loopType
 */
PMActivity.prototype.createLoopCharacteristics = function (element, loopType) {
    var loopTypeA,
        loopChar = {
            isSequential: false,
            behavior: 'All'
        };
    loopTypeA = this.mapLoopTypes[loopType];
    element['loopCharacteristics'] = PMDesigner.bpmnFactory.create(loopTypeA, loopChar);
    if (loopType == "SEQUENTIAL") {
        element['loopCharacteristics'].set('isSequential', true);
    } else {
        element['loopCharacteristics'].set('isSequential', false);
    }
};

PMActivity.prototype.createBpmn = function (type) {

    if (!this.businessObject.elem && !(this instanceof PMUI.draw.MultipleSelectionContainer)) {
        this.createWithBpmn(type, 'businessObject');
    }
    this.updateBounds(this.businessObject.di);
    if (this.parent.getType() === 'PMCanvas' && !this.parent.businessObject.di) {
        this.canvas.createBPMNDiagram();
    }
    //LOOP characteristics
    if (this.act_loop_type && this.act_loop_type !== "EMPTY") {
        this.createLoopCharacteristics(this.businessObject.elem, this.act_loop_type);
    }

    if (this.parent.businessObject.elem) {
        this.updateShapeParent(this.businessObject, this.parent.businessObject);
    } else {
        //Here create busines object to new process
        this.parent.createBusinesObject();

        this.updateShapeParent(this.businessObject, this.parent.businessObject);
    }
};
