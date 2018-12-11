/**
 * @class PMEvent
 * @param {Object} options
 */
var PMEvent = function (options) {
    PMShape.call(this, options);
    /**
     * Defines the alphanumeric unique code
     * @type {String}
     */
    this.evn_uid = null;
    /**
     * Defines the name
     * @type {String}
     */
    this.evn_name = null;
    /**
     * Defines the event type
     * @type {String}
     */
    this.evn_type = null;
    /**
     * Defines the event marker supported
     * @type {String}
     */
    this.evn_marker = null;
    /**
     * Defines id the event interrups or not the execution
     * @type {Boolean}
     */
    this.evn_is_interrupting = true;
    /**
     * Defines the activity attachec when the event is a boundary element
     * @type {String}
     */
    this.evn_attached_to = null;
    /**
     * Defines if the event can cancel the activity attached to
     * @type {Boolean}
     */
    this.evn_cancel_activity = false;
    /**
     * Define the activity related when event is playing as transactional event
     * @type {String}
     */
    this.evn_activity_ref = null;
    /**
     * Defines if the event needs to wait for completation status
     * @type {Boolean}
     */
    this.evn_wait_for_completion = false;
    /**
     * Defines the error name when event is playing like an error event
     * @type {String}
     */
    this.evn_error_name = null;
    /**
     * Defines the error code when event is playing like an error event
     * @type {String}
     */
    this.evn_error_code = null;
    /**
     * Defines the escalation name when event is playing like
     * an escalation event
     * @type {String}
     */
    this.evn_escalation_name = null;
    /**
     * Defines the escalation name when event is playing like
     * an escalation event
     * @type {String}
     */
    this.evn_escalation_code = null;
    /**
     * Defines the condition on the event
     * @type {String}
     */
    this.evn_condition = null;
    /**
     * Defines the message association
     * @type {String}
     */
    this.evn_message = null;
    /**
     * Defines the operation tom be executed when event is used like
     * a transactional event
     * @type {String}
     */
    this.evn_operation_name = null;
    /**
     * XXXX
     * @type {String}
     */
    this.evn_operation_implementation_ref = null;
    /**
     * Defines the date to be executed a timer event
     * @type {String}
     */
    this.evn_time_date = null;
    /**
     * Defines the time cycle to be executed a timer event
     * @type {String}
     */
    this.evn_time_cycle = null;
    /**
     * Defines the duration of the timer event
     * @type {String}
     */
    this.evn_time_duration = null;
    /**
     * Define the behavior of the event. Valid values are: CATCH, THROW
     * @type {String}
     */
    this.evn_behavior = null;

    /**
     * Defines the order of the boundary event when is attached to an activity
     * @type {Number}
     */
    this.numberRelativeToActivity = 0;
    this.businessObject = {};

    PMEvent.prototype.init.call(this, options);
};

PMEvent.prototype = new PMShape();
/**
 * Defines the object type
 * @type {String}
 */
PMEvent.prototype.type = 'PMEvent';
/**
 * Initialize the object with default values
 * @param {Object} options
 */
