<?php if (!empty($errors)): ?>
	<!-- 错误信息 -->
	<div id="errors">
		<div class="alert alert-error alert-block">
			<a class="close" data-dismiss="alert" href="#">×</a>
			<h4 class="alert-heading">有以下错误：</h4>
			<?php $i = 1;?>
			<?php foreach ($errors as $field => $messages) :?>
				<?php if (is_array($messages)) : ?>
					<?php foreach ($messages as $message): ?>
						<p><?php echo ($i++).' '.$message; ?></p>
					<?php endforeach;?>
				<?php else : ?>
					<p><?php echo ($i++).' '.$messages; ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

