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
		$this->full_path = CMS_ROOT . DS . 'public' . DS . implode(DS, func_get_args());
		$this->up_url = URL_PUBLIC . 'admin/plugin/survey' . '/browse/' . substr($this->segment, 0, strripos($this->segment, '/', -1));
		$directory = new DirectoryIterator($this->full_path);
		$dirs = array();
		$files = array(
			'csvs' => array(),
			'fname' => array(),
			'ext' => array(),
			'files' => array(),
			'surveys' => array()
		);
		// separate into dirs, csvs, and other files
		foreach ($directory as $direntry) {
			if ($direntry->isDot()) {
				continue;
			}
			if ($direntry->getType() == 'dir') {
				$dirs[] = $direntry->getFilename();
			}
			elseif ((strcasecmp($direntry->getExtension(), 'csv')) == 0) {
				$files['csvs'][] = substr($direntry->getFilename(), 0, -4);
			}
			else {
				$files['fname'][] = $direntry->getBasename(
					(strlen($direntry->getExtension()) > 0)
						? '.' . $direntry->getExtension()
						: '' );
				$files['ext'][] = $direntry->getExtension();
			}
		}
		// find possible survey definition files
		foreach ($files['csvs'] as $csv_file) {
			$key = array_search($csv_file, $files['fname']);
			if ($key !== FALSE) {
				if ($files['ext'][$key]) {
					$files['ext'][$key] = '.' . $files['ext'][$key];
				}
				$files['files'][] = $csv_file . $files['ext'][$key];
				end($files['files']);
				$test_file = $this->full_path . DS . $files['files'][key($files['files'])];
				if (($test_survey = parse_ini_file($test_file, TRUE)) == FALSE) {
					$files['surveys'][] = 'Error parsing file';
				}
				elseif (array_key_exists('meta', $test_survey) == FALSE) {
					$files['surveys'][] = 'Survey file missing meta section';
				}
				elseif ($test_survey['meta']['name'] == '') {
					$files['surveys'][] = 'Bad file format';
				}
				else {
					$files['surveys'][] = strip_tags($test_survey['meta']['name']);
				}
			}
		}
		$arg_array = array(
			'full_path' => $this->full_path,
			'up_url' => $this->up_url,
			'segment' => $this->segment,
			'dirs' => $dirs,
			'surveys' => array_combine($files['files'], $files['surveys'])
		);
		$this->display('survey/views/browse', $arg_array);
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