PMEvent.prototype.mapBpmnType = {
    'START': {
        'MESSAGECATCH': 'bpmn:MessageEventDefinition',
        'TIMER': 'bpmn:TimerEventDefinition',
        'CONDITIONAL': 'bpmn:ConditionalEventDefinition',
        'SIGNALCATCH': 'bpmn:SignalEventDefinition'
    },
    'INTERMEDIATE': {
        'MESSAGETHROW': 'bpmn:MessageEventDefinition',
        'EMAIL': 'bpmn:MessageEventDefinition',
        'MESSAGECATCH': 'bpmn:MessageEventDefinition',
        'TIMER': 'bpmn:TimerEventDefinition',
        'CONDITIONAL': 'bpmn:ConditionalEventDefinition',
        'LINKCATCH': 'bpmn:LinkEventDefinition',
        'SIGNALCATCH': 'bpmn:SignalEventDefinition',
        'LINKTHROW': 'bpmn:LinkEventDefinition',
        'COMPENSATIONTHROW': 'bpmn:CompensateEventDefinition',
        'SIGNALTHROW': 'bpmn:SignalEventDefinition'

    },
    'BOUNDARY': {
        'MESSAGETHROW': 'bpmn:MessageEventDefinition',
        'MESSAGECATCH': 'bpmn:MessageEventDefinition',
        'TIMER': 'bpmn:TimerEventDefinition',
        'CONDITIONAL': 'bpmn:ConditionalEventDefinition',
        'LINKCATCH': 'bpmn:LinkEventDefinition',
        'SIGNALCATCH': 'bpmn:SignalEventDefinition',
        'LINKTHROW': 'bpmn:LinkEventDefinition',
        'COMPENSATIONTHROW': 'bpmn:CompensateEventDefinition',
        'SIGNALTHROW': 'bpmn:SignalEventDefinition',
        'ERRORCATCH': 'bpmn:ErrorEventDefinition'
    },
    'END': {
        'MESSAGETHROW': 'bpmn:MessageEventDefinition',
        'EMAIL': 'bpmn:MessageEventDefinition',
        'SIGNALTHROW': 'bpmn:SignalEventDefinition',
        'ERRORTHROW': 'bpmn:ErrorEventDefinition',
        'CANCELHROW': 'bpmn:EscalationEventDefinition',
        'COMPENSATIONTHROW': 'bpmn:CompensateEventDefinition',
        'TERMINATETHROW': 'bpmn:TerminateEventDefinition',
        'CANCELTHROW': 'bpmn:CancelEventDefinition'
    }
};
PMEvent.prototype.supportedList = {
    'START': {
        'EMPTY': true,
        'MESSAGECATCH': true,
        'TIMER': true,
        'CONDITIONAL': true,
        'SIGNALCATCH': true
    },
    'INTERMEDIATE': {
        'MESSAGETHROW': true,
        'MESSAGECATCH': true,
        'TIMER': true,
        'CONDITIONAL': true,
        'SIGNALCATCH': true,
        'SIGNALTHROW': true,
        'EMAIL': true
    },
    'BOUNDARY': {
        'MESSAGETHROW': false,
        'MESSAGECATCH': false,
        'TIMER': false,
        'CONDITIONAL': false,
        'LINKCATCH': false,
        'SIGNALCATCH': false,
        'LINKTHROW': false,
        'COMPENSATIONTHROW': false,
        'SIGNALTHROW': false,
        'ERRORCATCH': false
    },
    'END': {
        'EMPTY': true,
        'MESSAGETHROW': true,
        'SIGNALTHROW': true,
        'ERRORTHROW': true,
        'CANCELHROW': false,
        'COMPENSATIONTHROW': false,
        'TERMINATETHROW': true,
        'CANCELTHROW': false,
        'EMAIL': true
    }
};

PMEvent.prototype.init = function (options) {
    var defaults = {
        evn_uid: '',
        evn_is_interrupting: true,
        evn_message: '',
        evn_name: '',
        evn_marker: 'EMPTY',
        evn_type: 'START',
        evn_behavior: 'CATCH'
    };
    jQuery.extend(true, defaults, options);
    this.setEventUid(defaults.evn_uid)
        .setEventType(defaults.evn_type)
        .setEventMarker(defaults.evn_marker)
        .setEventMessage(defaults.evn_message)
        .setBehavior(defaults.evn_behavior)
        .setCondition(defaults.evn_condition)
        .setAttachedTo(defaults.evn_attached_to)
        .setIsInterrupting(defaults.evn_is_interrupting);
    if (defaults.evn_name) {
        this.setName(defaults.evn_name);
    }
    this.setOnBeforeContextMenu(this.beforeContextMenu);
};

/**
 * Sets the label element
 * @param {String} value
 * @return {*}
 */
