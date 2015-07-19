<style>
	input{
		width: 250px;
	}
</style>
<div class="section-<?=$pluginName ?>">
	<div class="blocks-plugin">
		<div class="main-settings-container" id="tab-shop-settings">
			<h4>Второстепенные настройки шаблона</h4>
			<table class="main-settings-list">
				<tbody>
					<?foreach($entity as $item):?>
					<tr>
						<td><span><?=$item['name']?></span></td>
						<?if($item['option'] == 'aboutFooter'):?>
						<td><textarea cols="40" rows="8" class="settings-input option" name="<?=$item['option']?>"><?=$item['value']?></textarea></td>
						<?elseif($item['option'] == 'leftBanner'):?>
						<td>
							<p><input type="text" name="<?=$item['option']?>" value="<?=$item['value']?>" class="settings-input option" id="bannerField"></p>
							<div class="banner-img"><img src="<?=SITE.'/'.PLUGIN_DIR.'tpl-info/img/'.$item['value'];?>" width="270"></div>
							<form class="bannerform" method="post" noengine="true" enctype="multipart/form-data">
								<a href="javascript:void(0);" class="add-watermark" style="float:left; width:150px;">
								<span>Загрузить баннер</span>
								  <input type="file" name="photoimg" class="add-img">
								</a>
							</form>
						</td>
						<?else:?>
						<td><input type="text" name="<?=$item['option']?>" value="<?=$item['value']?>" class="settings-input option"></td>
						<?endif;?>
						<td><span><?=$item['desc']?></span></td>
					</tr>
					<?endforeach;?>
				</tbody>
			</table>
			<button class="save-button save-settings">
				<span>Сохранить</span>
			</button>
			<div class="clear"></div>
		</div>
	</div>
</div>