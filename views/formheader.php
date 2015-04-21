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

<h2><?php echo $survey_name ?></h2>

<p><?php echo $user_msg ?></p>

<?php if ($error_question): ?>
<p><?php echo $error_msg ?></p>

<?php endif; ?>
<form id="form" name="form" method="post">
