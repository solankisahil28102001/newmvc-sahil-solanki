var ajax = {
	method : 'post',
	url : null,
	params: {},
	form : null,
	getUrl : function() {
		return this.url;
	},

	setUrl : function(url) {
		this.url = url;
		return this;
	},

	getMethod : function() {
		return this.method;
	},

	setMethod : function(method) {
		this.method = method;
		return this;
	},

	getForm : function() {
		return this.form;
	},

	setForm : function(formId) {
		this.form = $("#" + formId);
		this.prepareRequestParams();
		return this;
	},

	getParams : function() {
		return this.params;
	},

	setParams : function(params) {
		this.params = (params);
		return this;
	},

	prepareRequestParams: function () {
		this.setUrl(this.getForm().attr('action'));
		this.setMethod(this.getForm().attr('method'));
		this.setParams(this.getForm().serializeArray());
	},

	resetRequestParams: function() {
		this.setParams({});
		this.setMethod('post');
		this.setUrl(null);
	},

	call : function(){
		let self = this;
		$.ajax({
			url : this.getUrl(),
			type: this.getMethod(),
			data: this.getParams(),
			dataType: 'json'
		}).done(function(response) {
			$('#' + response.element).html(response.html);
			console.log(response.html);
			if (response.message !== undefined) {
				$('#message').html(response.message).addClass('alert bg-success text-white fw-bold');
			}
			self.resetRequestParams();
		});
	}

};

