<?php if (isset($surveys)): ?>
<h3><?php echo __('Survey Summaries') ?></h3>
<?php foreach ($surveys as $f): ?>
  <p><a href="<?php echo $f[0] ?>"><?php echo $f[1] ?></a></p>
<?php endforeach; ?>
<?php else: ?>
<?php echo $html ?>
<?php endif; ?>

