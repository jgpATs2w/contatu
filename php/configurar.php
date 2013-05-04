<?
include_once('nav.fun.php');
html_start('configurar');
?>
Contraseña: <input type="password" id="pass" onchange="updatePassword()"/><input type="checkbox" onchange="document.getElementById('pass').type= this.checked? 'text' : 'password'"/><label class="smalltext">mostrar</label>
<script type="text/javascript">getPassword()</script>
<h3>Cuentas</h3>
<p class="smalltext">[<a href="javascript:toogle_div('div_0')">añadir</a>]</p>
<div id="div_0" class="hidden">
	alias: <input type="text" id="input_0"/>; descripción<input type="text" id="input_1"><button class="big" onclick="addAccount();">ok</button>
</div>

<table id="table_0"></table>
<script>
	load_accounts();
</script>

<h3>Copias de seguridad</h3>
<a href="javascript:void(0)" onclick="backup()">Realizar copia de seguridad ahora</a><br>
<label id="label_backup"></label>
<? html_end('configurar') ?>
