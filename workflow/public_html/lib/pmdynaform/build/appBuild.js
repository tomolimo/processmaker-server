var datagrid = {}, index = 0, size = 97, generateDataGrid, data, datos;
 var datastore = {
              "1": [
                {
                  "value": "103",
                  "label": "103"
                },
                {
                  "value": "HQ336336HGG45JH5G45H4H5G4JHG54",
                  "label": "HQ336336HGG45JH5G45H4H5G4JHG54"
                },
                {
                  "value": "2004-10-19",
                  "label": "2004-10-19"
                },
                {
                  "value": "6066.78",
                  "label": "6066.78"
                }
              ],
              "2": [
                {
                  "value": "103",
                  "label": "103"
                },
                {
                  "value": "JM555205JDASD897AS7DAS87D9AS7D7AS98D7AS98D7AS87D9A",
                  "label": "JM555205JDASD897AS7DAS87D9AS7D7AS98D7AS98D7AS87D9A"
                },
                {
                  "value": "2003-06-05",
                  "label": "2003-06-05"
                },
                {
                  "value": "14571.44",
                  "label": "14571.44"
                }
              ],
              "3": [
                {
                  "value": "103",
                  "label": "103"
                },
                {
                  "value": "OM314933",
                  "label": "OM314933"
                },
                {
                  "value": "2004-12-18",
                  "label": "2004-12-18"
                },
                {
                  "value": "1676.14",
                  "label": "1676.14"
                }
              ],
              "4": [
                {
                  "value": "112",
                  "label": "112"
                },
                {
                  "value": "BO864823",
                  "label": "BO864823"
                },
                {
                  "value": "2004-12-17",
                  "label": "2004-12-17"
                },
                {
                  "value": "14191.12",
                  "label": "14191.12"
                }
              ],
              "5": [
                {
                  "value": "112",
                  "label": "112"
                },
                {
                  "value": "HQ55022",
                  "label": "HQ55022"
                },
                {
                  "value": "2003-06-06",
                  "label": "2003-06-06"
                },
                {
                  "value": "32641.98",
                  "label": "32641.98"
                }
              ]
            };
