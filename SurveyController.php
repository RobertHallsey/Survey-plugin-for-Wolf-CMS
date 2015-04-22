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
			$error = $survey->load_survey_file($survey_name);
			if ($error) exit($error);
			$error = $survey->load_survey_responses();
			if ($error) exit($error);
			$survey->summarize_responses();
			$html = $survey->build_summary($survey_name, TRUE);
			$this->display('survey/views/summaries', array('html' => $html));
		}
	}

}