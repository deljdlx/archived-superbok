(function() {

	var tagName='pmd-form-integer';

	document.addEventListener('DOMContentLoaded', function() {



		var link=document.createElement('link');
		link.setAttribute('rel', 'import');
		link.setAttribute('href', 'asset/webcomponent/pmd/form/integer/template.html');
		link.addEventListener('load', function() {



			var content = this.import.querySelector('template.template.pmd.form.integer');



			document.body.appendChild(document.importNode(content, true));




			var prototype=Object.create(HTMLElement.prototype);
			prototype.createdCallback=function() {

				this.rootElement=this.createShadowRoot();

				var template = document.querySelector('template.template.pmd.form.integer');

				var clone = document.importNode(template.content, true);



				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('label').innerHTML=this.getAttribute('data-caption');

				$(this.rootElement.querySelector('.attribute_value')).val(this.getAttribute('data-value'));


				/*allowing only numbers
				$(this.rootElement.querySelector('.attribute_value')).keyup(function (e) {
					this.value=this.value.replace(/\D/gi, '');
				});
				*/



				if(this.subtype=='html') {

				}
			};

			prototype.attachedCallback=function() {


				if(typeof(componentHandler)!='undefined') {
					componentHandler.upgradeElement(this.rootElement.querySelector('.mdl-textfield'));
				}

				this.rootElement.querySelector('#input').setAttribute('name', this.getAttribute('data-name'));
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