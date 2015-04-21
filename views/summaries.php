<?php

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

/**
 * The Survey Plugin for Wolf CMS makes it easy to conduct custom surveys.
 *
 * This file is part of the Survey Plugin for Wolf CMS.
 *
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright Robert Hallsey, 2015
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
?>
<?php if (isset($surveys)): ?>
<h3><?php echo __('Survey Summaries') ?></h3>
<?php foreach ($surveys as $f): ?>
  <p><a href="<?php echo $f[0] ?>"><?php echo $f[1] ?></a></p>
<?php endforeach; ?>
<?php else: ?>
<?php echo $html ?>
<?php endif; ?>

