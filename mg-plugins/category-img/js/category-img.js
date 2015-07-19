var categoryImgModule = (function() {
	return {

		field: '<label><form method="post"><span class="custom-text">Изображение категории:</span><input type="file" name="imgCategory"></form></label>',
		fieldCurrent: '<label><span class="custom-text">Текущее изображение:</span><input type="text" name="currentImg" disabled></label>',
		countCat: '',
		flag: '',
		categoryIdEdit: '',

		init: function()
		{
			admin.ajaxRequest({
				mguniqueurl: 'action/getCountCat',
				pluginHandler: 'category-img'
			}, function(response) {
				categoryImgModule.countCat = response.data.count;
			});

			$('#add-category-wrapper .category-filter').append(categoryImgModule.fieldCurrent + categoryImgModule.field);

			// Загрузка изображения

			$('input[name="imgCategory"]').bind('change', function(){
				$(this).parent('form').ajaxForm({
					type: 'POST',
					url: 'ajax',
					data: {
						mguniqueurl: 'action/loadImg',
						pluginHandler: 'category-img'
					},
					cache: false,
					dataType: 'json',
					success: function(response)
					{
						admin.indication(response.status, response.msg);
						$('input[name="currentImg"]').val(response.data.img);
					}
				}).submit();
			});

			// Редактирование категории

			$('.edit-sub-cat').bind('click', function(){
				categoryImgModule.categoryIdEdit = $(this).attr('id');
				categoryImgModule.flag = 'edit';
				admin.ajaxRequest({
					mguniqueurl: 'action/getImg',
					pluginHandler: 'category-img',
					id: categoryImgModule.categoryIdEdit
				}, function(response) {
					$('input[name="currentImg"]').val(response.data.img);
				});	
			});


			// Добавление категории

			$('.section-category .add-new-button').bind('click', function(){
				categoryImgModule.flag = 'add';
			});

			// Добавление в БД изображения

			$('#add-category-wrapper .save-button').bind('click', function(){
				var cat_id = $('#add-category-wrapper select[name="parent"]').val();
				var title  = $('#add-category-wrapper input[name="title"]').val();
				var url    = $('#add-category-wrapper input[name="url"]').val();
				if(title != '' && url != '')
				{
					if(categoryImgModule.flag == 'edit')
					{
						admin.ajaxRequest({
							mguniqueurl: 'action/editImg',
							pluginHandler: 'category-img',
							img: $('input[name="currentImg"]').val(),
							id_cat: categoryImgModule.categoryIdEdit
						}, function(response) {
							admin.indication(response.status, response.msg);
						});	
					}
					else if(categoryImgModule.flag == 'add')
					{
						admin.ajaxRequest({
							mguniqueurl: 'action/addImg',
							pluginHandler: 'category-img',
							img: $('input[name="currentImg"]').val(),
							id_cat: categoryImgModule.countCat
						}, function(response) {
							admin.indication(response.status, response.msg);
						});	
					}
				}
			});
		}
	}
})();

$(document).ready(function() {
	categoryImgModule.init();
});