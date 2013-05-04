/**
	 * input: x,y <- coordinates over window. Need to be transformed previously
	 * 			plot <- s2wPlot object where is called from
	 */
	function Point(x,y, plot){
			this.dateX = x; this.valueY = y;
			this.plot = plot;
			plot.readMaxMinY(y);
	}
	Point.prototype.draw = function(context,fillColor){
			this.x = this.transformX(this.dateX); 
			this.y = this.transformY(this.valueY);
			context.fillStyle = fillColor; 
			context.fillRect(this.x,this.y-10,10,10);
			context.fill()
	};
	Point.prototype.transformX = function (x){
		z = Math.abs(this.plot.Awx-this.plot.Owx)/this.plot.timediff(this.plot.Ad,this.plot.Od);
		return this.plot.Owx + this.plot.timediff(x,this.plot.Od)*z;
	}
	Point.prototype.transformY = function (y){
		w = Math.abs(y) * Math.abs(this.plot.Bwy-this.plot.Owy);
		return this.plot.Owy - w/this.plot.By;
	}
	function Path(name, color, plot, index){
		this.name = name;
		this.fillColor = color;
		this.Points = [];
		this.plot = plot;
		this.index = index;
		this.updateChecked();
	}
	Path.prototype.updateChecked = function(){
		input = document.getElementById('input_path_'+this.index);
		this.checked = (input == null)? true : input.checked;
	}
	Path.prototype.addPoint = function(x,y){
			this.Points.push(new Point(x,y, this.plot));
		}
	Path.prototype.drawPoints = function(){
			if(!this.checked) return;
			for(i=0; i<this.Points.length; i++){
				this.Points[i].draw(context,this.fillColor);
			}
		}
	function AbsciseMark(x,plot){
		this.x = plot.transformX(x);
		this.tick = x;
		this.plot = plot;
	}
	AbsciseMark.prototype.draw = function(context,color){
				context.fillStyle = color;
				context.textBaseline = "top";
				context.fillRect(this.x,this.plot.Owy,1,3);
				context.fillText(this.tick, this.x, this.plot.Owy+this.plot.MARGIN_ABSCISE_FONT);
				context.fill()
			}
	function OrdinateMark(y, plot){
		this.y = plot.transformY(y);
		this.tick = y;
		this.plot = plot;
	}
	OrdinateMark.prototype.draw = function(context, color){
			context.fillStyle = color; context.textAlign = "end";
			context.fillRect(this.plot.Owx, this.y,3,1);
			context.fillText(this.tick,this.plot.Owx-this.plot.MARGIN_ORDINATE_FONT, this.y);
		}
