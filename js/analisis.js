var y, cc;
function analisis_load(){
	var u = window.location.href; 
	var m = document.getElementById('month').value; 
	y = document.getElementById('year').value; 
	var t = document.getElementById('type').value; console.info('type: '+t);
	var d = document.getElementById('details').checked;
	cc = document.getElementById('cc').value;

	document.getElementById('month').disabled = (t === "promedios");
	document.getElementById('button_plot').disabled = eval(t !== "promedios");
	document.getElementById('details').disabled = (t !== "mayor");

	if(t === "promedios")
		loadInformesPromedios();
	else
		window.location.replace("informes.php?month="+m+"&type="+t+"&details="+d+"&year="+y+"&cc="+cc);	
	 	
}
function openPlotWindow(){
	y = document.getElementById('year').value; 
	cc = document.getElementById('cc').value;
	window.open('informes.plot.php?y='+y+'&cc='+cc);
}
function toogle_inputs(t){
	document.getElementById('month').disabled='true';
}
function loadInformesPromedios(){console.info('loading promedios...');
	
	ajax({
		url: "informes.promedios.php?y="+y+"&cc="+cc,
		type: "json",
		onSuccess: function(data){
			R = data.split(";");
			if(eval(R[0]))
				magic_table_sortable({TABLE_ID: "at", DATA: eval("["+R[1]+"]")}).show();
			else{
				document.getElementById('at').innerHTML ="no hay datos en "+document.getElementById('year').value;
			}
		}
	});
}

