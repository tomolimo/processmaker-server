

var ViewDashboardPresenter = function (model) {
	this.helper = new ViewDashboardHelper();
	this.helper.assert(model != null, "A model must be passed for the presenter work.")
    this.model = model;
};

ViewDashboardPresenter.prototype.getUserDashboards = function (userId) {
	var that = this;
	var requestFinished = $.Deferred();
	that.model.userDashboards(userId)
			.done(function(modelData){
			   	var viewModel = that.userDashboardsViewModel(modelData)
				requestFinished.resolve(viewModel);
			});
	return requestFinished.promise();
};

ViewDashboardPresenter.prototype.userDashboardsViewModel = function(data) {
	var that = this;
	//if null data is returned we default to an empty array
	if (data == null) { data = []; }
	var returnList = [];
	var hasFavorite = false;
	$.each(data, function(index, originalObject) {
		var map = {
			"DAS_TITLE" : "title",
			"DAS_UID" : "id",
			"DAS_FAVORITE" : "isFavorite"
		};
		var newObject = that.helper.merge(originalObject, {}, map);
		if (newObject.isFavorite == 1) {
			hasFavorite = true;
		}
		returnList.push(newObject);
	});

	//if no favorite is selected, the first one is selected.
	if (!hasFavorite && returnList.length > 0 ) {
		returnList[0].isFavorite = 1;
	}

	return returnList;
};

ViewDashboardPresenter.prototype.getDashboardIndicators = function (dashboardId,initDate, endDate) {
	if (dashboardId == null) {throw new Error ("getDashboardIndicators -> dashboardId can't be null");};
	var that = this;
	var requestFinished = $.Deferred();
	this.model.dashboardIndicators (dashboardId,  initDate, endDate)
		.done(function (modelData) {
			var viewModel = that.dashboardIndicatorsViewModel(modelData)
			requestFinished.resolve(viewModel);
		});
	return requestFinished.promise();
};

ViewDashboardPresenter.prototype.dashboardIndicatorsViewModel = function(data) {
	if (data == null) {return null;}
	var that = this;
	var returnList = [];
	var i = 1;
	$.each(data, function(index, originalObject) {
		var map = {
			"DAS_IND_UID" : "id",
			"DAS_IND_TITLE" : "title",
			"DAS_IND_TYPE" : "type",
			"DAS_IND_VARIATION" : "comparative",
			"DAS_IND_PERCENT_VARIATION" : "percentComparative",
			"DAS_IND_DIRECTION" : "direction",
			"DAS_IND_VALUE" : "value",
			"DAS_IND_X" : "x",
			"DAS_IND_Y" : "y",
			"DAS_IND_WIDTH" : "width",
			"DAS_IND_HEIGHT" : "height",
			"DAS_UID_PROCESS" : "process",
			"PERCENTAGE_OVERDUE" : "percentageOverdue",
			"PERCENTAGE_AT_RISK" : "percentageAtRisk",
			"PERCENTAGE_ON_TIME" : "percentageOnTime"
		};

		var newObject = that.helper.merge(originalObject, {}, map);
		newObject.toDrawX =  newObject.x;
		//newObject.toDrawX =  (newObject.x == 0) ? 12 - 12/i : newObject.x;

		newObject.toDrawY = (newObject.y == 0) ? 6 : newObject.y;
		newObject.toDrawHeight = (newObject.y == 0) ? 2 : newObject.height;
		newObject.toDrawWidth = (newObject.y == 0) ? 12 / data.length : newObject.width;
		newObject.directionSymbol = (newObject.direction == "1") ? "<" : ">";
		newObject.isWellDone = (newObject.direction == "1") 
								? parseFloat(newObject.value) <= parseFloat(newObject.comparative)
								: parseFloat(newObject.value) >= parseFloat(newObject.comparative);
        
		newObject.category = (newObject.type == "1010" || newObject.type == "1030")
									? "special"
									: "normal";

		//rounding
		newObject.comparative =  Math.round(newObject.comparative*100)/100;
		newObject.comparative = ((newObject.comparative > 0) ? "+": "") + newObject.comparative;

		newObject.percentComparative = (newObject.percentComparative != '--')
										? '(' + newObject.percentComparative + '%)'
										: "";
		newObject.percentComparative = (newObject.comparative == 0 && newObject.percentComparative !=  '')
										? "(0%)"
										: newObject.percentComparative;

		newObject.value = that.roundedIndicatorValue(newObject.value);
		newObject.favorite = 0;

		that.setStatusButtonWidthsAndDisplayValues(newObject);
		newObject.overdueVisibility = (newObject.percentageOverdueWidth > 0) ? "visible" : "hidden";
		newObject.atRiskVisibility = (newObject.percentageAtRiskWidth > 0) ? "visible" : "hidden";
		newObject.onTimeVisibility = (newObject.percentageOnTimeWidth > 0) ? "visible" : "hidden";
		returnList.push(newObject);
		i++;
	});

	//sort the array for drawing in toDrawX order
	returnList.sort(function (a, b) {
		return ((a.toDrawX <= b.toDrawX) ? -1 : 1);
	});
	if (returnList.length > 0)  {
		returnList[0].favorite = 1;
	}
	return returnList;
};


