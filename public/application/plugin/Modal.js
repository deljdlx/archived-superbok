Application.prototype.modal={
	containerSelector: 'pmd-application-modal'
};


Application.prototype.modal.show=function(content) {
	if(!document.getElementById(this.containerSelector)) {
		$('body').append(
			'<div id="'+this.containerSelector+'" class="modal fade" role="dialog" style="position: absolute">'+
			'	<div class="modal-dialog">'+
			'		<div class="modal-content">'+
			'			<div class="modal-header">'+
			'				<button type="button" class="close" data-dismiss="modal">&times;</button>'+
			'				<h4 class="modal-title">Modal Header</h4>'+
			'			</div>'+
			'			<div class="modal-body">'+
			'				<p>Some text in the modal.</p>'+
			'			</div>'+
			'			<div class="modal-footer">'+
			'				<button type="button" class="btn btn-default" data-dismiss="modal">Créer</button>'+
			'				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>'+
			'			</div>'+
			'		</div>'+
			'	</div>'+
			'</div>'
		)
	}
	$('#'+this.containerSelector).modal('show');
}

Application.prototype.modal.hide=function(content) {
	$('#'+this.containerSelector).modal('hide');
}