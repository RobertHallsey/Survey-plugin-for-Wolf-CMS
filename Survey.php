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
 * class Survey
 */

class Survey {

	protected $survey_path = '';
	protected $survey_file = '';
	protected $survey_name = '';
	protected $survey_data = array();
	protected $response_count = 0;
	protected $question_number = 1;
	protected $error = 0;
	protected $timestamp = 0;
	protected $js_function = 'formReset';

	function __construct($survey_arg = '') {
		if ($survey_arg) {
			$this->survey_name = realpath('public/' . $survey_arg);
			if ($this->survey_name === FALSE) {
				$this->survey_name = realpath($survey_arg);
				if ($this->survey_name === FALSE) {
					exit(__('Survey file not found'));
				}
			}
			$this->survey_path = dirname($this->survey_name);
			$this->survey_file = basename($this->survey_name);
		}
	}

	function get_variables() {
		return array (
			'survey_file' => $this->survey_file,
			'survey_path' => $this->survey_path,
			'survey_name' => $this->survey_name,
			'survey_data' => $this->survey_data,
			'question_number' => $this->question_number,
			'error' => $this->error,
			'timestamp' => $this->timestamp,
			'js_function' => $this->js_function
		);
	}

	function set_variables($vars) {
		$this->survey_file = $vars['survey_file'];
		$this->survey_path = $vars['survey_path'];
		$this->survey_name = $vars['survey_name'];
		$this->survey_data = $vars['survey_data'];
		$this->question_number = $vars['question_number'];
		$this->error = $vars['error'];
		$this->timestamp = $vars['timestamp'];
		$this->js_function = $vars['js_function'];
	}

