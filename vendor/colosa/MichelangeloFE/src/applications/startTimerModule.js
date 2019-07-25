(function () {

    var loadValuesStartTimer, openForm, updateStatus;

    PMDesigner.startTimer = function (element) {

        var startTimer = element,
            restClient,
            loadServerData,
            listUsers,
            itemsDaly,
            itemsWeekly,
            loadOptionsRadio,
            itemsMonthly,
            itemsOneTime,
            itemsEvery,
            showProperties,
            updateCaseScheduler,
            buttonCancel,
            loadUsers,
            loadDataForm,
            formCreateCaseScheduler,
            schedulerListWindow,
            buttonSave,
            dataForm;

        loadValuesStartTimer = function ($flag) {
            listUsers = [];
            dataForm = [];
            restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: 'activity/' + startTimer.ports.get(0).connection.flo_element_dest + '/assignee/all',
                            method: 'GET'
                        },
                        {
                            url: 'case-scheduler/' + startTimer.evn_uid,
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    listUsers = response[0].response;
                    dataForm = response[1].response;
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: [null, 'There are problems loading the Start Timer, please try again.'.translate()]
            });
            restClient.executeRestClient();
            if ($flag) {
                return listUsers.length;
            }
            return true;
        };

        updateCaseScheduler = function (data) {
            var restProxy = new PMRestClient({
                endpoint: 'case-scheduler/' + startTimer.evn_uid,
                typeRequest: 'update',
                data: data,
                functionSuccess: function (xhr, response) {
                    formCreateCaseScheduler.reset();
                    schedulerListWindow.close();
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageSuccess: 'Start Timer updated successfully'.translate(),
                messageError: 'There are problems updating the Start Timer, please try again.'.translate(),
                flashContainer: document.body
            });
            restProxy.executeRestClient();
        };

        updateStatus = function () {
            var status = (typeof dataForm.sch_state !== 'undefined') ? ((dataForm.sch_state === 'ACTIVE') ? 'INACTIVE' : 'ACTIVE') : 'INACTIVE';
            dataForm.sch_state = status;
            updateCaseScheduler(dataForm);
        };

        //Items for the form Create and edit Case Scheduler
        itemsDaly = [
            {
                pmType: 'panel',
                layout: 'hbox',
                items: [
                    {
                        id: 'startDate',
                        pmType: 'datetime',
                        label: 'Start date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: true,
                        dateFormat: 'yy mm dd',
                        dateTime: false,
                        name: 'startDate',
                        valueType: 'date',
                        labelWidth: '26%'
                    },
                    {
                        id: 'endDate',
                        pmType: 'datetime',
                        label: 'End date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: false,
                        dateFormat: 'yy mm dd',
                        dateTime: false,
                        name: 'endDate',
                        valueType: 'date'
                    }
                ]
            },
            {
                id: 'execttime',
                pmType: 'text',
                label: 'Execution time'.translate(),
                value: '',
                required: true,
                name: 'execttime',
                placeholder: '(HH:MM) Format 24 hrs.'.translate(),
                valueType: 'string',
                validators: [{
                    pmType: 'regexp',
                    criteria: /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/,
                    errorMessage: 'Please enter a valid hour.'.translate()
                }
                ],
                helper: 'Example: 1:00, 14:30, 00:00'
            }
        ];

        itemsWeekly = [
            {
                pmType: 'panel',
                layout: 'hbox',
                items: [
                    {
                        id: 'startDate',
                        pmType: 'datetime',
                        label: 'Start date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: true,
                        dateFormat: 'yy mm dd',
                        dateTime: false,
                        name: 'startDate',
                        valueType: 'date',
                        labelWidth: '26%'
                    },
                    {
                        id: 'endDate',
                        pmType: 'datetime',
                        label: 'End date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: false,
                        dateFormat: 'yy mm dd',
                        dateTime: false,
                        name: 'endDate',
                        valueType: 'date'
                    }
                ]
            },
            {
                id: 'execttime',
                pmType: 'text',
                label: 'Execution time'.translate(),
                value: '',
                required: true,
                name: 'execttime',
                placeholder: '(HH:MM) Format 24 hrs.'.translate(),
                valueType: 'string',
                validators: [{
                    pmType: 'regexp',
                    criteria: /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/,
                    errorMessage: 'Please enter a valid hour.'.translate()
                }
                ],
                helper: 'Example: 1:00, 14:30, 00:00'
            },
            {
                id: 'daysoftheweek',
                pmType: 'checkbox',
                label: 'Select the day(s) of the week below'.translate(),
                value: '',
                name: 'daysoftheweek',
                required: false,
                controlPositioning: 'horizontal',
                maxDirectionOptions: 3,
                options: [
                    {
                        id: 'monday',
                        label: 'Monday'.translate(),
                        value: '1'
                    },
                    {
                        id: 'tuesday',
                        label: 'Tuesday'.translate(),
                        value: '2'
                    },
                    {
                        id: 'wednesday',
                        label: 'Wednesday'.translate(),
                        value: '3'
                    },
                    {
                        id: 'thursday',
                        label: 'Thursday'.translate(),
                        value: '4'
                    },
                    {
                        id: 'friday',
                        label: 'Friday'.translate(),
                        value: '5'
                    },
                    {
                        id: 'saturday',
                        label: 'Saturday'.translate(),
                        value: '6'
                    },
                    {
                        id: 'sunday',
                        label: 'Sunday'.translate(),
                        value: '7'
                    }
                ]
            }
        ];

        loadOptionsRadio = function (newVal) {
            var paneldaysofMonth = formCreateCaseScheduler.getItems()[1].getItems()[2].getItems()[1];
            paneldaysofMonth.setVisible(true);
            if (newVal === 'dayofmonth') {
                paneldaysofMonth.getItems()[0].setVisible(true);
                paneldaysofMonth.getItems()[1].setVisible(false);
            } else if (newVal === 'day') {
                paneldaysofMonth.getItems()[0].setVisible(false);
                paneldaysofMonth.getItems()[1].setVisible(true);
            }
        };

        itemsMonthly = [
            {
                pmType: 'panel',
                layout: 'hbox',
                items: [
                    {
                        id: 'startDate',
                        pmType: 'datetime',
                        label: 'Start date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: true,
                        dateFormat: 'yy mm dd',
                        datetime: false,
                        name: 'startDate',
                        valueType: 'date',
                        labelWidth: '26%'
                    },
                    {
                        id: 'endDate',
                        pmType: 'datetime',
                        label: 'End date'.translate(),
                        value: '',
                        returnFormat: 'yy-mm-dd',
                        required: false,
                        dateFormat: 'yy mm dd',
                        datetime: false,
                        name: 'endDate',
                        valueType: 'date'
                    }
                ]
            },
            {
                id: 'execttime',
                pmType: 'text',
                label: 'Execution time'.translate(),
                value: '',
                required: true,
                name: 'execttime',
                placeholder: '(HH:MM) Format 24 hrs.'.translate(),
                valueType: 'string',
                controlsWidth: 580,
                validators: [{
                    pmType: 'regexp',
                    criteria: /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/,
                    errorMessage: 'Please enter a valid hour.'.translate()
                }
                ],
                helper: 'Example: 1:00, 14:30, 00:00'
            },
            {
                pmType: 'panel',
                id: 'panelDays',
                layout: 'hbox',
                items: [
                    {
                        id: 'options',
                        pmType: 'radio',
                        label: '',
                        value: '',
                        name: 'options',
                        required: false,
                        controlPositioning: 'vertical',
                        maxDirectionOptions: 4,
                        options: [
                            {
                                id: 'dayMonth',
                                label: 'Day of month'.translate(),
                                value: 'dayofmonth'
                            },
                            {
                                id: 'day',
                                label: 'The day'.translate(),
                                value: 'day'
                            }
                        ],
                        onChange: function (newVal, oldVal) {
                            loadOptionsRadio(newVal);
                        },
                        labelWidth: '46%'
                    },
                    {
                        pmType: 'panel',
                        id: 'paneldaysofMonth',
                        layout: 'vbox',
                        items: [
                            {
                                id: 'dayoftheMonth',
                                pmType: 'text',
                                label: '',
                                value: '',
                                placeholder: 'Day of the month (example: 1)'.translate(),
                                required: false,
                                name: 'dayMonth',
                                valueType: 'string'
                            },
                            {
                                pmType: 'panel',
                                id: 'panelmonth',
                                layout: 'hbox',
                                items: [
                                    {
                                        id: 'first',
                                        pmType: 'dropdown',
                                        label: '',
                                        value: '',
                                        required: false,
                                        name: 'first',
                                        valueType: 'string',
                                        options: [
                                            {
                                                label: 'First'.translate(),
                                                value: '1'
                                            },
                                            {
                                                label: 'Second'.translate(),
                                                value: '2'
                                            },
                                            {
                                                label: 'Third'.translate(),
                                                value: '3'
                                            },
                                            {
                                                label: 'Fourth'.translate(),
                                                value: '4'
                                            },
                                            {
                                                label: 'Last'.translate(),
                                                value: '5'
                                            }
                                        ],
                                        controlsWidth: 100
                                    },
                                    {
                                        id: 'day',
                                        pmType: 'dropdown',
                                        label: '',
                                        value: '',
                                        required: false,
                                        name: 'day',
                                        valueType: 'string',
                                        options: [
                                            {
                                                label: 'Monday'.translate(),
                                                value: '1'
                                            },
                                            {
                                                label: 'Tuesday'.translate(),
                                                value: '2'
                                            },
                                            {
                                                label: 'Wednesday'.translate(),
                                                value: '3'
                                            },
                                            {
                                                label: 'Thursday'.translate(),
                                                value: '4'
                                            },
                                            {
                                                label: 'Friday'.translate(),
                                                value: '5'
                                            },
                                            {
                                                label: 'Saturday'.translate(),
                                                value: '6'
                                            },
                                            {
                                                label: 'Sunday'.translate(),
                                                value: '7'
                                            }
                                        ],
                                        controlsWidth: 100
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                id: 'months',
                pmType: 'checkbox',
                label: 'Of the month(s)'.translate(),
                value: '',
                name: 'months',
                required: false,
                controlPositioning: 'horizontal',
                maxDirectionOptions: 4,
                options: [
                    {
                        id: 'jan',
                        label: 'Jan'.translate(),
                        value: '1'
                    },
                    {
                        id: 'feb',
                        label: 'Feb'.translate(),
                        value: '2'
                    },
                    {
                        id: 'mar',
                        label: 'Mar'.translate(),
                        value: '3'
                    },
                    {
                        id: 'apr',
                        label: 'Apr'.translate(),
                        value: '4'
                    },
                    {
                        id: 'may',
                        label: 'May'.translate(),
                        value: '5'
                    },
                    {
                        id: 'jun',
                        label: 'Jun'.translate(),
                        value: '6'
                    },
                    {
                        id: 'jul',
                        label: 'Jul'.translate(),
                        value: '7'
                    },
                    {
                        id: 'aug',
                        label: 'Aug'.translate(),
                        value: '8'
                    },
                    {
                        id: 'sep',
                        label: 'Sep'.translate(),
                        value: '9'
                    },
                    {
                        id: 'oct',
                        label: 'Oct'.translate(),
                        value: '10'
                    },
                    {
                        id: 'nov',
                        label: 'Nov'.translate(),
                        value: '11'
                    },
                    {
                        id: 'dec',
                        label: 'Dec'.translate(),
                        value: '12'
                    }
                ]
            }
        ];

        itemsOneTime = [{
            id: 'execttime',
            pmType: 'text',
            label: 'Execution time'.translate(),
            value: '',
            required: true,
            name: 'execttime',
            placeholder: '(HH:MM) Format 24 hrs.'.translate(),
            valueType: 'string',
            validators: [{
                pmType: 'regexp',
                criteria: /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/,
                errorMessage: 'Please enter a valid hour.'.translate()
            }
            ],
            helper: 'Example: 1:00, 14:30, 00:00'
        }];

        itemsEvery = [{
            id: 'execttime',
            pmType: 'text',
            label: 'Execute every Hour(s)'.translate(),
            value: '',
            required: true,
            name: 'execttime',
            valueType: 'string',
            placeholder: '(HH:MM) Format 24 hrs.'.translate(),
            validators: [{
                pmType: 'regexp',
                criteria: /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/,
                errorMessage: 'Please enter a valid hour.'.translate()
            }
            ],
            helper: 'Example: 1:00, 14:30, 00:00'
        }];

        showProperties = function (newValue) {
            switch (newValue) {
                case 'daily':
                    formCreateCaseScheduler.getItems()[1].clearItems();
                    formCreateCaseScheduler.getItems()[1].setVisible(true);
                    formCreateCaseScheduler.getItems()[1].setItems(itemsDaly);
                    break;
                case 'weekly':
                    formCreateCaseScheduler.getItems()[1].clearItems();
                    formCreateCaseScheduler.getItems()[1].setVisible(true);
                    formCreateCaseScheduler.getItems()[1].setItems(itemsWeekly);
                    break;
                case 'monthly':
                    formCreateCaseScheduler.getItems()[1].clearItems();
                    formCreateCaseScheduler.getItems()[1].setVisible(true);
                    formCreateCaseScheduler.getItems()[1].setItems(itemsMonthly);
                    var paneldaysofMonth = formCreateCaseScheduler.getItems()[1].getItems()[2].getItems()[1];
                    paneldaysofMonth.setVisible(false);
                    formCreateCaseScheduler.getItems()[1].getItems()[2].getItems()[0].hideColon();
                    paneldaysofMonth.getItems()[0].hideColon();
                    paneldaysofMonth.getItems()[1].getItems()[0].hideColon();
                    paneldaysofMonth.getItems()[1].getItems()[1].hideColon();
                    break;
                case 'oneTime':
                    formCreateCaseScheduler.getItems()[1].clearItems();
                    formCreateCaseScheduler.getItems()[1].setVisible(true);
                    formCreateCaseScheduler.getItems()[1].setItems(itemsOneTime);
                    break;
                case 'every':
                    formCreateCaseScheduler.getItems()[1].clearItems();
                    formCreateCaseScheduler.getItems()[1].setVisible(true);
                    formCreateCaseScheduler.getItems()[1].setItems(itemsEvery);
                    validateKeysField(formCreateCaseScheduler.getField('execttime').getControls()[0].getHTML(), ['isnumber', 'iscolon']);
                    break;
            }
        };

        //Form to Edit and create the Case Scheduler
        formCreateCaseScheduler = new PMUI.form.Form({
            id: 'formCreateCaseScheduler',
            border: false,
            visibleHeader: false,
            width: '925px',
            name: 'formcreate',
            title: '',
            items: [
                {
                    id: 'panelProperties',
                    pmType: 'panel',
                    layout: 'vbox',
                    fieldset: true,
                    height: '350px',
                    legend: 'Properties'.translate(),
                    items: [
                        {
                            id: 'state',
                            pmType: 'dropdown',
                            label: 'Status'.translate(),
                            name: 'state',
                            required: true,
                            value: '',
                            controlsWidth: 150,
                            options: [
                                {
                                    value: 'ACTIVE',
                                    label: 'Active'.translate()
                                },
                                {
                                    value: 'INACTIVE',
                                    label: 'Inactive'.translate()
                                }
                            ]
                        },
                        {
                            id: 'username',
                            pmType: 'dropdown',
                            label: 'User'.translate(),
                            name: 'username',
                            required: true,
                            controlsWidth: 300,
                            value: '',
                            options: [],
                            onChange: function (newValue, prevValue) {
                            }
                        },
                        {
                            id: 'name',
                            pmType: 'text',
                            label: 'Name'.translate(),
                            value: startTimer.evn_name,
                            required: true,
                            name: 'name',
                            visible: false,
                            valueType: 'string'
                        },
                        {
                            id: 'performTask',
                            pmType: 'dropdown',
                            label: 'Perform this task'.translate(),
                            name: 'performTask',
                            required: true,
                            value: '',
                            controlsWidth: 300,
                            options: [
                                {
                                    label: '- Select -'.translate(),
                                    value: '',
                                    disabled: true,
                                    selected: true
                                },
                                {
                                    value: 'daily',
                                    label: 'Daily'.translate()
                                },
                                {
                                    value: 'weekly',
                                    label: 'Weekly'.translate()
                                },
                                {
                                    value: 'monthly',
                                    label: 'Monthly'.translate()
                                },
                                {
                                    value: 'oneTime',
                                    label: 'One Time Only'.translate()
                                },
                                {
                                    value: 'every',
                                    label: 'Every'.translate()
                                }
                            ],
                            onChange: function (newValue, prevValue) {
                                showProperties(newValue);
                            }
                        }
                    ]
                },
                {
                    id: 'panelSelectDate',
                    pmType: 'panel',
                    layout: 'vbox',
                    fieldset: true,
                    visible: false,
                    height: '350px',
                    legend: 'Select the date and time for case(s) to be initiated.'.translate(),
                    items: []
                }
            ]
        });

        buttonCancel = new PMUI.ui.Button({
            id: 'cancelSchedulerButton',
            text: 'Cancel'.translate(),
            buttonType: 'error',
            handler: function (event) {
                if (formCreateCaseScheduler.isDirty()) {
                    var message_window = new PMUI.ui.MessageWindow({
                        windowMessageType: 'warning',
                        width: 490,
                        bodyHeight: 'auto',
                        id: 'cancelMessageStartTimer',
                        title: 'Start Timer Event'.translate(),
                        message: 'Are you sure you want to discard your changes?'.translate(),
                        footerItems: [
                            {
                                text: 'No'.translate(),
                                handler: function () {
                                    message_window.close();
                                },
                                buttonType: "error"
                            },
                            {
                                text: 'Yes'.translate(),
                                handler: function () {
                                    message_window.close();
                                    schedulerListWindow.close();
                                },
                                buttonType: "success"
                            }
                        ]
                    });
                    message_window.open();
                    message_window.showFooter();
                } else {
                    formCreateCaseScheduler.reset();
                    schedulerListWindow.close();
                }
            }
        });

        //Window Buttons
        buttonSave = new PMUI.ui.Button({
            id: 'saveSchedulerButton',
            text: 'Save'.translate(),
            height: 31,
            buttonType: 'success',
            handler: function (event) {
                if (formCreateCaseScheduler.isValid()) {
                    var dataFormCreate = formCreateCaseScheduler.getData();

                    var dataToSend = {
                        sch_del_user_name: dataFormCreate.username,
                        sch_name: startTimer.evn_name,
                        tas_uid: startTimer.ports.get(0).connection.flo_element_dest,
                        sch_start_time: '',
                        sch_start_date: '',
                        sch_week_days: '',
                        sch_start_day: '',
                        sch_start_day_opt_1: '',
                        sch_start_day_opt_2: '',
                        sch_months: '',
                        sch_end_date: '',
                        sch_repeat_every: '',
                        sch_state: (dataFormCreate.state !== '') ? dataFormCreate.state : 'ACTIVE',
                        sch_option: ''
                    };

                    var perform = dataFormCreate.performTask;
                    switch (perform) {
                        case 'daily':
                            dataToSend.sch_option = '1';
                            dataToSend.sch_start_time = dataFormCreate.execttime;
                            dataToSend.sch_start_date = dataFormCreate.startDate;
                            dataToSend.sch_end_date = dataFormCreate.endDate;
                            break;
                        case 'weekly':
                            var formdays = eval(dataFormCreate.daysoftheweek);
                            var days = '';
                            for (i = 0; i < formdays.length; i += 1) {
                                if (i !== (formdays.length - 1)) {
                                    days += formdays[i] + '|';
                                } else {
                                    days += formdays[i];
                                }
                            }

                            dataToSend.sch_option = '2';
                            dataToSend.sch_start_time = dataFormCreate.execttime;
                            dataToSend.sch_start_date = dataFormCreate.startDate;
                            dataToSend.sch_end_date = dataFormCreate.endDate;
                            dataToSend.sch_week_days = days;
                            break;
                        case 'monthly':
                            dataToSend.sch_option = '3';
                            dataToSend.sch_start_time = dataFormCreate.execttime;
                            dataToSend.sch_start_date = dataFormCreate.startDate;
                            dataToSend.sch_end_date = dataFormCreate.endDate;
                            if (dataFormCreate.options === 'dayofmonth') {
                                var formmonths = eval(dataFormCreate.months);
                                var months = '';
                                for (i = 0; i < formmonths.length; i += 1) {
                                    if (i !== (formmonths.length - 1)) {
                                        months += formmonths[i] + '|';
                                    } else {
                                        months += formmonths[i];
                                    }
                                }
                                dataToSend.sch_start_day = '1'; //Day of month
                                dataToSend.sch_start_day_opt_1 = dataFormCreate.dayMonth;//1 to 31 - day of the month
                            } else if (dataFormCreate.options === 'day') {
                                var opt2 = dataFormCreate.first + '|' + dataFormCreate.day;
                                var formmonths = eval(dataFormCreate.months);
                                var months = '';
                                for (i = 0; i < formmonths.length; i += 1) {
                                    if (i !== (formmonths.length - 1)) {
                                        months += formmonths[i] + '|';
                                    } else {
                                        months += formmonths[i];
                                    }
                                }
                                dataToSend.sch_start_day = '2'; //Day of month
                                dataToSend.sch_start_day_opt_2 = opt2;//1 to 31 - day of the month

                            }
                            dataToSend.sch_months = months;
                            break;
                        case 'oneTime':
                            dataToSend.sch_option = '4';
                            dataToSend.sch_start_time = dataFormCreate.execttime;
                            break;
                        case 'every':
                            dataToSend.sch_option = '5';
                            dataToSend.sch_repeat_every = timeToDecimal(dataFormCreate.execttime).toFixed(2);
                            break;
                    }
                    updateCaseScheduler(dataToSend);
                }
            }
        });

        //load users
        loadUsers = function () {
            var field = formCreateCaseScheduler.getField('username');
            field.clearOptions();
            for (var i = 0; i < listUsers.length; i += 1) {
                field.addOption({
                    value: listUsers[i].aas_username,
                    label: listUsers[i].aas_name + ' ' + listUsers[i].aas_lastname
                });
            }
        };

        loadDataForm = function () {
            var loadTime,
                option,
                daysVal,
                monthsVal,
                monthsop,
                days,
                i,
                startTime,
                finallyST,
                dataEdit;

            loadTime = function (dataEdit, dataForm) {
                var starDate,
                    startTime,
                    finallyST,
                    endDate;
                starDate = dataForm.sch_start_date ? dataForm.sch_start_date.split(' ') : [''];
                endDate = dataForm.sch_end_date ? dataForm.sch_end_date.split(' ') : [''];

                startTime = dataForm.sch_start_time.split(' ');
                startTime = startTime[1].split(':');
                finallyST = startTime[0] + ':' + startTime[1];
                dataEdit[4].setValue(starDate[0]);
                dataEdit[5].setValue(endDate[0]);
                dataEdit[6].setValue(finallyST);
            };

            dataEdit = formCreateCaseScheduler.getFields();

            dataEdit[0].setValue(dataForm.sch_state);
            dataEdit[1].setValue(dataForm.sch_del_user_name);
            option = 'daily';
            switch (dataForm.sch_option) {
                case '1':
                    option = 'daily';
                    dataEdit[3].setValue(option);
                    showProperties(option);
                    dataEdit = formCreateCaseScheduler.getFields();
                    loadTime(dataEdit, dataForm);
                    break;
                case '2':
                    option = 'weekly';
                    dataEdit[3].setValue(option);
                    showProperties(option);
                    dataEdit = formCreateCaseScheduler.getFields();
                    loadTime(dataEdit, dataForm);

                    daysVal = "[\"";
                    days = dataForm.sch_week_days.split('|');
                    for (i = 0; i < days.length; i += 1) {
                        if (i !== (days.length - 1)) {
                            daysVal += days[i] + "\",\"";
                        } else {
                            daysVal += days[i] + "\"]";
                        }
                    }
                    dataEdit[7].setValue(daysVal);
                    break;
                case '3':
                    option = 'monthly';
                    dataEdit[3].setValue(option);
                    showProperties(option);
                    dataEdit = formCreateCaseScheduler.getFields();
                    loadTime(dataEdit, dataForm);
                    days = [];
                    if (dataForm.sch_start_day !== '') {
                        days = dataForm.sch_start_day.split('|');
                    }

                    if (days[0] === '1') {
                        dataEdit[7].setValue('dayofmonth');
                        loadOptionsRadio('dayofmonth');
                        dataEdit[8].setValue(days[1]);
                    } else if (days[0] === '2') {
                        dataEdit[7].setValue('day');
                        loadOptionsRadio('day');
                        dataEdit[9].setValue(days[1]);
                        dataEdit[10].setValue(days[2]);
                    }
                    monthsVal = "[\"";
                    monthsop = dataForm.sch_months.split('|');
                    for (i = 0; i < monthsop.length; i += 1) {
                        if (i !== (monthsop.length - 1)) {
                            monthsVal += monthsop[i] + "\",\"";
                        } else {
                            monthsVal += monthsop[i] + "\"]";
                        }
                    }
                    dataEdit[11].setValue(monthsVal);
                    break;
                case '4':
                    option = 'oneTime';
                    dataEdit[3].setValue(option);
                    showProperties(option);
                    dataEdit = formCreateCaseScheduler.getFields();
                    startTime = dataForm.sch_start_time.split(' ');
                    startTime = startTime[1].split(':');
                    finallyST = startTime[0] + ':' + startTime[1];
                    dataEdit[4].setValue(finallyST);
                    break;
                case '5':
                    option = 'every';
                    dataEdit[3].setValue(option);
                    showProperties(option);
                    dataEdit = formCreateCaseScheduler.getFields();
                    dataEdit[4].setValue(decimalToTime(parseFloat(dataForm.sch_repeat_every)));
                    break;
            }
        };

        //Main window Case Scheduler
        schedulerListWindow = new PMUI.ui.Window({
            id: 'schedulerListWindow',
            title: 'Start Timer Event'.translate(),
            height: DEFAULT_WINDOW_HEIGHT,
            width: DEFAULT_WINDOW_WIDTH,
            buttonPanelPosition: 'top',
            buttons: [
                buttonSave,
                {pmType: 'label', text: 'or'},
                buttonCancel
            ]
        });

        openForm = function () {
            schedulerListWindow.addItem(formCreateCaseScheduler);
            schedulerListWindow.open();
            loadUsers();
            loadDataForm();
            applyStyleWindowForm(schedulerListWindow);
            schedulerListWindow.showFooter();
            schedulerListWindow.defineEvents();
        };
    };

    PMDesigner.startTimer.openForm = function (element) {
        openForm();
    };

    PMDesigner.startTimer.validate = function (element) {
        if (element.ports.isEmpty()) {
            PMDesigner.msgFlash('Must connect to a Task'.translate(), document.body, 'error', 3000, 5);
            return false;
        }
        PMDesigner.startTimer(element);
        if (loadValuesStartTimer(true) === 0) {
            PMDesigner.msgFlash('The task doesn\'t have assigned users'.translate(), document.body, 'info', 3000, 5);
            return false;
        }
        return true;
    };

}());