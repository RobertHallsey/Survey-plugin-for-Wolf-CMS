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

/* Darnit! Can't do this until PHP 5.6...
const DATA_HOME_PATH = CMS_ROOT . DS . 'public' . DS;
const BROWSE_URL = URL_PUBLIC . 'admin/plugin/survey/browse/';
const VIEW_URL = URL_PUBLIC . 'admin/plugin/survey/view/';
*/

	protected $DATA_HOME_PATH = '';
	protected $BROWSE_URL = '';
	protected $VIEW_URL = '';

	function __construct() {
		$this->DATA_HOME_PATH = CMS_ROOT . DS . 'public' . DS;
		$this->BROWSE_URL = URL_PUBLIC . 'admin/plugin/survey/browse/';
		$this->VIEW_URL = URL_PUBLIC . 'admin/plugin/survey/view/';
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/survey/views/sidebar'));
	}
	
	function index() {
		$this->browse();
	}

	function browse() {
		$segment = implode('/', func_get_args());
		switch (func_num_args()) {
		case 0:
			$full_path = $this->DATA_HOME_PATH;
			$up_url = $this->BROWSE_URL;
			break;
		case 1:
			$full_path = $this->DATA_HOME_PATH . str_replace('/', DS, $segment) . DS;
			$up_url = $this->BROWSE_URL;
			break;
		default:
			$full_path = $this->DATA_HOME_PATH . str_replace('/', DS, $segment) . DS;
			$up_url = $this->BROWSE_URL . substr($segment, 0, strripos($segment, '/', -1));
			break;
		}
		$dirs = array();
		foreach (glob($full_path . '*', GLOB_ONLYDIR) as $dir) {
			$dirs[] = array(
				$this->BROWSE_URL . str_replace(DS, '/', str_replace($this->DATA_HOME_PATH, '', $dir)),
				basename($dir)
			);
		}
		$files = array();
		$a = glob($full_path . '*'); // all files
		$b = glob($full_path . '*', GLOB_ONLYDIR); // all subdirectories
		$c = glob($full_path . '*.' . SURVEY_RESPONSE_FILE_EXT); // survey response files
		$d = array_diff($a, $b, $c); // all files except subdirectories and survey response files
		$e = array_uintersect($d, $c, "self::filename_difference"); // survey definition files
		foreach ($e as $file) {
			// verify it's a survey definition file 
			$test = new Survey($file);
			$error = $test->loadSurveyFile();
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
			$file = $this->VIEW_URL . str_replace(DS, '/', str_replace($this->DATA_HOME_PATH, '', $file));
			if (substr($line, 0, 1) == ';') {
				do {
					$line = substr($line, 1);
				} while (substr($line, 0, 1) == ';' || substr($line, 0, 1) == ' ');
				$files[] = array($file,	$line);
			}
			else {
				$files[] = array($file,	'Unamed Survey');
			}
		}
		$arg_array = array(
			'segment' => $segment,
			'up_url' => $up_url,
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

	public function view($given_survey = '') {
		if ($given_survey == '') {
			exit(__('No survey name specified'));
		}
		$given_survey = $this->DATA_HOME_PATH . implode(DS, func_get_args());
		$survey = new Survey($given_survey);
		$survey->prepareSummary();
		$arg_array = array(
			'html' => $survey->theSummary()
		);
		$this->display('survey/views/view', $arg_array);
	}

    function docs() {
		$this->display('survey/views/index');
	}

}
