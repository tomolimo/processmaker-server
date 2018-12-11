PMDesigner.sidebar = [];

PMDesigner.sidebar.push(
    new ToolbarPanel({
        buttons: [
            {
                selector: 'TASK',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-task'
                ],
                tooltip: "Task".translate()
            },
            {
                selector: 'SUB_PROCESS',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-subprocess'
                ],
                tooltip: "Sub Process".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'EXCLUSIVE',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-gateway-exclusive'
                ],
                tooltip: "Exclusive Gateway".translate()
            },
            {
                selector: 'PARALLEL',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-gateway-parallel'
                ],
                tooltip: "Parallel gateway".translate()
            },
            {
                selector: 'INCLUSIVE',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-gateway-inclusive'
                ],
                tooltip: "Inclusive Gateway".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'START',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-start'
                ],
                tooltip: "Start Event".translate()
            },
            {
                selector: 'START_TIMER',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-event-start-timer'
                ],
                tooltip: "Start Timer Event".translate()
            },
            {
                selector: 'INTERMEDIATE_EMAIL',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-intermediate-send-mesage'
                ],
                tooltip: "Intermediate Email Event".translate()
            },
            {
                selector: 'INTERMEDIATE_TIMER',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-event-intermediate-timer'
                ],
                tooltip: "Intermediate Timer Event".translate()
            },
            {
                selector: 'END',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-end'
                ],
                tooltip: "End Event".translate()
            },
            {
                selector: 'END_EMAIL',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-end-message'
                ],
                tooltip: "End Email Event ".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'DATAOBJECT',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-data-object'
                ],
                tooltip: "Data Object".translate()
            },
            {
                selector: 'DATASTORE',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-data-store'
                ],
                tooltip: "Data Store".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'PARTICIPANT',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-blackbox'
                ],
                tooltip: " Black Box Pool".translate()
            },
            {
                selector: 'POOL',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-pool'
                ],
                tooltip: "Pool".translate()
            },
            {
                selector: 'LANE',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-lane'
                ],
                tooltip: "Lane".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'GROUP',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-group'
                ],
                tooltip: "Group".translate()
            },
            {
                selector: 'TEXT_ANNOTATION',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-annotation'
                ],
                tooltip: "Text Annotation".translate()
            }
        ]
    }),
    new ToolbarPanel({
        buttons: [
            {
                selector: 'LASSO',
                className: [
                    'mafe-designer-icon',
                    'mafe-toolbar-lasso'
                ],
                tooltip: "Lasso".translate()
            }

        ]
    })
);