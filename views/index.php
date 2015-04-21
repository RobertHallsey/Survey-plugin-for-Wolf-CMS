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
<h1><?php echo __('Survey'); ?></h1>

<p>The Survey Plugin lets you conduct custom surveys within your Wolf CMS pages. You must first create a Survey Description File and place it in the public/ directory. Suppose you call that file "my_survey." To conduct the survey within a Wolf CMS page, you would place the following code in the page.</p>

<code>
&lt;?php
	if (Plugin::isEnabled('survey_conduct')) survey('my_survey');
?&gt;
</code>

<p>Survey responses are collected in the file "my_survey.csv," also found in the public/ directory.</p>
<p>Please see the Documentation tab for more information.</p>

<hr