function s2wPlot(canvas){
	this.XSCALE = 0.8; this.YSCALE = 0.8; this.FONTSCALE = 0.1; this.POINTSCALE = 0.01; this.SCALE_Y_AXIS = 1.2;
	this.MARGIN_SIDES= 50; this.MARGIN_TOP= 20; this.MARGIN_ABSCISE_FONT = 5; this.MARGIN_ORDINATE_FONT = 2;
	this.BACKCOLOR = "#000"; this.COLOR_ABSCISE_TEXT = "#000"; this.COLOR_ORDINATES = "#000";
	this.DEFAULT_MIN_DATE = '2013-01-01'; this.DEFAULT_MAX_DATE = '2013-12-31';
	this.DEFAULT_MAX_VALUE = 500;
	
	this.canvas = canvas;
	this.Paths = [];
	
	this.rand = function (){
		num = Math.random()*255;
		return num.toFixed(0);}
	this.randomColor = function (){
		colorstr = "rgb("+this.rand()+","+this.rand()+","+this.rand()+")";
		
		return colorstr;
	}
	this.MAX_Y = this.DEFAULT_MAX_VALUE; this.MIN_Y=0;
	this.readMaxMinY = function (y){
		if(y>this.MAX_Y) this.MAX_Y = this.SCALE_Y_AXIS * y;
		if(y<this.MIN_Y) this.MIN_Y = y;
	}
	this.MAX_DATE = this.DEFAULT_MAX_DATE; this.MIN_DATE = this.DEFAULT_MIN_DATE;
	this.readMaxMinDate = function (d){
		if(new Date(MIN_DATE).getTime() > new Date(d).getTime()) MIN_DATE = d;
		if(new Date(MAX_DATE).getTime() < new Date(d).getTime()) MAX_DATE = d;
	}
	this.drawOrdinates = function (){
		step = Math.pow(10, Math.floor(Math.LOG10E * Math.log(this.MAX_Y - this.MIN_Y))); 
		
		y = (this.MIN_Y < 0)? 0: this.MIN_Y;
		do{
			new OrdinateMark(y, this).draw(context,this.COLOR_ORDINATES);
			y+=step;
		}while(y<this.MAX_Y)
		
	}
	this.drawAbscises = function (){
		this.date2string = function(d){
			y = d.getFullYear(); m = d.getMonth()+1; if(m<10) m= "0"+m
			
			return y+"-"+m+"-01";}
		
		toDate = new Date(this.MAX_DATE); fromDate = new Date(this.MIN_DATE);
		xDate = fromDate;
		
		do{
			x = this.date2string(xDate);
			new AbsciseMark(x, this).draw(context,this.COLOR_ORDINATES);
			
			xDate = new Date(xDate.getFullYear(),xDate.getMonth()+1,1);
			
		}while(xDate.getTime() < toDate.getTime());
		
	}
	/**
	 * input: d1, d2 <- strings of date as '2012-03-01'
	 * output: difference (in msec) between dates 
	 */
	this.timediff = function (d1, d2){
		r = Math.abs((new Date(d1).getTime() - new Date(d2).getTime()));
		return r;
	}
	this.scaleCanvas = function(){
		this.canvas.width = this.XSCALE * window.innerWidth;
		this.canvas.height = this.YSCALE * window.innerHeight;
		this.Awx = this.canvas.width - this.MARGIN_SIDES;
		this.Owx = this.MARGIN_SIDES;
		this.Bwy = this.MARGIN_TOP;
		this.Owy = this.canvas.height - this.MARGIN_TOP;
		this.Od = this.MIN_DATE; this.Ad = this.MAX_DATE;
		this.By = this.MAX_Y;
	}
	/**
	 * input: 	vd <- date of v in string format i.e. '2012-03-01'
	 * 			vv <- value of v in real, euro units
	 */
	this.transformX = function (x){
		return (this.Owx + this.timediff(x,this.Od)*Math.abs(this.Awx-this.Owx)/this.timediff(this.Ad,this.Od));
	}
	this.transformY = function (y){
		return this.Owy - y * Math.abs(this.Bwy-this.Owy)/this.By;
	}
	this.getContext = function(){
		context = this.canvas.getContext("2d");
		context.strokeStyle = this.BACKCOLOR;
		return context;
	}
	this.drawAxis = function(){
		this.context.moveTo(this.Owx,this.Owy); context.lineTo(this.Owx,this.Bwy);
		this.context.moveTo(this.Owx,this.Owy); context.lineTo(this.Awx,this.Owy);
	
		this.context.stroke();
	}
	this.setData = function(data){
		this.Data = eval((data));
		
		this.importData();
	}
	this.importData = function (){
		m = 0;
		for(concept in this.Data){
			this.Paths.push(new Path(concept, this.randomColor(), this, m));
			if(this.Data[concept] instanceof Array){
				for(i = 0; i<this.Data[concept].length; i++)
					this.Paths[m].addPoint(this.Data[concept][i]['cuando'], Math.abs(this.Data[concept][i]['cuanto']));
			
			}else if(this.Data[concept] instanceof Object){
				for(j in this.Data[concept])
					this.Paths[m].addPoint(j,this.Data[concept][j]);
				
			}else
				console.error('s2wPlot.importData#unknown data');		
			
			m++;
		}
	}
	this.drawPaths = function(){
		for(paths_index=0; paths_index<this.Paths.length; paths_index++){
			this.Paths[paths_index].updateChecked();
			this.Paths[paths_index].drawPoints();
		}
	}
	this.LegendLoaded = false;
	this.drawLegend = function(){
		div = document.getElementById('div_legend');
		s = "";
		for(i=0; i<this.Paths.length; i++){
			s += "<input id='input_path_"+i+"' checked='"+this.Paths[i].checked
							+"' type='checkbox' onchange='Plot.plot()'/> <label style='color: "+this.Paths[i].fillColor
							+"'>"+this.Paths[i].name+"</label><br>";
		}
		div.innerHTML = s;
		this.LegendLoaded = true;
	}
	this.plot = function(){
		if(this.Data == null) console.error('Data must be set first');
		console.info('plotting...');
		this.context = this.getContext();
		this.scaleCanvas();
		this.context.fillStyle = this.COLOR_ABSCISE_TEXT;
		this.context.textBaseline = "top";
		this.drawAxis();
		this.drawAbscises();
		this.drawOrdinates();
		
		this.drawPaths();
		
		if(!this.LegendLoaded) this.drawLegend();
		
	}
}
