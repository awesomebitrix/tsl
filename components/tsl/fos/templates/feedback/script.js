function SendForm(folder, formId){
	this.url = folder + '/handler.php'
	// this.url = document.location.href;
	this.feedbackform = $('#form_' + formId);
	this.btnWrap = this.feedbackform.find('.btn_cover');
	this.bindEvents();
}

SendForm.prototype.bindEvents = function() {
	var that = this,
	isValidate = validate( $(this) );
	that.feedbackform.on('submit', function() {			
		if (isValidate){
			var query = that.feedbackform.serializeArray();
			that.SendRequest(query);
			return false;
		}
	});
};

SendForm.prototype.SendRequest = function(query) {
	var that = this;
    $.ajax({
        url: that.url,
        async: true,
        context: this,
        data: query,
        type: 'POST'
    }).done(function(data){
		that.ShowMessage(jQuery.parseJSON(data));
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        that.ShowMessage();
    });
}

SendForm.prototype.ShowMessage = function(data) {
	var that = this;
	if (data.success){
		that.btnWrap.append('<div class="important" style="text-align:center">Сообщение отправлено!</div><br>');
	}else{
		that.btnWrap.append('<div class="important" style="text-align:center">Произошла ошибка!</div><br>');
	}
}

