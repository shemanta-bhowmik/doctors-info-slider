<?php 
/*
Plugin Name: Doctors Information Slider
Plugin URI:  http://shemantabhowmik.com
Description: Very easy to use Slider to show Doctors Information
Version:     1.0.0
Author:      Batch 24
Author URI:  http://batch24.xyz
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: doctors-info

*/


defined( 'ABSPATH' ) or die( 'directory browing is disabled' );

class Doctor{

	/**
	 * All importants actions which will be added instantly after activation this plugin are here.
	 * -- plugin main action hook
	 * -- metabox files required
	 * -- scripts files hook
	 */
	public function __construct(){

		// required files 
		if( file_exists( dirname(__FILE__) . '/metabox/init.php' ) ){
			require_once( dirname(__FILE__) . '/metabox/init.php');
		}
		if( file_exists( dirname(__FILE__) . '/metabox/metabox-config.php' ) ){
			require_once( dirname(__FILE__) . '/metabox/metabox-config.php');
		}

		add_action( 'init', array($this, 'sujan_doctors_slider_post') );

		add_action('wp_enqueue_scripts', array( $this, 'external_scripts_and_styles') );

	}

	/**
	 * All style and script files are here
	 * -- owl carouse css file
	 * -- custom css file
	 * -- owl carousel js file
	 * -- custom js file
	 */
	public function external_scripts_and_styles(){

		wp_enqueue_style('owl-carousel', PLUGINS_URL('css/owl.carousel.css', __FILE__));

		wp_enqueue_style('owl-custom', PLUGINS_URL('css/owl.custom.css', __FILE__));

		wp_enqueue_script('owl-carousel', PLUGINS_URL('js/owl.carousel.min.js', __FILE__), array('jquery'));

		wp_enqueue_script('owl-custom', PLUGINS_URL('js/owl.custom.js', __FILE__), array('jquery'));
	
	}

	public function sujan_doctors_slider_post() {

		$labels = array(
			'name'               => _x( 'Doctors', 'Doctors general name', 'doctors-info' ),
			'singular_name'      => _x( 'Doctor', 'Doctors singular name', 'doctors-info' ),
			'menu_name'          => _x( 'Doctors', 'Doctors admin menu', 'doctors-info' ),
			'name_admin_bar'     => _x( 'Doctor', 'add new on admin bar', 'doctors-info' ),
			'add_new'            => _x( 'Add New info', 'book', 'doctors-info' ),
			'add_new_item'       => __( 'Add New info', 'doctors-info' ),
			'new_item'           => __( 'New Doctor', 'doctors-info' ),
			'edit_item'          => __( 'Edit info', 'doctors-info' ),
			'view_item'          => __( 'View info', 'doctors-info' ),
			'all_items'          => __( 'All Doctors', 'doctors-info' ),
			'search_items'       => __( 'Search Doctors', 'doctors-info' ),
			'parent_item_colon'  => __( 'Parent Doctors:', 'doctors-info' ),
			'not_found'          => __( 'No Doctors info found.', 'doctors-info' ),
			'not_found_in_trash' => __( 'No Doctors info found in Trash.', 'doctors-info' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'doctors-info' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'doctor' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon' 		 => 'dashicons-plus-alt',
			'supports'           => array( 'title', 'thumbnail' )
		);

		register_post_type( 'doctors_info', $args );

		/**
		 * Taxinomy register for this plugin
		 * Name "Speciality"
		 * user can add speciality easily using this taxonomy
		 */

		$label = array(
			'name'                       => _x( 'Speciality', 'Speciality general name' ),
			'singular_name'              => _x( 'Speciality', 'Speciality singular name' ),
			'search_items'               => __( 'Search Speciality' ),
			'popular_items'              => __( 'Popular Speciality' ),
			'all_items'                  => __( 'All Speciality' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Speciality' ),
			'update_item'                => __( 'Update Speciality' ),
			'add_new_item'               => __( 'Add New Speciality' ),
			'new_item_name'              => __( 'New Speciality Name' ),
			'separate_items_with_commas' => __( 'Separate Speciality with commas' ),
			'add_or_remove_items'        => __( 'Add or remove Speciality' ),
			'choose_from_most_used'      => __( 'Choose from the most used Speciality' ),
			'not_found'                  => __( 'No Speciality found.' ),
			'menu_name'                  => __( 'Speciality' ),
		);

		$arguments = array(
			'hierarchical'          => true,
			'labels'                => $label,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'writer' ),
		);

		register_taxonomy( 'doctors-speciality', 'doctors_info', $arguments );
	}

	/**
	 * Adding shortcode 
	 */
	public function doctors_shortcode(){

		add_shortcode('doctors-info', array( $this, 'doctors_info_output' ) );

	}

	public function doctors_info_output(){

		ob_start(); 
		
		$prefix = '_prefix_';
		
		$doctors_info = new WP_Query( array(
	
			'post_type' => 'doctors_info',
			'posts_per_page' => -1
		
		) );

		?>
		<div class="sujan-doctors">

		<?php while($doctors_info->have_posts()) : $doctors_info->the_post();
		
		?>

			<div class="doctors-info">
				<div class="info-left">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</div>
				<div class="info-right">
					<div class="informations">
						<ul>
							<li>Name: <span><?php echo get_post_meta(get_the_id(), $prefix.'doctors_name', true); ?></span></li>
							<li>Speciality: <span>
								
								<?php 
								
								$specialities = get_the_terms(get_the_id(), 'doctors-speciality');

								if ( !empty( $specialities ) )
									foreach($specialities as $speciality)
										echo $speciality->name;
								else
									echo 'N/A';
								
								?>
							</span></li>
							<li>Age: <span><?php echo get_post_meta(get_the_id(), $prefix.'doctors_age', true); ?></span></li>
							<li>Degree: <span><?php echo get_post_meta(get_the_id(), $prefix.'doctors_degree', true); ?></span></li>
							<li>Chamber: <span><?php echo get_post_meta(get_the_id(), $prefix.'doctors_chamber', true); ?></span></li>
							
						</ul>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
		</div>
		<?php return ob_get_clean();
	}

}

$doctor = new Doctor();

$doctor->doctors_shortcode();


