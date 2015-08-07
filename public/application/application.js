function Application() {
	this.moduleRoot='module';

	this.mainPanelNodeSelector='main.mainPanel';
	this.mainPanel=$(this.mainPanelNodeSelector);
	this.loadedJavascripts={};
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
			this.loadModule(request.call.module);
		}

		if(this.lastAction!=request.call.action) {
			this.lastAction=request.call.action;
			this.runAction(request.call.module, request.call.action);
		}
	}.bind(this), 200);
}


Application.prototype.runAction=function(module, action) {

	var data=action.split('.');
	var controller=data[0];
	var method=data[1];


	if(typeof(Application.modules[module])!='undefined') {
		var module=Application.modules[module]

		module[controller][method].call();
	}

	//console.debug(action);
	//eval(action+'()');
}




Application.prototype.loadModule=function(moduleName) {
	this.ajax({
		url: this.moduleRoot+'/'+moduleName+'/initialize',
		success: function(data) {
			this.mainPanel.html(data.view);


			if(typeof(data.javascripts)!='undefined') {
				for(var javascriptName in data.javascripts) {
					if(typeof(data.javascripts[javascriptName].url)!='undefined') {

						if(typeof(this.loadedJavascripts[data.javascripts[javascriptName].url])=='undefined') {
							this.loadedJavascripts[data.javascripts[javascriptName].url]=true

							var callback=function() {
								eval(data.javascripts[javascriptName].callback);
							};

							this.loadJavascript(data.javascripts[javascriptName].url, callback.bind(this));
						}
					}
				}
			}

		}.bind(this)
	});
}













Application.prototype.loadJavascript=function(url, callback) {
	$.getScript(url, callback);
}



Application.prototype.ajax=function(options) {
	return $.ajax(options);
}