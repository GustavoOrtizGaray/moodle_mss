/**
 * Manage the courses view for the academicplan block.
 *
 * @package    block_academicplan
 * @copyright  2018 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery','block_academicplan/repository'],
function($,Repository) {

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
    /**
     * Inicialice la lista de cursos y las vistas de tarjetas en la carga de la página.
     *
     * @param {object} root El elemento raíz para la vista de cursos.
     * @param {object} content El elemento de contenido para la vista de cursos.
     */
    var initializePagedContent = function(root) {
        namespace = "block_myacademicplan_" + root.attr('id') + "_" + Math.random();

        var pagingLimit = parseInt(root.find(Selectors.courseView.region).attr('data-paging'), 10);
        var itemsPerPage = NUMCOURSES_PERPAGE.map(function(value) {
            var active = false;
            if (value == pagingLimit) {
                active = true;
            }

            return {
                value: value,
                active: active
            };
        });

        // Filtre todas las opciones de paginación que son demasiado grandes para la cantidad de cursos en los que está inscrito el usuario.
        var totalCourseCount = parseInt(root.find(Selectors.courseView.region).attr('data-totalcoursecount'), 10);
        if (totalCourseCount) {
            itemsPerPage = itemsPerPage.filter(function(pagingOption) {
                return pagingOption.value < totalCourseCount;
            });
        }

        var filters = getFilterValues(root);
        var config = $.extend({}, DEFAULT_PAGED_CONTENT_CONFIG);
        config.eventNamespace = namespace;

        var pagedContentPromise = PagedContentFactory.createWithLimit(itemsPerPage,
            function(pagesData, actions) {
                var promises = [];

                pagesData.forEach(function(pageData) {
                    var currentPage = pageData.pageNumber;
                    var limit = (pageData.limit > 0) ? pageData.limit : 0;

                    // Restablecer variables locales si los límites han cambiado
                    if (lastLimit != limit) {
                        loadedPages = [];
                        courseOffset = 0;
                        lastPage = 0;
                    }

                    if (lastPage == currentPage) {
                        // Si estamos en la última página y tenemos sus datos, cárguelos desde el caché
                        actions.allItemsLoaded(lastPage);
                        promises.push(renderCourses(root, loadedPages[currentPage]));
                        return;
                    }

                    lastLimit = limit;

                    // Obtenga 2 páginas de datos, ya que los necesitaremos para la funcionalidad oculta.
                    if (loadedPages[currentPage + 1] == undefined) {
                        if (loadedPages[currentPage] == undefined) {
                            limit *= 2;
                        }
                    }

                    var pagePromise = getMyCourses(
                        filters,
                        limit
                    ).then(function(coursesData) {
                        var courses = coursesData.courses;
                        var nextPageStart = 0;
                        var pageCourses = [];

                        // Si se cargan los datos de la página actual, asegúrese de maximizarlos al límite de la página.
                        if (loadedPages[currentPage] != undefined) {
                            pageCourses = loadedPages[currentPage].courses;
                            var currentPageLength = pageCourses.length;
                            if (currentPageLength < pageData.limit) {
                                nextPageStart = pageData.limit - currentPageLength;
                                pageCourses = $.merge(loadedPages[currentPage].courses, courses.slice(0, nextPageStart));
                            }
                        } else {
                            nextPageStart = pageData.limit;
                            pageCourses = (pageData.limit > 0) ? courses.slice(0, pageData.limit) : courses;
                        }

                        // Terminado de configurar la página actual
                        loadedPages[currentPage] = {
                            courses: pageCourses
                        };

                        // Configura la página siguiente

                        var remainingCourses = nextPageStart ? courses.slice(nextPageStart, courses.length) : [];
                        if (remainingCourses.length) {
                            loadedPages[currentPage + 1] = {
                                courses: remainingCourses
                            };
                        }

                        // Establezca la última página en la página actual o en la siguiente.
                        if (loadedPages[currentPage].courses.length < pageData.limit || !remainingCourses.length) {
                            lastPage = currentPage;
                            actions.allItemsLoaded(currentPage);
                        } else if (loadedPages[currentPage + 1] != undefined
                            && loadedPages[currentPage + 1].courses.length < pageData.limit) {
                            lastPage = currentPage + 1;
                        }

                        courseOffset = coursesData.nextoffset;
                        return renderCourses(root, loadedPages[currentPage]);
                    })
                    .catch(Notification.exception);

                    promises.push(pagePromise);
                });

                return promises;
            },
            config
        );

        pagedContentPromise.then(function(html, js) {
            registerPagedEventHandlers(root, namespace);
            return Templates.replaceNodeContents(root.find(Selectors.courseView.region), html, js);
        }).catch(Notification.exception);
    };

    var getMyCourses = function(filters, limit) {

        return Repository.getEnrolledCoursesByTimeline();
    };

    return{
        init: init
    }; 

});