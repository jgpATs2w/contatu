var D = null;
mt_accounts = null;
function load_accounts(){
	ajax({
		url: "table2json.php?q=select * from accounts where username='"+sessionStorage.getItem('username')+"'",
		type: "txt",
		onSuccess: function(data){
			D = eval(data);
			if(D.length <= 0) return; 
			mt_accounts = magic_table({
				url: "query.php?q=update accounts",
				HEADERS: new Array("Id", "Alias", "Descripcion"),
				KEYS: new Array("id", "alias", "code"),
				DATA: D
			})
		},
		onError: function(){console.info('load_accounts.configurar.jserror cargando cuentas'); 
					show_alert('error cargando cuentas.');
				},
		onComplete: function(){console.info('completado')}
	})
}
function addAccount(){
	var alias = document.getElementById('input_0').value;
	var code = document.getElementById('input_1').value;
	show_alert('esperando al servidor...');
	ajax({
		url: "query.php?q=insert into accounts (username, alias, code) values ('"+sessionStorage.getItem('username')+"','"+alias+"','"+code+"');",
		type: "txt",
		onSuccess: function(data){show_alert('cuenta añadida');
						load_accounts();
					},
		onError: function(){show_alert('error añadiendo cuenta.')}
	})
}
function getPassword(){
	ajax({
		url: "../mod/upa/table2json.php?q=select poema from main where uname ='"+sessionStorage.getItem('username')+"'",
		type: "text",
		onSuccess: function(data){
			var p = eval(data)[0]["poema"];
			console.info("received password "+p);
			document.getElementById('pass').value = p;
		},
		onError: function(){show_alert('error leyendo password')}
	})
}
function updatePassword(){
	var item = document.getElementById('pass');
	ajax({
		url: "../mod/upa/query.php?q=update main set poema='"+item.value+"' where uname='"+sessionStorage.getItem('username')+"'",
		type: "text",
		onSuccess: function(data){
			console.info('updatePassword.configurar.js#ajax success');
			show_alert('password actualizado');
			getPassword(user);
		},
		onError: function(){show_alert('error actualizando password.')}
	})
}
function backup(){
	var l = document.getElementById('label_backup'); l.innerHTML = "realizando copia de seguridad...";
	ajax({
		url: "backup.php",
		onSuccess: function(data){show_alert('copia realizada, iniciando descarga...')
			var f = '../tmp/'+data;
			l.innerHTML = 'copia de seguridad realizada. Si no se descarga automáticamente, <a href=\''+f+'\'>pincha aqui</a> con el boton derecho y guarda el archivo en tu ordenador.';
			window.open('saveas.php?file='+data,'_blank');
		},
		onError: function(){show_alert('error en la descarga.');}
	
	})
}
