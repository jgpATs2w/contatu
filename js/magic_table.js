
/*
 * magic_table definition
 */
function magic_table(options){
	var TABLE_ID = "table_0";
	var HEADERS = new Array("id", "Alias", "Descripcion");
	var KEYS = new Array("id", "alias", "code");
	var D = [{"id":"1","alias":"santander","code":"572.1"},
			{"id":"2","alias":"caja","code":"570.1"}];
			
		
	var q = false;
	var cellid;
	options = {
		HEADERS: options.HEADERS,
		KEYS: options.KEYS || KEYS,
		DATA: options.DATA || D,
		url: options.url || ""
	}	
	D = options.DATA.sort(function(a,b){return a.id > b.id});
	this.loadTable = function (){
	var t = document.getElementById(TABLE_ID);
		t.innerHTML ="";
		//var h = t.createTHead();
		var r = t.insertRow(0);
		for(var i = 0; i<HEADERS.length; i++){
			var h = r.insertCell(i); h.innerHTML = HEADERS[i];
		}
		var h = r.insertCell(i); h.innerHTML = "";
		for(i=0;i<D.length;i++){
				var id_prefix = "td_"+i;
				var row = t.insertRow(i+1); row.className = "td";
				
				for(var j = 0; j<KEYS.length; j++){
					var c = row.insertCell(j); c.innerHTML = D[i][KEYS[j]]; 
						c.id="cell_"+D[i]["id"]+"_"+KEYS[j]; 
						c.onmouseover=function(){q=true; if(!editing) cellid = this.id;} 
						c.onmouseout=function(){q=false;}
				}
				var c = row.insertCell(j); c.innerHTML = "<a href='javascript:void(0)' onclick=\"mt_accounts.del('"+D[i]['id']+"')\">borrar</a>"; 
						c.id="cell_"+D[i]["id"]+"_"+KEYS[j]; 
				
		}
	}
	
	window.onclick = function(){ //console.info('window.onclick q:'+q+',editing:'+editing);
		if(q && !editing){ cellclicked(); return; };
		if(!q && editing) input2text(); }
	
	var v1=false; var editing = false;
	function cellclicked(){ if(editing) return;
		var cell = document.getElementById(cellid);
		cell.innerHTML = "<input id='input_edited' type='text' value='"+cell.innerHTML+"' onmouseover='v1=false' onmouseout='v1=true''/>";
			cell.onkeyup = input_keyup;
		editing = true;
		console.info("cell edited in "+cellid);
	}
	
	function input2text(){console.info('input2text '+cellid)
		var v = document.getElementById('input_edited').value; 
		document.getElementById(cellid).innerHTML = v;
		editing = false;
	}
	function input_keyup(){ if(event.keyCode != 13) return;
		var v = document.getElementById('input_edited');
		ajax({
			url: options.url+" set "+cellid.split("_")[2]+"='"+v.value+"' where id="+cellid.split("_")[1],
			type: "text",
			onSuccess: function(data){
				load_accounts();
			},
			onError:  function(){
							show_alert('error actualizando dato de tabla')
							console.info('error on ajax on '+TABLE_ID)}
		})
	}
	this.del = function (id){
				if(!confirm('seguro que desea borrar la cuenta '+id+'?')) return;
				ajax({
					url: "query.php?q=delete from accounts where id = "+id+" and username='"+sessionStorage.getItem('username')+"'",
					onSuccess: function (data){
						console.info('del.magic_table#deleted id '+id);
						window.location.reload();
					},
					onError: function(){show_alert('error borrando dato con id '+id); }
				})
			}
	loadTable();
	return this;
}
	
	