	/**
	 * Loads survey file into $survey_data array.
	 *
	 * @param string $survey_file
	 * @return error message or ''
	 */
	function load_survey_file() {
		if (!file_exists($this->survey_name)) {
			return __('Survey file not found');
		}
		//check the survey file for errors
		if (($this->survey_data = parse_ini_file($this->survey_name, TRUE)) == FALSE) {
			return __('Cannot parse survey file');
		}
		if (array_key_exists('meta', $this->survey_data) == FALSE) {
			return __('Survey file missing meta section');
		}
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				if (!array_key_exists('type', $section_data)) {
					return __('Section \'%name%\' has missing Type property', array('%name%' => $section_name));
				}
				if (!array_key_exists('questions', $section_data) ||
					 (!is_array($section_data['questions']))) {
					return __('Section \'%name%\' has missing or malformed questions', array('%name%' => $section_name));
				}
				if (!array_key_exists('answers', $section_data) ||
					 (!is_array($section_data['answers']))) {
					return __('Section \'%name%\' has missing or malformed answers', array('%name%' => $section_name));
				}
			}
		}
	}

	function prefill_survey_responses() {
		// pre-fill with blank responses
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				switch ($this->survey_data[$section_name]['type']) {
				case 1:
          if ( ! array_key_exists('help', $this->survey_data[$section_name])) {
            $this->survey_data[$section_name]['help'] = '';
          }
				case 2:
					$this->survey_data[$section_name]['responses'] =
						array_fill(0, count($this->survey_data[$section_name]['questions']), 0);
					break;
				case 3:
					$this->survey_data[$section_name]['responses'] =
						array_fill(0, count($this->survey_data[$section_name]['answers']), 0);
					break;
				}
			}
		}
		return '';
	}

	function load_survey_responses() {
		// load CSV file into $responses[]
		$response_file = $this->survey_name . '.csv';
		if (!file_exists($response_file)) {
			return __('Survey response file not found');
		}
		$CSV_count = 0;
		$responses = array();
		$file_handle = fopen($response_file, 'r');
		while (($data = fgetcsv($file_handle)) == TRUE) {
			$responses[] = $data;
			// make sure each line has same number of values
			if ($CSV_count == 0) {
				$CSV_count = count(current($responses));
			}
			if ($CSV_count != count(current($responses))) {
				return __('File has lines of different value counts');
			}
		}
		$this->response_count = count($responses);
		// load $responses[] into $survey array
		foreach ($responses as $response) {
			$offset = 2;
			foreach ($this->survey_data as $section_name => $section_data) {
				if ($section_name != 'meta') {
					$section_type = (($this->survey_data[$section_name]['type'] == 3) ? 'answers' : 'questions');
					foreach ($section_data[$section_type] as $k => $v) {
						$this->survey_data[$section_name]['responses'][$k][] = $response[$offset];
						$offset++;
					}
				}
			}
		}
		return '';
	}

	function build_form($fancy = TRUE) {
		return
			$this->build_header($fancy) .
			$this->build_body() .
			$this->build_footer($fancy);
	}

	function build_header($fancy) {
		$error_msg = '';
		if ($this->js_function == 'formDisable') {
			$user_msg = $this->survey_data['meta']['goodbye'];
		}
		else {
			$user_msg = $this->survey_data['meta']['hello'];
			if ($this->error) {
				$error_msg = (($this->error> 0)
					? __('Please answer question #%question_number%', array('%question_number%' => $this->error))
					: __('Question #%question_number%\'s last option is either/or', array('%question_number' => -$this->error)));
			}
		}
		$view_file = SURVEY_VIEWS . 'surveyheader';
		$arg_array = array(
			'survey_title' => $this->survey_data['meta']['name'],
			'user_msg' => $user_msg,
			'error_msg' => $error_msg,
			'error_question' => $this->error,
			'fancy' => $fancy
		);
		return new View($view_file, $arg_array);
	}

	function build_body() {
		$form_html = '';
		$question_number = 1;
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				if (isset($this->survey_data[$section_name]['title'])) {
					$form_html .= '<h3>' . $this->survey_data[$section_name]['title'] . '</h3>' . "\n";
				}
				$view_file = SURVEY_VIEWS . 'qtype' . $this->survey_data[$section_name]['type'];
				$arg_array = array(
					'number' => $question_number,
					'name' => $section_name,
					'data' => $section_data,
				);
				$form_html .= new View($view_file, $arg_array);
				$question_number += count($section_data['questions']);
			}
		}
		return $form_html;
	}

	function build_footer($fancy) {
		$execute = (($this->js_function == '') ? '' : $this->js_function . '();');
		$view_file = SURVEY_VIEWS . 'surveyfooter';
		$arg_array = array(
			'execute' => $execute,
			'disabled' => ($this->js_function == 'formDisable'),
			'timestamp' => $this->timestamp,
			'fancy' => $fancy
		);
		return new View($view_file, $arg_array);
	}

	function build_summary($survey_file, $fancy = TRUE) {
		$view_file = SURVEY_VIEWS . 'summaryheader';
		$arg_array = array(
			'survey_name' => $this->survey_data['meta']['name'],
			'response_count' => $this->response_count,
			'fancy' => $fancy
		);
		$html = new View($view_file, $arg_array);
		$this->question_number = 1;
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				$view_file = SURVEY_VIEWS . 'stype' . $this->survey_data[$section_name]['type'];
				$arg_array = array(
					'question_number' => $this->question_number,
					'data' => $section_data,
					'response_count' => $this->response_count,
					'fancy' => $fancy
				);
				$html .= new View($view_file, $arg_array);
				$this->question_number += count($section_data['questions']);
			}
		}
		$arg_array = array(
			'fancy' => $fancy
		);
		$view_file = SURVEY_VIEWS . 'summaryfooter';
    	$html .= new View($view_file, $arg_array);
		return $html;
	}

	/**
	 * Updates $survey_data array after form POST.
	 *
	 * @param array $data
	 * @return nothing
	 */
	function update_survey_data($data) {
		foreach ($data as $section_name => $section_data) {
			$this->survey_data[$section_name]['responses'] = $section_data['responses'];
		}
	}

	function validate_errors() {
		$this->question_number = 1;
		$this->error = 0;
		$this->js_function = '';
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				$validate_function = 'validate_type' . $section_data['type'];
				$this->error = $this->$validate_function($this->question_number, $section_data['responses']);

//				$validation = new $validate_type($arg_array);
//				$this->error = $validation->validate_entry($this->question_number);

				if ($this->error) break;
				$this->question_number += count($section_data['responses']);
			}
		}
		return $this->error;
	}

	function validate_type1($question_number, $responses) {
		foreach ($responses as $response) {
			if ($response == 0) {
				return $question_number;
			}
			$question_number++;
		}
		return 0;
	}

	function validate_type2($question_number, $responses) {
		return (($responses[0] == 0) ? $question_number : 0);
	}

	function validate_type3($question_number, $responses) {
		$array_size = count($responses) - 1;
		if (in_array(1, array_slice($responses, 0, $array_size)) && $responses[$array_size] == 1) {
			return -$question_number;
		}
		if (!in_array(1, $responses)) {
			return $question_number;
		}
		return 0;
	}

	function save_data() {
		$this->timestamp = time();
		$cur_line = '"' . date('Y-m-d', $this->timestamp) . '",' .
								'"' . date('H:i:s', $this->timestamp) . '"';
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				foreach ($section_data['responses'] as $response) {
					$cur_line .= ',' . $response;
				}
			}
		}
		$cur_line .= "\r\n";
		$file_handle = fopen($this->survey_name . '.csv', 'a');
		fwrite($file_handle, $cur_line);
		fclose($file_handle);
		touch($this->survey_name . '.csv', $this->timestamp);
		$this->js_function = 'formDisable';
	}

	function summarize_responses() {
		// summarize responses in $this->survey_data array
		foreach ($this->survey_data as $section_name => $section_data) {
			if ($section_name != 'meta') {
				$summarize_function = 'summarize_type' . $this->survey_data[$section_name]['type'];
				$this->survey_data[$section_name]['summary'] =
					$this->$summarize_function($section_name, $section_data);
			}
		}
	}

	function summarize_type1($section_name, $section_data) {
		$answer_count = count($section_data['answers']);
		foreach ($section_data['questions'] as $kq => $q) {
			$temp_array = array_count_values($section_data['responses'][$kq]);
			$section_data['summary'][$kq] = array_fill(0, $answer_count * 2, 0);
			foreach ($temp_array as $kt => $temp_value) {
				$kt--;
				$section_data['summary'][$kq][$kt] = $temp_value;
				$section_data['summary'][$kq][$kt + $answer_count] =
					round($temp_value / $this->response_count * 100, 0);
			}
		}
		return $section_data['summary'];
	}

	function summarize_type2($section_name, $section_data) {
		$answer_count = count($section_data['answers']);
		$temp_array = array_count_values($section_data['responses'][0]);
		$section_data['summary'] = array_fill(0, $answer_count, array (0, 0));
		foreach ($temp_array as $kt => $temp_value) {
			$kt--;
			$section_data['summary'][$kt] = array (
				0 => $temp_value,
				1 => round($temp_value / $this->response_count * 100, 0)
			);
		}
		return $section_data['summary'];
	}

	function summarize_type3($section_name, $section_data) {
		$answer_count = count($section_data['answers']);
		$temp_array = array_fill(0, count($section_data['responses']), 0);
		foreach ($section_data['responses'] as $kr => $response) {
			foreach ($response as $r) {
				$temp_array[$kr] += $r;
			}
		}
		$section_data['summary'] = array_fill(0, $answer_count, array (0, 0));
		foreach ($temp_array as $kt => $temp_value) {
			$section_data['summary'][$kt][0] = $temp_value;
			$section_data['summary'][$kt][1] =
				round($temp_value / $this->response_count * 100, 0);
		}
		return $section_data['summary'];
	}

}
