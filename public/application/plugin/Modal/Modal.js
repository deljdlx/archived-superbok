Application.prototype.modal={
	containerSelector: 'pmd-application-modal',

	notificationContainerSelector:'pmd-notificationPopup-container'
};


Application.prototype.modal.show=function(content) {
	if(!document.getElementById(this.containerSelector)) {
		$('body').append(
			'<div id="'+this.containerSelector+'" class="modal fade" role="dialog" style="position: absolute">'+
			'	<div class="modal-dialog">'+
			'		<div class="modal-content">'+
			'		</div>'+
			'	</div>'+
			'</div>'
		);
	}
	this.container=document.getElementById(this.containerSelector);
	$(this.container).find('.modal-content').html(content);

	$('#'+this.containerSelector).modal('show');
}

Application.prototype.modal.hide=function() {
	$('#'+this.containerSelector).modal('hide');
}

Application.prototype.modal.notification=function(content, duration) {

	if(!document.getElementById(this.notificationContainerSelector)) {
		$('body').append(
			'<div id="'+this.notificationContainerSelector+'">'+
			'	<h4 class="content modal-header alert alert-success"></h4>'+
			'</div>'
		);
	}

	if(!duration) {
		duration=2500;
	}

	$('#'+this.notificationContainerSelector).find('.content').html(content);
	$('#'+this.notificationContainerSelector).addClass('active');
	setTimeout(function() {
		$('#'+this.notificationContainerSelector).removeClass('active');
	}.bind(this), duration)

}

Application.prototype.modal.alert=function(content) {
	this.show(
		'			<div class="modal-header alert alert-danger">'+
		'				<button type="button" class="close" data-dismiss="modal">&times;</button>'+
		'				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Erreur</h4>'+
		'			</div>'+
		'			<div class="modal-body">'+
		'				<p>'+content+'</p>'+
		'			</div>'+
		'			<div class="modal-footer">'+
		'				<button type="button" class="btn btn-default confirm" data-dismiss="modal">OK</button>'+
		'			</div>'
	);
}

Application.prototype.modal.showConfirmBox=function(content, callbackValidate, callbackCancel) {

	this.show(
		'			<div class="modal-header alert alert-info">'+
		'				<button type="button" class="close" data-dismiss="modal">&times;</button>'+
		'				<h4 class="modal-title"><i class="fa fa-question-circle"></i> Confirmation</h4>'+
		'			</div>'+
		'			<div class="modal-body">'+
		'				<p>'+content+'</p>'+
		'			</div>'+
		'			<div class="modal-footer">'+
		'				<button type="button" class="btn btn-default confirm" >OK</button>'+
		'				<button type="button" class="btn btn-default cancel" data-dismiss="modal">Annuler</button>'+
		'			</div>'
	);

	$(this.container).find('button.confirm').click(callbackValidate)


}