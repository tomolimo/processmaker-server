var iSample = 0;
var samples = [
	{
		file: "sample1.json",
		helper: "Basic Fields"
	},
	{
		file: "sample2.json",
		helper: "Required, DataType and Hint property"
	},
	{
		file: "sample3.json",
		helper: "Fields with mask"
	},
	{
		file: "sample4.json",
		helper: "Mode View"
	},
	{
		file: "sample5.json",
		helper: "Fields disabled"
	},
	{
		file: "sample6.json",
		helper: "Formula"
	},
	{
		file: "sample7.json",
		helper: "Layouts"
	},
	{
		file: "sample8.json",
		helper: "Layouts II"
	},
	{
		file: "sample9.json",
		helper: "Upload file"
	},
	{
		file: "sample10.json",
		helper: "Grids"
	},
	{
		file: "sample11.json",
		helper: "SubForm"
	},
	{
		file: "sample12.json",
		helper: "GeoMap"
	}
];
previousSample = function(){
	iSample--;
	if (iSample >= 0) {
		loadSample(iSample);
	} else {
		iSample = 0;
	}
};
nextSample = function(){
	iSample++;
	if (iSample < samples.length) {
		loadSample(iSample);
	} else {
		iSample = samples.length-1;
	}
};
loadSample = function (sample) {
	$.getJSON("data/"+samples[sample].file, function (data) {
        loadForm(data);
        iSample = sample;
    }).fail(function (a) {
        console.error("Error loading fields");
    });
};
loadToolbar = function() {
	var div = document.createElement("div");

	div.style.cssText = "text-align: center; margin:1%;";
	div.innerHTML = '<button type="button" onclick="previousSample();return false;" class="btn btn-info btn-sm"><</button>';
	for (var k=0; k<samples.length; k+=1) {
		div.innerHTML += '<button type="button" title="'+ samples[k].helper+'" data-toggle="tooltip" data-placement="bottom" onclick="loadSample('+k+');return false;" class="btn btn-default btn-sm">'+parseInt(k+1)+'</button>';
	}
	div.innerHTML += '<button type="button" onclick="nextSample();return false;" class="btn btn-info btn-sm">></button>';
	document.body.appendChild(div);
	$(document.body).find("[data-toggle=tooltip]").tooltip().click(function(e) {
        $(this).tooltip('toggle');
    });
}

var loadForm = function (data) {
	var fieldCollection, 
	fieldView,
	project,
	dataJSON = data;

	if (typeof data !== "object") {
		dataJSON = JSON.parse(data);
	}
	$(".pmdynaform-container").remove();
	window.	project = new PMDynaform.core.Project({
		data: dataJSON,
		renderTo: document.body
	});

};

$(document).ready(function () {
    $.getJSON("data/"+samples[iSample].file, function (data) {
    	loadToolbar();
        loadForm(data);
        $(".pmdynaform-container").css("float","left");
    }).fail(function (a) {
        console.error("Error loading fields");
    });

});