ViewDashboardPresenter.prototype.roundedIndicatorValue = function (value) { 
	if (value == 0) {
		return "0";
	}
	/*if (value > 0 && value < 0.1) {
		return "<0.1";
	}*/
	return Math.round(value*100)/100 + "";

}

ViewDashboardPresenter.prototype.setStatusButtonWidthsAndDisplayValues = function (data) {
	var minPercent = 10;
	var barsLessThanMin = [];
	var barsNormal = [];

	var classifyBar = function (bar) {
		if (bar.valueRounded <= minPercent && bar.valueRounded > 0) {
			barsLessThanMin.push (bar);
		} else {
			barsNormal.push (bar)	
		}
	}

	var atRisk = {
		type : "atRisk",
		value : data.percentageAtRisk,
		valueRounded : Math.round(data.percentageAtRisk)
	};

	var overdue = {
		type : "overdue",
		value : data.percentageOverdue,
		valueRounded : Math.round(data.percentageOverdue)
	};

	var onTime = {
		type : "onTime",
		value : data.percentageOnTime,
		valueRounded : Math.round(data.percentageOnTime)
	};

	atRisk.valueToShow = (this.helper.zeroIfNull(atRisk.valueRounded) == 0) 
						? ""
						: atRisk.valueRounded + "%";

	overdue.valueToShow = (this.helper.zeroIfNull(overdue.valueRounded) == 0) 
						? ""
						: overdue.valueRounded + "%";

	onTime.valueToShow = (this.helper.zeroIfNull(onTime.valueRounded) == 0) 
						? ""
						: onTime.valueRounded + "%";

	classifyBar(atRisk);
	classifyBar(overdue);
	classifyBar(onTime);

	var widthToDivide = 100 - barsLessThanMin.length * minPercent;
	var normalsSum = 0;
	$.each (barsNormal, function() {
		normalsSum += this.valueRounded;
	});

	$.each(barsNormal, function(key, bar) {
		bar.width = widthToDivide * bar.valueRounded / normalsSum;
	});

	$.each(barsLessThanMin, function(key, bar) {
		bar.width = minPercent;
	});

	if (atRisk.valueToShow == 0 && overdue.valueToShow == 0 && onTime.valueToShow == 0) {
		onTime.valueToShow = G_STRING['ID_INBOX_EMPTY'];
		onTime.width = 100;
	}

	data.percentageOverdueWidth = overdue.width;
	data.percentageOnTimeWidth = onTime.width;
	data.percentageAtRiskWidth = atRisk.width;

	data.percentageOverdueToShow = overdue.valueToShow;
	data.percentageAtRiskToShow = atRisk.valueToShow;
	data.percentageOnTimeToShow = onTime.valueToShow;
}

