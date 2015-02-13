<?php
/**
* Object that fetches the options from the database on construction
*/ 
class PluginOptions {
	protected $options = null;
	
	function __construct(){
	}
	
	public function loadOptions(){
		$this->options = get_option('bureauopvallend');
	}

	public function getOptions(){
		return $this->options;		
	}
	
	public function getOption($optionKey){
		return $this->options[$optionKey];
	}
	public function setOptions($options){
		$this->options = $options;
	}
	
	public function registerSettings(){
		register_setting( 'bureauopvallend', 'bureauopvallend' ); //store all webshop settings in one array
	}
	
}
?>
