<form id="rondin">
	<fieldset class="personalblock">
	<legend><strong><?php p($l->t('Rondin Logs')); ?></strong></legend>

		<ul id="config_handlers">
			<li>
				<h2>RotatingFile</h2>
				<ul>
					<li>
							<ul class="params">
							<li>Param 1 : <input /></li>
							<li>Param 2 : <input /></li>
						</ul>
					</li>
					<li><br />Formatter : 
						<select><option>Formatters</option></select><ul  class="params">
							<li>Param 1 : <input /></li>
							<li>Param 2 : <input /></li>
						</ul>
					</li>
				</ul>
				<h3>Processors</h3>
				<ul>
					<li>FirePHP
						<ul class="params">
							<li>Param 1 : <input /></li>
							<li>Param 2 : <input /></li>
						</ul>
					</li>
					<li>Line</li>
				</ul>
				<select><option>Processors </option></select>
			</li>
		</ul>

		<select id="avail_handler">
			<option value=""><?php p($l->t('Choose a handler')); ?></option>
			<?php foreach($_['handlers'] as $handler):?>
				<option value="<?php echo $handler;?>"><?php echo $handler;?></option>
			<?php endforeach;?>
		</select>

	</fieldset>
</form>
