function Application() {
	this.moduleRoot='moduleview';

	this.mainPanelNodeSelector='main.mainPanel';
	this.mainPanel=$(this.mainPanelNodeSelector);
	this.loadedJavascripts={};
	this.loadedCSS={};
}


Application.modules={};

Application.prototype.start=function() {

	this.route();

}



Application.prototype.getParameters=function(buffer) {

	var parametersBuffer=buffer.replace(/.*?#(.*)/g, '$1');
	parametersBuffer=parametersBuffer.replace(/^&/, '');

	var callParametersBuffer=parametersBuffer.replace(/(.*?)\?.*/, '$1');
	var userParametersBuffer=parametersBuffer.replace(/.*?\?(.*)/, '$1');


	var parametersBuffers=callParametersBuffer.split('&');
	var parameters={};

	for(var i=0; i<parametersBuffers.length; i++) {
		var userParameters=parametersBuffers[i].split('=');
		parameters[userParameters[0]]=userParameters[1];
	}


	var parametersBuffers=userParametersBuffer.split('&');
	var customParameters={};

	for(var i=0; i<parametersBuffers.length; i++) {
		var userParameters=parametersBuffers[i].split('=');
		customParameters[userParameters[0]]=userParameters[1];
	}




	return {
		call: parameters,
		parameters: customParameters
	};
}

Application.prototype.route=function() {

	this.lastModule=null;
	this.lastAction=null;

	this.routeInterval=setInterval(function() {
		var request=this.getParameters(document.location.toString())


		if(this.lastModule!=request.call.module) {
			this.lastModule=request.call.module;
			this.loadModule(request.call.module, request);
		}

		if(this.lastAction!=request.call.action) {
			this.lastAction=request.call.action;
			this.runAction(request.call.module, request);
		}

	}.bind(this), 200);
}


Application.prototype.runAction=function(moduleName, request) {

	var action=request.call.action;

	var data=action.split('.');
	var controller=data[0];
	var method=data[1];


	if(typeof(Application.modules[moduleName])!='undefined') {

		console.debug(Application.modules);

		var module=Application.modules[moduleName]
		module[controller][method].call();
	}
}




Application.prototype.loadModule=function(moduleName, request) {
	this.ajax({
		url: this.moduleRoot+'/'+moduleName+'/initialize',
		success: function(data) {

			this.mainPanel.html(data.view);


			if(typeof(data.css)!='undefined') {
				for (var cssName in data.css) {
					if (typeof(this.loadedCSS[data.css[cssName].url]) == 'undefined') {
						this.loadedCSS[data.css[cssName].url] = data
						this.loadCSS(data.css[cssName].url);
					}
				}
			}



			if(typeof(data.javascripts)!='undefined') {
				var loadedScripts=0;
				var nbSripts=0;
				for(var javascriptName in data.javascripts) {
					nbSripts++;
					if(typeof(data.javascripts[javascriptName].url)!='undefined') {

						if(typeof(this.loadedJavascripts[data.javascripts[javascriptName].url])=='undefined') {
							this.loadedJavascripts[data.javascripts[javascriptName].url] = data

							if (nbSripts == Object.keys(data.javascripts).length) {
								var callback = function () {
									this.runAction(moduleName, request);
								}
							}
							else {
								var callback = function () {
									loadedScripts++;
								};
							}
							this.loadJavascript(data.javascripts[javascriptName].url, callback.bind(this));
						}
						else {
							this.runAction(moduleName, request);
						}
					}
				}
			}

		}.bind(this)
	});
}





Application.prototype.loadCSS=function(url) {
	$('head').append('<link rel="stylesheet" href="'+url+'"></link>');
}









Application.prototype.loadJavascript=function(url, callback) {
	$.getScript(url, callback);
}



Application.prototype.ajax=function(options) {
	return $.ajax(options);
}