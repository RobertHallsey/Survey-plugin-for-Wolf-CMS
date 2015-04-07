<?php if (!$disabled): ?>
<p><input type="reset" value="<?php echo __('Clear form and start over') ?>" /><input type="submit" name="submit" value="<?php echo __('Done!') ?>" /></p>
</form>
<?php else: ?>
</form>
<p><?php echo __('Validation Timestamp: ') . date('M. d, Y h:i:s A', $timestamp) ?></p>
<?php endif; ?>
<?php if ($execute): ?>
<script type="text/javascript">
	function formDisable() {
		var form = document.getElementById("form");
		var elements = form.elements;
		for (var i = 0, len = elements.length; i < len; i++) {
			elements[i].disabled = true;
		}
	}
	function formReset() {
		this.form.reset()
	}
	<?php echo $execute ?>
</script>
<?php endif; ?>
