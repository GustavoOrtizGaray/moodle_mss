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
 * @package   theme_mss
 * @copyright 2020 Héctor Ortiz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_mss_admin_settingspage_tabs('themesettingmss', get_string('configtitle', 'theme_mss'));
    $page = new admin_settingpage('theme_mss_general', get_string('generalsettings', 'theme_mss'));

    // Preset.
    $name = 'theme_mss/preset';
    $title = get_string('preset', 'theme_mss');
    $description = get_string('preset_desc','theme_mss');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_mss', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // Estos son los construidos en presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'mss');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Configuración de archivos preestablecidos.
    $name = 'theme_mss/presetfiles';
    $title = get_string('presetfiles','theme_mss');
    $description = get_string('presetfiles_desc', 'theme_mss');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Configuración de imagen de fondo.
    $name = 'theme_mss/loginbackgroundimage';
    $title = get_string('loginbackgroundimage', 'theme_mss');
    $description = get_string('loginbackgroundimage_desc', 'theme_mss');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $body-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_mss/brandcolor';
    $title = get_string('brandcolor', 'theme_mss');
    $description = get_string('brandcolor_desc', 'theme_mss');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_mss_advanced', get_string('advancedsettings', 'theme_mss'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_mss/scsspre',
        get_string('rawscsspre', 'theme_mss'), get_string('rawscsspre_desc', 'theme_mss'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_mss/scss', get_string('rawscss', 'theme_mss'),
        get_string('rawscss_desc', 'theme_mss'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Login page background setting.                                                                                               
    // We use variables for readability.                                                                                            
    $name = 'theme_mss/loginbackgroundimage';                                                                                     
    $title = get_string('loginbackgroundimage', 'theme_mss');                                                                     
    $description = get_string('loginbackgroundimage_desc', 'theme_mss');                                                          
    // This creates the new setting.                                                                                                
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');                             
    // This means that theme caches will automatically be cleared when this setting is changed.                                     
    $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
    // We always have to add the setting to a page for it to have any effect.                                                       
    $page->add($setting);

    
}
