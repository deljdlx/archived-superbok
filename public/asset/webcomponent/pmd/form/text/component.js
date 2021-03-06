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


				if(this.subtype=='html') {
					//$(this.rootElement).find('input').replaceWith('<textarea class="mdl-textfield__input" type="text" rows= "20" id="input" "></textarea>');
					var template = document.querySelector('template.template.pmd.form.text').content.querySelector('.html');
				}
				else {
					var template = document.querySelector('template.template.pmd.form.text').content.querySelector('.simple');
				}


				var clone = document.importNode(template.content, true);
				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('label').innerHTML=this.getAttribute('data-caption');

				$(this.rootElement.querySelector('.attribute_value')).val(this.getAttribute('data-value'));

				if(this.subtype=='html') {

				}
			};

			prototype.attachedCallback=function() {


				if(typeof(componentHandler)!='undefined') {
					componentHandler.upgradeElement(this.rootElement.querySelector('.mdl-textfield'));
				}

				this.rootElement.querySelector('#input').setAttribute('name', this.getAttribute('data-name'));



				if(this.subtype=='html') {
				}
				else {

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