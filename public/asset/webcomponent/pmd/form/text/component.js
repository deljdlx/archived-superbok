(function() {

	var tagName='pmd-form-text';

	document.addEventListener('DOMContentLoaded', function() {



		var link=document.createElement('link');
		link.setAttribute('rel', 'import');
		link.setAttribute('href', 'asset/webcomponent/pmd/form/text/template.html');
		link.addEventListener('load', function() {



			var content = this.import.querySelector('template.template.pmd.form.text');

			console.debug(content);

			document.body.appendChild(document.importNode(content, true));




			var prototype=Object.create(HTMLElement.prototype);
			prototype.createdCallback=function() {

				//Adding a Shadow DOM
				this.rootElement=this.createShadowRoot();

				this.subtype=this.getAttribute('data-subtype');
				var template = document.querySelector('template.template.pmd.form.text');


				var clone = document.importNode(template.content, true);
				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('label').innerHTML=this.getAttribute('data-caption');

				if(this.subtype=='html') {

					$(this.rootElement).find('label').removeClass('mdl-textfield__label');
					$(this.rootElement).find('input').parent('div').removeClass('mdl-textfield');
					$(this.rootElement).find('input').replaceWith('<div class="pmd form text html" style="min-height: 300px; width:100%;"></div>');
				}

			};

			prototype.attachedCallback=function() {




				if(this.subtype=='html') {

				}
				else {
					if(typeof(componentHandler)!='undefined') {
						componentHandler.upgradeElement(this.rootElement.querySelector('.mdl-textfield'));
					}
				}
			};

			prototype.detachedCallback=function() {
				console.log("Retrait du DOM");
			};

			document.registerElement(
				tagName, {
					prototype: prototype
				}
			);
		});
		document.getElementsByTagName('head')[0].appendChild(link);
	});



})();