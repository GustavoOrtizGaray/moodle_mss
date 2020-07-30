<?php
// Cada archivo debe tener GPL y derechos de autor en el encabezado; lo omitimos en los tutoriales pero no debe omitirlo de verdad.
 
// Esta línea protege el archivo del acceso directo de una URL.                                                               
defined('MOODLE_INTERNAL') || die();                                                                                                
 
// Esta es la versión del complemento.                                                                                              
$plugin->version = '2016102100';                                                                                                    
 
// Esta es la versión de Moodle que requiere este complemento.                                                                             
$plugin->requires = '2016070700';                                                                                                   
 
// Este es el nombre del componente del complemento: siempre comienza con 'theme_'                                                       
// para temas y debe ser el mismo que el nombre de la carpeta.                                                                     
$plugin->component = 'theme_mss';                                                                                                
 
// Esta es una lista de complementos, este complemento depende de (y sus versiones).                                                         
$plugin->dependencies = [                                                                                                           
    'theme_boost' => '2016102100'                                                                                                   
];