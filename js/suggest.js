var getFunctionsUrl = "suggest.php?keyword=";
var phpHelpUrl="http://www.php.net/manual/en/function.";
var httpRequestKeyword = "";
var userKeyword = "";
var suggestions = 0;
var suggestionMaxLength = 30;
var isKeyUpDownPressed = false;
var autocompletedKeyword = "";
var hasResults = false;
var timeoutId = -1;
var position = -1;
var oCache = new Object();
var minVisiblePosition = 0;
var maxVisiblePosition = 9;
var debugMode = true;
var xmlHttpGetSuggestions = createXmlHttpRequestObject();


function createXmlHttpRequestObject(){
 return xmlHttp;
}

function suggest_init(){
	var oKeyword = document.getElementById("que");
	
	oKeyword.setAttribute("autocomplete", "off");
	oKeyword.value = "";
	oKeyword.focus();
	setTimeout("checkForChanges()", 500);
	
	document.onclick = hideSuggestions;
}
	
function addToCache(keyword, values){
	oCache[keyword] = new Array();
	
	for(i=0; i<values.length; i++)
		oCache[keyword][i] = values[i];
}
	
function checkCache(keyword){
	if(oCache[keyword])
		return true;

	for(i=keyword.length-2; i>=0; i--){
		var currentKeyword = keyword.substring(0, i+1);
	
		if(oCache[currentKeyword]){
			var cacheResults = oCache[currentKeyword];
			var keywordResults = new Array();
			var keywordResultsSize = 0;
			for(j=0;j<cacheResults.length;j++){
				if(cacheResults[j].indexOf(keyword) == 0)
					keywordResults[keywordResultsSize++] = cacheResults[j];
			}
					
			addToCache(keyword, keywordResults);
			return true;
		}
	}
	return false;
}
				
function getSuggestions(keyword){console.info("getting suggestions for "+keyword);
	if(keyword != "" && !isKeyUpDownPressed){
		isInCache = checkCache(keyword);
		if(isInCache == true){
			httpRequestKeyword=keyword;
			userKeyword=keyword;
			displayResults(keyword, oCache[keyword]);
		}else{
			if(xmlHttpGetSuggestions){
				try{
					if (xmlHttpGetSuggestions.readyState == 4 || xmlHttpGetSuggestions.readyState == 0){
						httpRequestKeyword = keyword;
						userKeyword = keyword;
						xmlHttpGetSuggestions.open("GET", getFunctionsUrl + encode(keyword), true);
						xmlHttpGetSuggestions.onreadystatechange =
						handleGettingSuggestions;
						xmlHttpGetSuggestions.send(null);
					}else{
						userKeyword = keyword;
							if(timeoutId != -1)
								clearTimeout(timeoutId);
						timeoutId = setTimeout("getSuggestions(userKeyword);", 500);
					}
				}catch(e){
					displayError("Can't connect to server:\n" + e.toString());
				}
			}
		}
	}
}
			
function xmlToArray(resultsXml){
	var resultsArray= new Array();
	for(i=0;i<resultsXml.length;i++)
		resultsArray[i]=resultsXml.item(i).firstChild.data;
		
	return resultsArray;
}
		
function handleGettingSuggestions(){
	if (xmlHttpGetSuggestions.readyState == 4){
		if (xmlHttpGetSuggestions.status == 200){
			try{
				updateSuggestions();
			}catch(e){
				displayError(e.toString());
			}
		}else{
			displayError("There was a problem retrieving the data:\n" +	xmlHttpGetSuggestions.statusText);
		}
	}
}

function updateSuggestions(){
		var response = xmlHttpGetSuggestions.responseText;
		if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0)
			throw(response.length == 0 ? "Void server response." : response);
			
		response = xmlHttpGetSuggestions.responseXML.documentElement;
				
		nameArray = new Array();
				
		if(response.childNodes.length){
				nameArray= xmlToArray(response.getElementsByTagName("name"));
		}
				
		if(httpRequestKeyword == userKeyword){
			displayResults(httpRequestKeyword, nameArray);
		}else{
				addToCache(httpRequestKeyword, nameArray);
		}
}
				
function displayResults(keyword, results_array){
	var div = "<table>";
	
	if(!oCache[keyword] && keyword)
		addToCache(keyword, results_array);
				
	if(results_array.length == 0){
		div += "<tr><td>No hay resultados para <strong>" + keyword + "</strong></td></tr>";
		hasResults = false;
		suggestions = 0;
	}else{
		position = -1;
		isKeyUpDownPressed = false;
		hasResults = true;
		suggestions = oCache[keyword].length;
		for (var i=0; i<oCache[keyword].length; i++){
			crtFunction = oCache[keyword][i];
			crtFunctionLink = crtFunction;
			
			while(crtFunctionLink.indexOf("_") !=-1)
				crtFunctionLink = crtFunctionLink.replace("_","-");
				
			div += "<tr id='tr" + i + "' onmouseover='handleOnMouseOver(this);' " + "onmouseout='handleOnMouseOut(this);'>" +
				"<td align='left'>"+crtFunction+"</td></tr>";

		}
	}
	
	div += "</table>";
	var oSuggest = document.getElementById("suggest");
	var oScroll = document.getElementById("scroll");
	
	oScroll.scrollTop = 0;
	oSuggest.innerHTML = div;
	oScroll.style.visibility = "visible";
	if(results_array.length > 0)
		autocompleteKeyword();
}

