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

<div id="ss"><!-- ss survey summary -->
<?php if ($fancy == TRUE) : ?>
<h2><?php echo __('Survey Summary') ?></h2>

<h3><?php echo __('Survey Name:') ?> <?php echo $survey_name ?></h3>

<h3><?php echo __('Total Responses:')?> <?php echo $response_count ?></h3>

<?php endif; ?>