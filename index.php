<?php

/* Security measure */
if (!defined('IN_CMS')) exit();

/**
 * The Survey Plugin for Wolf CMS makes it easy to conduct custom surveys.
 *
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright Robert Hallsey, 2015
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 *
 * This file is part of the Survey Plugin for Wolf CMS.
 *
 * The Survey Plugin for Wolf CMS is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The Survey Plugin for Wolf CMS is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Plugin::setInfos(array(
	'id'          => 'survey',
	'title'       => __('Survey'),
	'description' => __('Conduct surveys in WolfCMS.'),
	'version'     => '0.1',
	'license'     => 'GPL',
	'author'      => 'Robert Hallsey',
	'website'     => 'http://www.clicketyhome.com/',
	//'update_url'  => 'http://www.wolfcms.org/plugin-versions.xml',
	'require_wolf_version' => '0.7.7'
));

/**
 * Some path locations
 */
define('SURVEY_PATH', getcwd() . DS . 'public' . DS);
define('SURVEY_VIEWS', getcwd() . DS . 'wolf' . DS . 'plugins' . DS . 'survey' . DS . 'views' . DS);

// Add the plugin's tab and controller
Plugin::addController('survey', __('Survey'));

// Add the models to the autoLoader
AutoLoader::addFile('Survey', CORE_ROOT . '/plugins/survey/Survey.php');

function survey_conduct($survey_name = '', $fancy = TRUE) {
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if ($survey_name == '') {
			exit(__('No survey name specified'));
		}
		$survey = new Survey;
		$error = $survey->load_survey_file($survey_name);
		if ($error) exit($error);
		$survey->prefill_survey_responses();
		$save = $survey->get_variables();
		$_SESSION['save'] = $save;
	}
	else {
		if (!isset($_SESSION['save'])) {
			exit(__('Survey is finished'));
		}
		$survey = new Survey;
		$save = $_SESSION['save'];
		$survey->set_variables($save);
		$survey->update_survey_data($_POST['survey_data']);
		if ($survey->validate_errors() == 0) {
			$survey->save_data();
			unset($_SESSION['save']);
		}
	}
	$html = $survey->build_form($fancy);
	echo $html;
}

function survey_summarize($survey_name = '', $fancy = TRUE) {
	if ($survey_name == '') {
		exit(__('No survey name specified'));
	}
	$survey = new Survey;
	$error = $survey->load_survey_file($survey_name);
	if ($error) exit($error);
	$error = $survey->load_survey_responses();
	if ($error) exit($error);
	$survey->summarize_responses();
	$html = $survey->build_summary($survey_name, $fancy);
	echo $html;
}
