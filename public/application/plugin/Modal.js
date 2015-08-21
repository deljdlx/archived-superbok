Application.prototype.modal={
	containerSelector: 'pmd-application-modal'
};


Application.prototype.modal.show=function(content) {
	if(!document.getElementById(this.containerSelector)) {
		$('body').append(
			'<div id="'+this.containerSelector+'" class="modal fade" role="dialog" style="position: absolute">'+
			'	<div class="modal-dialog">'+
			'		<div class="modal-content">'+
						content+
			'		</div>'+
			'	</div>'+
			'</div>'
		);

		this.container=document.getElementById(this.containerSelector);
	}

	$('#'+this.containerSelector).modal('show');
}

Application.prototype.modal.hide=function() {
	$('#'+this.containerSelector).modal('hide');
}


Application.prototype.modal.showConfirmBox=function(content, callbackValidate, callbackCancel) {
	//console.debug(this)
	this.show(
		'			<div class="modal-header">'+
		'				<button type="button" class="close" data-dismiss="modal">&times;</button>'+
		'				<h4 class="modal-title">Confirmation</h4>'+
		'			</div>'+
		'			<div class="modal-body">'+
		'				<p>'+content+'</p>'+
		'			</div>'+
		'			<div class="modal-footer">'+
		'				<button type="button" class="btn btn-default confirm" data-dismiss="modal">OK</button>'+
		'				<button type="button" class="btn btn-default cancel" data-dismiss="modal">Annuler</button>'+
		'			</div>'
	);

	$(this.container).find('button.confirm').click(callbackValidate)


}