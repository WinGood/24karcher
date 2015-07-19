var feedBackModule = (function(){
	return {
		msgBox: $('#feed-back-ajx-msg'),
		init: function()
		{
			$('#feed-back-send').bind('click', function(){
				feedBackModule.sendFeedBack();
				return false;
			});
		},
		sendFeedBack: function()
		{
			$('#feed-back-ajx-msg').html('Подождите, идет отправка комментария...');
			$('#feed-back-ajx-msg').addClass('active');
			$.ajax({
				url: mgBaseDir + '/ajax',
				dataType: 'json',
				type: 'POST',
				data: {
					mguniqueurl: 'action/send',
					pluginHandler: 'feed-back-ajx',
					fio: $('input[name="fio"]').val(),
					email: $('input[name="email"]').val(),
					message: $('textarea[name="message"]').val(),
					capcha: $('input[name="capcha"]').val()
				},
				cache: false,
				success: function(response)
				{
					console.log(response);
					if(response.status != 'error')
					{
						$('.add-cmt-form input').val('');
						$('.add-cmt-form textarea').val('');
						$('#feed-back-ajx-msg').addClass('success');
					}
					else
					{
						$('#feed-back-ajx-msg').addClass('error');
					}
					$('#feed-back-ajx-msg').html(response.msg);
				}
			});
		}
	}
})();

$(document).ready(function() {
	feedBackModule.init();
});