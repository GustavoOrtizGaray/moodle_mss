/**
 * Manage the courses view for the academicplan block.
 *
 * @package    block_academicplan
 * @copyright  2018 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'],
function($) {

    /**
     * Inicialice la lista de cursos y las vistas de tarjetas en la carga de la página.
     *
     * @param {object} root El elemento raíz para la vista de cursos.
     */
    var init = function(root) {
        initializePagedContent(root);
    };
    /**
     * Inicialice la lista de cursos y las vistas de tarjetas en la carga de la página.
     *
     * @param {object} root El elemento raíz para la vista de cursos.
     * @param {object} content El elemento de contenido para la vista de cursos.
     */
    var initializePagedContent = function(root) {
        namespace = "block_academicplan_" + root.attr('id') + "_" + Math.random();
        alert( "nombre de espacio :" + namespace );
        return namespace;
    }; 

    return{
        init: init
    }; 

});