generateDataGrid = function (){
  for(var i = 0 ; i < size ; i+=1){
    datagrid[(i+1).toString()] = datastore[(Math.floor((Math.random() * 5) + 1)).toString()];
  }
  return datagrid;
}
window.onload = function () {
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        mode: "application/ld+json",
        viewportMargin: Infinity
    });
    editor.setSize(350, 500);
    document.getElementById("file").onchange = function (e) {
        var r = new FileReader();
        r.readAsText(this.files[0], "UTF-8");
        r.onload = function (e) {
            editor.setValue(e.target.result);
            $(".pmdynaform-container").remove();
        };
        this.form.reset();
    };
    $(".CodeMirror").hide();
    document.getElementById("test").onclick = function () {
      datos = generateDataGrid();
      $(".pmdynaform-container").remove();
      var uno = new Date(), dos, tiempo, m, s;
      isd = 0;
      console.clear();
data = data = {
  "name": "edit_form",
  "description": "",
  "items": [
    {
      "type": "form",
      "id": "229723097555a11781ed309062592560",
      "name": "edit_form",
      "description": "",
      "mode": "edit",
      "script":"",
      "language": "en",
      "externalLibs": "",
      "items": [
        [
          {
            "type": "grid",
            "variable": "grilla",
            "id": "grilla",
            "name": "grilla",
            "label": "grid_1",
            "hint": "",
            "columns": [
              {
                "type": "suggest",
                "variable": "",
                "dataType": "",
                "id": "suggest0000000002",
                "name": "suggest0000000002",
                "label": "suggest_2",
                "defaultValue": "",
                "placeholder": "",
                "hint": " ad ad ad ad ad ad ad a",
                "required": false,
                "dependentFields": [],
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
                "options": [
                  {
                    "value": "uno",
                    "label": "primero"
                  },
                  {
                    "value": "dos",
                    "label": "segundo"
                  }
                ],
                "columnWidth": "",
                "width": 100,
                "title": "suggest_2",
                "data": {
                  "value": "uno",
                  "label": "primero"
                }
              },
              {
                "type": "text",
                "variable": "",
                "dataType": "",
                "id": "text0000000002",
                "name": "text0000000002",
                "label": "text_2",
                "defaultValue": "aaaaaaa",
                "placeholder": "",
                "hint": "",
                "required": false,
                "dependentFields": [],
                "textTransform": "none",
                "validate": "",
                "validateMessage": "",
                "maxLength": 1000,
                "formula": "",
                "mode": "parent",
                "operation": "",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "columnWidth": "",
                "width": 100,
                "title": "text_2",
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "textarea",
                "variable": "",
                "dataType": "",
                "id": "textarea0000000003",
                "name": "textarea0000000003",
                "label": "textarea_2",
                "defaultValue": "",
                "placeholder": "",
                "hint": "",
                "required": false,
                "validate": "",
                "validateMessage": "",
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "columnWidth": "",
                "width": 100,
                "title": "textarea_2",
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "dropdown",
                "variable": "",
                "dataType": "",
                "id": "dropdown0000000002",
                "name": "dropdown0000000002",
                "label": "dropdown_2",
                "defaultValue": "",
                "hint": "",
                "required": false,
                "dependentFields": [],
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [
                  {
                    "value": "un",
                    "label": "primero"
                  },
                  {
                    "value": "dos",
                    "label": "segundo"
                  },
                  {
                    "value": "tres",
                    "label": "tercero"
                  }
                ],
                "columnWidth": "",
                "width": 100,
                "title": "dropdown_2",
                "data": {
                  "value": "un",
                  "label": "primero"
                }
              },
              {
                "type": "checkbox",
                "variable": "",
                "dataType": "",
                "id": "checkbox0000000003",
                "name": "checkbox0000000003",
                "label": "checkbox_2",
                "defaultValue": "",
                "hint": "",
                "required": false,
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "columnWidth": "",
                "width": 100,
                "title": "checkbox_2",
                "data": {
                  "value": [],
                  "label": "[]"
                }
              },
              {
                "type": "datetime",
                "variable": "",
                "dataType": "",
                "id": "datetime0000000002",
                "name": "datetime0000000002",
                "label": "datetime_1",
                "placeholder": "",
                "hint": "",
                "required": false,
                "mode": "parent",
                "format": "YYYY-MM-DD",
                "dayViewHeaderFormat": "MMMM YYYY",
                "extraFormats": false,
                "stepping": 1,
                "minDate": "",
                "maxDate": "",
                "useCurrent": "true",
                "collapse": true,
                "locale": "",
                "defaultDate": "",
                "disabledDates": false,
                "enabledDates": false,
                "icons": {
                  "time": "glyphicon glyphicon-time",
                  "date": "glyphicon glyphicon-calendar",
                  "up": "glyphicon glyphicon-chevron-up",
                  "down": "glyphicon glyphicon-chevron-down",
                  "previous": "glyphicon glyphicon-chevron-left",
                  "next": "glyphicon glyphicon-chevron-right",
                  "today": "glyphicon glyphicon-screenshot",
                  "clear": "glyphicon glyphicon-trash"
                },
                "useStrict": false,
                "sideBySide": false,
                "daysOfWeekDisabled": [],
                "calendarWeeks": false,
                "viewMode": "days",
                "toolbarPlacement": "default",
                "showTodayButton": false,
                "showClear": "false",
                "widgetPositioning": {
                  "horizontal": "auto",
                  "vertical": "auto"
                },
                "widgetParent": null,
                "keepOpen": false,
                "columnWidth": "",
                "width": 100,
                "title": "datetime_1",
                "data": {
                  "value": "",
                  "label": ""
                },
                "dbConnection": "none",
                "sql": "",
                "options": []
              },
              {
                "type": "hidden",
                "variable": "",
                "dataType": "",
                "id": "hidden0000000001",
                "name": "hidden0000000001",
                "defaultValue": "richard",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "file",
                "id": "file0000000001",
                "name": "file0000000001",
                "label": "file_1",
                "hint": "",
                "required": false,
                "dnd": false,
                "extensions": "*",
                "size": 1024,
                "sizeUnity": "KB",
                "mode": "parent",
                "multiple": false,
                "columnWidth": "",
                "width": 100,
                "title": "file_1"
              },
              {
                "type": "link",
                "id": "link0000000001",
                "name": "link0000000001",
                "label": "link_1",
                "value": "google",
                "defaultValue": "",
                "href": "http://www.google.com/",
                "hint": "",
                "columnWidth": "",
                "width": 100,
                "title": "link_1"
              },
              {
                "type": "hidden",
                "variable": "",
                "dataType": "",
                "id": "hidden0000000002",
                "name": "hidden0000000002",
                "defaultValue": "",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "data": {
                  "value": "",
                  "label": ""
                }
              }
            ],
            "data": [],
            "mode": "parent",
            "layout": "static",
            "pageSize": "5",
            "addRow": true,
            "deleteRow": true,
            "title": "grid_1",
            "colSpan": 12,
            rows : 100
          }
        ],
        [
          {
            "type": "submit",
            "id": "submit0000000001",
            "name": "submit0000000001",
            "label": "submit_1",
            "colSpan": 12
          }
        ]
      ],
      "variables": []
    }
  ]
}
/*data = {
  "name": "edit_form",
  "description": "",
  "items": [
    {
      "type": "form",
      "id": "229723097555a11781ed309062592560",
      "name": "edit_form",
      "description": "",
      "mode": "edit",
      "script": {
        "type": "js",
        "code": ""
      },
      "language": "en",
      "externalLibs": "",
      "items": [
        [
          {
            "type": "title",
            "id": "title0000000001",
            "name": "title0000000001",
            "label": "Este es un titulo",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "subtitle",
            "id": "subtitle0000000001",
            "name": "subtitle0000000001",
            "label": "Este es un Subtitulo",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "dropdown",
            "variable": "COUNTRY",
            "dataType": "string",
            "id": "COUNTRY",
            "name": "COUNTRY",
            "label": "dropdown_3",
            "defaultValue": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
            "options": [
              {
                "label": "Andorra",
                "value": "AD"
              },
              {
                "label": "United Arab Emirates",
                "value": "AE"
              },
              {
                "label": "Afghanistan",
                "value": "AF"
              },
              {
                "label": "Antigua and Barbuda",
                "value": "AG"
              },
              {
                "label": "Anguilla",
                "value": "AI"
              },
              {
                "label": "Albania",
                "value": "AL"
              },
              {
                "label": "Armenia",
                "value": "AM"
              },
              {
                "label": "Netherlands Antilles",
                "value": "AN"
              },
              {
                "label": "Angola",
                "value": "AO"
              },
              {
                "label": "Antarctica",
                "value": "AQ"
              },
              {
                "label": "Argentina",
                "value": "AR"
              },
              {
                "label": "American Samoa",
                "value": "AS"
              },
              {
                "label": "Austria",
                "value": "AT"
              },
              {
                "label": "Australia",
                "value": "AU"
              },
              {
                "label": "Aruba",
                "value": "AW"
              },
              {
                "label": "Azerbaijan",
                "value": "AZ"
              },
              {
                "label": "Bosnia and Herzegovina",
                "value": "BA"
              },
              {
                "label": "Barbados",
                "value": "BB"
              },
              {
                "label": "Bangladesh",
                "value": "BD"
              },
              {
                "label": "Belgium",
                "value": "BE"
              },
              {
                "label": "Burkina Faso",
                "value": "BF"
              },
              {
                "label": "Bulgaria",
                "value": "BG"
              },
              {
                "label": "Bahrain",
                "value": "BH"
              },
              {
                "label": "Burundi",
                "value": "BI"
              },
              {
                "label": "Benin",
                "value": "BJ"
              },
              {
                "label": "Bermuda",
                "value": "BM"
              },
              {
                "label": "Brunei Darussalam",
                "value": "BN"
              },
              {
                "label": "Bolivia",
                "value": "BO"
              },
              {
                "label": "Brazil",
                "value": "BR"
              },
              {
                "label": "Bahamas",
                "value": "BS"
              },
              {
                "label": "Bhutan",
                "value": "BT"
              },
              {
                "label": "Botswana",
                "value": "BW"
              },
              {
                "label": "Belarus",
                "value": "BY"
              },
              {
                "label": "Belize",
                "value": "BZ"
              },
              {
                "label": "Canada",
                "value": "CA"
              },
              {
                "label": "Cocos (Keeling) Islands",
                "value": "CC"
              },
              {
                "label": "Congo, The Democratic Republic of the",
                "value": "CD"
              },
              {
                "label": "Central African Republic",
                "value": "CF"
              },
              {
                "label": "Congo",
                "value": "CG"
              },
              {
                "label": "Switzerland",
                "value": "CH"
              },
              {
                "label": "Côte-d' lvoire",
                "value": "CI"
              },
              {
                "label": "Cook Islands",
                "value": "CK"
              },
              {
                "label": "Chile",
                "value": "CL"
              },
              {
                "label": "Cameroon",
                "value": "CM"
              },
              {
                "label": "China",
                "value": "CN"
              },
              {
                "label": "Colombia",
                "value": "CO"
              },
              {
                "label": "Costa Rica",
                "value": "CR"
              },
              {
                "label": "Serbia and Montenegro",
                "value": "CS"
              },
              {
                "label": "Cuba",
                "value": "CU"
              },
              {
                "label": "Cape Verde",
                "value": "CV"
              },
              {
                "label": "Christmas Island",
                "value": "CX"
              },
              {
                "label": "Cyprus",
                "value": "CY"
              },
              {
                "label": "Czech Republic",
                "value": "CZ"
              },
              {
                "label": "Germany",
                "value": "DE"
              },
              {
                "label": "Djibouti",
                "value": "DJ"
              },
              {
                "label": "Denmark",
                "value": "DK"
              },
              {
                "label": "Dominica",
                "value": "DM"
              },
              {
                "label": "Dominican Republic",
                "value": "DO"
              },
              {
                "label": "Algeria",
                "value": "DZ"
              },
              {
                "label": "Ecuador",
                "value": "EC"
              },
              {
                "label": "Estonia",
                "value": "EE"
              },
              {
                "label": "Egypt",
                "value": "EG"
              },
              {
                "label": "Western Sahara",
                "value": "EH"
              },
              {
                "label": "Eritrea",
                "value": "ER"
              },
              {
                "label": "Spain",
                "value": "ES"
              },
              {
                "label": "Ethiopia",
                "value": "ET"
              },
              {
                "label": "Finland",
                "value": "FI"
              },
              {
                "label": "Fiji",
                "value": "FJ"
              },
              {
                "label": "Falkland Islands (Malvinas)",
                "value": "FK"
              },
              {
                "label": "Micronesia, Federated States of",
                "value": "FM"
              },
              {
                "label": "Faroe Islands",
                "value": "FO"
              },
              {
                "label": "France",
                "value": "FR"
              },
              {
                "label": "Gabon",
                "value": "GA"
              },
              {
                "label": "United Kingdom",
                "value": "GB"
              },
              {
                "label": "Grenada",
                "value": "GD"
              },
              {
                "label": "Georgia",
                "value": "GE"
              },
              {
                "label": "French Guiana",
                "value": "GF"
              },
              {
                "label": "Guernsey",
                "value": "GG"
              },
              {
                "label": "Ghana",
                "value": "GH"
              },
              {
                "label": "Gibraltar",
                "value": "GI"
              },
              {
                "label": "Greenland",
                "value": "GL"
              },
              {
                "label": "Gambia",
                "value": "GM"
              },
              {
                "label": "Guinea",
                "value": "GN"
              },
              {
                "label": "Guadeloupe",
                "value": "GP"
              },
              {
                "label": "Equatorial Guinea",
                "value": "GQ"
              },
              {
                "label": "Greece",
                "value": "GR"
              },
              {
                "label": "South Georgia and the South Sandwich Islands",
                "value": "GS"
              },
              {
                "label": "Guatemala",
                "value": "GT"
              },
              {
                "label": "Guam",
                "value": "GU"
              },
              {
                "label": "Guinea-Bissau",
                "value": "GW"
              },
              {
                "label": "Guyana",
                "value": "GY"
              },
              {
                "label": "Hong Kong",
                "value": "HK"
              },
              {
                "label": "Heard Island and McDonald Islands",
                "value": "HM"
              },
              {
                "label": "Honduras",
                "value": "HN"
              },
              {
                "label": "Croatia",
                "value": "HR"
              },
              {
                "label": "Haiti",
                "value": "HT"
              },
              {
                "label": "Hungary",
                "value": "HU"
              },
              {
                "label": "Indonesia",
                "value": "ID"
              },
              {
                "label": "Ireland",
                "value": "IE"
              },
              {
                "label": "Israel",
                "value": "IL"
              },
              {
                "label": "Isle of Man",
                "value": "IM"
              },
              {
                "label": "India",
                "value": "IN"
              },
              {
                "label": "British Indian Ocean Territory",
                "value": "IO"
              },
              {
                "label": "Iraq",
                "value": "IQ"
              },
              {
                "label": "Iran, Islamic Republic of",
                "value": "IR"
              },
              {
                "label": "Iceland",
                "value": "IS"
              },
              {
                "label": "Italy",
                "value": "IT"
              },
              {
                "label": "Jersey",
                "value": "JE"
              },
              {
                "label": "Jamaica",
                "value": "JM"
              },
              {
                "label": "Jordan",
                "value": "JO"
              },
              {
                "label": "Japan",
                "value": "JP"
              },
              {
                "label": "Kenya",
                "value": "KE"
              },
              {
                "label": "Kyrgyzstan",
                "value": "KG"
              },
              {
                "label": "Cambodia",
                "value": "KH"
              },
              {
                "label": "Kiribati",
                "value": "KI"
              },
              {
                "label": "Comoros",
                "value": "KM"
              },
              {
                "label": "Saint Kitts and Nevis",
                "value": "KN"
              },
              {
                "label": "Korea, Democratic People's Republic of",
                "value": "KP"
              },
              {
                "label": "Korea, Republic of",
                "value": "KR"
              },
              {
                "label": "Kuwait",
                "value": "KW"
              },
              {
                "label": "Cayman Islands",
                "value": "KY"
              },
              {
                "label": "Kazakhstan",
                "value": "KZ"
              },
              {
                "label": "Lao People's Democratic Republic",
                "value": "LA"
              },
              {
                "label": "Lebanon",
                "value": "LB"
              },
              {
                "label": "Saint Lucia",
                "value": "LC"
              },
              {
                "label": "Liechtenstein",
                "value": "LI"
              },
              {
                "label": "Sri Lanka",
                "value": "LK"
              },
              {
                "label": "Liberia",
                "value": "LR"
              },
              {
                "label": "Lesotho",
                "value": "LS"
              },
              {
                "label": "Lithuania",
                "value": "LT"
              },
              {
                "label": "Luxembourg",
                "value": "LU"
              },
              {
                "label": "Latvia",
                "value": "LV"
              },
              {
                "label": "Libyan Arab Jamahiriya",
                "value": "LY"
              },
              {
                "label": "Morocco",
                "value": "MA"
              },
              {
                "label": "Monaco",
                "value": "MC"
              },
              {
                "label": "Moldova, Republic of",
                "value": "MD"
              },
              {
                "label": "Montenegro",
                "value": "ME"
              },
              {
                "label": "Madagascar",
                "value": "MG"
              },
              {
                "label": "Marshall Islands",
                "value": "MH"
              },
              {
                "label": "Macedonia, The former Yugoslav Republic of",
                "value": "MK"
              },
              {
                "label": "Mali",
                "value": "ML"
              },
              {
                "label": "Myanmar",
                "value": "MM"
              },
              {
                "label": "Mongolia",
                "value": "MN"
              },
              {
                "label": "Macao",
                "value": "MO"
              },
              {
                "label": "Northern Mariana Islands",
                "value": "MP"
              },
              {
                "label": "Martinique",
                "value": "MQ"
              },
              {
                "label": "Mauritania",
                "value": "MR"
              },
              {
                "label": "Montserrat",
                "value": "MS"
              },
              {
                "label": "Malta",
                "value": "MT"
              },
              {
                "label": "Mauritius",
                "value": "MU"
              },
              {
                "label": "Maldives",
                "value": "MV"
              },
              {
                "label": "Malawi",
                "value": "MW"
              },
              {
                "label": "Mexico",
                "value": "MX"
              },
              {
                "label": "Malaysia",
                "value": "MY"
              },
              {
                "label": "Mozambique",
                "value": "MZ"
              },
              {
                "label": "Namibia",
                "value": "NA"
              },
              {
                "label": "New Caledonia",
                "value": "NC"
              },
              {
                "label": "Niger",
                "value": "NE"
              },
              {
                "label": "Norfolk Island",
                "value": "NF"
              },
              {
                "label": "Nigeria",
                "value": "NG"
              },
              {
                "label": "Nicaragua",
                "value": "NI"
              },
              {
                "label": "Netherlands",
                "value": "NL"
              },
              {
                "label": "Norway",
                "value": "NO"
              },
              {
                "label": "Nepal",
                "value": "NP"
              },
              {
                "label": "Nauru",
                "value": "NR"
              },
              {
                "label": "Niue",
                "value": "NU"
              },
              {
                "label": "New Zealand",
                "value": "NZ"
              },
              {
                "label": "Oman",
                "value": "OM"
              },
              {
                "label": "Panama",
                "value": "PA"
              },
              {
                "label": "Peru",
                "value": "PE"
              },
              {
                "label": "French Polynesia",
                "value": "PF"
              },
              {
                "label": "Papua New Guinea",
                "value": "PG"
              },
              {
                "label": "Philippines",
                "value": "PH"
              },
              {
                "label": "Pakistan",
                "value": "PK"
              },
              {
                "label": "Poland",
                "value": "PL"
              },
              {
                "label": "Saint Pierre and Miquelon",
                "value": "PM"
              },
              {
                "label": "Pitcairn",
                "value": "PN"
              },
              {
                "label": "Puerto Rico",
                "value": "PR"
              },
              {
                "label": "Portugal",
                "value": "PT"
              },
              {
                "label": "Palau",
                "value": "PW"
              },
              {
                "label": "Paraguay",
                "value": "PY"
              },
              {
                "label": "Qatar",
                "value": "QA"
              },
              {
                "label": "Reunion",
                "value": "RE"
              },
              {
                "label": "Romania",
                "value": "RO"
              },
              {
                "label": "Serbia",
                "value": "RS"
              },
              {
                "label": "Russian Federation",
                "value": "RU"
              },
              {
                "label": "Rwanda",
                "value": "RW"
              },
              {
                "label": "Saudi Arabia",
                "value": "SA"
              },
              {
                "label": "Solomon Islands",
                "value": "SB"
              },
              {
                "label": "Seychelles",
                "value": "SC"
              },
              {
                "label": "Sudan",
                "value": "SD"
              },
              {
                "label": "Sweden",
                "value": "SE"
              },
              {
                "label": "Singapore",
                "value": "SG"
              },
              {
                "label": "Saint Helena",
                "value": "SH"
              },
              {
                "label": "Slovenia",
                "value": "SI"
              },
              {
                "label": "Svalbard and Jan Mayen",
                "value": "SJ"
              },
              {
                "label": "Slovakia",
                "value": "SK"
              },
              {
                "label": "Sierra Leone",
                "value": "SL"
              },
              {
                "label": "San Marino",
                "value": "SM"
              },
              {
                "label": "Senegal",
                "value": "SN"
              },
              {
                "label": "Somalia",
                "value": "SO"
              },
              {
                "label": "Suriname",
                "value": "SR"
              },
              {
                "label": "Sao Tome and Principe",
                "value": "ST"
              },
              {
                "label": "El Salvador",
                "value": "SV"
              },
              {
                "label": "Syrian Arab Republic",
                "value": "SY"
              },
              {
                "label": "Swaziland",
                "value": "SZ"
              },
              {
                "label": "Turks and Caicos Islands",
                "value": "TC"
              },
              {
                "label": "Chad",
                "value": "TD"
              },
              {
                "label": "French Southern Territories",
                "value": "TF"
              },
              {
                "label": "Togo",
                "value": "TG"
              },
              {
                "label": "Thailand",
                "value": "TH"
              },
              {
                "label": "Tajikistan",
                "value": "TJ"
              },
              {
                "label": "Tokelau",
                "value": "TK"
              },
              {
                "label": "Timor-Leste",
                "value": "TL"
              },
              {
                "label": "Turkmenistan",
                "value": "TM"
              },
              {
                "label": "Tunisia",
                "value": "TN"
              },
              {
                "label": "Tonga",
                "value": "TO"
              },
              {
                "label": "Turkey",
                "value": "TR"
              },
              {
                "label": "Trinidad and Tobago",
                "value": "TT"
              },
              {
                "label": "Tuvalu",
                "value": "TV"
              },
              {
                "label": "Taiwan, Province of China",
                "value": "TW"
              },
              {
                "label": "Tanzania, United Republic of",
                "value": "TZ"
              },
              {
                "label": "Ukraine",
                "value": "UA"
              },
              {
                "label": "Uganda",
                "value": "UG"
              },
              {
                "label": "United States Minor Outlying Islands",
                "value": "UM"
              },
              {
                "label": "United States",
                "value": "US"
              },
              {
                "label": "Uruguay",
                "value": "UY"
              },
              {
                "label": "Uzbekistan",
                "value": "UZ"
              },
              {
                "label": "Holy See (Vatican City State)",
                "value": "VA"
              },
              {
                "label": "Saint Vincent and the Grenadines",
                "value": "VC"
              },
              {
                "label": "Venezuela",
                "value": "VE"
              },
              {
                "label": "Virgin Islands, British",
                "value": "VG"
              },
              {
                "label": "Virgin Islands, U.S.",
                "value": "VI"
              },
              {
                "label": "Viet Nam",
                "value": "VN"
              },
              {
                "label": "Vanuatu",
                "value": "VU"
              },
              {
                "label": "Wallis and Futuna",
                "value": "WF"
              },
              {
                "label": "Samoa",
                "value": "WS"
              },
              {
                "label": "Installations in International Waters",
                "value": "XZ"
              },
              {
                "label": "Yemen",
                "value": "YE"
              },
              {
                "label": "Mayotte",
                "value": "YT"
              },
              {
                "label": "South Africa",
                "value": "ZA"
              },
              {
                "label": "Zambia",
                "value": "ZM"
              },
              {
                "label": "Zimbabwe",
                "value": "ZW"
              }
            ],
            "var_uid": "7790396875589e409d29cd4091845454",
            "var_name": "COUNTRY",
            "colSpan": 12,
            "data": {
              "value": "AD",
              "label": "Andorra"
            }
          }
        ],
        [
          {
            "type": "suggest",
            "variable": "STATE",
            "dataType": "string",
            "id": "STATE",
            "name": "STATE",
            "label": "suggest_3",
            "defaultValue": "",
            "placeholder": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "SELECT IS_UID, IS_NAME FROM ISO_SUBDIVISION WHERE IC_UID = \"@#COUNTRY\" ORDER BY IS_NAME",
            "options": [],
            "var_uid": "9435841245589e42e222f26030564550",
            "var_name": "STATE",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            }
          }
        ],
        [
          {
            "type": "text",
            "variable": "LOCATION",
            "dataType": "string",
            "id": "LOCATION",
            "name": "LOCATION",
            "label": "text_3",
            "defaultValue": "",
            "placeholder": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "textTransform": "none",
            "validate": "",
            "validateMessage": "",
            "maxLength": 1000,
            "formula": "",
            "mode": "parent",
            "operation": "",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "SELECT IL_UID, IL_NAME FROM ISO_LOCATION WHERE IC_UID = \"@#COUNTRY\" AND IS_UID = \"@#STATE\"",
            "options": [],
            "var_uid": "4196090065589e43e5240c8035150545",
            "var_name": "LOCATION",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            }
          }
        ],
        [
          {
            "type": "suggest",
            "variable": "suggest_1",
            "dataType": "string",
            "id": "suggest_1",
            "name": "suggest_1",
            "label": "suggest_1",
            "defaultValue": "",
            "placeholder": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
            "options": [
              {
                "label": "Andorra",
                "value": "AD"
              },
              {
                "label": "United Arab Emirates",
                "value": "AE"
              },
              {
                "label": "Afghanistan",
                "value": "AF"
              },
              {
                "label": "Antigua and Barbuda",
                "value": "AG"
              },
              {
                "label": "Anguilla",
                "value": "AI"
              },
              {
                "label": "Albania",
                "value": "AL"
              },
              {
                "label": "Armenia",
                "value": "AM"
              },
              {
                "label": "Netherlands Antilles",
                "value": "AN"
              },
              {
                "label": "Angola",
                "value": "AO"
              },
              {
                "label": "Antarctica",
                "value": "AQ"
              },
              {
                "label": "Argentina",
                "value": "AR"
              },
              {
                "label": "American Samoa",
                "value": "AS"
              },
              {
                "label": "Austria",
                "value": "AT"
              },
              {
                "label": "Australia",
                "value": "AU"
              },
              {
                "label": "Aruba",
                "value": "AW"
              },
              {
                "label": "Azerbaijan",
                "value": "AZ"
              },
              {
                "label": "Bosnia and Herzegovina",
                "value": "BA"
              },
              {
                "label": "Barbados",
                "value": "BB"
              },
              {
                "label": "Bangladesh",
                "value": "BD"
              },
              {
                "label": "Belgium",
                "value": "BE"
              },
              {
                "label": "Burkina Faso",
                "value": "BF"
              },
              {
                "label": "Bulgaria",
                "value": "BG"
              },
              {
                "label": "Bahrain",
                "value": "BH"
              },
              {
                "label": "Burundi",
                "value": "BI"
              },
              {
                "label": "Benin",
                "value": "BJ"
              },
              {
                "label": "Bermuda",
                "value": "BM"
              },
              {
                "label": "Brunei Darussalam",
                "value": "BN"
              },
              {
                "label": "Bolivia",
                "value": "BO"
              },
              {
                "label": "Brazil",
                "value": "BR"
              },
              {
                "label": "Bahamas",
                "value": "BS"
              },
              {
                "label": "Bhutan",
                "value": "BT"
              },
              {
                "label": "Botswana",
                "value": "BW"
              },
              {
                "label": "Belarus",
                "value": "BY"
              },
              {
                "label": "Belize",
                "value": "BZ"
              },
              {
                "label": "Canada",
                "value": "CA"
              },
              {
                "label": "Cocos (Keeling) Islands",
                "value": "CC"
              },
              {
                "label": "Congo, The Democratic Republic of the",
                "value": "CD"
              },
              {
                "label": "Central African Republic",
                "value": "CF"
              },
              {
                "label": "Congo",
                "value": "CG"
              },
              {
                "label": "Switzerland",
                "value": "CH"
              },
              {
                "label": "Côte-d' lvoire",
                "value": "CI"
              },
              {
                "label": "Cook Islands",
                "value": "CK"
              },
              {
                "label": "Chile",
                "value": "CL"
              },
              {
                "label": "Cameroon",
                "value": "CM"
              },
              {
                "label": "China",
                "value": "CN"
              },
              {
                "label": "Colombia",
                "value": "CO"
              },
              {
                "label": "Costa Rica",
                "value": "CR"
              },
              {
                "label": "Serbia and Montenegro",
                "value": "CS"
              },
              {
                "label": "Cuba",
                "value": "CU"
              },
              {
                "label": "Cape Verde",
                "value": "CV"
              },
              {
                "label": "Christmas Island",
                "value": "CX"
              },
              {
                "label": "Cyprus",
                "value": "CY"
              },
              {
                "label": "Czech Republic",
                "value": "CZ"
              },
              {
                "label": "Germany",
                "value": "DE"
              },
              {
                "label": "Djibouti",
                "value": "DJ"
              },
              {
                "label": "Denmark",
                "value": "DK"
              },
              {
                "label": "Dominica",
                "value": "DM"
              },
              {
                "label": "Dominican Republic",
                "value": "DO"
              },
              {
                "label": "Algeria",
                "value": "DZ"
              },
              {
                "label": "Ecuador",
                "value": "EC"
              },
              {
                "label": "Estonia",
                "value": "EE"
              },
              {
                "label": "Egypt",
                "value": "EG"
              },
              {
                "label": "Western Sahara",
                "value": "EH"
              },
              {
                "label": "Eritrea",
                "value": "ER"
              },
              {
                "label": "Spain",
                "value": "ES"
              },
              {
                "label": "Ethiopia",
                "value": "ET"
              },
              {
                "label": "Finland",
                "value": "FI"
              },
              {
                "label": "Fiji",
                "value": "FJ"
              },
              {
                "label": "Falkland Islands (Malvinas)",
                "value": "FK"
              },
              {
                "label": "Micronesia, Federated States of",
                "value": "FM"
              },
              {
                "label": "Faroe Islands",
                "value": "FO"
              },
              {
                "label": "France",
                "value": "FR"
              },
              {
                "label": "Gabon",
                "value": "GA"
              },
              {
                "label": "United Kingdom",
                "value": "GB"
              },
              {
                "label": "Grenada",
                "value": "GD"
              },
              {
                "label": "Georgia",
                "value": "GE"
              },
              {
                "label": "French Guiana",
                "value": "GF"
              },
              {
                "label": "Guernsey",
                "value": "GG"
              },
              {
                "label": "Ghana",
                "value": "GH"
              },
              {
                "label": "Gibraltar",
                "value": "GI"
              },
              {
                "label": "Greenland",
                "value": "GL"
              },
              {
                "label": "Gambia",
                "value": "GM"
              },
              {
                "label": "Guinea",
                "value": "GN"
              },
              {
                "label": "Guadeloupe",
                "value": "GP"
              },
              {
                "label": "Equatorial Guinea",
                "value": "GQ"
              },
              {
                "label": "Greece",
                "value": "GR"
              },
              {
                "label": "South Georgia and the South Sandwich Islands",
                "value": "GS"
              },
              {
                "label": "Guatemala",
                "value": "GT"
              },
              {
                "label": "Guam",
                "value": "GU"
              },
              {
                "label": "Guinea-Bissau",
                "value": "GW"
              },
              {
                "label": "Guyana",
                "value": "GY"
              },
              {
                "label": "Hong Kong",
                "value": "HK"
              },
              {
                "label": "Heard Island and McDonald Islands",
                "value": "HM"
              },
              {
                "label": "Honduras",
                "value": "HN"
              },
              {
                "label": "Croatia",
                "value": "HR"
              },
              {
                "label": "Haiti",
                "value": "HT"
              },
              {
                "label": "Hungary",
                "value": "HU"
              },
              {
                "label": "Indonesia",
                "value": "ID"
              },
              {
                "label": "Ireland",
                "value": "IE"
              },
              {
                "label": "Israel",
                "value": "IL"
              },
              {
                "label": "Isle of Man",
                "value": "IM"
              },
              {
                "label": "India",
                "value": "IN"
              },
              {
                "label": "British Indian Ocean Territory",
                "value": "IO"
              },
              {
                "label": "Iraq",
                "value": "IQ"
              },
              {
                "label": "Iran, Islamic Republic of",
                "value": "IR"
              },
              {
                "label": "Iceland",
                "value": "IS"
              },
              {
                "label": "Italy",
                "value": "IT"
              },
              {
                "label": "Jersey",
                "value": "JE"
              },
              {
                "label": "Jamaica",
                "value": "JM"
              },
              {
                "label": "Jordan",
                "value": "JO"
              },
              {
                "label": "Japan",
                "value": "JP"
              },
              {
                "label": "Kenya",
                "value": "KE"
              },
              {
                "label": "Kyrgyzstan",
                "value": "KG"
              },
              {
                "label": "Cambodia",
                "value": "KH"
              },
              {
                "label": "Kiribati",
                "value": "KI"
              },
              {
                "label": "Comoros",
                "value": "KM"
              },
              {
                "label": "Saint Kitts and Nevis",
                "value": "KN"
              },
              {
                "label": "Korea, Democratic People's Republic of",
                "value": "KP"
              },
              {
                "label": "Korea, Republic of",
                "value": "KR"
              },
              {
                "label": "Kuwait",
                "value": "KW"
              },
              {
                "label": "Cayman Islands",
                "value": "KY"
              },
              {
                "label": "Kazakhstan",
                "value": "KZ"
              },
              {
                "label": "Lao People's Democratic Republic",
                "value": "LA"
              },
              {
                "label": "Lebanon",
                "value": "LB"
              },
              {
                "label": "Saint Lucia",
                "value": "LC"
              },
              {
                "label": "Liechtenstein",
                "value": "LI"
              },
              {
                "label": "Sri Lanka",
                "value": "LK"
              },
              {
                "label": "Liberia",
                "value": "LR"
              },
              {
                "label": "Lesotho",
                "value": "LS"
              },
              {
                "label": "Lithuania",
                "value": "LT"
              },
              {
                "label": "Luxembourg",
                "value": "LU"
              },
              {
                "label": "Latvia",
                "value": "LV"
              },
              {
                "label": "Libyan Arab Jamahiriya",
                "value": "LY"
              },
              {
                "label": "Morocco",
                "value": "MA"
              },
              {
                "label": "Monaco",
                "value": "MC"
              },
              {
                "label": "Moldova, Republic of",
                "value": "MD"
              },
              {
                "label": "Montenegro",
                "value": "ME"
              },
              {
                "label": "Madagascar",
                "value": "MG"
              },
              {
                "label": "Marshall Islands",
                "value": "MH"
              },
              {
                "label": "Macedonia, The former Yugoslav Republic of",
                "value": "MK"
              },
              {
                "label": "Mali",
                "value": "ML"
              },
              {
                "label": "Myanmar",
                "value": "MM"
              },
              {
                "label": "Mongolia",
                "value": "MN"
              },
              {
                "label": "Macao",
                "value": "MO"
              },
              {
                "label": "Northern Mariana Islands",
                "value": "MP"
              },
              {
                "label": "Martinique",
                "value": "MQ"
              },
              {
                "label": "Mauritania",
                "value": "MR"
              },
              {
                "label": "Montserrat",
                "value": "MS"
              },
              {
                "label": "Malta",
                "value": "MT"
              },
              {
                "label": "Mauritius",
                "value": "MU"
              },
              {
                "label": "Maldives",
                "value": "MV"
              },
              {
                "label": "Malawi",
                "value": "MW"
              },
              {
                "label": "Mexico",
                "value": "MX"
              },
              {
                "label": "Malaysia",
                "value": "MY"
              },
              {
                "label": "Mozambique",
                "value": "MZ"
              },
              {
                "label": "Namibia",
                "value": "NA"
              },
              {
                "label": "New Caledonia",
                "value": "NC"
              },
              {
                "label": "Niger",
                "value": "NE"
              },
              {
                "label": "Norfolk Island",
                "value": "NF"
              },
              {
                "label": "Nigeria",
                "value": "NG"
              },
              {
                "label": "Nicaragua",
                "value": "NI"
              },
              {
                "label": "Netherlands",
                "value": "NL"
              },
              {
                "label": "Norway",
                "value": "NO"
              },
              {
                "label": "Nepal",
                "value": "NP"
              },
              {
                "label": "Nauru",
                "value": "NR"
              },
              {
                "label": "Niue",
                "value": "NU"
              },
              {
                "label": "New Zealand",
                "value": "NZ"
              },
              {
                "label": "Oman",
                "value": "OM"
              },
              {
                "label": "Panama",
                "value": "PA"
              },
              {
                "label": "Peru",
                "value": "PE"
              },
              {
                "label": "French Polynesia",
                "value": "PF"
              },
              {
                "label": "Papua New Guinea",
                "value": "PG"
              },
              {
                "label": "Philippines",
                "value": "PH"
              },
              {
                "label": "Pakistan",
                "value": "PK"
              },
              {
                "label": "Poland",
                "value": "PL"
              },
              {
                "label": "Saint Pierre and Miquelon",
                "value": "PM"
              },
              {
                "label": "Pitcairn",
                "value": "PN"
              },
              {
                "label": "Puerto Rico",
                "value": "PR"
              },
              {
                "label": "Portugal",
                "value": "PT"
              },
              {
                "label": "Palau",
                "value": "PW"
              },
              {
                "label": "Paraguay",
                "value": "PY"
              },
              {
                "label": "Qatar",
                "value": "QA"
              },
              {
                "label": "Reunion",
                "value": "RE"
              },
              {
                "label": "Romania",
                "value": "RO"
              },
              {
                "label": "Serbia",
                "value": "RS"
              },
              {
                "label": "Russian Federation",
                "value": "RU"
              },
              {
                "label": "Rwanda",
                "value": "RW"
              },
              {
                "label": "Saudi Arabia",
                "value": "SA"
              },
              {
                "label": "Solomon Islands",
                "value": "SB"
              },
              {
                "label": "Seychelles",
                "value": "SC"
              },
              {
                "label": "Sudan",
                "value": "SD"
              },
              {
                "label": "Sweden",
                "value": "SE"
              },
              {
                "label": "Singapore",
                "value": "SG"
              },
              {
                "label": "Saint Helena",
                "value": "SH"
              },
              {
                "label": "Slovenia",
                "value": "SI"
              },
              {
                "label": "Svalbard and Jan Mayen",
                "value": "SJ"
              },
              {
                "label": "Slovakia",
                "value": "SK"
              },
              {
                "label": "Sierra Leone",
                "value": "SL"
              },
              {
                "label": "San Marino",
                "value": "SM"
              },
              {
                "label": "Senegal",
                "value": "SN"
              },
              {
                "label": "Somalia",
                "value": "SO"
              },
              {
                "label": "Suriname",
                "value": "SR"
              },
              {
                "label": "Sao Tome and Principe",
                "value": "ST"
              },
              {
                "label": "El Salvador",
                "value": "SV"
              },
              {
                "label": "Syrian Arab Republic",
                "value": "SY"
              },
              {
                "label": "Swaziland",
                "value": "SZ"
              },
              {
                "label": "Turks and Caicos Islands",
                "value": "TC"
              },
              {
                "label": "Chad",
                "value": "TD"
              },
              {
                "label": "French Southern Territories",
                "value": "TF"
              },
              {
                "label": "Togo",
                "value": "TG"
              },
              {
                "label": "Thailand",
                "value": "TH"
              },
              {
                "label": "Tajikistan",
                "value": "TJ"
              },
              {
                "label": "Tokelau",
                "value": "TK"
              },
              {
                "label": "Timor-Leste",
                "value": "TL"
              },
              {
                "label": "Turkmenistan",
                "value": "TM"
              },
              {
                "label": "Tunisia",
                "value": "TN"
              },
              {
                "label": "Tonga",
                "value": "TO"
              },
              {
                "label": "Turkey",
                "value": "TR"
              },
              {
                "label": "Trinidad and Tobago",
                "value": "TT"
              },
              {
                "label": "Tuvalu",
                "value": "TV"
              },
              {
                "label": "Taiwan, Province of China",
                "value": "TW"
              },
              {
                "label": "Tanzania, United Republic of",
                "value": "TZ"
              },
              {
                "label": "Ukraine",
                "value": "UA"
              },
              {
                "label": "Uganda",
                "value": "UG"
              },
              {
                "label": "United States Minor Outlying Islands",
                "value": "UM"
              },
              {
                "label": "United States",
                "value": "US"
              },
              {
                "label": "Uruguay",
                "value": "UY"
              },
              {
                "label": "Uzbekistan",
                "value": "UZ"
              },
              {
                "label": "Holy See (Vatican City State)",
                "value": "VA"
              },
              {
                "label": "Saint Vincent and the Grenadines",
                "value": "VC"
              },
              {
                "label": "Venezuela",
                "value": "VE"
              },
              {
                "label": "Virgin Islands, British",
                "value": "VG"
              },
              {
                "label": "Virgin Islands, U.S.",
                "value": "VI"
              },
              {
                "label": "Viet Nam",
                "value": "VN"
              },
              {
                "label": "Vanuatu",
                "value": "VU"
              },
              {
                "label": "Wallis and Futuna",
                "value": "WF"
              },
              {
                "label": "Samoa",
                "value": "WS"
              },
              {
                "label": "Installations in International Waters",
                "value": "XZ"
              },
              {
                "label": "Yemen",
                "value": "YE"
              },
              {
                "label": "Mayotte",
                "value": "YT"
              },
              {
                "label": "South Africa",
                "value": "ZA"
              },
              {
                "label": "Zambia",
                "value": "ZM"
              },
              {
                "label": "Zimbabwe",
                "value": "ZW"
              }
            ],
            "var_uid": "698967615555ce7261c58c0021226847",
            "var_name": "suggest_1",
            "colSpan": 12,
            "data": {
              "value": "AD",
              "label": "Andorra"
            }
          }
        ],
        [
          {
            "type": "annotation",
            "id": "annotation0000000001",
            "name": "annotation0000000001",
            "label": "annotation_1 awd aw awd awd awd awd awd awd awd awd awd awd awd awd ad awd aw awd aw",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "text",
            "variable": "single_string1",
            "dataType": "string",
            "id": "single_string1",
            "name": "single_string1",
            "label": "text_1",
            "defaultValue": "",
            "placeholder": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "textTransform": "none",
            "validate": "^[a-zA-Z0-9]+.?[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-zA-Z0-9]{2,4}$",
            "validateMessage": "por favor intro un correo valido",
            "maxLength": 1000,
            "formula": "",
            "mode": "edit",
            "operation": "",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [],
            "var_uid": "873219556555a11648046f8072320954",
            "var_name": "single_string1",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            }
          }
        ],
        [
          {
            "type": "textarea",
            "variable": "single_string2",
            "dataType": "string",
            "id": "single_string2",
            "name": "single_string2",
            "label": "textarea_1",
            "defaultValue": "",
            "placeholder": "",
            "hint": "",
            "required": false,
            "validate": "",
            "validateMessage": "",
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [],
            "var_uid": "722328203555a310a0e4681013044088",
            "var_name": "single_string2",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            }
          }
        ],
        [
          {
            "type": "dropdown",
            "variable": "options1_string",
            "dataType": "string",
            "id": "options1_string",
            "name": "options1_string",
            "label": "dropdown_1",
            "defaultValue": "",
            "hint": "",
            "required": false,
            "dependentFields": [],
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
            "options": [
              {
                "label": "Andorra",
                "value": "AD"
              },
              {
                "label": "United Arab Emirates",
                "value": "AE"
              },
              {
                "label": "Afghanistan",
                "value": "AF"
              },
              {
                "label": "Antigua and Barbuda",
                "value": "AG"
              },
              {
                "label": "Anguilla",
                "value": "AI"
              },
              {
                "label": "Albania",
                "value": "AL"
              },
              {
                "label": "Armenia",
                "value": "AM"
              },
              {
                "label": "Netherlands Antilles",
                "value": "AN"
              },
              {
                "label": "Angola",
                "value": "AO"
              },
              {
                "label": "Antarctica",
                "value": "AQ"
              },
              {
                "label": "Argentina",
                "value": "AR"
              },
              {
                "label": "American Samoa",
                "value": "AS"
              },
              {
                "label": "Austria",
                "value": "AT"
              },
              {
                "label": "Australia",
                "value": "AU"
              },
              {
                "label": "Aruba",
                "value": "AW"
              },
              {
                "label": "Azerbaijan",
                "value": "AZ"
              },
              {
                "label": "Bosnia and Herzegovina",
                "value": "BA"
              },
              {
                "label": "Barbados",
                "value": "BB"
              },
              {
                "label": "Bangladesh",
                "value": "BD"
              },
              {
                "label": "Belgium",
                "value": "BE"
              },
              {
                "label": "Burkina Faso",
                "value": "BF"
              },
              {
                "label": "Bulgaria",
                "value": "BG"
              },
              {
                "label": "Bahrain",
                "value": "BH"
              },
              {
                "label": "Burundi",
                "value": "BI"
              },
              {
                "label": "Benin",
                "value": "BJ"
              },
              {
                "label": "Bermuda",
                "value": "BM"
              },
              {
                "label": "Brunei Darussalam",
                "value": "BN"
              },
              {
                "label": "Bolivia",
                "value": "BO"
              },
              {
                "label": "Brazil",
                "value": "BR"
              },
              {
                "label": "Bahamas",
                "value": "BS"
              },
              {
                "label": "Bhutan",
                "value": "BT"
              },
              {
                "label": "Botswana",
                "value": "BW"
              },
              {
                "label": "Belarus",
                "value": "BY"
              },
              {
                "label": "Belize",
                "value": "BZ"
              },
              {
                "label": "Canada",
                "value": "CA"
              },
              {
                "label": "Cocos (Keeling) Islands",
                "value": "CC"
              },
              {
                "label": "Congo, The Democratic Republic of the",
                "value": "CD"
              },
              {
                "label": "Central African Republic",
                "value": "CF"
              },
              {
                "label": "Congo",
                "value": "CG"
              },
              {
                "label": "Switzerland",
                "value": "CH"
              },
              {
                "label": "Côte-d' lvoire",
                "value": "CI"
              },
              {
                "label": "Cook Islands",
                "value": "CK"
              },
              {
                "label": "Chile",
                "value": "CL"
              },
              {
                "label": "Cameroon",
                "value": "CM"
              },
              {
                "label": "China",
                "value": "CN"
              },
              {
                "label": "Colombia",
                "value": "CO"
              },
              {
                "label": "Costa Rica",
                "value": "CR"
              },
              {
                "label": "Serbia and Montenegro",
                "value": "CS"
              },
              {
                "label": "Cuba",
                "value": "CU"
              },
              {
                "label": "Cape Verde",
                "value": "CV"
              },
              {
                "label": "Christmas Island",
                "value": "CX"
              },
              {
                "label": "Cyprus",
                "value": "CY"
              },
              {
                "label": "Czech Republic",
                "value": "CZ"
              },
              {
                "label": "Germany",
                "value": "DE"
              },
              {
                "label": "Djibouti",
                "value": "DJ"
              },
              {
                "label": "Denmark",
                "value": "DK"
              },
              {
                "label": "Dominica",
                "value": "DM"
              },
              {
                "label": "Dominican Republic",
                "value": "DO"
              },
              {
                "label": "Algeria",
                "value": "DZ"
              },
              {
                "label": "Ecuador",
                "value": "EC"
              },
              {
                "label": "Estonia",
                "value": "EE"
              },
              {
                "label": "Egypt",
                "value": "EG"
              },
              {
                "label": "Western Sahara",
                "value": "EH"
              },
              {
                "label": "Eritrea",
                "value": "ER"
              },
              {
                "label": "Spain",
                "value": "ES"
              },
              {
                "label": "Ethiopia",
                "value": "ET"
              },
              {
                "label": "Finland",
                "value": "FI"
              },
              {
                "label": "Fiji",
                "value": "FJ"
              },
              {
                "label": "Falkland Islands (Malvinas)",
                "value": "FK"
              },
              {
                "label": "Micronesia, Federated States of",
                "value": "FM"
              },
              {
                "label": "Faroe Islands",
                "value": "FO"
              },
              {
                "label": "France",
                "value": "FR"
              },
              {
                "label": "Gabon",
                "value": "GA"
              },
              {
                "label": "United Kingdom",
                "value": "GB"
              },
              {
                "label": "Grenada",
                "value": "GD"
              },
              {
                "label": "Georgia",
                "value": "GE"
              },
              {
                "label": "French Guiana",
                "value": "GF"
              },
              {
                "label": "Guernsey",
                "value": "GG"
              },
              {
                "label": "Ghana",
                "value": "GH"
              },
              {
                "label": "Gibraltar",
                "value": "GI"
              },
              {
                "label": "Greenland",
                "value": "GL"
              },
              {
                "label": "Gambia",
                "value": "GM"
              },
              {
                "label": "Guinea",
                "value": "GN"
              },
              {
                "label": "Guadeloupe",
                "value": "GP"
              },
              {
                "label": "Equatorial Guinea",
                "value": "GQ"
              },
              {
                "label": "Greece",
                "value": "GR"
              },
              {
                "label": "South Georgia and the South Sandwich Islands",
                "value": "GS"
              },
              {
                "label": "Guatemala",
                "value": "GT"
              },
              {
                "label": "Guam",
                "value": "GU"
              },
              {
                "label": "Guinea-Bissau",
                "value": "GW"
              },
              {
                "label": "Guyana",
                "value": "GY"
              },
              {
                "label": "Hong Kong",
                "value": "HK"
              },
              {
                "label": "Heard Island and McDonald Islands",
                "value": "HM"
              },
              {
                "label": "Honduras",
                "value": "HN"
              },
              {
                "label": "Croatia",
                "value": "HR"
              },
              {
                "label": "Haiti",
                "value": "HT"
              },
              {
                "label": "Hungary",
                "value": "HU"
              },
              {
                "label": "Indonesia",
                "value": "ID"
              },
              {
                "label": "Ireland",
                "value": "IE"
              },
              {
                "label": "Israel",
                "value": "IL"
              },
              {
                "label": "Isle of Man",
                "value": "IM"
              },
              {
                "label": "India",
                "value": "IN"
              },
              {
                "label": "British Indian Ocean Territory",
                "value": "IO"
              },
              {
                "label": "Iraq",
                "value": "IQ"
              },
              {
                "label": "Iran, Islamic Republic of",
                "value": "IR"
              },
              {
                "label": "Iceland",
                "value": "IS"
              },
              {
                "label": "Italy",
                "value": "IT"
              },
              {
                "label": "Jersey",
                "value": "JE"
              },
              {
                "label": "Jamaica",
                "value": "JM"
              },
              {
                "label": "Jordan",
                "value": "JO"
              },
              {
                "label": "Japan",
                "value": "JP"
              },
              {
                "label": "Kenya",
                "value": "KE"
              },
              {
                "label": "Kyrgyzstan",
                "value": "KG"
              },
              {
                "label": "Cambodia",
                "value": "KH"
              },
              {
                "label": "Kiribati",
                "value": "KI"
              },
              {
                "label": "Comoros",
                "value": "KM"
              },
              {
                "label": "Saint Kitts and Nevis",
                "value": "KN"
              },
              {
                "label": "Korea, Democratic People's Republic of",
                "value": "KP"
              },
              {
                "label": "Korea, Republic of",
                "value": "KR"
              },
              {
                "label": "Kuwait",
                "value": "KW"
              },
              {
                "label": "Cayman Islands",
                "value": "KY"
              },
              {
                "label": "Kazakhstan",
                "value": "KZ"
              },
              {
                "label": "Lao People's Democratic Republic",
                "value": "LA"
              },
              {
                "label": "Lebanon",
                "value": "LB"
              },
              {
                "label": "Saint Lucia",
                "value": "LC"
              },
              {
                "label": "Liechtenstein",
                "value": "LI"
              },
              {
                "label": "Sri Lanka",
                "value": "LK"
              },
              {
                "label": "Liberia",
                "value": "LR"
              },
              {
                "label": "Lesotho",
                "value": "LS"
              },
              {
                "label": "Lithuania",
                "value": "LT"
              },
              {
                "label": "Luxembourg",
                "value": "LU"
              },
              {
                "label": "Latvia",
                "value": "LV"
              },
              {
                "label": "Libyan Arab Jamahiriya",
                "value": "LY"
              },
              {
                "label": "Morocco",
                "value": "MA"
              },
              {
                "label": "Monaco",
                "value": "MC"
              },
              {
                "label": "Moldova, Republic of",
                "value": "MD"
              },
              {
                "label": "Montenegro",
                "value": "ME"
              },
              {
                "label": "Madagascar",
                "value": "MG"
              },
              {
                "label": "Marshall Islands",
                "value": "MH"
              },
              {
                "label": "Macedonia, The former Yugoslav Republic of",
                "value": "MK"
              },
              {
                "label": "Mali",
                "value": "ML"
              },
              {
                "label": "Myanmar",
                "value": "MM"
              },
              {
                "label": "Mongolia",
                "value": "MN"
              },
              {
                "label": "Macao",
                "value": "MO"
              },
              {
                "label": "Northern Mariana Islands",
                "value": "MP"
              },
              {
                "label": "Martinique",
                "value": "MQ"
              },
              {
                "label": "Mauritania",
                "value": "MR"
              },
              {
                "label": "Montserrat",
                "value": "MS"
              },
              {
                "label": "Malta",
                "value": "MT"
              },
              {
                "label": "Mauritius",
                "value": "MU"
              },
              {
                "label": "Maldives",
                "value": "MV"
              },
              {
                "label": "Malawi",
                "value": "MW"
              },
              {
                "label": "Mexico",
                "value": "MX"
              },
              {
                "label": "Malaysia",
                "value": "MY"
              },
              {
                "label": "Mozambique",
                "value": "MZ"
              },
              {
                "label": "Namibia",
                "value": "NA"
              },
              {
                "label": "New Caledonia",
                "value": "NC"
              },
              {
                "label": "Niger",
                "value": "NE"
              },
              {
                "label": "Norfolk Island",
                "value": "NF"
              },
              {
                "label": "Nigeria",
                "value": "NG"
              },
              {
                "label": "Nicaragua",
                "value": "NI"
              },
              {
                "label": "Netherlands",
                "value": "NL"
              },
              {
                "label": "Norway",
                "value": "NO"
              },
              {
                "label": "Nepal",
                "value": "NP"
              },
              {
                "label": "Nauru",
                "value": "NR"
              },
              {
                "label": "Niue",
                "value": "NU"
              },
              {
                "label": "New Zealand",
                "value": "NZ"
              },
              {
                "label": "Oman",
                "value": "OM"
              },
              {
                "label": "Panama",
                "value": "PA"
              },
              {
                "label": "Peru",
                "value": "PE"
              },
              {
                "label": "French Polynesia",
                "value": "PF"
              },
              {
                "label": "Papua New Guinea",
                "value": "PG"
              },
              {
                "label": "Philippines",
                "value": "PH"
              },
              {
                "label": "Pakistan",
                "value": "PK"
              },
              {
                "label": "Poland",
                "value": "PL"
              },
              {
                "label": "Saint Pierre and Miquelon",
                "value": "PM"
              },
              {
                "label": "Pitcairn",
                "value": "PN"
              },
              {
                "label": "Puerto Rico",
                "value": "PR"
              },
              {
                "label": "Portugal",
                "value": "PT"
              },
              {
                "label": "Palau",
                "value": "PW"
              },
              {
                "label": "Paraguay",
                "value": "PY"
              },
              {
                "label": "Qatar",
                "value": "QA"
              },
              {
                "label": "Reunion",
                "value": "RE"
              },
              {
                "label": "Romania",
                "value": "RO"
              },
              {
                "label": "Serbia",
                "value": "RS"
              },
              {
                "label": "Russian Federation",
                "value": "RU"
              },
              {
                "label": "Rwanda",
                "value": "RW"
              },
              {
                "label": "Saudi Arabia",
                "value": "SA"
              },
              {
                "label": "Solomon Islands",
                "value": "SB"
              },
              {
                "label": "Seychelles",
                "value": "SC"
              },
              {
                "label": "Sudan",
                "value": "SD"
              },
              {
                "label": "Sweden",
                "value": "SE"
              },
              {
                "label": "Singapore",
                "value": "SG"
              },
              {
                "label": "Saint Helena",
                "value": "SH"
              },
              {
                "label": "Slovenia",
                "value": "SI"
              },
              {
                "label": "Svalbard and Jan Mayen",
                "value": "SJ"
              },
              {
                "label": "Slovakia",
                "value": "SK"
              },
              {
                "label": "Sierra Leone",
                "value": "SL"
              },
              {
                "label": "San Marino",
                "value": "SM"
              },
              {
                "label": "Senegal",
                "value": "SN"
              },
              {
                "label": "Somalia",
                "value": "SO"
              },
              {
                "label": "Suriname",
                "value": "SR"
              },
              {
                "label": "Sao Tome and Principe",
                "value": "ST"
              },
              {
                "label": "El Salvador",
                "value": "SV"
              },
              {
                "label": "Syrian Arab Republic",
                "value": "SY"
              },
              {
                "label": "Swaziland",
                "value": "SZ"
              },
              {
                "label": "Turks and Caicos Islands",
                "value": "TC"
              },
              {
                "label": "Chad",
                "value": "TD"
              },
              {
                "label": "French Southern Territories",
                "value": "TF"
              },
              {
                "label": "Togo",
                "value": "TG"
              },
              {
                "label": "Thailand",
                "value": "TH"
              },
              {
                "label": "Tajikistan",
                "value": "TJ"
              },
              {
                "label": "Tokelau",
                "value": "TK"
              },
              {
                "label": "Timor-Leste",
                "value": "TL"
              },
              {
                "label": "Turkmenistan",
                "value": "TM"
              },
              {
                "label": "Tunisia",
                "value": "TN"
              },
              {
                "label": "Tonga",
                "value": "TO"
              },
              {
                "label": "Turkey",
                "value": "TR"
              },
              {
                "label": "Trinidad and Tobago",
                "value": "TT"
              },
              {
                "label": "Tuvalu",
                "value": "TV"
              },
              {
                "label": "Taiwan, Province of China",
                "value": "TW"
              },
              {
                "label": "Tanzania, United Republic of",
                "value": "TZ"
              },
              {
                "label": "Ukraine",
                "value": "UA"
              },
              {
                "label": "Uganda",
                "value": "UG"
              },
              {
                "label": "United States Minor Outlying Islands",
                "value": "UM"
              },
              {
                "label": "United States",
                "value": "US"
              },
              {
                "label": "Uruguay",
                "value": "UY"
              },
              {
                "label": "Uzbekistan",
                "value": "UZ"
              },
              {
                "label": "Holy See (Vatican City State)",
                "value": "VA"
              },
              {
                "label": "Saint Vincent and the Grenadines",
                "value": "VC"
              },
              {
                "label": "Venezuela",
                "value": "VE"
              },
              {
                "label": "Virgin Islands, British",
                "value": "VG"
              },
              {
                "label": "Virgin Islands, U.S.",
                "value": "VI"
              },
              {
                "label": "Viet Nam",
                "value": "VN"
              },
              {
                "label": "Vanuatu",
                "value": "VU"
              },
              {
                "label": "Wallis and Futuna",
                "value": "WF"
              },
              {
                "label": "Samoa",
                "value": "WS"
              },
              {
                "label": "Installations in International Waters",
                "value": "XZ"
              },
              {
                "label": "Yemen",
                "value": "YE"
              },
              {
                "label": "Mayotte",
                "value": "YT"
              },
              {
                "label": "South Africa",
                "value": "ZA"
              },
              {
                "label": "Zambia",
                "value": "ZM"
              },
              {
                "label": "Zimbabwe",
                "value": "ZW"
              }
            ],
            "var_uid": "824867629555a325c3634b0059874149",
            "var_name": "options1_string",
            "colSpan": 12,
            "data": {
              "value": "AD",
              "label": "Andorra"
            }
          }
        ],
        [
          {
            "type": "checkbox",
            "variable": "options2_string",
            "dataType": "string",
            "id": "options2_string",
            "name": "options2_string",
            "label": "checkbox_1",
            "defaultValue": "",
            "hint": "",
            "required": false,
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [
              {
                "value": "uno",
                "label": "var_1"
              },
              {
                "value": "dos",
                "label": "var_2"
              },
              {
                "value": "tres",
                "label": "var_3"
              },
              {
                "value": "cuatro",
                "label": "var_4"
              },
              {
                "value": "cinco",
                "label": "var_5"
              }
            ],
            "var_uid": "396463705555b34b3c20588012364208",
            "var_name": "options2_string",
            "colSpan": 12,
            "data": {
              "value": [],
              "label": "[]"
            }
          }
        ],
        [
          {
            "type": "checkbox",
            "variable": "boolean1",
            "dataType": "boolean",
            "id": "boolean1",
            "name": "boolean1",
            "label": "checkbox_boolean",
            "defaultValue": "",
            "hint": "",
            "required": false,
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [
              {
                "value": "1",
                "label": "verdad"
              },
              {
                "value": "0",
                "label": "falso"
              }
            ],
            "var_uid": "967190937555b3fc268f5e1077618654",
            "var_name": "boolean1",
            "colSpan": 12,
            "data": {
              "value": [],
              "label": "[]"
            }
          }
        ],
        [
          {
            "type": "hidden",
            "variable": "single_hidden",
            "dataType": "string",
            "id": "single_hidden",
            "name": "single_hidden",
            "defaultValue": "richard",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [],
            "var_uid": "921667908557b320cc67c80000575767",
            "var_name": "single_hidden",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            }
          }
        ],
        [
          {
            "type": "radio",
            "variable": "options3",
            "dataType": "string",
            "id": "options3",
            "name": "options3",
            "label": "radio_1",
            "defaultValue": "",
            "hint": "",
            "required": false,
            "mode": "parent",
            "dbConnection": "workflow",
            "dbConnectionLabel": "PM Database",
            "sql": "",
            "options": [
              {
                "value": "opt1",
                "label": "option1"
              },
              {
                "value": "opt2",
                "label": "option2"
              },
              {
                "value": "opt3",
                "label": "option3"
              },
              {
                "value": "opt4",
                "label": "option4"
              }
            ],
            "var_uid": "995155804555b47accdcbf6058244129",
            "var_name": "options3",
            "colSpan": 12,
            "data": {
              "value": "opt1",
              "label": "option1"
            }
          }
        ],
        [
          {
            "type": "datetime",
            "variable": "datetime",
            "dataType": "datetime",
            "id": "datetime",
            "name": "datetime",
            "label": "datetime_2",
            "placeholder": "",
            "hint": "",
            "required": false,
            "mode": "parent",
            "format": "LLLL",
            "dayViewHeaderFormat": "MMMM YYYY",
            "extraFormats": false,
            "stepping": 1,
            "minDate": "",
            "maxDate": "",
            "useCurrent": "true",
            "collapse": true,
            "locale": "",
            "defaultDate": "",
            "disabledDates": false,
            "enabledDates": false,
            "icons": {
              "time": "glyphicon glyphicon-time",
              "date": "glyphicon glyphicon-calendar",
              "up": "glyphicon glyphicon-chevron-up",
              "down": "glyphicon glyphicon-chevron-down",
              "previous": "glyphicon glyphicon-chevron-left",
              "next": "glyphicon glyphicon-chevron-right",
              "today": "glyphicon glyphicon-screenshot",
              "clear": "glyphicon glyphicon-trash"
            },
            "useStrict": false,
            "sideBySide": false,
            "daysOfWeekDisabled": [],
            "calendarWeeks": false,
            "viewMode": "days",
            "toolbarPlacement": "default",
            "showTodayButton": false,
            "showClear": "false",
            "widgetPositioning": {
              "horizontal": "auto",
              "vertical": "auto"
            },
            "widgetParent": null,
            "keepOpen": false,
            "var_uid": "773430543555ce71a36faa2038187288",
            "var_name": "datetime",
            "colSpan": 12,
            "data": {
              "value": "",
              "label": ""
            },
            "dbConnection": "none",
            "sql": "",
            "options": []
          }
        ],
        [
          {
            "type": "subtitle",
            "id": "subtitle0000000003",
            "name": "subtitle0000000003",
            "label": "Mobile controls",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "location",
            "variable": "",
            "id": "location0000000001",
            "name": "location0000000001",
            "label": "location_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "scannerCode",
            "variable": "",
            "id": "scannerCode0000000001",
            "name": "scannerCode0000000001",
            "label": "scannerCode_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "signature",
            "id": "signature0000000001",
            "name": "signature0000000001",
            "label": "signature_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "audioMobile",
            "id": "audioMobile0000000001",
            "name": "audioMobile0000000001",
            "label": "audioMobile_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "imageMobile",
            "id": "imageMobile0000000001",
            "name": "imageMobile0000000001",
            "label": "imageMobile_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "videoMobile",
            "id": "videoMobile0000000001",
            "name": "videoMobile0000000001",
            "label": "videoMobile_1",
            "hint": "",
            "required": false,
            "colSpan": 12
          }
        ],
        [
          {
            "type": "subtitle",
            "id": "subtitle0000000002",
            "name": "subtitle0000000002",
            "label": "grillas",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "grid",
            "variable": "grilla",
            "id": "grilla",
            "name": "grilla",
            "label": "grid_1",
            "hint": "",
            "columns": [
              {
                "type": "suggest",
                "variable": "",
                "dataType": "",
                "id": "suggest0000000002",
                "name": "suggest0000000002",
                "label": "suggest_2",
                "defaultValue": "",
                "placeholder": "",
                "hint": " ad ad ad ad ad ad ad a",
                "required": false,
                "dependentFields": [],
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
                "options": [
                  {
                    "value": "uno",
                    "label": "primero"
                  },
                  {
                    "value": "dos",
                    "label": "segundo"
                  },
                  {
                    "value": "tres",
                    "label": "tercero"
                  },
                  {
                    "label": "Andorra",
                    "value": "AD"
                  },
                  {
                    "label": "United Arab Emirates",
                    "value": "AE"
                  },
                  {
                    "label": "Afghanistan",
                    "value": "AF"
                  },
                  {
                    "label": "Antigua and Barbuda",
                    "value": "AG"
                  },
                  {
                    "label": "Anguilla",
                    "value": "AI"
                  },
                  {
                    "label": "Albania",
                    "value": "AL"
                  },
                  {
                    "label": "Armenia",
                    "value": "AM"
                  },
                  {
                    "label": "Netherlands Antilles",
                    "value": "AN"
                  },
                  {
                    "label": "Angola",
                    "value": "AO"
                  },
                  {
                    "label": "Antarctica",
                    "value": "AQ"
                  },
                  {
                    "label": "Argentina",
                    "value": "AR"
                  },
                  {
                    "label": "American Samoa",
                    "value": "AS"
                  },
                  {
                    "label": "Austria",
                    "value": "AT"
                  },
                  {
                    "label": "Australia",
                    "value": "AU"
                  },
                  {
                    "label": "Aruba",
                    "value": "AW"
                  },
                  {
                    "label": "Azerbaijan",
                    "value": "AZ"
                  },
                  {
                    "label": "Bosnia and Herzegovina",
                    "value": "BA"
                  },
                  {
                    "label": "Barbados",
                    "value": "BB"
                  },
                  {
                    "label": "Bangladesh",
                    "value": "BD"
                  },
                  {
                    "label": "Belgium",
                    "value": "BE"
                  },
                  {
                    "label": "Burkina Faso",
                    "value": "BF"
                  },
                  {
                    "label": "Bulgaria",
                    "value": "BG"
                  },
                  {
                    "label": "Bahrain",
                    "value": "BH"
                  },
                  {
                    "label": "Burundi",
                    "value": "BI"
                  },
                  {
                    "label": "Benin",
                    "value": "BJ"
                  },
                  {
                    "label": "Bermuda",
                    "value": "BM"
                  },
                  {
                    "label": "Brunei Darussalam",
                    "value": "BN"
                  },
                  {
                    "label": "Bolivia",
                    "value": "BO"
                  },
                  {
                    "label": "Brazil",
                    "value": "BR"
                  },
                  {
                    "label": "Bahamas",
                    "value": "BS"
                  },
                  {
                    "label": "Bhutan",
                    "value": "BT"
                  },
                  {
                    "label": "Botswana",
                    "value": "BW"
                  },
                  {
                    "label": "Belarus",
                    "value": "BY"
                  },
                  {
                    "label": "Belize",
                    "value": "BZ"
                  },
                  {
                    "label": "Canada",
                    "value": "CA"
                  },
                  {
                    "label": "Cocos (Keeling) Islands",
                    "value": "CC"
                  },
                  {
                    "label": "Congo, The Democratic Republic of the",
                    "value": "CD"
                  },
                  {
                    "label": "Central African Republic",
                    "value": "CF"
                  },
                  {
                    "label": "Congo",
                    "value": "CG"
                  },
                  {
                    "label": "Switzerland",
                    "value": "CH"
                  },
                  {
                    "label": "Côte-d' lvoire",
                    "value": "CI"
                  },
                  {
                    "label": "Cook Islands",
                    "value": "CK"
                  },
                  {
                    "label": "Chile",
                    "value": "CL"
                  },
                  {
                    "label": "Cameroon",
                    "value": "CM"
                  },
                  {
                    "label": "China",
                    "value": "CN"
                  },
                  {
                    "label": "Colombia",
                    "value": "CO"
                  },
                  {
                    "label": "Costa Rica",
                    "value": "CR"
                  },
                  {
                    "label": "Serbia and Montenegro",
                    "value": "CS"
                  },
                  {
                    "label": "Cuba",
                    "value": "CU"
                  },
                  {
                    "label": "Cape Verde",
                    "value": "CV"
                  },
                  {
                    "label": "Christmas Island",
                    "value": "CX"
                  },
                  {
                    "label": "Cyprus",
                    "value": "CY"
                  },
                  {
                    "label": "Czech Republic",
                    "value": "CZ"
                  },
                  {
                    "label": "Germany",
                    "value": "DE"
                  },
                  {
                    "label": "Djibouti",
                    "value": "DJ"
                  },
                  {
                    "label": "Denmark",
                    "value": "DK"
                  },
                  {
                    "label": "Dominica",
                    "value": "DM"
                  },
                  {
                    "label": "Dominican Republic",
                    "value": "DO"
                  },
                  {
                    "label": "Algeria",
                    "value": "DZ"
                  },
                  {
                    "label": "Ecuador",
                    "value": "EC"
                  },
                  {
                    "label": "Estonia",
                    "value": "EE"
                  },
                  {
                    "label": "Egypt",
                    "value": "EG"
                  },
                  {
                    "label": "Western Sahara",
                    "value": "EH"
                  },
                  {
                    "label": "Eritrea",
                    "value": "ER"
                  },
                  {
                    "label": "Spain",
                    "value": "ES"
                  },
                  {
                    "label": "Ethiopia",
                    "value": "ET"
                  },
                  {
                    "label": "Finland",
                    "value": "FI"
                  },
                  {
                    "label": "Fiji",
                    "value": "FJ"
                  },
                  {
                    "label": "Falkland Islands (Malvinas)",
                    "value": "FK"
                  },
                  {
                    "label": "Micronesia, Federated States of",
                    "value": "FM"
                  },
                  {
                    "label": "Faroe Islands",
                    "value": "FO"
                  },
                  {
                    "label": "France",
                    "value": "FR"
                  },
                  {
                    "label": "Gabon",
                    "value": "GA"
                  },
                  {
                    "label": "United Kingdom",
                    "value": "GB"
                  },
                  {
                    "label": "Grenada",
                    "value": "GD"
                  },
                  {
                    "label": "Georgia",
                    "value": "GE"
                  },
                  {
                    "label": "French Guiana",
                    "value": "GF"
                  },
                  {
                    "label": "Guernsey",
                    "value": "GG"
                  },
                  {
                    "label": "Ghana",
                    "value": "GH"
                  },
                  {
                    "label": "Gibraltar",
                    "value": "GI"
                  },
                  {
                    "label": "Greenland",
                    "value": "GL"
                  },
                  {
                    "label": "Gambia",
                    "value": "GM"
                  },
                  {
                    "label": "Guinea",
                    "value": "GN"
                  },
                  {
                    "label": "Guadeloupe",
                    "value": "GP"
                  },
                  {
                    "label": "Equatorial Guinea",
                    "value": "GQ"
                  },
                  {
                    "label": "Greece",
                    "value": "GR"
                  },
                  {
                    "label": "South Georgia and the South Sandwich Islands",
                    "value": "GS"
                  },
                  {
                    "label": "Guatemala",
                    "value": "GT"
                  },
                  {
                    "label": "Guam",
                    "value": "GU"
                  },
                  {
                    "label": "Guinea-Bissau",
                    "value": "GW"
                  },
                  {
                    "label": "Guyana",
                    "value": "GY"
                  },
                  {
                    "label": "Hong Kong",
                    "value": "HK"
                  },
                  {
                    "label": "Heard Island and McDonald Islands",
                    "value": "HM"
                  },
                  {
                    "label": "Honduras",
                    "value": "HN"
                  },
                  {
                    "label": "Croatia",
                    "value": "HR"
                  },
                  {
                    "label": "Haiti",
                    "value": "HT"
                  },
                  {
                    "label": "Hungary",
                    "value": "HU"
                  },
                  {
                    "label": "Indonesia",
                    "value": "ID"
                  },
                  {
                    "label": "Ireland",
                    "value": "IE"
                  },
                  {
                    "label": "Israel",
                    "value": "IL"
                  },
                  {
                    "label": "Isle of Man",
                    "value": "IM"
                  },
                  {
                    "label": "India",
                    "value": "IN"
                  },
                  {
                    "label": "British Indian Ocean Territory",
                    "value": "IO"
                  },
                  {
                    "label": "Iraq",
                    "value": "IQ"
                  },
                  {
                    "label": "Iran, Islamic Republic of",
                    "value": "IR"
                  },
                  {
                    "label": "Iceland",
                    "value": "IS"
                  },
                  {
                    "label": "Italy",
                    "value": "IT"
                  },
                  {
                    "label": "Jersey",
                    "value": "JE"
                  },
                  {
                    "label": "Jamaica",
                    "value": "JM"
                  },
                  {
                    "label": "Jordan",
                    "value": "JO"
                  },
                  {
                    "label": "Japan",
                    "value": "JP"
                  },
                  {
                    "label": "Kenya",
                    "value": "KE"
                  },
                  {
                    "label": "Kyrgyzstan",
                    "value": "KG"
                  },
                  {
                    "label": "Cambodia",
                    "value": "KH"
                  },
                  {
                    "label": "Kiribati",
                    "value": "KI"
                  },
                  {
                    "label": "Comoros",
                    "value": "KM"
                  },
                  {
                    "label": "Saint Kitts and Nevis",
                    "value": "KN"
                  },
                  {
                    "label": "Korea, Democratic People's Republic of",
                    "value": "KP"
                  },
                  {
                    "label": "Korea, Republic of",
                    "value": "KR"
                  },
                  {
                    "label": "Kuwait",
                    "value": "KW"
                  },
                  {
                    "label": "Cayman Islands",
                    "value": "KY"
                  },
                  {
                    "label": "Kazakhstan",
                    "value": "KZ"
                  },
                  {
                    "label": "Lao People's Democratic Republic",
                    "value": "LA"
                  },
                  {
                    "label": "Lebanon",
                    "value": "LB"
                  },
                  {
                    "label": "Saint Lucia",
                    "value": "LC"
                  },
                  {
                    "label": "Liechtenstein",
                    "value": "LI"
                  },
                  {
                    "label": "Sri Lanka",
                    "value": "LK"
                  },
                  {
                    "label": "Liberia",
                    "value": "LR"
                  },
                  {
                    "label": "Lesotho",
                    "value": "LS"
                  },
                  {
                    "label": "Lithuania",
                    "value": "LT"
                  },
                  {
                    "label": "Luxembourg",
                    "value": "LU"
                  },
                  {
                    "label": "Latvia",
                    "value": "LV"
                  },
                  {
                    "label": "Libyan Arab Jamahiriya",
                    "value": "LY"
                  },
                  {
                    "label": "Morocco",
                    "value": "MA"
                  },
                  {
                    "label": "Monaco",
                    "value": "MC"
                  },
                  {
                    "label": "Moldova, Republic of",
                    "value": "MD"
                  },
                  {
                    "label": "Montenegro",
                    "value": "ME"
                  },
                  {
                    "label": "Madagascar",
                    "value": "MG"
                  },
                  {
                    "label": "Marshall Islands",
                    "value": "MH"
                  },
                  {
                    "label": "Macedonia, The former Yugoslav Republic of",
                    "value": "MK"
                  },
                  {
                    "label": "Mali",
                    "value": "ML"
                  },
                  {
                    "label": "Myanmar",
                    "value": "MM"
                  },
                  {
                    "label": "Mongolia",
                    "value": "MN"
                  },
                  {
                    "label": "Macao",
                    "value": "MO"
                  },
                  {
                    "label": "Northern Mariana Islands",
                    "value": "MP"
                  },
                  {
                    "label": "Martinique",
                    "value": "MQ"
                  },
                  {
                    "label": "Mauritania",
                    "value": "MR"
                  },
                  {
                    "label": "Montserrat",
                    "value": "MS"
                  },
                  {
                    "label": "Malta",
                    "value": "MT"
                  },
                  {
                    "label": "Mauritius",
                    "value": "MU"
                  },
                  {
                    "label": "Maldives",
                    "value": "MV"
                  },
                  {
                    "label": "Malawi",
                    "value": "MW"
                  },
                  {
                    "label": "Mexico",
                    "value": "MX"
                  },
                  {
                    "label": "Malaysia",
                    "value": "MY"
                  },
                  {
                    "label": "Mozambique",
                    "value": "MZ"
                  },
                  {
                    "label": "Namibia",
                    "value": "NA"
                  },
                  {
                    "label": "New Caledonia",
                    "value": "NC"
                  },
                  {
                    "label": "Niger",
                    "value": "NE"
                  },
                  {
                    "label": "Norfolk Island",
                    "value": "NF"
                  },
                  {
                    "label": "Nigeria",
                    "value": "NG"
                  },
                  {
                    "label": "Nicaragua",
                    "value": "NI"
                  },
                  {
                    "label": "Netherlands",
                    "value": "NL"
                  },
                  {
                    "label": "Norway",
                    "value": "NO"
                  },
                  {
                    "label": "Nepal",
                    "value": "NP"
                  },
                  {
                    "label": "Nauru",
                    "value": "NR"
                  },
                  {
                    "label": "Niue",
                    "value": "NU"
                  },
                  {
                    "label": "New Zealand",
                    "value": "NZ"
                  },
                  {
                    "label": "Oman",
                    "value": "OM"
                  },
                  {
                    "label": "Panama",
                    "value": "PA"
                  },
                  {
                    "label": "Peru",
                    "value": "PE"
                  },
                  {
                    "label": "French Polynesia",
                    "value": "PF"
                  },
                  {
                    "label": "Papua New Guinea",
                    "value": "PG"
                  },
                  {
                    "label": "Philippines",
                    "value": "PH"
                  },
                  {
                    "label": "Pakistan",
                    "value": "PK"
                  },
                  {
                    "label": "Poland",
                    "value": "PL"
                  },
                  {
                    "label": "Saint Pierre and Miquelon",
                    "value": "PM"
                  },
                  {
                    "label": "Pitcairn",
                    "value": "PN"
                  },
                  {
                    "label": "Puerto Rico",
                    "value": "PR"
                  },
                  {
                    "label": "Portugal",
                    "value": "PT"
                  },
                  {
                    "label": "Palau",
                    "value": "PW"
                  },
                  {
                    "label": "Paraguay",
                    "value": "PY"
                  },
                  {
                    "label": "Qatar",
                    "value": "QA"
                  },
                  {
                    "label": "Reunion",
                    "value": "RE"
                  },
                  {
                    "label": "Romania",
                    "value": "RO"
                  },
                  {
                    "label": "Serbia",
                    "value": "RS"
                  },
                  {
                    "label": "Russian Federation",
                    "value": "RU"
                  },
                  {
                    "label": "Rwanda",
                    "value": "RW"
                  },
                  {
                    "label": "Saudi Arabia",
                    "value": "SA"
                  },
                  {
                    "label": "Solomon Islands",
                    "value": "SB"
                  },
                  {
                    "label": "Seychelles",
                    "value": "SC"
                  },
                  {
                    "label": "Sudan",
                    "value": "SD"
                  },
                  {
                    "label": "Sweden",
                    "value": "SE"
                  },
                  {
                    "label": "Singapore",
                    "value": "SG"
                  },
                  {
                    "label": "Saint Helena",
                    "value": "SH"
                  },
                  {
                    "label": "Slovenia",
                    "value": "SI"
                  },
                  {
                    "label": "Svalbard and Jan Mayen",
                    "value": "SJ"
                  },
                  {
                    "label": "Slovakia",
                    "value": "SK"
                  },
                  {
                    "label": "Sierra Leone",
                    "value": "SL"
                  },
                  {
                    "label": "San Marino",
                    "value": "SM"
                  },
                  {
                    "label": "Senegal",
                    "value": "SN"
                  },
                  {
                    "label": "Somalia",
                    "value": "SO"
                  },
                  {
                    "label": "Suriname",
                    "value": "SR"
                  },
                  {
                    "label": "Sao Tome and Principe",
                    "value": "ST"
                  },
                  {
                    "label": "El Salvador",
                    "value": "SV"
                  },
                  {
                    "label": "Syrian Arab Republic",
                    "value": "SY"
                  },
                  {
                    "label": "Swaziland",
                    "value": "SZ"
                  },
                  {
                    "label": "Turks and Caicos Islands",
                    "value": "TC"
                  },
                  {
                    "label": "Chad",
                    "value": "TD"
                  },
                  {
                    "label": "French Southern Territories",
                    "value": "TF"
                  },
                  {
                    "label": "Togo",
                    "value": "TG"
                  },
                  {
                    "label": "Thailand",
                    "value": "TH"
                  },
                  {
                    "label": "Tajikistan",
                    "value": "TJ"
                  },
                  {
                    "label": "Tokelau",
                    "value": "TK"
                  },
                  {
                    "label": "Timor-Leste",
                    "value": "TL"
                  },
                  {
                    "label": "Turkmenistan",
                    "value": "TM"
                  },
                  {
                    "label": "Tunisia",
                    "value": "TN"
                  },
                  {
                    "label": "Tonga",
                    "value": "TO"
                  },
                  {
                    "label": "Turkey",
                    "value": "TR"
                  },
                  {
                    "label": "Trinidad and Tobago",
                    "value": "TT"
                  },
                  {
                    "label": "Tuvalu",
                    "value": "TV"
                  },
                  {
                    "label": "Taiwan, Province of China",
                    "value": "TW"
                  },
                  {
                    "label": "Tanzania, United Republic of",
                    "value": "TZ"
                  },
                  {
                    "label": "Ukraine",
                    "value": "UA"
                  },
                  {
                    "label": "Uganda",
                    "value": "UG"
                  },
                  {
                    "label": "United States Minor Outlying Islands",
                    "value": "UM"
                  },
                  {
                    "label": "United States",
                    "value": "US"
                  },
                  {
                    "label": "Uruguay",
                    "value": "UY"
                  },
                  {
                    "label": "Uzbekistan",
                    "value": "UZ"
                  },
                  {
                    "label": "Holy See (Vatican City State)",
                    "value": "VA"
                  },
                  {
                    "label": "Saint Vincent and the Grenadines",
                    "value": "VC"
                  },
                  {
                    "label": "Venezuela",
                    "value": "VE"
                  },
                  {
                    "label": "Virgin Islands, British",
                    "value": "VG"
                  },
                  {
                    "label": "Virgin Islands, U.S.",
                    "value": "VI"
                  },
                  {
                    "label": "Viet Nam",
                    "value": "VN"
                  },
                  {
                    "label": "Vanuatu",
                    "value": "VU"
                  },
                  {
                    "label": "Wallis and Futuna",
                    "value": "WF"
                  },
                  {
                    "label": "Samoa",
                    "value": "WS"
                  },
                  {
                    "label": "Installations in International Waters",
                    "value": "XZ"
                  },
                  {
                    "label": "Yemen",
                    "value": "YE"
                  },
                  {
                    "label": "Mayotte",
                    "value": "YT"
                  },
                  {
                    "label": "South Africa",
                    "value": "ZA"
                  },
                  {
                    "label": "Zambia",
                    "value": "ZM"
                  },
                  {
                    "label": "Zimbabwe",
                    "value": "ZW"
                  }
                ],
                "width": 100,
                "title": "suggest_2",
                "data": {
                  "value": "uno",
                  "label": "primero"
                }
              },
              {
                "type": "text",
                "variable": "",
                "dataType": "",
                "id": "text0000000002",
                "name": "text0000000002",
                "label": "text_2",
                "defaultValue": "",
                "placeholder": "",
                "hint": "",
                "required": false,
                "dependentFields": [],
                "textTransform": "none",
                "validate": "",
                "validateMessage": "",
                "maxLength": 1000,
                "formula": "",
                "mode": "parent",
                "operation": "",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "title": "text_2",
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "textarea",
                "variable": "",
                "dataType": "",
                "id": "textarea0000000003",
                "name": "textarea0000000003",
                "label": "textarea_2",
                "defaultValue": "",
                "placeholder": "",
                "hint": "",
                "required": false,
                "validate": "",
                "validateMessage": "",
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "title": "textarea_2",
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "dropdown",
                "variable": "",
                "dataType": "",
                "id": "dropdown0000000002",
                "name": "dropdown0000000002",
                "label": "dropdown_2",
                "defaultValue": "",
                "hint": "",
                "required": false,
                "dependentFields": [],
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [
                  {
                    "value": "un",
                    "label": "primero"
                  },
                  {
                    "value": "dos",
                    "label": "segundo"
                  },
                  {
                    "value": "tres",
                    "label": "tercero"
                  }
                ],
                "width": 100,
                "title": "dropdown_2",
                "data": {
                  "value": "un",
                  "label": "primero"
                }
              },
              {
                "type": "checkbox",
                "variable": "",
                "dataType": "",
                "id": "checkbox0000000003",
                "name": "checkbox0000000003",
                "label": "checkbox_2",
                "defaultValue": "",
                "hint": "",
                "required": false,
                "mode": "parent",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "title": "checkbox_2",
                "data": {
                  "value": [],
                  "label": "[]"
                }
              },
              {
                "type": "datetime",
                "variable": "",
                "dataType": "",
                "id": "datetime0000000002",
                "name": "datetime0000000002",
                "label": "datetime_1",
                "placeholder": "",
                "hint": "",
                "required": false,
                "mode": "parent",
                "format": "YYYY-MM-DD",
                "dayViewHeaderFormat": "MMMM YYYY",
                "extraFormats": false,
                "stepping": 1,
                "minDate": "",
                "maxDate": "",
                "useCurrent": "true",
                "collapse": true,
                "locale": "",
                "defaultDate": "",
                "disabledDates": false,
                "enabledDates": false,
                "icons": {
                  "time": "glyphicon glyphicon-time",
                  "date": "glyphicon glyphicon-calendar",
                  "up": "glyphicon glyphicon-chevron-up",
                  "down": "glyphicon glyphicon-chevron-down",
                  "previous": "glyphicon glyphicon-chevron-left",
                  "next": "glyphicon glyphicon-chevron-right",
                  "today": "glyphicon glyphicon-screenshot",
                  "clear": "glyphicon glyphicon-trash"
                },
                "useStrict": false,
                "sideBySide": false,
                "daysOfWeekDisabled": [],
                "calendarWeeks": false,
                "viewMode": "days",
                "toolbarPlacement": "default",
                "showTodayButton": false,
                "showClear": "false",
                "widgetPositioning": {
                  "horizontal": "auto",
                  "vertical": "auto"
                },
                "widgetParent": null,
                "keepOpen": false,
                "width": 100,
                "title": "datetime_1",
                "data": {
                  "value": "",
                  "label": ""
                },
                "dbConnection": "none",
                "sql": "",
                "options": []
              },
              {
                "type": "hidden",
                "variable": "",
                "dataType": "",
                "id": "hidden0000000001",
                "name": "hidden0000000001",
                "defaultValue": "richard",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "data": {
                  "value": "",
                  "label": ""
                }
              },
              {
                "type": "file",
                "id": "file0000000001",
                "name": "file0000000001",
                "label": "file_1",
                "hint": "",
                "required": false,
                "dnd": false,
                "extensions": "*",
                "size": 1024,
                "sizeUnity": "KB",
                "mode": "parent",
                "multiple": false,
                "width": 100,
                "title": "file_1",
                "data": {
                  "value": [],
                  "label": "[]"
                }
              },
              {
                "type": "link",
                "id": "link0000000001",
                "name": "link0000000001",
                "label": "link_1",
                "value": "",
                "defaultValue": "",
                "href": "http://www.google.com/",
                "hint": "",
                "width": 100,
                "title": "link_1"
              },
              {
                "type": "hidden",
                "variable": "",
                "dataType": "",
                "id": "hidden0000000002",
                "name": "hidden0000000002",
                "defaultValue": "",
                "dbConnection": "workflow",
                "dbConnectionLabel": "PM Database",
                "sql": "",
                "options": [],
                "width": 100,
                "data": {
                  "value": "",
                  "label": ""
                }
              }
            ],
            "data": [],
            "mode": "parent",
            "layout": "static",
            "pageSize": "0",
            "addRow": true,
            "deleteRow": true,
            "title": "grid_1",
            "colSpan": 12
          }
        ],
        [
          {
            "type": "submit",
            "id": "submit0000000001",
            "name": "submit0000000001",
            "label": "submit_1",
            "colSpan": 12
          }
        ]
      ],
      "variables": [
        {
          "var_uid": "7790396875589e409d29cd4091845454",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "COUNTRY",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "9435841245589e42e222f26030564550",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "STATE",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "SELECT IS_UID, IS_NAME FROM ISO_SUBDIVISION WHERE IC_UID = \"@#COUNTRY\" ORDER BY IS_NAME",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "4196090065589e43e5240c8035150545",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "LOCATION",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "SELECT IL_UID, IL_NAME FROM ISO_LOCATION WHERE IC_UID = \"@#COUNTRY\" AND IS_UID = \"@#STATE\"",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "698967615555ce7261c58c0021226847",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "suggest_1",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "873219556555a11648046f8072320954",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "single_string1",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "722328203555a310a0e4681013044088",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "single_string2",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "824867629555a325c3634b0059874149",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "options1_string",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "SELECT IC_UID, IC_NAME FROM ISO_COUNTRY",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "396463705555b34b3c20588012364208",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "options2_string",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[{\"value\":\"uno\",\"label\":\"var_1\"},{\"value\":\"dos\",\"label\":\"var_2\"},{\"value\":\"tres\",\"label\":\"var_3\"},{\"value\":\"cuatro\",\"label\":\"var_4\"},{\"value\":\"cinco\",\"label\":\"var_5\"}]"
        },
        {
          "var_uid": "967190937555b3fc268f5e1077618654",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "boolean1",
          "var_field_type": "boolean",
          "var_field_size": 10,
          "var_label": "boolean",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[{\"value\":\"1\",\"label\":\"verdad\"},{\"value\":\"0\",\"label\":\"falso\"}]"
        },
        {
          "var_uid": "921667908557b320cc67c80000575767",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "single_hidden",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        {
          "var_uid": "995155804555b47accdcbf6058244129",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "options3",
          "var_field_type": "string",
          "var_field_size": 10,
          "var_label": "string",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[{\"value\":\"opt1\",\"label\":\"option1\"},{\"value\":\"opt2\",\"label\":\"option2\"},{\"value\":\"opt3\",\"label\":\"option3\"},{\"value\":\"opt4\",\"label\":\"option4\"}]"
        },
        {
          "var_uid": "773430543555ce71a36faa2038187288",
          "prj_uid": "498574572555a0fa9474b40040026428",
          "var_name": "datetime",
          "var_field_type": "datetime",
          "var_field_size": 10,
          "var_label": "datetime",
          "var_dbconnection": "workflow",
          "var_dbconnection_label": "PM Database",
          "var_sql": "",
          "var_null": 0,
          "var_default": "",
          "var_accepted_values": "[]"
        },
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null
      ]
    }
  ]
}*/
      window.project = new PMDynaform.core.Project({
          data: data, //JSON.parse(editor.getValue()),
          renderTo: document.getElementById("container"),
          submitRest: true,
          keys: {
              server: "http://ronald3.pmos.colosa.net/", //"http://michelangelo.pmos3.colosa.net/",
              projectId: "498574572555a0fa9474b40040026428", //"25084755253f3a016907523058545566",
              workspace: "testAdmin" //"workflow3"
          },
          token: {
              accessToken: "4b3fd851be263b320f10731b0d2aa444c3fdf8e9" //"db0498b53483bb840e996a27d23ace1d49f1e35b"
          },
          renderTo : document.body
      });
      $(".pmdynaform-container").css({
          "float":"left",
          "width" : "100%"
      });
      dos = new Date();
      tiempo = (dos - uno) / 1000;
      m = Math.floor(tiempo / 60);
      tiempo -= m;
      s = Math.floor(tiempo);
      tiempo -= s;
      $('#lista').append('<li>Add ' + size + ' datos de fila ' + m  + 'm '+ s + 's ' + Math.round(tiempo * 1000) + 'ms</li>');
    };
    document.getElementById("run_script").onclick = function () {
      for (i = 0 ; i < window.project.forms[0].items.asArray().length ; i+=1) {
        //fields.push($("#"+window.project.forms[i].items.asArray()[i].model.get("id"));
        console.log($("#"+window.project.forms[0].items.asArray()[i].model.get("id")).getText()) ;
      }
    }

    document.getElementById("format").onclick = function () {
        var a = editor.getCursor(true);
        var b = editor.getCursor(false);
        if (a === b) {
            a = {line: 0, ch: 0};
            b = {line: editor.doc.lastLine(), ch: editor.getValue().length};
        }
        editor.autoFormatRange(a, b);
    };
    document.getElementById("desktop").onclick = function () {
        $(".pmdynaform-container").css({"width": "1024px","float" : "left"});
    };
    document.getElementById("tablet").onclick = function () {
        $(".pmdynaform-container").css({"width": "800px", "float" : "left"});
    };
    document.getElementById("smartphone").onclick = function () {
        $(".pmdynaform-container").css({"width": "400px","float" : "left"});
    };
};