/*++++++++ FIRST LEVEL INDICATOR DATA +++++++++++++*/
ViewDashboardPresenter.prototype.getIndicatorData = function (indicatorId, indicatorType, initDate, endDate) {
	var that = this;
	var requestFinished = $.Deferred();
	switch (indicatorType) {
		case "1010":
			this.model.peiData(indicatorId, initDate, endDate)
				.done(function(modelData) {
						var viewModel = that.peiViewModel(modelData)
						requestFinished.resolve(viewModel);
				});
			break;
		case "1030":
			this.model.ueiData(indicatorId, initDate, endDate)
				.done(function(modelData) {
						var viewModel = that.ueiViewModel(modelData)
						requestFinished.resolve(viewModel);
				});
			break;
		case "1050":
			this.model.statusData(indicatorId)
				.done(function(modelData) {
					var viewModel = that.statusViewModel(indicatorId, modelData)
					requestFinished.resolve(viewModel);
				});
			break;
		default:
			this.model.generalIndicatorData(indicatorId, initDate, endDate)
				.done(function(modelData) {
						var viewModel = that.indicatorViewModel(modelData)
						requestFinished.resolve(viewModel);
				});
			break;
	}
	return requestFinished.promise();
};

ViewDashboardPresenter.prototype.peiViewModel = function(data) {
	if (data == null) {return null;}
	var that = this;
	var graphData = [];
	$.each(data.data, function(index, originalObject) {
		originalObject.name = that.helper.labelIfEmpty(originalObject.name);
		var map = {
			"name" : "datalabel",
			"inefficiencyCost" : "value"
		};
		var newObject = that.helper.merge(originalObject, {}, map);
		graphData.push(newObject);
		originalObject.efficiencyIndexToShow = that.roundedIndicatorValue(originalObject.efficiencyIndex);
		//rounded to 1 decimal
		originalObject.inefficiencyCostToShow =  Math.round(originalObject.inefficiencyCost * 10) / 10;
		originalObject.indicatorId = data.id;
		originalObject.json = JSON.stringify(originalObject);
	});

	var retval = {};
	retval = data;

	this.makeShortLabel(graphData, 10);
	retval.dataToDraw = this.adaptGraphData(graphData);

	retval.inefficiencyCostToShow = Math.round(retval.inefficiencyCost * 10) / 10;
	retval.efficiencyIndexToShow = that.roundedIndicatorValue(retval.efficiencyIndex);
	return retval;
};

ViewDashboardPresenter.prototype.ueiViewModel = function(data) {
	if (data == null) {return null;}
	var that = this;
	var graphData = [];
	$.each(data.data, function(index, originalObject) {
		originalObject.name = that.helper.labelIfEmpty(originalObject.name);
		var map = {
			"name" : "datalabel",
			"inefficiencyCost" : "value",
			"deviationTime" : "dispersion"
		};
		var newObject = that.helper.merge(originalObject, {}, map);
		graphData.push(newObject);
		originalObject.inefficiencyCostToShow = Math.round(originalObject.inefficiencyCost * 10)/10;
		originalObject.efficiencyIndexToShow = that.roundedIndicatorValue(originalObject.efficiencyIndex);
		originalObject.indicatorId = data.id;
		originalObject.json = JSON.stringify(originalObject);
	});

	var retval = {};
	retval = data;
	this.makeShortLabel(graphData, 10);
	retval.dataToDraw = this.adaptGraphData(graphData);

	retval.inefficiencyCostToShow = Math.round(retval.inefficiencyCost * 10) / 10;
	retval.efficiencyIndexToShow = that.roundedIndicatorValue(retval.efficiencyIndex);
	return retval;
};

