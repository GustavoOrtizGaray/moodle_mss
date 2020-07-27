<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for the myacademicplan block
 *
 * @package    block_myacademicplan
 * @copyright  2019 Tom Dickman <tomdickman@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot . '/blocks/myacademicplan/lib.php');

    // Opciones de presentación encabezado.
    $settings->add(new admin_setting_heading('block_myacademicplan/appearance',
            get_string('appearance', 'admin'),
            ''));

    // Mostrar categorías de cursos en los elementos del curso del panel (cards, lists, summary items).
    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaycategories',
            get_string('displaycategories', 'block_myacademicplan'),
            get_string('displaycategories_help', 'block_myacademicplan'),
            1));

    // Enable / Disable available layouts.
    $choices = array(BLOCK_MYACADEMICPLAN_VIEW_CARD => get_string('card', 'block_myacademicplan'),
            BLOCK_MYACADEMICPLAN_VIEW_LIST => get_string('list', 'block_myacademicplan'),
            BLOCK_MYACADEMICPLAN_VIEW_SUMMARY => get_string('summary', 'block_myacademicplan'));
    $settings->add(new admin_setting_configmulticheckbox(
            'block_myacademicplan/layouts',
            get_string('layouts', 'block_myacademicplan'),
            get_string('layouts_help', 'block_myacademicplan'),
            $choices,
            $choices));
    unset ($choices);

    // Enable / Disable course filter items.
    $settings->add(new admin_setting_heading('block_myacademicplan/availablegroupings',
            get_string('availablegroupings', 'block_myacademicplan'),
            get_string('availablegroupings_desc', 'block_myacademicplan')));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingallincludinghidden',
            get_string('allincludinghidden', 'block_myacademicplan'),
            '',
            0));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingall',
            get_string('all', 'block_myacademicplan'),
            '',
            1));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupinginprogress',
            get_string('inprogress', 'block_myacademicplan'),
            '',
            1));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingpast',
            get_string('past', 'block_myacademicplan'),
            '',
            1));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingfuture',
            get_string('future', 'block_myacademicplan'),
            '',
            1));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingcustomfield',
            get_string('customfield', 'block_myacademicplan'),
            '',
            0));

    $choices = \core_customfield\api::get_fields_supporting_course_grouping();
    if ($choices) {
        $choices  = ['' => get_string('choosedots')] + $choices;
        $settings->add(new admin_setting_configselect(
                'block_myacademicplan/customfiltergrouping',
                get_string('customfiltergrouping', 'block_myacademicplan'),
                '',
                '',
                $choices));
    } else {
        $settings->add(new admin_setting_configempty(
                'block_myacademicplan/customfiltergrouping',
                get_string('customfiltergrouping', 'block_myacademicplan'),
                get_string('customfiltergrouping_nofields', 'block_myacademicplan')));
    }
    $settings->hide_if('block_myacademicplan/customfiltergrouping', 'block_myacademicplan/displaygroupingcustomfield');

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupingfavourites',
            get_string('favourites', 'block_myacademicplan'),
            '',
            1));

    $settings->add(new admin_setting_configcheckbox(
            'block_myacademicplan/displaygroupinghidden',
            get_string('hiddencourses', 'block_myacademicplan'),
            '',
            1));
}
