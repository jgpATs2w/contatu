<? session_start(); $uname = $_SESSION['uname']; 
include_once 'nav.fun.php'; html_start('movimientos');
$d = date("d");$m = date("m");$y = date("Y");?>
Desde:<input size="2" maxlength="2" type='text' title="día" id='day-from' value="01" onblur="if(this.value=='') this.value='01'" onFocus="if(this.value =='01' ) this.value=''"/>/<input size="2" maxlength="2" type='text' title="mes" id='month-from' value="01" onblur="if(this.value=='') this.value='01'" onFocus="if(this.value =='01' ) this.value=''"/>/<input size="4" maxlength="4" type='text' title="año" id='year-from' value="<?=$y?>" onblur="if(this.value=='') this.value='<?=$y?>'" onFocus="if(this.value =='<?=$y?>' ) this.value=''"/> 
Hasta:<input size="2" maxlength="2" type='text' title="día" id='day-to' value="<?=$d?>" onblur="if(this.value=='') this.value='<?=$d?>'" onFocus="if(this.value =='<?=$d?>' ) this.value=''"/>/<input size="2" maxlength="2" type='text' title="mes" id='month-to' value="<?=$m?>" onblur="if(this.value=='') this.value='<?=$m?>'" onFocus="if(this.value =='<?=$m?>' ) this.value=''"/>/<input size="4" maxlength="4" type='text' title="año" id='year-to' value="<?=$y?>" onblur="if(this.value=='') this.value='<?=$y?>'" onFocus="if(this.value =='<?=$y?>' ) this.value=''"/> 
<?
include_once 'psql.class.php';
$DB = new DB();
$r = $DB->query("select que from $uname group by que;");
?>
<select id="mov_select_que">
	<option value="%">Todos conceptos</option>
	<?$i=1; while($l = pg_fetch_assoc($r)){?>
	<option value="<?=$l['que']?>"><?=$l['que']?></option>
	<? }?>
</select>

<script>var defSearch='escribe aqui';</script>
<input id="tx" type="text" autofocus="true"></input>
<a href="javascript:void(0);" onclick="mov_search();">Buscar!</a>


<div id="search-output"></div>

<? html_end('movimientos');?>