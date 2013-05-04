<canvas id="canvas" style="border:1px solid #d3d3d3;">cargando..</canvas>
<div id="div_legend" style="position: absolute; top: 10px; right: 10px; z-index: 10; border:1px solid #d3d3d3; font-size: 12px;">cargando...</div>
<script src="../js/ajax.js"></script>
<script src="../js/sens2web.plot.js"></script>
<script>
	var Plot = new s2wPlot(document.getElementById('canvas'));
	
	ajax({
		url: "informes.promedios.php?y=<?=$_GET['y']?>&cc=<?=$_GET['cc']?>",
		type: "json",
		onSuccess: function(data){
			R = data.split(';');
			if(eval(R[0])){
				Plot.setData("("+R[1]+")");
				Plot.plot();
			}else
				console.error("recibido false del servidor");
		},
		onError: function(){
			console.error('error getting data from '+url)
		}
	});
	
		

	window.onresize = function(){
		Plot.plot();
	}
</script>