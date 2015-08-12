(function() {

	var tagName='pmd-form-image';

	document.addEventListener('DOMContentLoaded', function() {

		var link=document.createElement('link');
		link.setAttribute('rel', 'import');
		link.setAttribute('href', 'asset/webcomponent/pmd/form/image/template.html');
		link.addEventListener('load', function() {
			var content = this.import.querySelector('.template.pmd.form.image');

			document.body.appendChild(document.importNode(content, true));

			var prototype=Object.create(HTMLElement.prototype);

			prototype.createdCallback=function() {
				//Adding a Shadow DOM
				this.rootElement=this.createShadowRoot();
				var template = document.querySelector('.template.pmd.form.image');

				//new dom fragment instanciation
				var clone = document.importNode(template.content, true);

				this.rootElement.appendChild(clone);
				this.rootElement.querySelector('label').innerHTML=this.getAttribute('data-caption');
			};

			prototype.attachedCallback=function() {

				this.image = jQuery(jQuery(this.rootElement).find('img'));

				//img.attr('src', event.target.result);
				this.image.focusPointPicker(function(result){
					jQuery('#focus-point-result-2').html('Result : ' + JSON.stringify(result));
				});
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