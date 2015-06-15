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
 * // Some path and URL locations (defined in index.php)
 * define('SURVEY_ICONS', URL_PUBLIC . 'wolf/plugins/survey/icons/');
 * define('SURVEY_BROWSE', URL_PUBLIC . 'admin/plugin/survey/browse/');
 * define('SURVEY_VIEW', URL_PUBLIC . 'admin/plugin/survey/view/');
 * define('SURVEY_SUMMARIZE', URL_PUBLIC . 'public/');
 * define('SURVEY_DATA', CMS_ROOT . DS . 'public' . DS);
 * define('SURVEY_VIEWS', PLUGINS_ROOT . DS . 'survey' . DS . 'views/');
 * define('SURVEY_RESPONSE_FILE_EXT', 'csv');
 * // URL_PUBLIC, CMS_ROOT, and PLUGINS_ROOT are Wolf CMS constants
 */
 
/**
 * class SurveyController
 */

 
class SurveyController extends PluginController {

	public $segment;
	public $full_path;
	public $up_url;

	function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/survey/views/sidebar'));
		$this->up_url = '';
	}

	function index() {
		$this->display('survey/views/index');
	}

	function documentation() {
		$this->display('survey/views/documentation');
	}

    function browse() {
		$this->segment = implode('/', func_get_args());
		switch (func_num_args()) {
			case 0:
				$this->full_path = SURVEY_DATA;
				$this->up_url = array(
					SURVEY_BROWSE,
					'public'
				);
				break;
			case 1:
				$this->full_path = SURVEY_DATA . str_replace('/', DS, $this->segment) . DS;
				$this->up_url = array(
					SURVEY_BROWSE,
					$this->segment
				);
				break;
			default:
				$this->full_path = SURVEY_DATA . str_replace('/', DS, $this->segment) . DS;
				$this->up_url = array(
					SURVEY_BROWSE . substr($this->segment, 0, strripos($this->segment, '/', -1)),
					substr($this->segment, strripos($this->segment, '/', -1) + 1)
				);
				break;
		}
		$dirs = array();
		foreach (glob($this->full_path . '*', GLOB_ONLYDIR) as $dir) {
			$dirs[] = array(
				SURVEY_BROWSE . str_replace(DS, '/', str_replace(SURVEY_DATA, '', $dir)),
				basename($dir)
			);
		}
		$files = array();
		$a = glob($this->full_path . '*'); // all files
		$b = glob($this->full_path . '*', GLOB_ONLYDIR); // all subdirectories
		$c = glob($this->full_path . '*.' . SURVEY_RESPONSE_FILE_EXT); // survey response files
		$d = array_diff($a, $b, $c); // all files except subdirectories and survey response files
		$e = array_uintersect($d, $c, "self::filename_difference"); // survey definition files
		foreach ($e as $file) {
			// verify it's a survey definition file 
			$test = new Survey($file);
			$error = $test->load_survey_file();
			unset($test);
			if ($error) continue; // not a survey definition file
			// get first line of text, skipping blank lines
			$file_handle = fopen($file, 'r');
			$line = '';
			do {
				$line = trim(fgets($file_handle), " \t\r\n\0\x0B");
			} while ($line == '');
			fclose($file_handle);
			// get name following semi-colon, if there
			if (substr($line, 0, 1) == ';') {
				do {
					$line = substr($line, 1);
				} while (substr($line, 0, 1) == ';' || substr($line, 0, 1) == ' ');
				$files[] = array(
					SURVEY_VIEW . str_replace(DS, '/', str_replace(SURVEY_DATA, '', $file)) . basename($file),
					$line
				);
			}
			else {
				$files[] = array(
					SURVEY_VIEW . str_replace(DS, '/', str_replace(SURVEY_DATA, '', $file)) . basename($file),
					''
				);
			}
		}
		$arg_array = array(
			'up_url' => $this->up_url,
			'dirs' => $dirs,
			'files' => $files
		);
		$this->display('survey/views/browse', $arg_array);
	}
	
/**
* Compares the filename part of two fully qualified file references
*/
	private function filename_difference($a, $b) {
		$a = basename($a, '.' . pathinfo($a, PATHINFO_EXTENSION));
		$b = basename($b, '.' . SURVEY_RESPONSE_FILE_EXT);
		if ($a < $b) return -1;
		if ($a > $b) return 1;
		return 0;
	}

	public function view($survey_name = '') {
		if ($survey_name == '') {
			exit(__('No survey name specified'));
		}
		$survey_name = SURVEY_DATA . implode(DS, func_get_args());
		$survey = new Survey($survey_name);
		$error = $survey->load_survey_file($survey_name);
		if ($error) {exit($error);}
		$error = $survey->load_survey_responses();
		if ($error) {exit($error);}
		$survey->summarize_responses();
		$html = $survey->build_summary($survey_name);
		$arg_array = array(
			'html' => $html
		);
		$this->display('survey/views/view', $arg_array);
	}

}
