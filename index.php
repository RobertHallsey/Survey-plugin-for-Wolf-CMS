<?php

/* Security measure */
if (!defined('IN_CMS')) exit();

/**
 * The Survey plugin makes it easy to conduct custom surveys.
 *
 * @package Plugins
 * @subpackage survey
 *
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright Robert Hallsey, 2015
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
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

function survey_conduct($survey_name = '') {
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
	$survey->render_form();
}

function survey_summarize($survey_name = '') {
	if ($survey_name == '') {
		exit(__('No survey name specified'));
	}
	$survey = new Survey;
	$error = $survey->load_survey_file($survey_name);
	if ($error) exit($error);
	$error = $survey->load_survey_responses();
	if ($error) exit($error);
	$survey->summarize_responses();
	$survey->render_chart();
}
