var shown = false;
function showDiv(d){
	if (!shown){
		document.getElementById(d).style.display = 'block';
		shown = true;
	}else{
	 	document.getElementById(d).style.display = 'none';
		shown = false;
	}
}
function startUpload(){
	console.info("startupload#"+document.getElementById('cc').selectedIndex);
	document.getElementById('f1').action = "import.php?cc="+document.getElementById('cc'+document.getElementById('cc').selectedIndex).value;
	
	return true;
}

function showAlert(m){
	document.getElementById('alert').innerHTML = '<span class="msg">'+m+'</span>';
	
	setTimeout("showAlert('zzz')", 5000);
}
