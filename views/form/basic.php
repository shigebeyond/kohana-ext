<?php $model = substr(get_class($item), 6); ?>
<?php echo Form::open(Request::current()->uri(), array('class' => 'form-horizontal', 'accept-charset' => 'UTF-8')); ?>
    <fieldset>
    	<!-- 字段 -->
    	<?php foreach ($elements as $label => $field): ?>
	    <div class="control-group">
			<label class="control-label"><?php echo $label; ?></label>
			<div class="controls"><?php echo $field; ?></div>
		</div>
		<?php endforeach; ?>
	
	    <!-- 按钮 -->	
		<div class="form-actions align-center">
			<?php echo Form::submit('submit', (isset($item->id) ? '编辑' : '新建'), array('class' => 'btn btn-primary'))?>
			<?php echo HTML::anchor("/admin/$model/index", '返回', array('class' => 'btn')); ?>
			<?php echo isset($item->id) ? HTML::anchor("/admin/$model/show/$item->id", '查看', array('class' => 'btn btn-primary')) : ''; ?>
		</div>
	 </fieldset>
<?php echo Form::close(); ?>