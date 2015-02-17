<?php
class FilterView {
	function __construct($model){
		$this->model = $model;
	}
	function render(){
		$g = $this->model->getGames();
		$ret = '<script type="text/javascript">gameCategories = '.$g.'</script>';
		$ret .= '<script type="text/javascript" src="'.plugins_url('/bureauopvallend/js/filter.js').'"></script>';
		return $ret;	
	}
}
?>

