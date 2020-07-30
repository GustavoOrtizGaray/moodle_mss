/**
 * Un módulo de JavaScript para recuperar los coruses inscritos del servidor.
 *
 * @package    block_academicplan
 * @copyright  2018 Bas Brands <base@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core/ajax', 'core/notification'], function(Ajax, Notification) {

    /**
     * Recupere una lista de cursos inscritos.
     *
     * Los argumentos válidos son:
     * string classification    future, inprogress, past
     * int limit                cantidad de registros a retirar
     * int Offset               desplazamiento para paginación
     * int sort                 ordenar por acceso lasta o nombre
     *
     * @method getEnrolledCoursesByTimeline
     * @param {object} args Los argumentos de solicitud
     * @return {promise} Resuelto con una variedad de cursos.
     */
    var getEnrolledCoursesByTimeline = function(args) {

        var request = {
            methodname: 'core_course_get_enrolled_courses_by_timeline_classification',
            args: args
        };

        var promise = Ajax.call([request])[0];

        return promise;
    };

    /**
     * Establezca el estado favorito en una lista de cursos.
     *
     * Los argumentos válidos son:
     * Lista de cursos de matriz de números de identificación del curso.
     *
     * @param {Object} args Los argumentos se envían al servicio web.
     * @return {Promise} Resolver con advertencias.
     */
    var setFavouriteCourses = function(args) {

        var request = {
            methodname: 'core_course_set_favourite_courses',
            args: args
        };

        var promise = Ajax.call([request])[0];

        return promise;
    };

    /**
     * Actualiza las preferencias del usuario.
     *
     * @param {Object} args Los argumentos se envían al servicio web.
     *
     * Args de muestra:
     * {
     *     preferences: [
     *         {
     *             type: 'block_example_user_sort_preference'
     *             value: 'title'
     *         }
     *     ]
     * }
     */
    var updateUserPreferences = function(args) {
        var request = {
            methodname: 'core_user_update_user_preferences',
            args: args
        };

        Ajax.call([request])[0]
            .fail(Notification.exception);
    };

    return {
        getEnrolledCoursesByTimeline: getEnrolledCoursesByTimeline,
        setFavouriteCourses: setFavouriteCourses,
        updateUserPreferences: updateUserPreferences
    };
});
