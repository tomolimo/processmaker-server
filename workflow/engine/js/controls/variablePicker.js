leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
	console.log('Dropdown Type of Variables');
});

leimnud.event.add(document.getElementById('prefix'), 'change', function(event) {
	console.log('Dropdown Prefix');
});


leimnud.event.add(document.getElementById('search'), 'keypress', function(event) {
	var tecla = event.keyCode;
	if(tecla == '13')
	{
		console.log('Has pulsado enter');
		return false;
	}
});

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
	console.log('Dropdown Type of Variables');
});

function getValue(list) {
	console.log(list.value);
}