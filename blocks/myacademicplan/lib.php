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
 * Library functions for overview.
 *
 * @package   block_myacadmicplan
 * @copyright 2018 Peter Dias
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Constantes para las opciones de agrupación de preferencias del usuario
 */
define('BLOCK_MYACADEMICPLAN_GROUPING_ALLINCLUDINGHIDDEN', 'allincludinghidden');
define('BLOCK_MYACADEMICPLAN_GROUPING_ALL', 'all');
define('BLOCK_MYACADEMICPLAN_GROUPING_INPROGRESS', 'inprogress');
define('BLOCK_MYACADEMICPLAN_GROUPING_FUTURE', 'future');
define('BLOCK_MYACADEMICPLAN_GROUPING_PAST', 'past');
define('BLOCK_MYACADEMICPLAN_GROUPING_FAVOURITES', 'favourites');
define('BLOCK_MYACADEMICPLAN_GROUPING_HIDDEN', 'hidden');
define('BLOCK_MYACADEMICPLAN_GROUPING_CUSTOMFIELD', 'customfield');

/**
 * Permite la selección de todos los cursos sin un valor para el campo personalizado.
 */
define('BLOCK_MYACADEMICPLAN_CUSTOMFIELD_EMPTY', -1);

/**
 * Constantes para la línea de tiempo de opciones de clasificación de preferencias del usuario
 * 
 */
define('BLOCK_MYACADEMICPLAN_SORTING_TITLE', 'title');
define('BLOCK_MYACADEMICPLAN_SORTING_LASTACCESSED', 'lastaccessed');
define('BLOCK_MYACADEMICPLAN_SORTING_SHORTNAME', 'shortname');

/**
 * Constantes para las opciones de vista de preferencias del usuario
 */
define('BLOCK_MYACADEMICPLAN_VIEW_CARD', 'card');
define('BLOCK_MYACADEMICPLAN_VIEW_LIST', 'list');
define('BLOCK_MYACADEMICPLAN_VIEW_SUMMARY', 'summary');

/**
 * Constantes para las preferencias de paginación del usuario
 */
define('BLOCK_MYACADEMICPLAN_PAGING_12', 12);
define('BLOCK_MYACADEMICPLAN_PAGING_24', 24);
define('BLOCK_MYACADEMICPLAN_PAGING_48', 48);
define('BLOCK_MYACADEMICPLAN_PAGING_96', 96);
define('BLOCK_MYACADEMICPLAN_PAGING_ALL', 0);

/**
 * Constantes para la configuración de visualización de categoría de administrador
 */
define('BLOCK_MYACADEMICPLAN_DISPLAY_CATEGORIES_ON', 'on');
define('BLOCK_MYACADEMICPLAN_DISPLAY_CATEGORIES_OFF', 'off');

/**
 * Obtenga las preferencias de usuario actuales que están disponibles
 *
 * @return Matriz mixta que representa las opciones actuales junto con los valores predeterminados
 */
function block_myacademicplan_user_preferences() {
    $preferences['block_myacademicplan_user_grouping_preference'] = array(
        'null' => NULL_NOT_ALLOWED,
        'default' => BLOCK_MYACADEMICPLAN_GROUPING_ALL,
        'type' => PARAM_ALPHA,
        'choices' => array(
            BLOCK_MYACADEMICPLAN_GROUPING_ALLINCLUDINGHIDDEN,
            BLOCK_MYACADEMICPLAN_GROUPING_ALL,
            BLOCK_MYACADEMICPLAN_GROUPING_INPROGRESS,
            BLOCK_MYACADEMICPLAN_GROUPING_FUTURE,
            BLOCK_MYACADEMICPLAN_GROUPING_PAST,
            BLOCK_MYACADEMICPLAN_GROUPING_FAVOURITES,
            BLOCK_MYACADEMICPLAN_GROUPING_HIDDEN,
            BLOCK_MYACADEMICPLAN_GROUPING_CUSTOMFIELD,
        )
    );

    $preferences['block_myacademicplan_user_grouping_customfieldvalue_preference'] = [
        'null' => NULL_ALLOWED,
        'default' => null,
        'type' => PARAM_RAW,
    ];

    $preferences['block_myacademicplan_user_sort_preference'] = array(
        'null' => NULL_NOT_ALLOWED,
        'default' => BLOCK_MYACADEMICPLAN_SORTING_TITLE,
        'type' => PARAM_ALPHA,
        'choices' => array(
            BLOCK_MYACADEMICPLAN_SORTING_TITLE,
            BLOCK_MYACADEMICPLAN_SORTING_LASTACCESSED,
            BLOCK_MYACADEMICPLAN_SORTING_SHORTNAME
        )
    );
    $preferences['block_myacademicplan_user_view_preference'] = array(
        'null' => NULL_NOT_ALLOWED,
        'default' => BLOCK_MYACADEMICPLAN_VIEW_CARD,
        'type' => PARAM_ALPHA,
        'choices' => array(
            BLOCK_MYACADEMICPLAN_VIEW_CARD,
            BLOCK_MYACADEMICPLAN_VIEW_LIST,
            BLOCK_MYACADEMICPLAN_VIEW_SUMMARY
        )
    );

    $preferences['/^block_myacademicplan_hidden_course_(\d)+$/'] = array(
        'isregex' => true,
        'choices' => array(0, 1),
        'type' => PARAM_INT,
        'null' => NULL_NOT_ALLOWED,
        'default' => 'none'
    );

    $preferences['block_myacademicplan_user_paging_preference'] = array(
        'null' => NULL_NOT_ALLOWED,
        'default' => BLOCK_MYACADEMICPLAN_PAGING_12,
        'type' => PARAM_INT,
        'choices' => array(
            BLOCK_MYACADEMICPLAN_PAGING_12,
            BLOCK_MYACADEMICPLAN_PAGING_24,
            BLOCK_MYACADEMICPLAN_PAGING_48,
            BLOCK_MYACADEMICPLAN_PAGING_96,
            BLOCK_MYACADEMICPLAN_PAGING_ALL
        )
    );

    return $preferences;
}