ViewDashboardPresenter.prototype.statusViewModel = function(indicatorId, data) {
	if (data == null) {return null;}
	var that = this;
	data.id = indicatorId;
	var graph1Data = [];
	var graph2Data = [];
	var graph3Data = [];
	$.each(data.dataList, function(index, originalObject) {

		originalObject.taskTitle = that.helper.labelIfEmpty(originalObject.taskTitle);
		//var title = originalObject.taskTitle.substring(0,10);

		var newObject1 = {
			datalabel : originalObject.taskTitle,
			value : originalObject.percentageTotalOverdue
		};
		var newObject2 = {
			datalabel : originalObject.taskTitle,
			value : originalObject.percentageTotalAtRisk
		};
		var newObject3 = {
			datalabel : originalObject.taskTitle,
			value : originalObject.percentageTotalOnTime
		};

		if (newObject1.value > 0) {
			graph1Data.push(newObject1);
		}
		if (newObject2.value > 0) {
			graph2Data.push(newObject2);
		}
		if (newObject3.value > 0) {
			graph3Data.push(newObject3);
		}
		//we add the indicator id for reference
		originalObject.indicatorId = indicatorId;
	});


	that.makeShortLabel(graph1Data, 10);
	that.makeShortLabel(graph2Data, 10);
	that.makeShortLabel(graph3Data, 10);

	var retval = data;
	retval.graph1Data = this.orderGraphData(graph1Data, "down").splice(0,7)
	retval.graph2Data = this.orderGraphData(graph2Data, "down").splice(0,7)
	retval.graph3Data = this.orderGraphData(graph3Data, "down").splice(0,7)

	$.each(retval.graph1Data, function(index, item) { item.datalabel = (index + 1) + "." + item.datalabel;  });
	$.each(retval.graph2Data, function(index, item) { item.datalabel = (index + 1) + "." + item.datalabel;  });
	$.each(retval.graph3Data, function(index, item) { item.datalabel = (index + 1) + "." + item.datalabel;  });
	return retval;
};

ViewDashboardPresenter.prototype.indicatorViewModel = function(data) {
	if (data == null) {return null;}
	var that = this;
	$.each(data.graph1Data, function(index, originalObject) {
		var label = (('YEAR' in originalObject) ? originalObject.YEAR : "") ;
		label += (('MONTH' in originalObject) ? "/" + originalObject.MONTH : "") ;
		label += (('QUARTER' in originalObject) ?  "/" + originalObject.QUARTER : "");
		label += (('SEMESTER' in originalObject) ?  "/" + originalObject.SEMESTER : "");
		originalObject.datalabel = label;
	});

	$.each(data.graph2Data, function(index, originalObject) {
		var label = (('YEAR' in originalObject) ? originalObject.YEAR : "") ;
		label += (('MONTH' in originalObject) ? "/" + originalObject.MONTH : "") ;
		label += (('QUARTER' in originalObject) ?  "/" + originalObject.QUARTER : "");
		label += (('SEMESTER' in originalObject) ?  "/" + originalObject.SEMESTER : "") ;
		originalObject.datalabel = label;
	});
	return data;
};
/*-------FIRST LEVEL INDICATOR DATA */

/*++++++++ SECOND LEVEL INDICATOR DATA +++++++++++++*/
ViewDashboardPresenter.prototype.getSpecialIndicatorSecondLevel = function (entityId, indicatorType, initDate, endDate) {
	var that = this;
	var requestFinished = $.Deferred();
	//if modelData is passed (because it was cached on the view) no call is made to the server.
	//and just a order is applied to the list
	
	switch (indicatorType) {
		case "1010":
			this.model.peiDetailData(entityId, initDate, endDate)
				.done(function (modelData) {
					var viewModel = that.returnIndicatorSecondLevelPei(modelData);
					requestFinished.resolve(viewModel);
				});
			break;
		case "1030":
			this.model.ueiDetailData(entityId, initDate, endDate)
				.done(function (modelData) {
					var viewModel = that.returnIndicatorSecondLevelUei(modelData);
					requestFinished.resolve(viewModel);
				});
			break;
		default:
			throw new Error("Indicator type " + indicatorType + " has not detail data implemented of special indicator kind.");
	}
	return requestFinished.promise();
};

ViewDashboardPresenter.prototype.returnIndicatorSecondLevelPei = function(modelData) {
	if (modelData == null) {return null;}
	//modelData arrives in format [{users/tasks}]
	//returns object {dataToDraw[], entityData[] //user/tasks data}
	var that = this;
	var graphData = [];

	$.each(modelData, function(index, originalObject) {
		var map = {
			"name" : "datalabel",
			"inefficiencyCost" : "value",
			"deviationTime" : "dispersion"
		};
		var newObject = that.helper.merge(originalObject, {}, map);
		originalObject.inefficiencyCostToShow =  Math.round(originalObject.inefficiencyCost * 10) / 10;
		originalObject.efficiencyIndexToShow = that.roundedIndicatorValue(originalObject.efficiencyIndex);
		originalObject.deviationTimeToShow = Math.round(originalObject.deviationTime);
		originalObject.rankToShow = originalObject.rank + "/" + modelData.length;
		graphData.push(newObject);
	});
	var retval = {};
	this.makeShortLabel(graphData, 10);
	retval.dataToDraw = this.adaptGraphData(graphData);
	retval.entityData = modelData;
	return retval;
};

