<?php


namespace block_academicplan\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

require_once($CFG->dirroot . '/blocks/academicplan/lib.php');

class main implements renderable, templatable {
	private $customfiltergrouping;
	public function __construct(){
		$config = get_config('block_academicplan');
		//print_object($config);
		//$this->customfiltergrouping = $config->customfiltergrouping1;
	}

	public function get_formatted_available_layouts_for_export() {

        return array_map(array($this, 'format_layout_for_export'), $this->layouts);

    }

	public function export_for_template(renderer_base $output){
		global $CFG, $USER,$DB;

		//$fieldid = $DB->get_field('customfield_field', 'id', ['shortname' => $this->customfiltergrouping] );
        //if (!$fieldid) {
        //    return [];
        //}

		$courses = enrol_get_all_users_courses_academicplan( $USER->id , true);
		
		
		$defaultvariables=[
			'bodytext'=>'este texto es el de nuestro body de plan academico',
			'courses' => $courses,
		];

		//print_object($defaultvariables);
		return $defaultvariables;

	}

}