function checkForChanges(){
	var keyword = document.getElementById("que").value;
					
	if(keyword == ""){
		hideSuggestions();
		userKeyword="";
		httpRequestKeyword="";
	}
					
	setTimeout("checkForChanges()", 500);
					
	if((userKeyword != keyword) && (autocompletedKeyword != keyword) && (!isKeyUpDownPressed))
		getSuggestions(keyword);
}
			
function handleKeyUp(e){
	e = (!e) ? window.event : e;
	target = (!e.target) ? e.srcElement : e.target;
	
	if (target.nodeType == 3)
		target = target.parentNode;
					
	code = (e.charCode) ? e.charCode : ((e.keyCode) ? e.keyCode : ((e.which) ? e.which : 0)); console.info ("key code= "+code);
					
	if (e.type == "keyup"){
		isKeyUpDownPressed =false;
	
	if ((code < 13 && code != 8) || (code >=14 && code < 32) ||
		(code >= 33 && code <= 46 && code != 38 && code != 40) || (code >= 112 && code <= 123)){
	}else
		if(code == 13){//enter pressed
			if(position>=0){
				hideSuggestions();
			}
		}else
			if(code == 40){//down arrow pressed
				newTR=document.getElementById("tr"+(++position));
				oldTR=document.getElementById("tr"+(--position));
					
				if(position>=0 && position<suggestions-1)// deselect the old selected suggestion
					oldTR.className = "";
					
				if(position < suggestions - 1){// select the new suggestion and update the keyword
					newTR.className = "highlightrow";
					updateKeywordValue(newTR);
					position++;
				}
				e.cancelBubble = true;
				e.returnValue = false;
				isKeyUpDownPressed = true;
					
				if(position > maxVisiblePosition){// scroll down if the current window is no longer valid
					oScroll = document.getElementById("scroll");
					oScroll.scrollTop += 18;
					maxVisiblePosition += 1;
					minVisiblePosition += 1;
				}
			}else
				if(code == 38){// if the up arrow is pressed we go to the previous suggestion
					newTR=document.getElementById("tr"+(--position));
					oldTR=document.getElementById("tr"+(++position));
					
					if(position>=0 && position <= suggestions - 1){// deselect the old selected position
						oldTR.className = "";
					}
					
					if(position > 0){// select the new suggestion and update the keyword
						newTR.className = "highlightrow";
						updateKeywordValue(newTR);
						position--;
					
						if(position<minVisiblePosition){// scroll up if the current window is no longer valid
							oScroll = document.getElementById("scroll");
							oScroll.scrollTop -= 18;
							maxVisiblePosition -= 1;
							minVisiblePosition -= 1;
						}
					}else
						if(position == 0)
							position--;
					
					e.cancelBubble = true;
					e.returnValue = false;
					isKeyUpDownPressed = true;
				}
			}else if(code == 9 ){
				hideSuggestions();
				if(e.preventDefault()) {
                	e.preventDefault();
            	}
			}
}
					
function updateKeywordValue(oTr){console.info("updating keyword "+oTr.value);
	var oKeyword = document.getElementById("que");
	
	var cells = oTr.cells
	oKeyword.value = cells[0].innerHTML;
}
		
function deselectAll(){
	for(i=0; i<suggestions; i++){
		var oCrtTr = document.getElementById("tr" + i);
		oCrtTr.className = "";
	}
}
					
function handleOnMouseOver(oTr){
	deselectAll();
	oTr.className = "highlightrow";
	position = oTr.id.substring(2, oTr.id.length);
}
					
function handleOnMouseOut(oTr){
	oTr.className = "";
	position = -1;
}
					
function encode(uri){
	if (encodeURIComponent)
		return encodeURIComponent(uri);
	
	if (escape)
		return escape(uri);
}
					
function hideSuggestions(){
	var oScroll = document.getElementById("scroll");
	//oScroll.style.visibility = "hidden";
	oScroll.className = "hidden";
}
					
function selectRange(oText, start, length){
	if (oText.createTextRange){
		var oRange = oText.createTextRange();
		oRange.moveStart("character", start);
		oRange.moveEnd("character", length - oText.value.length);
		oRange.select();
	}else
		if (oText.setSelectionRange){
			oText.setSelectionRange(start, length);
		}
		oText.focus();
}
		
function autocompleteKeyword(){
	var oKeyword = document.getElementById("que");
	position=0;
	
	deselectAll();
	
	document.getElementById("tr0").className="highlightrow";
	updateKeywordValue(document.getElementById("tr0"));

	selectRange(oKeyword,httpRequestKeyword.length,oKeyword.value.length);

	autocompletedKeyword=oKeyword.value;
}
	
function displayError(message){
	alert("Error accessing the server! "+ (debugMode ? "\n" + message : ""));
}
