(function() {


	//==================================================================================

	var initializeComponent=function() {

		var flipperPrototype=Object.create(HTMLElement.prototype);
		
		//==================================================================================

		flipperPrototype.createdCallback=function() {
			//Adding a Shadow DOM
			this.rootElement=this.createShadowRoot();
			//loading template
			var template = document.querySelector('#eb-flipper');
			
			//new dom fragment instanciation
			var clone = document.importNode(template.content, true);
			this.rootElement.appendChild(clone);
			
			this.flipperElement=this.rootElement.querySelector('.flipper');
			
		};
		
		
		

		flipperPrototype.attachedCallback=function() {
			
			
			this.flipperElement.onclick=function() {
			
				var event = new Event('flip');
				// Listen for the event.
				//elem.addEventListener('build', function (e) { ... }, false);
				// Dispatch the event.
				this.dispatchEvent(event);
			
				if(this.className.match(/flipped/gi)) {
					this.className="flipper";
				}
				else {
					this.className="flipper flipped";
				}
				
			}
		};



		flipperPrototype.detachedCallback=function() {
			console.debug('detached');
		}

		flipperPrototype.attributeChangedCallback=function() {
			console.debug('attribute changed');
		};



		var flipper=document.registerElement('eb-flipper', {
		  prototype: flipperPrototype
		});
	};




	var link=document.createElement('link');
	link.setAttribute('rel', 'import');
	link.setAttribute('href', 'component/00-flipper/component.html');
	
	
	link.addEventListener('load', function() {
		var content = this.import.querySelector('#eb-flipper');
		document.body.appendChild(document.importNode(content, true));
		initializeComponent();
	});
	document.getElementsByTagName('head')[0].appendChild(link);
	
})();

