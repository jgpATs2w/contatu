
/*
 * 
 * magic_table_sortable definition
 * Argument options: requires TABLE_ID, DATA (json object)
 * 
 * Styling: 
 * 		.magic_table
 * 		.magic_table .headers_rows
 * 		.magic_table .headers_columns
 * 		.magic_table .body
 */
function magic_table_sortable(options){
	
	var HR = [], HC = [];// HR= Header Rows; HC = Header Columns
	
	this.loadTable = function (){ 
		D = options.DATA[0];//D = options.DATA[0].sort(function(a,b){return a.id > b.id});
		
		var t = document.getElementById(options.TABLE_ID);
			t.className = "magic_table";
			t.innerHTML ="";
			//headers
			var r = t.insertRow(0);
				r.className = "headers_columns";
			var h = r.insertCell(0); h.innerHTML = "";
			for(var i = 0; i<HC.length; i++){
				var h = r.insertCell(i+1); h.innerHTML = HC[i].split('-')[1];
			}
			
			//body
			for(i=0;i<HR.length;i++){
					var id_prefix = "td_"+i;
					var row = t.insertRow(i+1);
					
					var c = row.insertCell(0); c.innerHTML = HR[i];//first column
						c.className = "headers_rows";
					
					for(var j = 0; j<HC.length; j++){
						c = row.insertCell(j+1); c.innerHTML = D[HR[i]][HC[j]]; 
							c.className = "body";
							c.id="cell_"+HR[i]+"_"+HC[j]; 
					}
			}
					
		}
		this.checkOptions = function(){
			var A = new Array("DATA","TABLE_ID");
			
			for(var key in A){
				if (!(A[key] in options)){ console.error('FATAL! falta '+A[key]+' en options. '); return false;} 
			}
			return true;
		}
		this.readHeaders = function(){
			D = options.DATA[0];
			i=0; for(var row in D){
				HR[i] = row; i++;
				if(i>1) continue;
				j=0; for(var col in D[row]){
					HC[j] = col;
				j++;}
			}
			
		}
		this.show = function(){
			if(checkOptions()){
				readHeaders();
				loadTable();
				return this;
			}else
				return false;
		}
		
		return this;
		
	}

	
	