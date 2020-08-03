<?php
defined('MOODLE_INTERNAL') || die;

$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_academicplan'),
            get_string('descconfig', 'block_academicplan')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'simplehtml/Allow_HTML',
            get_string('labelallowplan', 'block_academicplan'),
            get_string('descallowplan', 'block_academicplan'),
            '0'
        ));

$choices = \core_customfield\api::get_fields_supporting_course_grouping();
if ($choices) {
    $choices  = ['' => get_string('choosedots')] + $choices;
    $settings->add(new admin_setting_configselect(
            'block_academicplan/customfiltergrouping1',
            get_string('customfiltergrouping', 'block_academicplan'),
            '',
            '',
            $choices));
} else {
    $settings->add(new admin_setting_configempty(
            'block_academicplan/customfiltergrouping1',
            get_string('customfiltergrouping', 'block_academicplan'),
            get_string('customfiltergrouping_nofields', 'block_academicplan')));
}