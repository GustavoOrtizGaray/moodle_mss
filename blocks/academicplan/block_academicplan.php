<?php

defined('MOODLE_INTERNAL') || die();

class block_academicplan extends block_base {
	public function init(){
		$this->title = get_string('acadmicplan','block_academicplan');
	}

	public function get_content() {
          if ($this->content !== null) {
            return $this->content;
          }
 		  
 		  $renderable= new \block_academicplan\output\main();
 		  $renderer = $this->page->get_renderer('block_academicplan');

          $this->content =  new stdClass;
          $this->content->text = $renderer->render($renderable);
          $this->content->footer = 'Footer here...';
 
          return $this->content;
    }

    public function specialization() {
	    if (isset($this->config)) {
	        if (empty($this->config->title)) {
	            $this->title = get_string('defaulttitle', 'block_academicplan');            
	        } else {
	            $this->title = $this->config->title;
	        }
	 
	        if (empty($this->config->text)) {
	            $this->config->text = get_string('defaulttext', 'block_academicplan');
	        }    
	    }
	}
	public function instance_allow_multiple() {
	  return true;
	}
}
