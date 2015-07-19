<div class="section-<?=$pluginName ?>">
	<div class="blocks-plugin">
		<div class="clearfix top-block">
			<div class="left">
				<div class="title-entity">Слайды</div>
			</div>
			<div class="right">
				<div class="add-new-button">
					<span>Добавить слайд</span>
				</div>
			</div>
		</div>
		  <div class="wrapper-entity-setting">
		    <div class="clear"></div>
		    <div class="entity-table-wrap">
		      <div class="clear"></div>
		      <div class="entity-settings-table-wrapper">
		        <table class="widget-table">
		          <thead>
		            <tr>
		              <th style="width:2%;">№</th>
		              <th style="width:12%; text-align: center;">Изображение</th>
		              <th style="width:12%; text-align: center;">Название ссылки</th>
		              <th style="width:15%; text-align: center;">Описание слайда</th>
		              <th style="width:6%;">Действия</th>
		            </tr>
		          </thead>
		          <tbody class="entity-table-tbody"> 
		            <?php if (empty($entity)): ?>
		              <tr id="no-results">
		                <td colspan="5" align="center"><?=$lang['ENTITY_NONE']; ?></td>
		              </tr>
		                <?php else: ?>
		                  <?php foreach ($entity as $row): ?>
		                  <tr data-id="<?=$row['id']; ?>">
		                    <td><?=$row['sort']; ?></td>
		                    <td class="type">
		                    	<img src="<?=SITE.'/'.PLUGIN_DIR.'slider-images/img/slides/'.$row['img'];?>">
		                    </td>
		                    <td>
		                    	<? if($row['is_link'] == 1): ?>  
		                    	<a href="<?=SITE.'/'.$row['url_link']?>" class='activity-product-true' target="_blank"><?=$row['name_link'];?></a>
		                    	<? else: ?>
		                    	----------------                       
		                    	<? endif; ?>
		                    </td>
		                    <td>
		                    	<? if($row['is_link'] == 1): ?>
		                    	<p><?=$row['desc'];?></p>
		                    	<? else: ?>
		                    	----------------                            
		                    	<? endif; ?>
		                    </td>
		                    <td class="actions">
		                      <ul class="action-list">
		                      	<li class="edit-row" data-id="<?=$row['id'] ?>"><a class="tool-tip-bottom" href="javascript:void(0);" title="<?=$lang['EDIT'];?>"></a></li>
		                        <li class="visible tool-tip-bottom  <?=($row['invisible']) ? 'active' : '' ?>" 
		                            data-id="<?=$row['id'] ?>" 
		                            title="<?=($row['invisible']) ? $lang['ACT_V_ENTITY'] : $lang['ACT_UNV_ENTITY']; ?>">
		                          <a href="javascript:void(0);"></a>
		                        </li>
		                        <li class="delete-row" data-id="<?=$row['id'] ?>">
		                          <a class="tool-tip-bottom" href="javascript:void(0);" title="<?=$lang['DELETE']; ?>"></a>
		                        </li>
		                      </ul>
		                    </td>
		                  </tr>
		                <?php endforeach; ?>
		              <?php endif; ?>
		          </tbody>
		        </table>
		      </div>
		    </div>
		    <div class="clear"></div>
		  </div>
	</div>
</div>

<div class="b-modal hidden-form slide-editor">
	<div class="custom-table-wrapper">
		<div class="widget-table-title">
		    <h4 class="pages-table-icon" id="modalTitle">Форма добавления слайда</h4>
		    <div class="b-modal_close"></div>
		</div>
		<div class="widget-table-body">
			<div class="form-el">
				<label for="chgType">Тип слайда:</label>
				<select name="type" id="chgType">
					<option value="desc">Описание</option>
					<option value="img">Изображение</option>
				</select>
			</div>
			<div class="block-for-form">
				<ul class="custom-form-wrapper type-desc">
					<div class="form-el">
						<label for="txtNameLink">Название ссылки</label>
						<input type="text" name="name_link" id="txtNameLink">
					</div>
					<div class="form-el">
						<label for="txtUrlLink">URL ссылки</label>
						<input type="text" name="url_link" id="txtUrlLink">
					</div>
					<div class="form-el">
						<div class="previewImgBox">
							<img src="http://placehold.it/1280x340">
							<input type="hidden" class="currentImg">
						</div>
						<form class="imageform" method="post" noengine="true" enctype="multipart/form-data">
							<label for="fileImg">Изображение</label>
							<input type="file" name="img">
						</form>
					</div>
					<div class="form-el">
						<label for="txtDesc">Описание</label>
						<textarea id="txtDesc" name="desc"></textarea>
					</div>
				</ul>
				<div class="custom-form-wrapper type-img">
					<div class="form-el">
						<div class="previewImgBox">
							<img src="http://placehold.it/1280x340">
						</div>
						<form class="imageform" method="post" noengine="true" enctype="multipart/form-data">
							<label for="fileImg">Изображение</label>
							<input type="file" name="img">
							<input type="hidden" class="currentImg">
						</form>
					</div>
				</div>
			</div>
			<button class="save-button"><span>Сохранить</span></button>
			<div class="clear"></div>
		</div>
	</div>
</div>