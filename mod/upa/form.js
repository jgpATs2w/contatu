var REDIRECT = "../../php/inicio.php";
var u;
var label_console;
window.onload = function(){
	label_console = document.getElementById('label_console');
	var u = sessionStorage.getItem('username');
	if(u){
		document.getElementById('userInput').value = u;
		document.getElementById("passInput").focus();
	} 
}
function info(m){
	label_console.style.color="green";
	label_console.innerHTML = m;
	console.info(m);}
function error(m){
	label_console.style.color="red";
	label_console.innerHTML = m;
	console.error(m);}
function userChanged(){ info('leyendo usuario');
	u = document.getElementById("userInput").value;
	ajax({
		url: "test.php?action=chk_u&user="+u,
		onSuccess: function(data){
			R = data.split(";");
			
			if(R[0] == "OK"){
				info('usuario valido');
				document.getElementById("passInput").focus();
				sessionStorage.setItem('username', u);
			}else
				error('usuario no válido');
		},
		onError: function(){error('error de comunicacion con el servidor');}
	})
}
function passChanged(){ info("conectando con servidor, por favor espere...");
	ajax({
		url: "test.php?action=chk_p&user="+document.getElementById("userInput").value+"&pass="+document.getElementById("passInput").value,
		onSuccess: function(data){
			R = data.split(";");
			if(R[0] == "OK"){
				info('usuario y pass validos, redirigiendo...')
					
				window.location=REDIRECT;				
			}else
				error('contraseña no valida')
		},
		onError: function(){error('error de comunicacion con el servidor');}})
}
