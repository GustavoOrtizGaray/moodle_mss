/**
 * Javascript para inicializar el bloque academicplan.
 *
 * @package    block_academicplan
 * @copyright  2018 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery','block_academicplan/view'],
    function($,View) {
        /**
         * Initialise all of the modules for the overview block.
         *
         * @param {object} root The root element for the overview block.
         */
        var init = function(root) {
            root = $(root);
            // Inicializar los elementos de navegación del curso.
            // ViewNav.init(root);
            // Inicializar los módulos de vista de cursos.
            View.init(root);
            console.log("root :" + root);
        };

        return {
            init: init
        };
    }
);