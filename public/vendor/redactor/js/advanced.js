$.Redactor.prototype.advanced = function()
{
    return {
        getTemplate: function()
        {
            return String()
            + '<section id="redactor-modal-advanced">'
            + '<label>Enter a text</label>'
            + '<textarea id="mymodal-textarea" rows="6"></textarea>'
            + '</section>';
        },
        init: function ()
        {
            var button = this.button.add('advanced', 'Advanced');
            this.button.addCallback(button, this.advanced.show);
 
            // make your added button as Font Awesome's icon
            this.button.setAwesome('advanced', 'fa-flag');
        },
        show: function()
        {
            this.modal.addTemplate('advanced', this.advanced.getTemplate());
 
            this.modal.load('advanced', 'Advanced Modal', 400);
 
            this.modal.createCancelButton();
 
            var button = this.modal.createActionButton('Insert');
            button.on('click', this.advanced.insert);
 
            this.selection.save();
            this.modal.show();
 
            $('#mymodal-textarea').focus();
        },
        insert: function()
        {
            var html = $('#mymodal-textarea').val();
 
            this.modal.close();
            this.selection.restore();



			var node=document.createElement('eb-flipper');
			node.innerHTML=
			'	<div class="front">front</div>'+
			'	<div class="back">back</div>';
			
            this.insert.node(
				node
			);
 
            this.code.sync();
 
        }
    };
};