PMEvent.prototype.setName = function (name) {
    if (typeof name !== 'undefined') {
        this.evn_name = name;
        if (this.label) {
            this.label.setMessage(name);
        }
    }
    return this;
};

PMEvent.prototype.getDataObject = function () {
    var container,
        element_id,
        name = this.getName();
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
        case 'PMActivity':
            container = 'bpmnActivity';
            element_id = this.parent.id;
            break;
        default:
            container = 'bpmnDiagram';
            element_id = this.canvas.id;
            break;
    }

    return {
        evn_uid: this.id,
        evn_name: name,
        evn_type: this.evn_type,
        evn_marker: this.evn_marker,
        evn_is_interrupting: this.evn_is_interrupting,
        evn_attached_to: this.evn_attached_to,
        evn_cancel_activity: this.evn_cancel_activity,
        evn_activity_ref: this.evn_activity_ref,
        evn_wait_for_completion: this.evn_wait_for_completion,
        evn_error_name: this.evn_error_name,
        evn_error_code: this.evn_error_code,
        evn_escalation_name: this.evn_escalation_name,
        evn_escalation_code: this.evn_escalation_code,
        evn_condition: this.evn_condition,
        evn_message: this.evn_message,
        evn_operation_name: this.evn_operation_name,
        evn_operation_implementation_ref: this.evn_operation_implementation_ref,
        evn_time_date: this.evn_time_date,
        evn_time_cycle: this.evn_time_cycle,
        evn_time_duration: this.evn_time_duration,
        evn_behavior: this.evn_behavior,
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
 * Sets the event uid property
 * @param {String} id
 * @return {*}
 */
PMEvent.prototype.setEventUid = function (id) {
    this.evn_uid = id;
    return this;
};

/**
 * Sets the event type property
 * @param {String} type
 * @return {*}
 */
PMEvent.prototype.setEventType = function (type) {
    type = type.toLowerCase();
    var defaultTypes = {
        start: 'START',
        end: 'END',
        intermediate: 'INTERMEDIATE',
        boundary: 'BOUNDARY'
    };
    if (defaultTypes[type]) {
        this.evn_type = defaultTypes[type];
    }
    return this;
};
/**
 * Sets the event marker property
 * @param {String} marker
 * @return {*}
 */
PMEvent.prototype.setEventMarker = function (marker) {
    this.evn_marker = marker;
    return this;
};
/**
 * Sets if the event interrups the execution or not
 * @param {Boolean} value
 * @return {*}
 */
PMEvent.prototype.setIsInterrupting = function (value) {
    if (typeof value === "boolean") {
        this.evn_is_interrupting = value;
    }
    return this;
};
/**
 * Sets the event behavior property
 * @param {String} behavior
 * @return {*}
 */
PMEvent.prototype.setBehavior = function (behavior) {
    behavior = behavior.toLowerCase();
    var defaultBehaviors = {
        "catch": 'CATCH',
        "throw": 'THROW'
    };
    if (defaultBehaviors[behavior]) {
        this.evn_behavior = defaultBehaviors[behavior];
    }
    return this;
};
/**
 * Sets the activity id where the event is attached to
 * @param {String} value
 * @param {Boolean} [cancel]
 * @return {*}
 */
PMEvent.prototype.setAttachedTo = function (value, cancel) {
    if (typeof cancel !== 'undefined') {
        if (typeof cancel === "boolean") {
            this.evn_cancel_activity = cancel;
        }
    } else {
        this.evn_cancel_activity = this.evn_cancel_activity || false;
    }
    this.evn_attached_to = value;
    return this;
};
/**
 * Destroy a event
 * @returns {PMEvent}
 */
PMEvent.prototype.destroy = function () {
    if (this.getType() === 'PMEvent' && this.getEventType() === 'BOUNDARY') {
        if (this.parent.boundaryPlaces && this.numberRelativeToActivity !== null) {
            this.parent.boundaryPlaces
                .get(this.numberRelativeToActivity)
                .available = true;
            this.parent.boundaryArray.remove(this);
        }
    }
    return this;
};
/**
 * Sets the event message
 * @param {String} msg
 * @return {*}
 */
PMEvent.prototype.setEventMessage = function (msg) {
    this.evn_message = msg;
    return this;
};
/**
 * Sets the event condition property
 * @param {String} value
 * @return {*}
 */
PMEvent.prototype.setCondition = function (value) {
    this.evn_condition = value;
    return this;
};
/**
 * Set the compensation properties
 * @param {String} activity
 * @param {Boolean} wait
 * @return {*}
 */
PMEvent.prototype.setCompensationActivity = function (activity, wait) {
    if (typeof wait !== 'undefined') {
        if (typeof wait === "boolean") {
            this.evn_wait_for_completion = wait;
        }
    } else {
        this.evn_wait_for_completion = this.evn_wait_for_completion || false;
    }
    this.evn_activity_ref = activity;
    return this;
};
/**
 * Sets the error properties
 * @param {String} name  Error Name
 * @param {String} code  Error Code
 * @return {*}
 */
PMEvent.prototype.setEventError = function (name, code) {
    this.evn_error_name = name;
    this.evn_error_code = code;
    return this;
};

/**
 * Sets the escalation properties
 * @param {String} name Escalation Name
 * @param {String} code Escalation Code
 * @return {*}
 */
PMEvent.prototype.setEventEscalation = function (name, code) {
    this.evn_escalation_name = name;
    this.evn_escalation_code = code;
    return this;
};
/**
 * Sets the event operation properties
 * @param {String} name
 * @param {String} implementation
 * @return {*}
 */
PMEvent.prototype.setEventOperation = function (name, implementation) {
    this.evn_operation_name = name;
    this.evn_operation_implementation_ref = implementation;
    return this;
};
/**
 * Sets the event timer properties
 * @param {String} date
 * @param {String} cycle
 * @param {String} duration
 * @return {*}
 */
PMEvent.prototype.setEventTimer = function (date, cycle, duration) {
    this.evn_time_date = date;
    this.evn_time_cycle = cycle;
    this.evn_time_duration = duration;
    return this;
};
/**
 * Sets te default_flow property
 * @param value
 * @return {*}
 */
PMEvent.prototype.setDefaultFlow = function (value) {
    PMShape.prototype.setDefaultFlow.call(this, value);
    this.evn_default_flow = value;
    return this;
};
/**
 * Attach the event to an activity
 * @return {*}
 */
PMEvent.prototype.attachToActivity = function () {
    var numBou = this.parent.getAvailableBoundaryPlace();
    if (numBou !== false) {
        this.parent.setBoundary(this, numBou);
        this.setAttachedTo(this.parent.getID());
        this.setNumber(numBou);
    } else {
        this.destroy();
        this.saveAndDestroy();
    }
    return this;
};

/**
 * Sets the number/order of the current event when is attached to an activity
 * @param {Number} num
 * @return {*}
 */
PMEvent.prototype.setNumber = function (num) {
    this.numberRelativeToActivity = num;
    return this;
};

PMEvent.prototype.getEventType = function () {
    return this.evn_type;
};

PMEvent.prototype.getEventMarker = function () {
    return this.evn_marker;
};

PMEvent.prototype.getEventMessage = function () {
    return this.evn_message;
};
/**
 * Validates if an even has an message connection
 * @returns {boolean}
 */
PMEvent.prototype.isAllowed = function () {
    var result = true,
        i,
        connection;
    for (i = 0; i < this.getPorts().getSize(); i += 1) {
        connection = this.getPorts().get(i).connection;
        if (connection.flo_type === 'MESSAGE') {
            result = false;
            break;
        }
    }
    return result;
};
/**
 * Change an event marker
 * @return {Object}
 */
PMEvent.prototype.changeMarkerTo = function (type, message) {
    var command,
        msg;
    if (this.isAllowed()) {
        command = new CommandChangeEventMarker(this, type);
        this.canvas.commandStack.add(command);
        command.execute();
    } else {
        msg = 'Invalid operation: Delete message flow before converting it to '.translate();
        PMDesigner.msgFlash(msg + message + ' Event'.translate(), document.body, 'error', 3000, 5);
    }
    return this;
};

PMEvent.prototype.manualCreateMenu = function (e) {
    var endMarker = null;
    switch (this.getEventType()) {
        case 'END':
            endMarker = {
                text: "End Event Type".translate(),
                icon: "mafe-menu-properties-action",
                id: "result",
                items: [
                    {
                        id: "endempty",
                        text: "Empty".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('EMPTY');
                            PMDesigner.project.updateElement([]);
                        },
                        disabled: true
                    },
                    {
                        id: "endemail",
                        text: "Email Message".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('EMAIL');
                            PMDesigner.project.updateElement([]);
                        }
                    },
                    {
                        id: "endmessagethrow",
                        text: "Message".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('MESSAGETHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    },
                    {
                        id: "enderrorthrow",
                        text: "Error".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('ERRORTHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    },
                    {
                        id: "endsignalthrow",
                        text: "Signal".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('SIGNALTHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    },
                    {
                        id: "endterminatethrow",
                        text: "Terminate".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('TERMINATETHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    }
                ]
            };
            break;
        case 'INTERMEDIATE':
            endMarker = intermediateThrowMarker = {
                text: "Intermediate Event Type".translate(),
                icon: "mafe-menu-properties-action",
                id: "result",
                items: [
                    {
                        id: "intermediateemail",
                        text: "Email Message".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('EMAIL');
                            PMDesigner.project.updateElement([]);
                        },
                        disabled: true
                    },
                    {
                        id: "intermediatemessagethrow",
                        text: "Send Message".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('MESSAGETHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    },
                    {
                        id: "intermediatesignalthrow",
                        text: "Signal".translate(),
                        onClick: function (menuOption) {
                            var targetElement = menuOption
                                .getMenuTargetElement();
                            targetElement.changeMarkerTo('SIGNALTHROW');
                            PMDesigner.project.updateElement([]);
                        }
                    }

                ]
            };
            break;
    }
    if (endMarker) {
        var tempMenu = new PMUI.menu.Menu(endMarker);
        tempMenu.setTargetElement(this);
        tempMenu.show(e.pageX, e.pageY + this.getZoomHeight() / 2 + 4);
    }
};
PMEvent.prototype.beforeContextMenu = function () {
    var items, i,
        menuItem,
        hasMarker = false;
    this.canvas.hideAllCoronas();
    if (this.canvas.readOnly) {
      return;
    }
    switch (this.getEventType()) {
        case 'END':
            items = this.menu.items.find('id', 'result').childMenu.items;
            break;
        case 'INTERMEDIATE':
        case 'START':
            if (this.evn_behavior === 'CATCH') {
                items = this.menu.items.find('id', 'trigger').childMenu.items;
            } else {
                items = this.menu.items.find('id', 'result').childMenu.items;
            }
            break;
        default:
            items = new PMUI.util.ArrayList();
            break;
    }
    for (i = 0; i < items.getSize(); i += 1) {
        menuItem = items.get(i);
        if (menuItem.id === this.getEventType().toLowerCase() +
            this.getEventMarker().toLowerCase()) {
            menuItem.disable();
            hasMarker = true;
        } else {
            menuItem.enable();
        }
    }
};
/**
 * Stringifies the PMEvent object
 * @return {Object}
 */
PMEvent.prototype.stringify = function () {
    var inheritedJSON = PMShape.prototype.stringify.call(this),
        thisJSON = {
            evn_marker: this.getEventMarker(),
            evn_message: this.getEventMessage(),
            evn_condition: this.evn_condition,
            evn_attached_to: this.evn_attached_to,
            evn_is_interrupting: this.evn_is_interrupting,
            evn_behavior: this.evn_behavior
        };
    jQuery.extend(true, inheritedJSON, thisJSON);
    return inheritedJSON;
};

PMEvent.prototype.createBpmn = function (type) {
    if (!this.businessObject.elem && !(this instanceof PMUI.draw.MultipleSelectionContainer)) {
        this.createWithBpmn(type, 'businessObject');
    }
    this.updateBounds(this.businessObject.di);
    if (this.parent.getType() === 'PMCanvas' && !this.parent.businessObject.di) {
        this.canvas.createBPMNDiagram();
    }
    if (this.parent.businessObject.elem) {
        if (this.getEventType() === 'BOUNDARY') {
            if (this.parent.parent.getType() === 'PMLane') {
                this.updateShapeParent(this.businessObject, this.parent.parent.businessObject);
            } else if (this.parent.parent.getType() === 'PMPool') {
                this.updateShapeParent(this.businessObject, this.parent.parent.businessObject);
            }
        } else {
            this.updateShapeParent(this.businessObject, this.parent.businessObject);
        }
    } else {
        this.parent.createBusinesObject();
        this.updateShapeParent(this.businessObject, this.parent.businessObject);
    }
};

PMEvent.prototype.updateBpmn = function () {
    this.updateBounds(this.businessObject.di);
    if (!this.parent.businessObject.elem) {
        this.parent.createBusinesObject();
    }
    if (this.getEventType() === 'BOUNDARY') {
        this.updateShapeParent(this.businessObject, this.parent.parent.businessObject);
    } else {
        this.updateShapeParent(this.businessObject, this.parent.businessObject);
    }

};
/**
 * create bpmn object and attach to businessObject event
 */
PMEvent.prototype.createWithBpmn = function (bpmnElementType) {
    PMShape.prototype.createWithBpmn.call(this, bpmnElementType, 'businessObject');
    this.businessObject.elem.eventDefinitions = [];
    if (this.getEventType() === 'BOUNDARY') {
        this.businessObject.elem.attachedToRef = this.parent.businessObject.elem;
    }
    this.createEventDefinition();
};

PMEvent.prototype.createEventDefinition = function () {
    var def, type;
    if (this.getEventMarker() !== 'EMPTY'
        && this.getEventMarker() !== 'MULTIPLECATCH'
        && this.getEventMarker() !== 'PARALLELCATCH') {
        type = this.mapBpmnType[this.getEventType()][this.getEventMarker()];
        def = PMDesigner.bpmnFactory.create(type, {id: 'def_' + PMUI.generateUniqueId()});
        this.businessObject.elem.eventDefinitions.push(def);
    }
};

PMEvent.prototype.updateBpmEventMarker = function (newBpmnType) {
    this.businessObject.elem.eventDefinitions = [];
    this.createEventDefinition();
};

PMEvent.prototype.isSupported = function () {
    var isSupported = false;
    if (this.supportedList[this.evn_type][this.getEventMarker()] == true) {
        isSupported = true;
    }
    return isSupported;
};
/**
 * PMEvent Properties
 */
PMEvent.prototype.eventProperties = function () {
    var typeEventMarker = this.getEventType() + "_" + this.getEventMarker(),
        windowMessage,
        that = this;
    switch (typeEventMarker) {
        case "START_TIMER":
        case "INTERMEDIATE_TIMER":
            PMDesigner.timerEventProperties(that);
            break;
        case "INTERMEDIATE_EMAIL":
        case "END_EMAIL":
            PMDesigner.emailEventProperties(that);
            break;
        case "START_MESSAGECATCH":
        case "INTERMEDIATE_MESSAGETHROW":
        case "INTERMEDIATE_MESSAGECATCH":
        case "END_MESSAGETHROW":
            windowMessage = new MessageEventDefinition(that);
            break;
    }
};