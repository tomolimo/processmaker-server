var defaultCrown = {
    items: [
        {
            id: "task",
            name: "Task".translate(),
            className: "mafe-corona-task",
            eventOnMouseDown: function (item) {
                item.canvas.canCreateShape = true;
                item.canvas.canCreateShapeType = 'TASK';
                item.canvas.canCreateShapeClass = 'mafe-toolbar-task';
                item.canvas.connectStartShape = item.parent.parent;
            },
            eventOnMouseOut: function (item) {
                if (item.canvas.canCreateShape) {
                    item.parent.hide();
                }
            }
        },
        {
            id: "gateway",
            name: 'Gateway'.translate(),
            className: 'mafe-corona-gateway-exclusive',
            eventOnClick: function (item) {
                item.parent.hide();
            },
            eventOnMouseDown: function (item) {
                item.canvas.canCreateShape = true;
                item.canvas.canCreateShapeType = 'EXCLUSIVE';
                item.canvas.canCreateShapeClass = 'mafe-toolbar-gateway-exclusive';
                item.canvas.connectStartShape = item.parent.parent;
            },
            eventOnMouseOut: function (item) {
                if (item.canvas.canCreateShape) {
                    item.parent.hide();
                }
            }
        },
        {
            id: "intermediate",
            name: 'Intermediate'.translate(),
            className: 'mafe-corona-intermediate',
            eventOnClick: function (item) {
                item.parent.hide();
            },
            eventOnMouseDown: function (item) {
                item.canvas.canCreateShape = true;
                item.canvas.canCreateShapeType = 'INTERMEDIATE_EMAIL';
                item.canvas.canCreateShapeClass = 'mafe-toolbar-intermediate-send-mesage';
                item.canvas.connectStartShape = item.parent.parent;
            },
            eventOnMouseOut: function (item) {
                if (item.canvas.canCreateShape) {
                    item.parent.hide();
                }
            }
        },
        {
            id: "end",
            name: 'End'.translate(),
            className: 'mafe-corona-end',
            eventOnClick: function (item) {
                item.parent.hide();
            },
            eventOnMouseDown: function (item) {
                item.canvas.canCreateShape = true;
                item.canvas.canCreateShapeType = 'END';
                item.canvas.canCreateShapeClass = 'mafe-toolbar-end';
                item.canvas.connectStartShape = item.parent.parent;
            },
            eventOnMouseOut: function (item) {
                if (item.canvas.canCreateShape) {
                    item.parent.hide();
                }
            }
        },
        {
            id: "flow",
            name: 'Flow'.translate(),
            className: 'mafe-corona-flow',
            eventOnClick: function (item) {
                item.parent.hide();
                item.parent.parent.canvas.hideAllFocusedLabels();
            },
            eventOnMouseDown: function (item) {
                item.canvas.canConnect = true;
                item.canvas.connectStartShape = item.parent.parent;
            }
        },
        {
            id: "properties",
            name: 'Properties'.translate(),
            className: 'mafe-corona-settings',
            eventOnClick: function (item) {
                item.parent.hide();
                PMDesigner.saveAndOpenSettings(item.parent.parent, PMDesigner.shapeProperties);
            }
        },
        {
            id: "delete",
            name: 'Delete'.translate(),
            className: 'mafe-corona-delete',
            eventOnClick: function (item) {
                PMUI.getActiveCanvas().removeElements();
                item.parent.hide();
            }
        }
    ]
};
