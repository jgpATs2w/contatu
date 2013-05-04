<?
session_start(); $_ENV['uname'] = $_SESSION['uname'];
include 'psql.class.php';  global $DB; $DB = new DB(); 
include 'inicio.lib.php'; $Accounts = $_SESSION['Accounts'] = getAccounts(); session_write_close(); 

include 'nav.fun.php'; html_start('inicio');

if(!$Accounts) die('Aun no están configuradas las cuentas. Vete a <a href=\'configurar.php\'>configurar</a> > cuentas');
$d = date("d");$m = date("m");$y = date("Y");
?>
<h2>Saldos</h2>
	<table id="t_saldos">
		<thead><td>Cuenta</td><td>Fecha</td><td>Saldo (eur.)</td></thead>
		<!--<tr><td class="tdh total">Total</td><td>-</td><td class="total num"><?=($s_caja+$s_comun+$s_coope)?></td></tr>-->
	</table>
	<script>
	function saldosLoad(){
		ajax({
			url: 'inicio.saldos.php',
			onSuccess: function(data){
				var D = eval(data);
				var tot = 0;
				if(document.getElementById('t_saldos_tbody')){
					for(var i =0; i<D.length; i++){
						document.getElementById('td'+i+'alias').innerHTML = D[i]['alias'];
						document.getElementById('td'+i+'date').innerHTML = D[i]['date'];
						document.getElementById('td'+i+'saldo').innerHTML = D[i]['saldo']; tot += parseFloat(D[i]['saldo']);	
					}
					document.getElementById('tdtotsaldo').innerHTML = tot;
				}else{
					var tb = document.createElement('tbody'); tb.id = "t_saldos_tbody";
					for(var i =0; i<D.length; i++){
						var r = document.createElement('tr');
						var c0 = document.createElement('td'); c0.id ="td"+i+"alias"; c0.innerHTML = D[i]['alias'];
						var c1 = document.createElement('td'); c1.id ="td"+i+"date"; c1.innerHTML = D[i]['date'];
						var c2 = document.createElement('td'); c2.id ="td"+i+"saldo"; c2.innerHTML = D[i]['saldo']; c2.className = "num"; tot += parseFloat(D[i]['saldo']);
						r.appendChild(c0); r.appendChild(c1);r.appendChild(c2);
						tb.appendChild(r);
					}
					var r = document.createElement('tr');
						var c0 = document.createElement('td'); c0.className = "tdh total"; c0.innerHTML = 'Total';
						var c1 = document.createElement('td'); c1.innerHTML = '';
						var c2 = document.createElement('td'); c2.className = 'total num'; c2.id = 'tdtotsaldo'; c2.innerHTML = tot.toFixed(2);
						r.appendChild(c0); r.appendChild(c1); r.appendChild(c2);
						tb.appendChild(r);
					document.getElementById('t_saldos').appendChild(tb);
				}
				
			}
		})
	}
	saldosLoad();
	</script>
	
<h2>inserta un nuevo dato</h2>
	[<a href="javascript:showDiv('fud');">sube un fichero de datos</a>]
	<div id="fud" style="display:none;">
		<p id="result"></p>
		<form id="f1" action="import.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
			File: <input name="myfile" type="file" /><br>	
			cuenta: <select id="cc"><? printAccountOptions(); ?></select>
			<input type="submit" name="submitBtn" value="Upload" />
		 </form>
	 
		<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
	</div>   
	
	<div id="insert">
		<div id="date"><input size="2" maxlength="2" type='text' title="día" id='d' value="<?=$d?>" onblur="if(this.value=='') this.value='<?=$d?>'" onFocus="if(this.value =='<?=$d?>' ) this.value=''"/>/<input size="2" maxlength="2" type='text' title="mes" id='m' value="<?=$m?>" onblur="if(this.value=='') this.value='<?=$m?>'" onFocus="if(this.value =='<?=$m?>' ) this.value=''"/>/<input size="4" maxlength="4" type='text' title="aÃ±o" id='y' value="<?=$y?>" onblur="if(this.value=='') this.value='<?=$y?>'" onFocus="if(this.value =='<?=$y?>' ) this.value=''"/></div>

		<div id="content" onclick="hideSuggestions();">
			<input size="20" type='text' title="escribe el concepto que desees" id='que' value="que" onblur="if(this.value=='') this.value='que'" onFocus="if(this.value =='que' ) this.value=''" onkeyup="handleKeyUp(event)" onfocusout="hideSuggestions();"/>
			<div id="scroll" class="unhidden">
				<div id="suggest"></div>
			</div>
		</div>
			
		<input size="20" type='text'  title="descripcion mas extensa" id='descriptor' value="descripcion" onblur="if(this.value=='') this.value='descripcion'" onFocus="if(this.value =='descripcion' ) this.value=''"/>
		<input size="7" type='text' title="cantidad gastada, con simbolo negativo(-) si es un gasto" id='cuanto' value="+/-XXX.X" onblur="if(this.value=='') this.value='+/-XXX.X'" onfocus="if(this.value =='+/-XXX.X' ) this.value=''" onkeyup="if(event.keyCode == 13){ insertRow();}"/>
		<select id="manual_cc">
			<? printAccountOptions(); ?>
		</select>
		<a id="a_insert" href="javascript:insertRow();">insertar!</a>
		
	</div>
		
	  
		  
<? html_end('inicio');?>
