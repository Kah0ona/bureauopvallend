<?php
class FilterWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bureauopvallend_filterwidget', // Base ID
			'Game filter widget', // Name
			array( 'description' => __( 'Toont checkboxes om games te filteren', 'text_domain' ), ) // Args
		);
		
		include_once(PLUGIN_PATH.'models/PluginOptions.php');				
		include_once(PLUGIN_PATH.'models/FilterModel.php');				
		include_once(PLUGIN_PATH.'views/FilterView.php');				
	}

 	public function form( $instance ) {
	 	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Titel', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 	
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance; 
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		$options = new PluginOptions();
		$options->loadOptions();
		
		$m = new FilterModel($options);
		$v = new FilterView($m);
		 
		echo $v->render();
		
		echo $after_widget;	
	}
}
?>
