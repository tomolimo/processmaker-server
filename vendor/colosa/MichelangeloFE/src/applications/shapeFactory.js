(function () {
    PMDesigner.shapeFactory = function (type, options) {
        var customshape = null,
            menuShape,
            defaultOptions = null,
            canvasName,
            name,
            id,
            classEvent = "start",
            pmCanvas = this.canvas,
            corona = PMDesigner.defaultCrown,
            wildcard;

        canvasName = new IncrementNameCanvas(pmCanvas);
        name = canvasName.get(type);

        if (typeof options === 'undefined') {
            options = {};
            if (type === 'START_MESSAGE') {
                type = 'START';
                classEvent = "message";
                options.evn_marker = 'MESSAGECATCH';
            }
            if (type === 'START_TIMER') {
                type = 'START';
                classEvent = "timer";
                options.evn_marker = 'TIMER';
            }

            if (type === 'END_MESSAGE') {
                type = 'END';
                classEvent = "message";
                options.evn_marker = 'MESSAGETHROW';
            }
            if (type === 'END_EMAIL') {
                type = 'END';
                classEvent = "email";
                options.evn_marker = 'EMAIL';
                options.evn_behavior = 'THROW';
            }
            if (type === 'INTERMEDIATE_SENDMESSAGE') {
                type = 'INTERMEDIATE';
                classEvent = "sendmessage";
                options.evn_marker = 'MESSAGETHROW';
                options.evn_behavior = 'THROW';
            }
            if (type === 'INTERMEDIATE_RECEIVEMESSAGE') {
                type = 'INTERMEDIATE';
                classEvent = "receivemessage";
                options.evn_marker = 'MESSAGECATCH';
                options.evn_behavior = 'CATCH';
            }
            if (type === 'INTERMEDIATE_EMAIL') {
                type = 'INTERMEDIATE';
                classEvent = "email";
                options.evn_marker = 'EMAIL';
                options.evn_behavior = 'THROW';
            }
            if (type === 'INTERMEDIATE_TIMER') {
                type = 'INTERMEDIATE';
                classEvent = "timer";
                options.evn_marker = 'TIMER';
            }
            if (type === 'BOUNDARY_EVENT') {
                type = 'BOUNDARY';
                classEvent = "receivemessage";
                options.evn_marker = 'EMPTY';
                options.evn_behavior = 'CATCH';
            }

            switch (type) {
                case 'COMPLEX':
                case 'PARALLEL':
                case 'EXCLUSIVE':
                case 'EVENTBASED':
                case 'EXCLUSIVEEVENTBASED':
                case 'PARALLELEVENTBASED':
                case 'INCLUSIVE':
                    options.gat_type = type;
                    break;
            }
        }
        if (type === 'DATAOBJECT') {
            type = 'DATAOBJECT';
            options.dat_object_type = 'dataobject';
        }
        if (type === 'DATAINPUT') {
            type = 'DATAOBJECT';
            options.dat_object_type = 'datainput';
        }
        if (type === 'DATAOUTPUT') {
            type = 'DATAOBJECT';
            options.dat_object_type = 'dataoutput';
        }

        wildcard = [
            {
                name: 'wildcard'.translate(),
                className: 'mafe-wildcard',
                onClick: null,
                column: 2
            }
        ];

        switch (type) {
            case 'TASK':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 150,
                    height: 75,
                    act_type: 'TASK',
                    act_name: name,
                    act_task_type: 'EMPTY',
                    act_loop_type: 'EMPTY',
                    minHeight: 30,
                    minWidth: 150,
                    maxHeight: 50,
                    maxWidth: 170,
                    container: "activity",
                    labels: [
                        {
                            message: name,
                            width: 0,
                            height: 0,
                            position: {
                                location: 'center',
                                diffX: 0,
                                diffY: 0
                            },
                            attachEvents: false
                        }
                    ],
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: [
                                    'mafe-activity-task'
                                ]
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }

                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'activityResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmactivitydrop",
                        selectors: [
                            "#BOUNDARY_EVENT",
                            ".mafe-event-boundary",
                            ".dragConnectHandler"
                        ]
                    },
                    markers: [
                        {
                            markerType: 'USERTASK',
                            x: 10,
                            y: 10,
                            position: 0,
                            markerZoomClasses: []
                        },
                        {
                            markerType: 'EMPTY',
                            x: 10,
                            y: 10,
                            position: 4,
                            markerZoomClasses: []
                        }
                    ],
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ],
                                markerZoomClassesWarning: [
                                    "mafe-style-warning-marker-7",
                                    "mafe-style-warning-marker-10",
                                    "mafe-style-warning-marker-14",
                                    "mafe-style-warning-marker-17",
                                    "mafe-style-warning-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona,
                    focusLabel: true
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.markers[0]
                    .markerZoomClasses = PMDesigner.updateMarkerLayerClasses(defaultOptions);
                defaultOptions.markers[1]
                    .markerZoomClasses = PMDesigner.updateLoopLayerClasses(defaultOptions);
                customshape = new PMActivity(defaultOptions);
                break;
            case 'SUB_PROCESS':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 150,
                    height: 75,
                    act_type: 'SUB_PROCESS',
                    act_loop_type: 'EMPTY',
                    act_name: name,
                    act_task_type: 'COLLAPSED',
                    minHeight: 30,
                    minWidth: 150,
                    maxHeight: 50,
                    maxWidth: 170,
                    container: "activity",
                    labels: [
                        {
                            message: name,
                            position: {
                                location: 'center',
                                diffX: 0,
                                diffY: 0
                            },
                            attachEvents: false
                        }
                    ],
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: [
                                    'mafe-activity-subprocess'
                                ]
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }

                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'activityResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmactivitydrop",
                        selectors: [
                            "#BOUNDARY_EVENT",
                            ".mafe-event-boundary",
                            ".dragConnectHandler"
                        ]
                    },
                    markers: [
                        {
                            markerType: 'COLLAPSED',
                            x: 10,
                            y: 10,
                            position: 4,
                            markerZoomClasses: [
                                "mafe-collapsed-marker-10",
                                "mafe-collapsed-marker-15",
                                "mafe-collapsed-marker-21",
                                "mafe-collapsed-marker-26",
                                "mafe-collapsed-marker-31"
                            ]
                        }
                    ],
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona,
                    focusLabel: true
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.markers[0]
                    .markerZoomClasses = PMDesigner.updateMarkerLayerClasses(defaultOptions);
                customshape = new PMActivity(defaultOptions);
                break;
            case 'START':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 33,
                    height: 33,
                    evn_type: 'START',
                    evn_name: '',
                    evn_marker: 'EMPTY',
                    evn_behavior: 'catch',
                    evn_message: 'LEAD',
                    labels: [
                        {
                            message: '',
                            visible: true,
                            width: 100,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 13
                            }
                        }
                    ],
                    style: {
                        cssClasses: ['mafe-event-start']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-event-start-empty-16',
                                'mafe-event-start-empty-24',
                                'mafe-event-start-empty-33',
                                'mafe-event-start-empty-41',
                                'mafe-event-start-empty-49'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.layers[0]
                    .zoomSprites = PMDesigner.updateLayerClasses(defaultOptions);
                customshape = new PMEvent(defaultOptions);
                break;
            case 'INTERMEDIATE':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 33,
                    height: 33,
                    evn_type: 'INTERMEDIATE',
                    evn_name: '',
                    evn_marker: 'EMPTY',
                    evn_behavior: 'CATCH',
                    labels: [
                        {
                            message: '',
                            visible: true,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 13
                            }
                        }
                    ],
                    style: {
                        cssClasses: ['mafe-event-intermediate']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-event-intermediate-16',
                                'mafe-event-intermediate-24',
                                'mafe-event-intermediate-33',
                                'mafe-event-intermediate-41',
                                'mafe-event-intermediate-49'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.layers[0]
                    .zoomSprites = PMDesigner.updateLayerClasses(defaultOptions);
                customshape = new PMEvent(defaultOptions);
                break;
            case 'BOUNDARY':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 33,
                    height: 33,
                    evn_type: 'BOUNDARY',
                    evn_name: '',
                    evn_marker: 'EMPTY',
                    evn_behavior: 'CATCH',
                    labels: [
                        {
                            message: '',
                            visible: true,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 0
                            }
                        }
                    ],
                    style: {
                        cssClasses: ['mafe-event-boundary']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-event-intermediate-16',
                                'mafe-event-intermediate-24',
                                'mafe-event-intermediate-33',
                                'mafe-event-intermediate-41',
                                'mafe-event-intermediate-49'
                            ]
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    drag: 'nodrag'
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.layers[0]
                    .zoomSprites = PMDesigner.updateLayerClasses(defaultOptions);
                customshape = new PMEvent(defaultOptions);
                break;

            case 'END':
                defaultOptions = {
                    canvas: pmCanvas,
                    width: 33,
                    height: 33,
                    evn_type: 'END',
                    evn_name: '',
                    evn_marker: 'EMPTY',
                    evn_behavior: 'throw',
                    labels: [
                        {
                            message: '',
                            visible: true,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 13
                            }
                        }
                    ],
                    style: {
                        cssClasses: ['mafe-event-end']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-event-end-empty-16',
                                'mafe-event-end-empty-24',
                                'mafe-event-end-empty-33',
                                'mafe-event-end-empty-41',
                                'mafe-event-end-empty-49'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.layers[0]
                    .zoomSprites = PMDesigner.updateLayerClasses(defaultOptions);
                customshape = new PMEvent(defaultOptions);
                break;

            case 'COMPLEX':
            case 'PARALLEL':
            case 'EXCLUSIVE':
            case 'EVENTBASED':
            case 'EXCLUSIVEEVENTBASED':
            case 'PARALLELEVENTBASED':
            case 'INCLUSIVE':
                defaultOptions = {
                    labels: [
                        {
                            message: '',
                            visible: true,
                            width: 100,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 13
                            }
                        }
                    ],
                    canvas: pmCanvas,
                    width: 41,
                    height: 41,
                    gat_type: 'EXCLUSIVE',
                    gat_name: '',
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-gateway-exclusive-20',
                                'mafe-gateway-exclusive-30',
                                'mafe-gateway-exclusive-41',
                                'mafe-gateway-exclusive-51',
                                'mafe-gateway-exclusive-61'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    validatorMarker: {
                        width: 12,
                        height: 12,
                        position: 2,
                        errors: {
                            style: {
                                markerZoomClassesError: [
                                    "mafe-style-error-marker-7",
                                    "mafe-style-error-marker-10",
                                    "mafe-style-error-marker-14",
                                    "mafe-style-error-marker-17",
                                    "mafe-style-error-marker-21"
                                ]
                            }
                        }
                    },
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.layers[0]
                    .zoomSprites = PMDesigner.updateGatewayLayerClasses(defaultOptions);
                customshape = new PMGateway(defaultOptions);
                break;
            case 'GROUP':
                defaultOptions = {
                    art_name: name,
                    art_type: 'GROUP',
                    canvas: pmCanvas,
                    width: 200,
                    height: 100,
                    style: {
                        cssClasses: ['mafe-artifact-group']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "background-layer",
                            priority: 1,
                            visible: true,
                            style: {
                                cssClasses: [
                                    'mafe-artifact-group'
                                ]
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'annotationResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        }
                    },
                    labels: [
                        {
                            message: "",
                            width: 0,
                            height: 0,
                            position: {
                                location: 'top',
                                diffX: 2,
                                diffY: 0
                            },
                            attachEvents: false,
                            updateParent: true,
                            style: {
                                cssClasses: [
                                    'mafe-label-annotation'
                                ]
                            }
                        }
                    ],
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    corona: corona,
                    focusLabel: true
                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMArtifact(defaultOptions);
                break;
            case 'TEXT_ANNOTATION':
                defaultOptions = {
                    art_name: name,
                    art_type: 'TEXT_ANNOTATION',
                    canvas: pmCanvas,
                    width: 100,
                    height: 30,
                    style: {
                        cssClasses: ['mafe-artifact-annotation']
                    },
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "background-layer",
                            priority: 1,
                            visible: true,
                            style: {
                                cssClasses: [
                                    'mafe-artifact-annotation'
                                ]
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'annotationResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        }
                    },
                    labels: [
                        {
                            message: name,
                            width: 0,
                            height: 0,
                            position: {
                                location: 'center',
                                diffX: 0,
                                diffY: 0
                            },
                            attachEvents: false,
                            updateParent: true
                        }
                    ],
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    corona: corona,
                    focusLabel: true
                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMArtifact(defaultOptions);
                break;

            case 'DATAOBJECT':
                defaultOptions = {
                    labels: [
                        {
                            message: '',
                            visible: true,
                            width: 100,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 0
                            }
                        }
                    ],
                    canvas: pmCanvas,
                    width: 33,
                    height: 41,
                    dat_type: 'DATAOBJECT',
                    dat_object_type: "dataobject",
                    dat_name: '',
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-dataobject-50',
                                'mafe-dataobject-75',
                                'mafe-dataobject-100',
                                'mafe-dataobject-125',
                                'mafe-dataobject-150'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    markers: [
                        {
                            markerType: 'USERTASK',
                            x: 10,
                            y: 10,
                            position: 0
                        }
                    ],
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                defaultOptions.markers[0]
                    .markerZoomClasses = PMDesigner.updateDataMarkerLayerClasses(defaultOptions);
                customshape = new PMData(defaultOptions);
                break;

            case 'DATASTORE':
                defaultOptions = {
                    labels: [
                        {
                            message: '',
                            visible: true,
                            width: 100,
                            position: {
                                location: 'bottom',
                                diffX: 0,
                                diffY: 0
                            }
                        }
                    ],
                    canvas: pmCanvas,
                    width: 41,
                    height: 41,
                    dat_type: 'DATASTORE',
                    dat_name: '',
                    layers: [
                        {
                            x: 0,
                            y: 0,
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites: [
                                'mafe-datastore-20',
                                'mafe-datastore-30',
                                'mafe-datastore-41',
                                'mafe-datastore-51',
                                'mafe-datastore-61'
                            ]
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    resizeBehavior: 'NoResize',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },

                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    corona: corona
                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMData(defaultOptions);
                break;
            case 'PARTICIPANT':
                defaultOptions = {
                    width: 500,
                    height: 130,
                    "canvas": this,
                    "connectAtMiddlePoints": false,
                    topLeft: true,
                    connectionType: 'dotted',
                    resizeBehavior: "participantResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmconnection",
                        selectors: ['.dragConnectHandler']
                    },
                    "style": {
                        cssClasses: ["mafe-pool"]

                    },
                    layers: [
                        {
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssProperties: {}
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    labels: [
                        {
                            message: name,
                            width: 0,
                            height: 0,
                            orientation: 'vertical',
                            position: {
                                location: 'center-left',
                                diffX: 15,
                                diffY: 0
                            },
                            attachEvents: false
                        }
                    ],
                    par_name: name,
                    corona: corona,
                    focusLabel: true

                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMParticipant(defaultOptions);
                break;
            case 'POOL':
                if (options.lns_name) {
                    name = options.lns_name;
                } else if (options.par_name) {
                    name = options.par_name;
                }
                defaultOptions = {
                    width: 700,
                    height: 200,
                    "canvas": pmCanvas,
                    "connectAtMiddlePoints": false,
                    topLeft: false,
                    connectionType: 'dotted',
                    resizeBehavior: "poolResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmcontainer",
                        selectors: [
                            "#TASK",
                            "#SUB_PROCESS",
                            "#START",
                            "#START_MESSAGE",
                            "#START_TIMER",
                            "#END",
                            "#END_MESSAGE",
                            "#END_EMAIL",
                            "#INTERMEDIATE_SENDMESSAGE",
                            "#INTERMEDIATE_RECEIVEMESSAGE",
                            "#INTERMEDIATE_EMAIL",
                            "#INTERMEDIATE_TIMER",
                            "#EXCLUSIVE",
                            "#PARALLEL",
                            "#INCLUSIVE",
                            "#DATAOBJECT",
                            "#DATASTORE",
                            "#TEXT_ANNOTATION",
                            "#LANE",
                            "#GROUP",
                            ".mafe-event-start",
                            ".mafe-event-intermediate",
                            ".mafe-event-end",
                            ".pmui-pmactivity",
                            ".pmui-pmgateway",
                            ".pmui-pmdata",
                            ".mafe-artifact-annotation",
                            ".mafe-artifact-group",
                            ".port"
                        ]
                    },
                    container: "pool",
                    "style": {
                        cssClasses: ["mafe-pool"]

                    },
                    layers: [
                        {
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssProperties: {}
                            }
                        },
                        {
                            x: 0,
                            y: 0,
                            layerName: "second-layer",
                            priority: 2,
                            visible: false,
                            style: {
                                cssClasses: []
                            }
                        }
                    ],
                    labels: [
                        {
                            message: name,
                            width: 0,
                            height: 0,
                            orientation: 'vertical',
                            position: {
                                location: 'center-left',
                                diffX: 15,
                                diffY: 0
                            },
                            attachEvents: false
                        }
                    ],
                    lns_name: name,
                    focusLabel: true,
                    corona: corona

                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMPool(defaultOptions);
                break;
            case 'LANE':
                defaultOptions = {
                    width: 500,
                    height: 200,
                    "canvas": pmCanvas,
                    "connectAtMiddlePoints": false,
                    topLeft: true,
                    connectionType: 'dotted',
                    resizeBehavior: "laneResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    "drop": {
                        type: "pmcontainer",
                        selectors: [
                            "#TASK",
                            "#SUB_PROCESS",
                            "#START",
                            "#START_MESSAGE",
                            "#START_TIMER",
                            "#END",
                            "#END_MESSAGE",
                            "#END_EMAIL",
                            "#INTERMEDIATE_SENDMESSAGE",
                            "#INTERMEDIATE_RECEIVEMESSAGE",
                            "#INTERMEDIATE_EMAIL",
                            "#INTERMEDIATE_TIMER",
                            "#EXCLUSIVE",
                            "#PARALLEL",
                            "#INCLUSIVE",
                            "#DATAOBJECT",
                            "#DATASTORE",
                            "#GROUP",
                            "#TEXT_ANNOTATION",
                            ".mafe-event-start",
                            ".mafe-event-intermediate",
                            ".mafe-event-end",
                            ".pmui-pmactivity",
                            ".pmui-pmgateway",
                            ".pmui-pmdata",
                            ".mafe-artifact-annotation",
                            ".mafe-artifact-group"
                        ]
                    },
                    container: "lane",
                    layers: [
                        {
                            layerName: "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssProperties: {}
                            }
                        }

                    ],
                    labels: [
                        {
                            message: name,
                            width: 0,
                            height: 0,
                            orientation: 'vertical',
                            position: {
                                location: 'center-left',
                                diffX: 15,
                                diffY: 0
                            }
                        }
                    ],
                    lan_name: name,
                    focusLabel: true

                };
                jQuery.extend(true, defaultOptions, options);
                customshape = new PMLane(defaultOptions);
                break;

        }
        if (customshape && !pmCanvas.readOnly) {
            customshape.attachListeners();
            customshape.extendedType = type;
            menuShape = PMDesigner.getMenuFactory(type);
            customshape.getHTML();
            customshape.setContextMenu(menuShape);
        }
        return customshape;
    };
    PMDesigner.updateLayerClasses = function (options) {
        return [
            'mafe-event-' + options.evn_type.toLowerCase() + '-' + options.evn_marker.toLowerCase() + '-16',
            'mafe-event-' + options.evn_type.toLowerCase() + '-' + options.evn_marker.toLowerCase() + '-24',
            'mafe-event-' + options.evn_type.toLowerCase() + '-' + options.evn_marker.toLowerCase() + '-33',
            'mafe-event-' + options.evn_type.toLowerCase() + '-' + options.evn_marker.toLowerCase() + '-41',
            'mafe-event-' + options.evn_type.toLowerCase() + '-' + options.evn_marker.toLowerCase() + '-49'
        ];
    };
    PMDesigner.updateGatewayLayerClasses = function (options) {
        return [
            'mafe-gateway-' + options.gat_type.toLowerCase() + '-20',
            'mafe-gateway-' + options.gat_type.toLowerCase() + '-30',
            'mafe-gateway-' + options.gat_type.toLowerCase() + '-41',
            'mafe-gateway-' + options.gat_type.toLowerCase() + '-51',
            'mafe-gateway-' + options.gat_type.toLowerCase() + '-61'
        ];
    };
    PMDesigner.updateMarkerLayerClasses = function (options) {
        if (options.act_task_type !== 'EMPTY') {
            return [
                "mafe-" + options.act_task_type.toLowerCase() + "-marker-10",
                "mafe-" + options.act_task_type.toLowerCase() + "-marker-15",
                "mafe-" + options.act_task_type.toLowerCase() + "-marker-21",
                "mafe-" + options.act_task_type.toLowerCase() + "-marker-26",
                "mafe-" + options.act_task_type.toLowerCase() + "-marker-31"
            ];
        }
    };
    PMDesigner.updateLoopLayerClasses = function (options) {
        if (options.act_loop_type !== 'EMPTY') {
            return [
                "mafe-" + options.act_loop_type.toLowerCase() + "-marker-10",
                "mafe-" + options.act_loop_type.toLowerCase() + "-marker-15",
                "mafe-" + options.act_loop_type.toLowerCase() + "-marker-21",
                "mafe-" + options.act_loop_type.toLowerCase() + "-marker-26",
                "mafe-" + options.act_loop_type.toLowerCase() + "-marker-31"
            ];
        }
    };
    PMDesigner.updateDataMarkerLayerClasses = function (options) {
        var type = options.dat_object_type.toLowerCase();
        if (type !== 'dataobject') {
            return [
                "mafe-" + type + "-marker-10",
                "mafe-" + type + "-marker-15",
                "mafe-" + type + "-marker-21",
                "mafe-" + type + "-marker-26",
                "mafe-" + type + "-marker-31"
            ];
        }
    };
    /**
     * Save a process and open the settings
     * @param shape
     * @param callback
     */
    PMDesigner.saveAndOpenSettings= function (shape, callback) {
        var splitedID;

        if(shape) {
            splitedID = shape.getID().split("-");
            if ((splitedID && splitedID[0] === 'pmui') || PMDesigner.project.isDirty()) {
                PMDesigner.restApi.execute({
                    data: JSON.stringify(PMDesigner.project.getDirtyObject()),
                    method: "update",
                    url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + "/project/" + PMDesigner.project.id,
                    success: function (data, textStatus, xhr) {
                        PMDesigner.project.listeners.success(PMDesigner.project, textStatus, data);
                        PMDesigner.project.isSave = false;
                        if(callback) {
                            callback(shape);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        PMDesigner.project.listeners.failure(that, textStatus, xhr);
                        PMDesigner.project.isSave = false;
                    }
                });
            } else {
                callback(shape);
            }
        }


    };
}());