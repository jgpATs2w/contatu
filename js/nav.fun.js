var user;
var div_alert;
function load(t){
	div_alert = document.getElementById('alert');
	console.info('loading menu for '+t);
	var I = new Array('inicio', 'movimientos', 'informes', 'configurar');
	var s = "<nav><ul>";
	for(mi in I){
		s += "<li>"
		if(I[mi] == t)
			s += "<span>"+I[mi]+"</span>";
		else 
			s += "<a href='../php/"+I[mi]+".php'>"+I[mi]+"</a>";
		s+= "</li>"; 
	}
	document.getElementById("menu").innerHTML = s+"</ul></nav>";
	
	
	showLogout();
	try{
		eval('onload_'+t+'()');
	}catch(e){}
	try{
		eval('suggest_init()');
	}catch(e){}
}


function showLogout(){
	document.getElementById('logout').innerHTML = "<label class='smalltext'>"+sessionStorage.getItem('username')+"  <a href='javascript:void(0)' onclick='logout()'>salir</a></label>";
	
}
function logout(){
	if(confirm("¿seguro que desea salir?")){
		ajax({
			url: "../mod/upa/logout.php",
			onSuccess: function(){console.info('logging out'); window.location="..";}
		})	
	}
}
function hide_div(id){
	document.getElementById(id).style.visibility="hidden";
}
function show_div(id){
	document.getElementById(id).style.visibility="block";
}
function toogle_div(id){
	var d = document.getElementById(id);
	d.className = d.className == 'hidden' ? 'unhidden' : 'hidden';
}
function toogle_rows(className){
	var H = document.getElementsByClassName(className);

	for(i=0;i<H.length;i++){
		H.item(i).style.display = (H.item(i).style.display == 'none')? 'block':'none';	
	}
}
function show_alert(m,d){
	div_alert.innerHTML = m;
	div_alert.className = "unhidden";
	setTimeout(function(){document.getElementById('alert').className='hidden';}, d||2000);
}