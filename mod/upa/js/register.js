var uok = 0; var pok = 0; var eok=0;
function testUser(){
	var u = document.getElementById('userInput').value;
	var t = /^[\w]+$/.test(u);
	document.getElementById('userLabel').innerHTML = 
		t?"ok":"formato a-z, A-Z, 0-9 o '_'. Al menos 1 caracter";
	if(!t) uok = 0;

	ajax({
		url: "table2json.php?q=select count(uname) from main where uname='"+u+"'",
		onSuccess: function(data){
			var D = eval(data);
			if(D[0]['count'] > 0){
				document.getElementById('userLabel').innerHTML = "el usuario existe";
				uok = 0;
			}else
				uok = 1;
		}
	})
	
}
function testPass(){
	var t = /^.{6,}/.test(document.getElementById('passInput').value);
	document.getElementById('passLabel').innerHTML = t?"ok":"al menos 6 caracteres";
	
	pok = t;
}
function testEmail(){
	var t = /^(?:\w+\.?)*\w+@(?:\w+\.)+\w+$/.test(document.getElementById('emailInput').value);
	document.getElementById('emailLabel').innerHTML = 
		t?"ok":"formato no valido";
	
	eok = t;
}
function showAlert(m){document.getElementById('label_main').innerHTML = m;}
function go(){
	if(!uok || !pok || !eok){
		 showAlert("revisa los campos, no puedo enviar esta informacion.");
		return;
	}
	showAlert('subiendo los datos...')
	var u = document.getElementById('userInput').value;
	
	ajax({
		url: "query.php?q=insert into main (uname, poema, email) values ('"+u+"','"+document.getElementById('passInput').value+"','"+document.getElementById('emailInput').value+"')",
		onSuccess: function (data){
			showAlert('usuario registrado con exito, configurando...')
			console.info('go.register#add user request successful');
			ajax({
				url: "../../php/query.php?q=create table "+u+" (id serial, que varchar(50), descriptor varchar(300), cuando date, cuanto real, cuenta varchar(10), primary key(id))",
				onSuccess: function (data){
					showAlert('ya tienes usuario!');
					console.info('go.register#create table request successful');	
					sessionStorage.setItem('username', u);
					setTimeout(function(){window.location.replace('index.html')},1000);
				}
			})	
		}
	})
}
