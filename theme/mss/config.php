<?php
 
// Cada archivo debe tener GPL y derechos de autor en el encabezado; lo omitimos en los tutoriales pero no debe omitirlo de verdad.
 
// Esta línea protege el archivo del acceso directo de una URL.                                                            
defined('MOODLE_INTERNAL') || die();
 
// $ THEME se define antes de incluir esta página y podemos definir configuraciones agregando propiedades a este objeto global.            
 
// La primera configuración que necesitamos es el nombre del tema. Esta debería ser la última parte del nombre del componente, y lo mismo             
// como el nombre del directorio para nuestro tema.                                                                                            
require_once(__DIR__ . '/lib.php');

$THEME->name = 'mss';
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->editor_scss = ['editor'];
$THEME->usefallback = true;
$THEME->scss = function($theme) {
    return theme_mss_get_main_scss_content($theme);
};

$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'file' => 'columns2.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Página principal del curso.
    'course' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Parte del curso, típico de los módulos: diseño de página predeterminado si $ cm especificado en require_login ().
    'incourse' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // La página de inicio del sitio.
    'frontpage' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    // Guiones de administración del servidor.
    'admin' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Mi página de tablero.
    'mydashboard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true),
    ),
    // Mi pagina publica.
    'mypublic' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),

    // Páginas que aparecen en ventanas emergentes: sin navegación, sin bloques, sin encabezado.
    'popup' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // Sin bloques y pie de página mínimo: ¡solo para diseños de marcos heredados!
    'frametop' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nocoursefooter' => true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Utilizado durante la actualización e instalación, y para el mensaje 'Este sitio está en mantenimiento'.
    // Esto no debe tener bloques, enlaces o llamadas a la API que conduzcan a la interacción de la base de datos o la memoria caché.
    // Tenga mucho cuidado si está modificando este diseño.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),
    // Debe mostrar solo el contenido y los encabezados básicos.
    'print' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // La distribución de página utilizada para safebrowser y securewindow.
    'secure' => array(
        'file' => 'secure.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    )
];

$THEME->parents = [];
$THEME->enable_dock = false;
$THEME->csstreepostprocessor = 'theme_mss_css_tree_post_processor';
$THEME->extrascsscallback = 'theme_mss_get_extra_scss';
$THEME->prescsscallback = 'theme_mss_get_pre_scss';
$THEME->precompiledcsscallback = 'theme_mss_get_precompiled_css';
$THEME->yuicssmodules = array();
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
