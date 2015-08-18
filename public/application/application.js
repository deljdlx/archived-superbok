function Application() {
	this.moduleRoot='moduleview';

	this.mainPanelNodeSelector='main.mainPanel';
	this.mainPanel=$(this.mainPanelNodeSelector);

	this.modules={};

	Application.mainInstance=this;
}

Application.modules={};


Application.prototype.start=function() {
	this.route();
}


Application.getInstance=function() {
	return Application.mainInstance;
}


Application.prototype.getModule=function (name) {
	return this.modules[name];
}


Application.prototype.setMainPanelContent=function(content) {
	this.mainPanel.html(content);
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


	console.debug(request);

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
			var module=new Module();
			module.loadData(data);
			module.start(function() {
				this.runAction(moduleName, request);
			}.bind(this));

			this.modules[moduleName]=module;

			return;

		}.bind(this)
	});
}



Application.prototype.ajax=function(options) {
	return $.ajax(options);
}




