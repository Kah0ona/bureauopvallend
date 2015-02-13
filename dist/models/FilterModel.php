<?php
class FilterModel {
	protected $options = null;
	
	function __construct($options){
		$this->options = $options;
	}


	/**
	 * returns a json object of games and their categories
	 */
	public function getGames(){
		//WP_Query all fitness_games CPTs
		return '';//json
	}
}
?>

