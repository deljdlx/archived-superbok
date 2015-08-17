(function() {

	var tagName='pmd-form-boolean';

	document.addEventListener('DOMContentLoaded', function() {



		var link=document.createElement('link');
		link.setAttribute('rel', 'import');
		link.setAttribute('href', 'asset/webcomponent/pmd/form/boolean/template.html');
		link.addEventListener('load', function() {



			var content = this.import.querySelector('template.template.pmd.form.boolean');


			document.body.appendChild(document.importNode(content, true));


			var prototype=Object.create(HTMLElement.prototype);
			prototype.createdCallback=function() {

				this.rootElement=this.createShadowRoot();

				var template = document.querySelector('template.template.pmd.form.boolean');

				var clone = document.importNode(template.content, true);



				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('span.mdl-switch__label').innerHTML=this.getAttribute('data-caption');


				//$(this.rootElement.querySelector('.attribute_value')).val(this.getAttribute('data-value'));
				if(this.getAttribute('data-value')) {
					$(this.rootElement.querySelector('.attribute_value')).attr('checked', 'checked');
				}


			};

			prototype.attachedCallback=function() {


				if(typeof(componentHandler)!='undefined') {
					componentHandler.upgradeElement(this.rootElement.querySelector('.mdl-switch'));
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