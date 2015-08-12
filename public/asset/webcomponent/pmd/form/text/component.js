(function() {

	var tagName='pmd-form-text';

	document.addEventListener('DOMContentLoaded', function() {

		var link=document.createElement('link');
		link.setAttribute('rel', 'import');
		link.setAttribute('href', 'asset/webcomponent/pmd/form/text/template.html');
		link.addEventListener('load', function() {
			var content = this.import.querySelector('.template.pmd.form.text');

			document.body.appendChild(document.importNode(content, true));

			var prototype=Object.create(HTMLElement.prototype);

			prototype.createdCallback=function() {
				//Adding a Shadow DOM
				this.rootElement=this.createShadowRoot();
				var template = document.querySelector('.template.pmd.form.text');

				var clone = document.importNode(template.content, true);

				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('label').innerHTML=this.getAttribute('data-caption');
			};

			prototype.attachedCallback=function() {

				componentHandler.upgradeElement(this.rootElement.querySelector('.mdl-textfield'));
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