ViewDashboardPresenter.prototype.returnIndicatorSecondLevelUei = function(modelData) {
	if (modelData == null) {return null;}
	//modelData arrives in format [{users/tasks}]
	//returns object {dataToDraw[], entityData[] //user/tasks data}
	var that = this;
	var graphData = [];

	$.each(modelData, function(index, originalObject) {
		var map = {
			"name" : "datalabel",
			"inefficiencyCost" : "value",
			"deviationTime" : "dispersion"
		};
		var newObject = that.helper.merge(originalObject, {}, map);
		originalObject.inefficiencyCostToShow = Math.round(originalObject.inefficiencyCost * 10) / 10;
		originalObject.efficiencyIndexToShow = that.roundedIndicatorValue(originalObject.efficiencyIndex);
		originalObject.deviationTimeToShow = Math.round(originalObject.deviationTime);
		originalObject.rankToShow = originalObject.rank + "/" + modelData.length;
		graphData.push(newObject);

	});
	var retval = {};
	this.makeShortLabel(graphData, 10);
	retval.dataToDraw = this.adaptGraphData(graphData);
	retval.entityData = modelData;
	return retval;
};
/*-------SECOND LEVEL INDICATOR DATA*/

ViewDashboardPresenter.prototype.orderDataList = function(listData, orderDirection, orderFunction) { 
	if (listData == null) {return null;}
	//orderDirection is passed in case no order FUnction is passed (to use in the default ordering)
	var orderToUse = orderFunction;
	if (orderFunction == undefined) {
		orderToUse = function (a ,b) {
			var retval = 0;
			if (orderDirection == "down") {
				retval = ((a.inefficiencyCost*1.0 <= b.inefficiencyCost*1.0) ? 1 : -1);
			}
			else {
				//the 1,-1 are flipped
				retval = ((a.inefficiencyCost*1.0 <= b.inefficiencyCost*1.0) ? -1 : 1);
			}
			return 	retval;
		}
	}
	return listData.sort(orderToUse);
}

ViewDashboardPresenter.prototype.orderGraphData = function(listData, orderDirection, orderFunction) { 
	if (listData == null) {return null;}
	//orderDirection is passed in case no order FUnction is passed (to use in the default ordering)
	var orderToUse = orderFunction;
	if (orderFunction == undefined) {
		orderToUse = function (a ,b) {
			var retval = 0;
			if (orderDirection == "down") {
				retval = ((a.value*1.0 <= b.value*1.0) ? 1 : -1);
			}
			else {
				//the 1,-1 are flipped
				retval = ((a.value*1.0 <= b.value*1.0) ? -1 : 1);
			}
			return 	retval;
		}
	}
	return listData.sort(orderToUse);
}

ViewDashboardPresenter.prototype.adaptGraphData = function(listData) { 
	var workList = this.orderGraphData(listData, "up");
	var newList = [];
	$.each(workList, function(index, item) {
		item.datalabel = (index + 1) + "." + item.datalabel;
		//use positive values for drawing;
		if (item.value > 0) {
			item.value = 0;
		}
		if (item.value < 0) {
			item.value = Math.abs(item.value);
		}

		if (item.value > 0) {
			newList.push(item);
		}
	});
	return newList.splice(0,7);
}

ViewDashboardPresenter.prototype.makeShortLabel = function(listData, labelLength) { 
	$.each(listData, function(index, item) {
		var longLabel = (item.datalabel == null) 
						? "" 
						: item.datalabel.substring(0, 50);

		var shortLabel = (item.datalabel == null) 
						? "" 
						: item.datalabel.substring(0, labelLength);

		item.datalabel = shortLabel;
		item.longlabel = longLabel;
	});
}
