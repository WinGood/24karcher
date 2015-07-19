$(document).ready(function() {
	
	$('#usrPhone').mask('9(999) 999-99-99');

	$('#ajxcallBackBtn').fancybox({
		minWidth: 300,
	});

	$('#ajxCallBack button').bind('click', function()
	{
		// Блокируем отправку формы
		$(this).addClass('disable');

		var formEl  = $('#ajxCallBack');
		var rfEl    = $('#ajxCallBack').find('.rf');
		var emptyEl = [];

		if(rfEl.length > 0)
		{
			rfEl.each(function(){
				if(this.value == '')
				{
					lightField(this);
					emptyEl.push(this);
				}
			});

			if(emptyEl.length == 0)
			{
				$(this).removeClass('disable');
			}
		}
		else
		{
			$(this).removeClass('disable');
		}

		function lightField(el)
		{
			$(el).css('border', '1px solid red');
			setTimeout(function(){
				$(el).removeAttr('style');
			}, 500);
		}

		if(!$(this).hasClass('disable'))
		{
			$.ajax({
				url: mgBaseDir + '/ajax',
				dataType: 'json',
				type: 'POST',
				data:{
				  mguniqueurl: 'action/handlerCall',
				  pluginHandler: 'call-back',
				  name: $('#usrName').val(),
				  phone: $('#usrPhone').val(),
				  comment: $('#usrComment').val(),
				  invisible: 0
				},
				success: function(response)
				{
					if(response.status != 'error')
						formEl.addClass('success');
					else
						formEl.addClass('error');
					
					formEl.find('.content-modal').empty();
					formEl.append('<p>'+response.msg+'</p>');
				}
			});
		}

	});
});