var eding = false;
var edingId;
function edit(id){
	if(!eding){
		document.getElementById(id).innerHTML = "<input type='text' id='e"+id+"' onkeyup='update(event,\""+id+"\");' value='"+document.getElementById(id).innerHTML+"'>";
		eding = true; edingId = id; console.info('editing '+edingId);
		
	}
}
function update(e, id){
     if(e.keyCode == 13){
    	var v = document.getElementById("e"+id).value;;
    	var S = id.split('_'); var mid = S[1]; var col = S[2];
    	xmlHttp.open("GET", "../php/update.php?q=update "+sessionStorage.getItem('username')+" set "+col+"='"+v+"' where id="+mid);
    	xmlHttp.onreadystatechange = function(){
    		if (xmlHttp.readyState == 4){
    			if (xmlHttp.status == 200){
    				console.info(xmlHttp.responseText);
    				document.getElementById(id).innerHTML = v;
    			}else
    				console.error("xmlHttp.status = "+xmlHttp.status);
    		}
    	}
    	xmlHttp.send(null);
		
		eding = false;
		
		setTimeout("analisis_load()", 1000);
     }
}
function supr(id){
	if(confirm("SEGURO??")){
		xmlHttp.open("GET", "../php/query.php?q=delete from "+sessionStorage.getItem('username')+" where id="+id);
		xmlHttp.onreadystatechange = function(){
			if (xmlHttp.readyState == 4){
				if (xmlHttp.status == 200){
					console.info(xmlHttp.responseText);
				}else
					console.error("xmlHttp.status = "+xmlHttp.status);
			}
		}
		xmlHttp.send(null);
		
		setTimeout("analisis_load()", 500);
	}
}
