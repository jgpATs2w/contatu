function Plot(){
	this.cid ="canvas";
	this.myColor = ["#ECD078","#D95B43","#C02942","#542437","#53777A","#ECD078","#D95B43","#C02942","#542437","#53777A",
		"#ECD078","#D95B43","#C02942","#542437","#53777A","#ECD078","#D95B43","#C02942","#542437","#53777A",
		"#ECD078","#D95B43","#C02942","#542437","#53777A","#ECD078","#D95B43","#C02942","#542437","#53777A"];
	this.myData = [10,30,20,60,40];
	this.T = ["uno","dos","tres","cuatro","cinco"];

	this.getTotal = function(){
		var myTotal = 0;
		for (var j = 0; j < this.myData.length; j++) 
			myTotal += this.myData[j];
		
		return myTotal;
	}
	
	this.go = function() {
		var r = 150; var ox = 250; var oy= 200; var to = 30;
		var canvas;
		var ctx;
		var lastend = 0;
		var myTotal = this.getTotal();
		
		canvas = document.getElementById(this.cid);
		ctx = canvas.getContext("2d");
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		
		for (var i = 0; i < this.myData.length; i++) {
			var inc =Math.PI*2*(this.myData[i]/myTotal);
			ctx.fillStyle = this.myColor[i];
			ctx.beginPath();
			ctx.moveTo(ox,oy);
			ctx.arc(ox,oy,r,lastend,lastend+inc,false);
			ctx.lineTo(ox,oy);
			ctx.fill();
			ctx.fillStyle = 'black';
			ctx.fillText(this.T[i],ox+Math.cos(lastend+inc/2)*(r+to),oy+Math.sin(lastend+inc/2)*(r+to));
			lastend += inc;
		}
	}
	this.plotGraph = function() {
		
	}
}