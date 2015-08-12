function Module()
{
	this.views={};
	this.javascripts={};
	this.css={};
}




Module.prototype.addView=function(name, content) {
	this.views[name]=content;
	return this;
}

Module.prototype.addCSS=function(name, content) {
	this.css[name]=content;
	return this;
}

Module.prototype.addJavascript=function(name, content) {
	this.javascripts[name]=content;
	return this;
}
Module.prototype.getView=function(name) {
	if(typeof(this.views[name])!='undefined') {
		return this.views[name];
	}
	else {
		return false;
	}

}


Module.prototype.loadData=function(data) {

	for(var name in data.views) {
		this.addView(name, data.views[name]);
	}
	for(var name in data.javascripts) {
		this.addJavascript(name, data.javascripts[name]);
	}
	for(var name in data.css) {
		this.addCSS(name, data.css[name]);
	}
	return this;
}

Module.prototype.start=function(callback) {


	for(var name in this.css) {
		this.loadCSS(name);
	}

	var customCallback=function() {
		customCallback.nbScriptLoaded++;

		console.debug(customCallback.nbScriptLoaded, customCallback.nbScript);

		if(customCallback.nbScriptLoaded==customCallback.nbScript) {
			customCallback.callback();
		}
	}

	customCallback.nbScript=Object.keys(this.javascripts).length
	customCallback.nbScriptLoaded=0;
	customCallback.callback=callback;

	for(var name in this.javascripts) {
		this.loadJavascript(name, customCallback);
	}
}




Module.prototype.loadJavascript=function(name, callback) {
	$.getScript(this.javascripts[name].url, callback);
}

Module.prototype.loadCSS=function(name) {
	$('head').append('<link rel="stylesheet" href="'+this.css[name].url+'"></link>');
}


