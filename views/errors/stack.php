<?php

      // Unique error identifier
      $error_id = uniqid('error');

      $code = isset($code) ? $code : 500;
      switch($code)
      {
	    case 403:
		  echo View::factory('errors/403')->set('url', URL::site( Request::initial()->uri(), true ) );
	    break;
      	    case 404:
		  echo View::factory('errors/404')->set('url', URL::site( Request::initial()->uri(), true ) );
	    break;
	    case 503:
		  echo View::factory('errors/503')->set('code', $code )->set('message', $message);
	    break;
	    default:
		  echo View::factory('errors/invalid')->set('code', $code )->set('message', $message);
	    break;
      }

?>

<div class="clear-block"></div>
<style type="text/css">

      div.big_box {
        /*padding: 10px;*/
        background: #eee;
        border: solid 1px #ccc;
        font-family: sans-serif;
	font-size: 14px;
        color: #111;
        width: 972px;
        margin: 20px auto;
      }

      div#framework_error {
        text-align: center;
      }

      div#error_details {
        text-align: left;
      }

      div.span-17 {
	float:left;
	margin-right:10px;
      }

      .clear-block {
	display:block;
	clear: both;
	margin-top:10px;
      }
      
#krishna_error { background: #ddd; font-size: 1em; font-family:sans-serif; text-align: left; color: #111; padding: 10px; }
#krishna_error h1,
#krishna_error h2 { margin: 0; padding: 1em; font-size: 1em; font-weight: normal; background: #911; color: #fff; }
	#krishna_error h1 a,
	#krishna_error h2 a { color: #fff; }
#krishna_error h2 { background: #222; }
#krishna_error h3 { margin: 0; padding: 0.4em 0 0; font-size: 1em; font-weight: normal; }
#krishna_error p { margin: 0; padding: 0.2em 0; }
#krishna_error a { color: #1b323b; }
#krishna_error pre { overflow: auto; white-space: pre-wrap; }
#krishna_error table { width: 100%; display: block; margin: 0 0 0.4em; padding: 0; border-collapse: collapse; background: #fff; }
	#krishna_error table td { border: solid 1px #ddd; text-align: left; vertical-align: top; padding: 0.4em; }
#krishna_error div.content { padding: 0.4em 1em 1em; overflow: hidden; }
#krishna_error pre.source { margin: 0 0 1em; padding: 0.4em; background: #fff; border: dotted 1px #b7c680; line-height: 1.2em; }
	#krishna_error pre.source span.line { display: block; }
	#krishna_error pre.source span.highlight { background: #f0eb96; }
		#krishna_error pre.source span.line span.number { color: #666; }
#krishna_error ol.trace { display: block; margin: 0 0 0 2em; padding: 0; list-style: decimal; }
	#krishna_error ol.trace li { margin: 0; padding: 0; }
.js .collapsed { display: none; }
</style>
<script type="text/javascript">
document.documentElement.className = document.documentElement.className + ' js';
function koggle(elem)
{
	elem = document.getElementById(elem);

	if (elem.style && elem.style['display'])
		// Only works with the "style" attr
		var disp = elem.style['display'];
	else if (elem.currentStyle)
		// For MSIE, naturally
		var disp = elem.currentStyle['display'];
	else if (window.getComputedStyle)
		// For most other browsers
		var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

	// Toggle the state of the "display" style
	elem.style.display = disp == 'block' ? 'none' : 'block';
	return false;
}
</script>

<?php //try { $user = Identity::active_user(); } catch (Exception $e) { } ?>
    <div class="big_box" id="error_details clear-block">
	    <h2>
		  <?php echo __("Hey wait, you're an admin!  We can tell you stuff.") ?>
	    </h2>
	    <br>
      
            <p>
		  <?php echo __("There's an error message below and you can find more details in application/logs (look for the file with the most recent date on it)..") ?>
	    </p>
	    <br>
      
    <div id="krishna_error">
	<h1><span class="type"><?php echo $type ?> [ <?php echo $code ?> ]:</span> <span class="message"><?php echo HTML::chars($message) ?></span></h1>
	<div id="<?php echo $error_id ?>" class="content">
		<p><span class="file"><?php echo Debug::path($file) ?> [ <?php echo $line ?> ]</span></p>
		<?php echo Debug::source($file, $line) ?>
		<ol class="trace">
		<?php foreach (Debug::trace($trace) as $i => $step): ?>
			<li>
				<p>
					<span class="file">
						<?php if ($step['file']): $source_id = $error_id.'source'.$i; ?>
							<a href="#<?php echo $source_id ?>" onclick="return koggle('<?php echo $source_id ?>')"><?php echo Debug::path($step['file']) ?> [ <?php echo $step['line'] ?> ]</a>
						<?php else: ?>
							{<?php echo __('PHP internal call') ?>}
						<?php endif ?>
					</span>
					&raquo;
					<?php echo $step['function'] ?>(<?php if ($step['args']): $args_id = $error_id.'args'.$i; ?><a href="#<?php echo $args_id ?>" onclick="return koggle('<?php echo $args_id ?>')"><?php echo __('arguments') ?></a><?php endif ?>)
				</p>
				<?php if (isset($args_id)): ?>
				<div id="<?php echo $args_id ?>" class="collapsed">
					<table cellspacing="0">
					<?php foreach ($step['args'] as $name => $arg): ?>
						<tr>
							<td><code><?php echo $name ?></code></td>
							<td><pre><?php echo Debug::dump($arg) ?></pre></td>
						</tr>
					<?php endforeach ?>
					</table>
				</div>
				<?php endif ?>
				<?php if (isset($source_id)): ?>
					<pre id="<?php echo $source_id ?>" class="source collapsed"><code><?php echo $step['source'] ?></code></pre>
				<?php endif ?>
			</li>
			<?php unset($args_id, $source_id); ?>
		<?php endforeach ?>
		</ol>
	</div>
	<h2><a href="#<?php echo $env_id = $error_id.'environment' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Environment') ?></a></h2>
	<div id="<?php echo $env_id ?>" class="content collapsed">
		<?php $included = get_included_files() ?>
		<h3><a href="#<?php echo $env_id = $error_id.'environment_included' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Included files') ?></a> (<?php echo count($included) ?>)</h3>
		<div id="<?php echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
				<?php foreach ($included as $file): ?>
				<tr>
					<td><code><?php echo Debug::path($file) ?></code></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
		<?php $included = get_loaded_extensions() ?>
		<h3><a href="#<?php echo $env_id = $error_id.'environment_loaded' ?>" onclick="return koggle('<?php echo $env_id ?>')"><?php echo __('Loaded extensions') ?></a> (<?php echo count($included) ?>)</h3>
		<div id="<?php echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
				<?php foreach ($included as $file): ?>
				<tr>
					<td><code><?php echo Debug::path($file) ?></code></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
		<?php foreach (array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>
		<?php if (empty($GLOBALS[$var]) OR ! is_array($GLOBALS[$var])) continue ?>
		<h3><a href="#<?php echo $env_id = $error_id.'environment'.strtolower($var) ?>" onclick="return koggle('<?php echo $env_id ?>')">$<?php echo $var ?></a></h3>
		<div id="<?php echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
				<?php foreach ($GLOBALS[$var] as $key => $value): ?>
				<tr>
					<td><code><?php echo HTML::chars($key) ?></code></td>
					<td><pre><?php echo Debug::dump($value) ?></pre></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
		<?php endforeach ?>
	</div>
   </div>
   </div>
