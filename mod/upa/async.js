var xmlHttp = ajaxRequestObject();

function ajaxRequestObject(){
	try{
		this.xmlHttp = new XMLHttpRequest();
	}catch(e){
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++){
			try{
				this.xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}catch (e) {}
		}
	}
	if (!this.xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else{
		console.info("async.lib.createXmlHttpRequestObject#xmlHttp object created");
		return this.xmlHttp;
	}
	
}
function checkBrowser(){
	return (window.XMLHttpRequest && window.XSLTProcessor && window.DOMParser) || 
			(window.ActiveXObject && createMsxml2DOMDocumentObject());
}
function createMsxml2DOMDocumentObject(){
	var msxml2DOM;
	var msxml2DOMDocumentVersions = new Array("Msxml2.DOMDocument.6.0","Msxml2.DOMDocument.5.0","Msxml2.DOMDocument.4.0");

	for (var i=0; i<msxml2DOMDocumentVersions.length && !msxml2DOM; i++){
		try{
			msxml2DOM = new ActiveXObject(msxml2DOMDocumentVersions[i]);
		}catch (e) {}
	}
	if (!msxml2DOM)
		alert("Please upgrade your MSXML version from \n" + "http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
	else
		return msxml2DOM;
}
