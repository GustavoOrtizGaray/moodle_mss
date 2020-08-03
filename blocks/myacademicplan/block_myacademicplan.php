<?php

defined('MOODLE_INTERNAL') || die();

class block_myacademicplan extends block_base{

	public function init(){
		$this->title = get_string('myacademicplan','block_myacademicplan');
	}
	/**
     * Returns the contents.
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        if (isset($this->content)) {
            return $this->content;
        }
        $group = get_user_preferences('block_myacademicplan_user_grouping_preference');
        $sort = get_user_preferences('block_myacademicplan_user_sort_preference');
        $view = get_user_preferences('block_myacademicplan_user_view_preference');
        $paging = get_user_preferences('block_myacademicplan_user_paging_preference');
        $customfieldvalue = get_user_preferences('block_myacademicplan_user_grouping_customfieldvalue_preference');

        $renderable = new \block_myacademicplan\output\main($group, $sort, $view, $paging, $customfieldvalue);
        $renderer = $this->page->get_renderer('block_myacademicplan');

        $this->content = new stdClass();
        $this->content->text = $renderer->render($renderable);
        $this->content->footer = '';

        return $this->content;
    }

    /**
     * Locations where block can be displayed.
     *
     * @return array
     */
    public function applicable_formats() {
        return array('li' => true);
    }

    /**
     * Allow the block to have a configuration page.
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        // Return all settings for all users since it is safe (no private keys, etc..).
        $configs = get_config('block_myacademicplan');

        // Get the customfield values (if any).
        if ($configs->displaygroupingcustomfield) {
            $group = get_user_preferences('block_myacademicplan_user_grouping_preference');
            $sort = get_user_preferences('block_myacademicplan_user_sort_preference');
            $view = get_user_preferences('block_myacademicplan_user_view_preference');
            $paging = get_user_preferences('block_myacademicplan_user_paging_preference');
            $customfieldvalue = get_user_preferences('block_myacademicplan_user_grouping_customfieldvalue_preference');

            $renderable = new \block_myacademicplan\output\main($group, $sort, $view, $paging, $customfieldvalue);
            $customfieldsexport = $renderable->get_customfield_values_for_export();
            if (!empty($customfieldsexport)) {
                $configs->customfieldsexport = json_encode($customfieldsexport);
            }
        }

        return (object) [
            'instance' => new stdClass(),
            'plugin' => $configs,
        ];
    }
}