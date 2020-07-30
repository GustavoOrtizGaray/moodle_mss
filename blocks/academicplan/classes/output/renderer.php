<?php
namespace block_academicplan\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;
use renderable;

/**
 * academicplan block renderer
 *
 * @package    block_academicplan
 * @copyright  2020 Hector Ortiz <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the main content for the block overview.
     *
     * @param main $main The main renderable
     * @return string HTML string
     */
    public function render_main(main $main) {
        return $this->render_from_template('block_academicplan/main', $main->export_for_template($this));
    }
}