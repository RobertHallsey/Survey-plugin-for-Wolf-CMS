<?php

/* Security measure */
if (!defined('IN_CMS')) exit();

/**
 * The Survey plugin makes it easy to conduct custom surveys.
 *
 * @author Robert Hallsey <rhallsey@yahoo.com>
 * @copyright Robert Hallsey, 2008
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 * @since Wolf version 7.5
 */

/**
 * class SurveyController
 */
 
class SurveyController extends PluginController {

	function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/survey/views/sidebar'));
	}

	function index() {
		$this->display('survey/views/index');
	}
 
	function documentation() {
		$this->display('survey/views/documentation');
	}

	function summaries($survey_name = '') {
		if ($survey_name == '') {
			foreach (glob(SURVEY_PATH . '*.csv') as $file_entry) {
				if (filesize($file_entry) > 0) {
					$survey_file = substr($file_entry, 0, strlen($file_entry) - strlen('.csv'));
					if (file_exists($survey_file)) {
						$file_handle = fopen($survey_file, 'r');
						$line = fgetss($file_handle);
						$line = trim(fgetss($file_handle));
						$survey_name = trim(substr($line, strpos($line, '"')), '"');
						$file_list[] = array(basename($survey_file), $survey_name);
					}
				}
			}
			$this->display('survey/views/summaries', array('surveys' => $file_list));
		}
		else {
			$survey = new Survey;
			$html = $survey->build_summary($survey_name);
			$this->display('survey/views/summaries', array('html' => $html));
		}
	}

}