function mov_search(){console.info('mov_search#starting...')
	var f = document.getElementById('year-from').value+'-'+document.getElementById('month-from').value+'-'+document.getElementById('day-from').value;
	var t = document.getElementById('year-to').value+'-'+document.getElementById('month-to').value+'-'+document.getElementById('day-to').value;
	var que = document.getElementById('mov_select_que').value;
	var tx = document.getElementById('tx').value;
	
	var s = "../php/mov.search.php?f="+f+"&t="+t+"&tx="+tx+"&que="+que;
	show_alert('esperando al servidor...');
	ajax({
		url: s,
		type: "txt",
		onSuccess: function (data){
			show_alert('datos recibidos');
			document.getElementById('search-output').innerHTML = data;
		},
		onError: function(){
			console.error("error on Ajax");
		},
		onComplete: function(){
			console.info("completed")
		}
	});
}
