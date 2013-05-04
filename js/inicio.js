function insertRow(){
	var d = document.getElementById('y').value+"-"+document.getElementById('m').value+"-"+document.getElementById('d').value;
	var v = "'"+d+"','"+document.getElementById('que').value+"','"+document.getElementById('descriptor').value+"',"+document.getElementById('cuanto').value+",'"+document.getElementById('manual_cc').value+"'";
	var q = "query.php?q="+encodeURIComponent("insert into "+sessionStorage.getItem('username')+" (cuando, que, descriptor, cuanto, cuenta) values ("+v+")"); console.debug(q);
	
	ajax({
		url: q,
		onSuccess: function(data){
			if(data.indexOf("OK")!=-1){
				showAlert(v+' insertado con exito');
				saldosLoad();
			}else{
				showAlert(v+' fallo!!');
			}
			
		}
	})
}