/**
 * Pre-delete course hook to cleanup any records with references to the deleted course.
 *
 * @param stdClass $course The deleted course
 */
function block_myacademicplan_pre_course_delete(\stdClass $course) {
    // Eliminar todos los cursos favoritos que se hayan creado para los usuarios, para este curso.
    $service = \core_favourites\service_factory::get_service_for_component('core_course');
    $service->delete_favourites_by_type_and_item('courses', $course->id);
}

function enrol_get_all_users_courses_listacursos($userid, $onlyactive = false, $fields = null, $sort = null) {
    global $DB;

    // Re-Arrange the course sorting according to the admin settings.
    $sort = enrol_get_courses_sortingsql($sort);

    // Guest account does not have any courses
    if (isguestuser($userid) or empty($userid)) {
        return(array());
    }

    $basefields = array('id', 'category', 'sortorder',
            'shortname', 'fullname', 'idnumber',
            'startdate', 'visible',
            'defaultgroupingid',
            'groupmode', 'groupmodeforce');

    if (empty($fields)) {
        $fields = $basefields;
    } else if (is_string($fields)) {
        // turn the fields from a string to an array
        $fields = explode(',', $fields);
        $fields = array_map('trim', $fields);
        $fields = array_unique(array_merge($basefields, $fields));
    } else if (is_array($fields)) {
        $fields = array_unique(array_merge($basefields, $fields));
    } else {
        throw new coding_exception('Invalid $fields parameter in enrol_get_all_users_courses()');
    }
    if (in_array('*', $fields)) {
        $fields = array('*');
    }

    $orderby = "";
    $sort    = trim($sort);
    if (!empty($sort)) {
        $rawsorts = explode(',', $sort);
        $sorts = array();
        foreach ($rawsorts as $rawsort) {
            $rawsort = trim($rawsort);
            if (strpos($rawsort, 'c.') === 0) {
                $rawsort = substr($rawsort, 2);
            }
            $sorts[] = trim($rawsort);
        }
        $sort = 'c.'.implode(',c.', $sorts);
        $orderby = "ORDER BY $sort";
    }

    $params = array('siteid'=>SITEID);

    if ($onlyactive) {
        $subwhere = "WHERE ue.status = :active AND e.status = :enabled AND ue.timestart < :now1 AND (ue.timeend = 0 OR ue.timeend > :now2)";
        $params['now1']    = round(time(), -2); // improves db caching
        $params['now2']    = $params['now1'];
        $params['active']  = ENROL_USER_ACTIVE;
        $params['enabled'] = ENROL_INSTANCE_ENABLED;
    } else {
        $subwhere = "";
    }

    $coursefields = 'c.' .join(',c.', $fields);
    $ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
    $ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
    $params['contextlevel'] = CONTEXT_COURSE;

    //note: we can not use DISTINCT + text fields due to Oracle and MS limitations, that is why we have the subselect there
    $parametroadicional=',case when c.id<=2 then true else "" END as first';
    $sql = "SELECT $coursefields $ccselect $parametroadicional
              FROM {course} c
              JOIN (SELECT DISTINCT e.courseid
                      FROM {enrol} e
                      JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)
                 $subwhere
                   ) en ON (en.courseid = c.id)
           $ccjoin
             WHERE c.id <> :siteid
          $orderby";
    $params['userid']  = $userid;

    $courses = $DB->get_records_sql($sql, $params);
    
    return $courses;
}