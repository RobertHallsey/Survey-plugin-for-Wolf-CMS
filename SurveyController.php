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
		$this->display('survey/views/index', array());
	}
 
	function documentation() {
		$this->display('survey/views/documentation', array());
	}

 
 }