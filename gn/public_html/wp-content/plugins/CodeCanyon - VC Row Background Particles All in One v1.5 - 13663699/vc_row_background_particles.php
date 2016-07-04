<?php
/**
 * Plugin Name: VC Row Background Particles All in One
 * Plugin URI: http://codecanyon.net/user/wptpnet/portfolio?ref=wptpnet
 * Description: The VC Row Background Particles allows you to set a background particle to Visual Composer Row elements.
 * Version: 1.5
 * Author: wptpnet
 * Author URI: http://codecanyon.net/user/wptpnet/portfolio?ref=wptpnet
 */
 
/**
 * Current version
 */
if ( ! defined( 'VCRBP_VERSION' ) ) {
    /**
     *
     */
    define( 'VCRBP_VERSION', '1.5' );
}
 
define( 'VCRBP_PLUGIN_URL', plugins_url( null, __FILE__ ) );
define( 'VCRBP_PLUGIN_DIR', dirname( __FILE__ ) );

if ( ! function_exists( 'vcrbp_request_param' ) ) {
	/**
	 * Get param value from $_REQUEST if exists.
	 * @return null|string - null for undefined param.
	 */
	function vcrbp_request_param( $param, $default = null ) {
		return isset( $_REQUEST[ $param ] ) ? $_REQUEST[ $param ] : $default;
	}
}

if ( ! function_exists( 'vcrbp_action' ) ) {
	/**
	 * Get VC special action param.
	 * @return string|null
	 */
	function vcrbp_action() {
		$vcrbp_action = null;
		if ( isset( $_GET['vc_action'] ) ) {
			$vcrbp_action = $_GET['vc_action'];
		} elseif ( isset( $_POST['vc_action'] ) ) {
			$vcrbp_action = $_POST['vc_action'];
		}

		return $vcrbp_action;
	}
}

if ( ! function_exists( 'vcrbp_is_inline' ) ) {
	/**
	 * Check vc is inline or not.
	 * @return bool
	 */
	function vcrbp_is_inline() {
		global $vcrbp_is_inline;
		if ( is_null( $vcrbp_is_inline ) ) {
			$vcrbp_is_inline = vcrbp_action() === 'vc_inline' || ! is_null( vcrbp_request_param( 'vc_inline' ) ) || vcrbp_request_param( 'vc_editable' ) === 'true';
		}

		return $vcrbp_is_inline;
	}
}

require_once( VCRBP_PLUGIN_DIR . '/vc_column_background_particles.php' );

/*-----------------------------------------------------------------------------------*/
/*	VC Row Background for Visual Composer
/*-----------------------------------------------------------------------------------*/

if( !class_exists( 'vc_row_background_particles' ) ){
	class vc_row_background_particles {
		function __construct(){
            add_action( 'wp_head', array($this, 'vcrbp_particle_wp_head') );
			add_action( 'admin_init', array($this, 'vcrbp_particle_init') );
			add_filter( 'vcrbp_row_particle', array($this, 'vcrbp_particle_shortcode'), 10, 3 );
			if ( function_exists( 'add_shortcode_param' ) )
			{
				add_shortcode_param( 'number' , array(&$this, 'number_settings_field' ) );
			}
		}
		function vcrbp_particle_wp_head(){
            if ( vcrbp_is_inline() ) {
                wp_enqueue_script( 'vcrbp.particles', VCRBP_PLUGIN_URL . '/scripts/particles.min.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.lightweight_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wp_lightweight_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.lightweight_dust_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wp_lightweight_dust_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.lightweight_snow_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wp_lightweight_snow_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.lightweight_star_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wp_lightweight_star_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.wp_rain_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wp_rain_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.plasma', VCRBP_PLUGIN_URL . '/scripts/admin/plasma.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.organic_lines', VCRBP_PLUGIN_URL . '/scripts/admin/organic-lines.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.rainbow_curves', VCRBP_PLUGIN_URL . '/scripts/admin/rainbow-curves.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.rainbow_fireflies', VCRBP_PLUGIN_URL . '/scripts/admin/rainbow-fireflies.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.rainbow_grid', VCRBP_PLUGIN_URL . '/scripts/admin/rainbow-grid.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.centaurus', VCRBP_PLUGIN_URL . '/scripts/admin/centaurus.js', array(), VCRBP_VERSION, true );
                
                wp_enqueue_script( 'vcrbp.jquery_gradient_particle', VCRBP_PLUGIN_URL . '/scripts/jquery.gradient_particle.min.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.gradient_particle', VCRBP_PLUGIN_URL . '/scripts/admin/wpbgcp.gradient_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.jquery_shocking_particle', VCRBP_PLUGIN_URL . '/scripts/jquery.shocking_particle.min.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.shocking_particle', VCRBP_PLUGIN_URL . '/scripts/admin/vcrbscp.shocking_particle.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.jquery_particleground', VCRBP_PLUGIN_URL . '/scripts/jquery.particleground.min.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.particleground', VCRBP_PLUGIN_URL . '/scripts/admin/wpvcpg.particleground.js', array(), VCRBP_VERSION, true );
                wp_enqueue_script( 'vcrbp.twinkle', VCRBP_PLUGIN_URL . '/scripts/admin/twinkle.js', array(), VCRBP_VERSION, true );
                
                wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
            }
        }
		function front_lw_scripts(){
			wp_enqueue_script( 'vcrbp.particles', VCRBP_PLUGIN_URL . '/scripts/particles.min.js', array(), VCRBP_VERSION, true );
			wp_enqueue_script( 'vcrbp.lightweight_particle', VCRBP_PLUGIN_URL . '/scripts/wp_lightweight_particle.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_lwd_scripts(){
			wp_enqueue_script( 'vcrbp.particles', VCRBP_PLUGIN_URL . '/scripts/particles.min.js', array(), VCRBP_VERSION, true );
			wp_enqueue_script( 'vcrbp.lightweight_dust_particle', VCRBP_PLUGIN_URL . '/scripts/wp_lightweight_dust_particle.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_lws_scripts(){
			wp_enqueue_script( 'vcrbp.particles', VCRBP_PLUGIN_URL . '/scripts/particles.min.js', array(), VCRBP_VERSION, true );
			wp_enqueue_script( 'vcrbp.lightweight_snow_particle', VCRBP_PLUGIN_URL . '/scripts/wp_lightweight_snow_particle.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_lwst_scripts(){
			wp_enqueue_script( 'vcrbp.particles', VCRBP_PLUGIN_URL . '/scripts/particles.min.js', array(), VCRBP_VERSION, true );
			wp_enqueue_script( 'vcrbp.lightweight_star_particle', VCRBP_PLUGIN_URL . '/scripts/wp_lightweight_star_particle.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_rain_scripts(){
			wp_enqueue_script( 'vcrbp.wp_rain_particle', VCRBP_PLUGIN_URL . '/scripts/wp_rain_particle.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_plasma_scripts(){
			wp_enqueue_script( 'vcrbp.plasma', VCRBP_PLUGIN_URL . '/scripts/plasma.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_organic_lines_scripts(){
			wp_enqueue_script( 'vcrbp.organic_lines', VCRBP_PLUGIN_URL . '/scripts/organic-lines.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_rainbow_curves_scripts(){
			wp_enqueue_script( 'vcrbp.rainbow_curves', VCRBP_PLUGIN_URL . '/scripts/rainbow-curves.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_rainbow_fireflies_scripts(){
			wp_enqueue_script( 'vcrbp.rainbow_fireflies', VCRBP_PLUGIN_URL . '/scripts/rainbow-fireflies.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_rainbow_grid_scripts(){
			wp_enqueue_script( 'vcrbp.rainbow_grid', VCRBP_PLUGIN_URL . '/scripts/rainbow-grid.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_centaurus_scripts(){
			wp_enqueue_script( 'vcrbp.centaurus', VCRBP_PLUGIN_URL . '/scripts/centaurus.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_gradient_particle_scripts(){
			wp_enqueue_script( 'vcrbp.jquery_gradient_particle', VCRBP_PLUGIN_URL . '/scripts/jquery.gradient_particle.min.js', array(), VCRBP_VERSION, true );
			wp_enqueue_script( 'vcrbp.gradient_particle', VCRBP_PLUGIN_URL . '/scripts/wpbgcp.gradient_particle.min.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_twinkle_scripts(){
			wp_enqueue_script( 'vcrbtp.twinkle', VCRBP_PLUGIN_URL . '/scripts/twinkle.min.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbtp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_shocking_particle_scripts(){
			wp_enqueue_script( 'vcrbtp.jquery_shocking_particle', VCRBP_PLUGIN_URL . '/scripts/jquery.shocking_particle.min.js', array(), VCRBP_VERSION, true );
            wp_enqueue_script( 'vcrbtp.shocking_particle', VCRBP_PLUGIN_URL . '/scripts/vcrbscp.shocking_particle.min.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbtp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function front_particleground_scripts(){
            wp_enqueue_script( 'vcrbp.jquery_particleground', VCRBP_PLUGIN_URL . '/scripts/jquery.particleground.min.js', array(), VCRBP_VERSION, true );
            wp_enqueue_script( 'vcrbp.particleground', VCRBP_PLUGIN_URL . '/scripts/wpvcpg.particleground.js', array(), VCRBP_VERSION, true );
            
			wp_enqueue_style( 'vcrbtp.style', VCRBP_PLUGIN_URL . '/vcrbp.css');
		}
		function number_settings_field($settings, $value)
		{
			$dependency = vc_generate_dependencies_attributes($settings);
			$param_name = isset($settings[ 'param_name' ]) ? $settings[ 'param_name' ] : '';
			$type = isset($settings[ 'type' ]) ? $settings[ 'type' ] : '';
			$min = isset($settings[ 'min' ]) ? $settings[ 'min' ] : '';
			$max = isset($settings[ 'max' ]) ? $settings[ 'max' ] : '';
			$suffix = isset($settings[ 'suffix' ]) ? $settings[ 'suffix' ] : '';
			$class = isset($settings[ 'class' ]) ? $settings[ 'class' ] : '';
			$output = '<input type="number" min="' . $min . '" max="' . $max . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" style="max-width:100px; margin-right: 10px;" />' . $suffix;
			return $output;
		}
		function vcrbp_particle_shortcode($output, $atts, $content){
            $vcrbp_active = $vcrbp_bg_color_active = $vcrbp_bg_color = $vcrbp_bg_opacity = $vcrbp_organic_lines_bg_color_active = $vcrbp_organic_lines_bg_color = $vcrbp_organic_lines_bg_opacity = "";

            $vcrbp_lw_number = $vcrbp_lw_active_density = $vcrbp_lw_density = $vcrbp_lw_color = $vcrbp_lw_shape_type = $vcrbp_lw_shape_polygon_nb_sides = $vcrbp_lw_active_size_random = $vcrbp_lw_size_value = $vcrbp_lw_active_opacity_random = $vcrbp_lw_opacity_value = $vcrbp_lw_enable_line_linked = $vcrbp_lw_line_linked_distance = $vcrbp_lw_line_linked_color = $vcrbp_lw_line_linked_opacity = $vcrbp_lw_line_linked_width = $vcrbp_lw_enable_move = $vcrbp_lw_move_direction = $vcrbp_lw_move_out_mode = $vcrbp_lw_move_speed = $vcrbp_lw_enable_attract = $vcrbp_lw_attract_rotate_x = $vcrbp_lw_attract_rotate_y = $vcrbp_lw_interaction_detect_on = $vcrbp_lw_interaction_enable_onhover = $vcrbp_lw_interaction_onhover_mode = $vcrbp_lw_interaction_enable_onclick = $vcrbp_lw_interaction_onclick_mode = $vcrbp_lw_mode_grab_distance = $vcrbp_lw_mode_grab_line_linked_opacity = $vcrbp_lw_mode_bubble_distance = $vcrbp_lw_mode_bubble_size = $vcrbp_lw_mode_bubble_duration = $vcrbp_lw_mode_bubble_opacity = $vcrbp_lw_mode_repulse_distance = $vcrbp_lw_mode_repulse_duration = $vcrbp_lw_mode_push_particles_nb = $vcrbp_lw_mode_remove_particles_nb = "";

            $vcrbp_lwd_number = $vcrbp_lwd_color = $vcrbp_lwd_active_size_random = $vcrbp_lwd_size_value = $vcrbp_lwd_active_opacity_random = $vcrbp_lwd_opacity_value = $vcrbp_lwd_move_direction = $vcrbp_lwd_move_speed = $vcrbp_lwd_interaction_detect_on = $vcrbp_lwd_interaction_enable_onhover = $vcrbp_lwd_interaction_onhover_mode = $vcrbp_lwd_interaction_enable_onclick = $vcrbp_lwd_interaction_onclick_mode = $vcrbp_lwd_mode_bubble_distance = $vcrbp_lwd_mode_bubble_size = $vcrbp_lwd_mode_bubble_duration = $vcrbp_lwd_mode_bubble_opacity = $vcrbp_lwd_mode_repulse_distance = $vcrbp_lwd_mode_repulse_duration = "";

            $vcrbp_lwst_number = $vcrbp_lwst_color = $vcrbp_lwst_active_size_random = $vcrbp_lwst_size_value = $vcrbp_lwst_active_opacity_random = $vcrbp_lwst_opacity_value = $vcrbp_lwst_move_direction = $vcrbp_lwst_move_speed = $vcrbp_lwst_interaction_detect_on = $vcrbp_lwst_interaction_enable_onhover = $vcrbp_lwst_interaction_onhover_mode = $vcrbp_lwst_interaction_enable_onclick = $vcrbp_lwst_interaction_onclick_mode = $vcrbp_lwst_mode_bubble_distance = $vcrbp_lwst_mode_bubble_size = $vcrbp_lwst_mode_bubble_duration = $vcrbp_lwst_mode_bubble_opacity = $vcrbp_lwst_mode_repulse_distance = $vcrbp_lwst_mode_repulse_duration = "";

            $vcrbp_lws_number = $vcrbp_lws_active_size_random = $vcrbp_lws_size_value = $vcrbp_lws_active_opacity_random = $vcrbp_lws_opacity_value = $vcrbp_lws_move_direction = $vcrbp_lws_move_speed = $vcrbp_lws_interaction_detect_on = $vcrbp_lws_interaction_enable_onhover = $vcrbp_lws_interaction_onhover_mode = $vcrbp_lws_interaction_enable_onclick = $vcrbp_lws_interaction_onclick_mode = $vcrbp_lws_mode_bubble_distance = $vcrbp_lws_mode_bubble_size = $vcrbp_lws_mode_bubble_duration = $vcrbp_lws_mode_bubble_opacity = $vcrbp_lws_mode_repulse_distance = $vcrbp_lws_mode_repulse_duration = "";

            $vcrbp_rain = $vcrbp_rain_multicolor = $vcrbp_rain_color = $vcrbp_rain_opacity = "";
            
            $vcrbp_plasma_interaction_active = $vcrbp_plasma_center_point = $vcrbp_plasma_ray = $vcrbp_plasma_radiant_span = "";
            
            $vcrbp_organic_lines_speed = $vcrbp_organic_lines_range = $vcrbp_organic_lines_line_alpha = "";
            
            $vcrbp_rcp_interaction_active = $vcrbp_rcp_center_point = $vcrbp_rcp_jitter = $vcrbp_rcp_line_width = $vcrbp_rcp_rad_apart = $vcrbp_rcp_acc_apart = $vcrbp_rcp_repaint_alpha = "";
            
            $vcrbp_rffp_particle_count = $vcrbp_rffp_particle_speed = $vcrbp_rffp_particle_angular_speed = $vcrbp_rffp_particle_ray_behaviour_prob = $vcrbp_rffp_connection_count = $vcrbp_rffp_connection_life = $vcrbp_rffp_connection_added_life = $vcrbp_rffp_connection_splits = $vcrbp_rffp_connection_jitter = $vcrbp_rffp_connection_span_multiplier = $vcrbp_rffp_particle_circle_behaviour_prob = "";
            
            $vcrbp_rbg_line_max_count = $vcrbp_rbg_line_spawn_prob = $vcrbp_rbg_line_max_length = $vcrbp_rbg_line_increment_prob = $vcrbp_rbg_line_decrement_prob = $vcrbp_rbg_line_safe_time = $vcrbp_rbg_line_mid_jitter = $vcrbp_rbg_line_mid_points = $vcrbp_rbg_line_hue_variation = $vcrbp_rbg_line_alpha = $vcrbp_rbg_side_num = $vcrbp_rbg_side = $vcrbp_rbg_rotation_vel = $vcrbp_rbg_scaling_input_multiplier = $vcrbp_rbg_scaling_multiplier = $vcrbp_rbg_hue_change = $vcrbp_rbg_repaint_alpha = "";

            $vcrbp_centaurus_number = $vcrbp_centaurus_inner_color = $vcrbp_centaurus_inner_opacity = $vcrbp_centaurus_outer_color = $vcrbp_centaurus_outer_opacity = $vcrbp_centaurus_mouse_active = "";
            
            $vcrbp_random_dot_color = $vcrbp_dot_color = $vcrbp_dot_opacity = $vcrbp_random_gradient_color = $vcrbp_gradient_color = $vcrbp_gradient_opacity = $vcrbp_min_speed_x = $vcrbp_max_speed_x = $vcrbp_min_speed_y = $vcrbp_max_speed_y = $vcrbp_direction_x = $vcrbp_direction_y = $vcrbp_density = $vcrbp_particle_radius = $vcrbp_line_width = $vcrbp_proximity = $vcrbp_parallax = $vcrbp_parallax_multiplier = $vcrbp_grab_hover = $vcrbp_grab_distance = "";
            
            $vcrbp_twinkle_random_color = $vcrbp_twinkle_color = $vcrbp_twinkle_opacity = "";
            
            $vcrbp_shocking_min_speed_x = $vcrbp_shocking_max_speed_x = $vcrbp_shocking_min_speed_y = $vcrbp_shocking_max_speed_y = $vcrbp_shocking_direction_x = $vcrbp_shocking_direction_y = $vcrbp_shocking_density = $vcrbp_shocking_dot_opacity = $vcrbp_shocking_dot_color = $vcrbp_shocking_line_color = $vcrbp_shocking_particle_radius = $vcrbp_shocking_line_width = $vcrbp_shocking_proximity = $vcrbp_shocking_parallax = $vcrbp_shocking_parallax_multiplier = $vcrbp_shocking_grab_hover = $vcrbp_shocking_grab_distance = "";
            
            $vcrbp_pg_min_speed_x = $vcrbp_pg_max_speed_x = $vcrbp_pg_min_speed_y = $vcrbp_pg_max_speed_y = $vcrbp_pg_direction_x = $vcrbp_pg_direction_y = $vcrbp_pg_density = $vcrbp_pg_dot_opacity = $vcrbp_pg_dot_color = $vcrbp_pg_line_opacity = $vcrbp_pg_line_color = $vcrbp_pg_particle_radius = $vcrbp_pg_line_width = $vcrbp_pg_proximity = $vcrbp_pg_parallax = $vcrbp_pg_parallax_multiplier = $vcrbp_pg_grab_hover = $vcrbp_pg_grab_distance = "";
            
			extract( shortcode_atts( array(
			    'vcrbp_active' => '',          
			    'vcrbp_bg_color_active' => '',          
			    'vcrbp_bg_color' => '#000000',
			    'vcrbp_bg_opacity' => '1.0',
			    'vcrbp_organic_lines_bg_color_active' => '',          
			    'vcrbp_organic_lines_bg_color' => '#000000',
			    'vcrbp_organic_lines_bg_opacity' => '0.05',        
			    'vcrbp_rffp_bg_color_active' => '',          
			    'vcrbp_rffp_bg_color' => '#000000',          
			    'vcrbp_rffp_bg_opacity' => '0.1',
                
                'vcrbp_lw_number' => '80',                // Lightweight
                'vcrbp_lw_active_density' => '',
                'vcrbp_lw_density' => '800',
                'vcrbp_lw_color' => '#ffffff',
                'vcrbp_lw_shape_type' => 'circle',
                'vcrbp_lw_shape_polygon_nb_sides' => '5',
                
                'vcrbp_lw_active_size_random' => '',
                'vcrbp_lw_size_value' => '3',
                
                'vcrbp_lw_active_opacity_random' => '',
                'vcrbp_lw_opacity_value' => '0.5',
                
                'vcrbp_lw_enable_line_linked' => '',
                'vcrbp_lw_line_linked_distance' => '150',
                'vcrbp_lw_line_linked_color' => '#ffffff',
                'vcrbp_lw_line_linked_opacity' => '0.4',
                'vcrbp_lw_line_linked_width' => '1',
                
                'vcrbp_lw_enable_move' => '',
                'vcrbp_lw_move_direction' => 'none',
                'vcrbp_lw_move_out_mode' => 'out',
                'vcrbp_lw_move_speed' => '6',
                
                'vcrbp_lw_enable_attract' => '',
                'vcrbp_lw_attract_rotate_x' => '600',
                'vcrbp_lw_attract_rotate_y' => '1200',
                
                'vcrbp_lw_interaction_detect_on' => 'canvas',
                'vcrbp_lw_interaction_enable_onhover' => '',
                'vcrbp_lw_interaction_onhover_mode' => 'grab',
                'vcrbp_lw_interaction_enable_onclick' => '',
                'vcrbp_lw_interaction_onclick_mode' => 'push',
                
                'vcrbp_lw_mode_grab_distance' => '400',
                'vcrbp_lw_mode_grab_line_linked_opacity' => '1',
                
                'vcrbp_lw_mode_bubble_distance' => '400',
                'vcrbp_lw_mode_bubble_size' => '40',
                'vcrbp_lw_mode_bubble_duration' => '2',
                'vcrbp_lw_mode_bubble_opacity' => '8',
                
                'vcrbp_lw_mode_repulse_distance' => '200',
                'vcrbp_lw_mode_repulse_duration' => '1.2',
            
                'vcrbp_lw_mode_push_particles_nb' => '4',
                'vcrbp_lw_mode_remove_particles_nb' => '4',

                
                'vcrbp_lwd_number' => '1000',                   // Lightweight Dust
                'vcrbp_lwd_color' => '#ffffff',
                
                'vcrbp_lwd_active_size_random' => '',
                'vcrbp_lwd_size_value' => '3',
                
                'vcrbp_lwd_active_opacity_random' => '',
                'vcrbp_lwd_opacity_value' => '0.5',
                
                'vcrbp_lwd_move_direction' => 'top',
                'vcrbp_lwd_move_speed' => '1',
                
                'vcrbp_lwd_interaction_detect_on' => 'window',
                'vcrbp_lwd_interaction_enable_onhover' => '',
                'vcrbp_lwd_interaction_onhover_mode' => 'bubble',
                'vcrbp_lwd_interaction_enable_onclick' => '',
                'vcrbp_lwd_interaction_onclick_mode' => 'repulse',
            
                'vcrbp_lwd_mode_bubble_distance' => '150',
                'vcrbp_lwd_mode_bubble_size' => '0',
                'vcrbp_lwd_mode_bubble_duration' => '2',
                'vcrbp_lwd_mode_bubble_opacity' => '0',
            
                'vcrbp_lwd_mode_repulse_distance' => '200',
                'vcrbp_lwd_mode_repulse_duration' => '0.4',
                
                'vcrbp_lwst_number' => '100',                   // Lightweight Star
                'vcrbp_lwst_color' => '#ffffff',
                
                'vcrbp_lwst_active_size_random' => '',
                'vcrbp_lwst_size_value' => '3',
                
                'vcrbp_lwst_active_opacity_random' => '',
                'vcrbp_lwst_opacity_value' => '0.5',
                
                'vcrbp_lwst_move_direction' => 'left',
                'vcrbp_lwst_move_speed' => '5',
                
                'vcrbp_lwst_interaction_detect_on' => 'canvas',
                'vcrbp_lwst_interaction_enable_onhover' => '',
                'vcrbp_lwst_interaction_onhover_mode' => 'bubble',
                'vcrbp_lwst_interaction_enable_onclick' => '',
                'vcrbp_lwst_interaction_onclick_mode' => 'repulse',
            
                'vcrbp_lwst_mode_bubble_distance' => '400',
                'vcrbp_lwst_mode_bubble_size' => '3',
                'vcrbp_lwst_mode_bubble_duration' => '2',
                'vcrbp_lwst_mode_bubble_opacity' => '1.5',
            
                'vcrbp_lwst_mode_repulse_distance' => '200',
                'vcrbp_lwst_mode_repulse_duration' => '0.4',
                
                'vcrbp_lws_number' => '400',                    // Lightweight Snow
                
                'vcrbp_lws_active_size_random' => '',
                'vcrbp_lws_size_value' => '10',
                
                'vcrbp_lws_active_opacity_random' => '',
                'vcrbp_lws_opacity_value' => '0.5',
                
                'vcrbp_lws_move_direction' => 'bottom',
                'vcrbp_lws_move_speed' => '3',
                
                'vcrbp_lws_interaction_detect_on' => 'canvas',
                'vcrbp_lws_interaction_enable_onhover' => '',
                'vcrbp_lws_interaction_onhover_mode' => 'bubble',
                'vcrbp_lws_interaction_enable_onclick' => '',
                'vcrbp_lws_interaction_onclick_mode' => 'repulse',
            
                'vcrbp_lws_mode_bubble_distance' => '400',
                'vcrbp_lws_mode_bubble_size' => '3',
                'vcrbp_lws_mode_bubble_duration' => '0.3',
                'vcrbp_lws_mode_bubble_opacity' => '1.0',
            
                'vcrbp_lws_mode_repulse_distance' => '200',
                'vcrbp_lws_mode_repulse_duration' => '0.4',
                
                'vcrbp_rain' => '2',	                        // Rain
                'vcrbp_rain_multicolor' => '',
                'vcrbp_rain_color' => '#0000ff',
                'vcrbp_rain_opacity' => '1.0',
                
                'vcrbp_plasma_interaction_active' => '',        // Plasma
                'vcrbp_plasma_center_point' => '5',
                'vcrbp_plasma_ray' => '30',
                'vcrbp_plasma_radiant_span' => '0.4',
                
                'vcrbp_organic_lines_speed' => '4',             // Organic Lines
                'vcrbp_organic_lines_range' => '80',
                'vcrbp_organic_lines_line_alpha' => '0.4',

                'vcrbp_rcp_interaction_active' => '',           // Rainbow Curves
                'vcrbp_rcp_center_point' => '5',
                'vcrbp_rcp_jitter' => '20',
                'vcrbp_rcp_line_width' => '0.1',
                'vcrbp_rcp_rad_apart' => '0.5',
                'vcrbp_rcp_acc_apart' => '0.001',
                'vcrbp_rcp_repaint_alpha' => '0.1',
                
                'vcrbp_rffp_particle_count' => '40',            // Rainbow Fireflies Particle
                'vcrbp_rffp_particle_speed' => '-2',
                'vcrbp_rffp_particle_angular_speed' => '0.03',
                'vcrbp_rffp_particle_ray_behaviour_prob' => '0.05',
                'vcrbp_rffp_particle_circle_behaviour_prob' => '0.1',
                
                'vcrbp_rffp_connection_count' => '10',
                'vcrbp_rffp_connection_life' => '10',
                'vcrbp_rffp_connection_added_life' => '10',
                'vcrbp_rffp_connection_splits' => '3',
                'vcrbp_rffp_connection_jitter' => '5',
                'vcrbp_rffp_connection_span_multiplier' => '0.2',
                
                'vcrbp_rbg_line_max_count' => '40',             // Rainbow Grid Particle
                'vcrbp_rbg_line_spawn_prob' => '0.1',
                'vcrbp_rbg_line_max_length' => '10',
                'vcrbp_rbg_line_increment_prob' => '0.5',
                'vcrbp_rbg_line_decrement_prob' => '0.7',
                'vcrbp_rbg_line_safe_time' => '150',
                'vcrbp_rbg_line_mid_jitter' => '7',
                'vcrbp_rbg_line_mid_points' => '3',
                'vcrbp_rbg_line_hue_variation' => '30',
                'vcrbp_rbg_line_alpha' => '1',

                'vcrbp_rbg_side_num' => '6',
                'vcrbp_rbg_side' => '30',
                'vcrbp_rbg_rotation_vel' => '0.002',
                'vcrbp_rbg_scaling_input_multiplier' => '0.01',
                'vcrbp_rbg_scaling_multiplier' => '0.3',
                'vcrbp_rbg_hue_change' => '0.6',
                'vcrbp_rbg_repaint_alpha' => '0.1',
                
                'vcrbp_centaurus_number' => '8000',             // Centaurus
                'vcrbp_centaurus_inner_color' => '#ff0000',
                'vcrbp_centaurus_inner_opacity' => '1.0',
                'vcrbp_centaurus_outer_color' => '#ff0000',
                'vcrbp_centaurus_outer_opacity' => '0.1',
                
                'vcrbp_centaurus_mouse_active' => '',
                                
                'vcrbp_random_dot_color' => '',	                // Gradient
                'vcrbp_dot_color' => '#ff0000',
                'vcrbp_dot_opacity' => '1.0',
                'vcrbp_random_gradient_color' => '',
                'vcrbp_gradient_color' => '#0000ff',
                'vcrbp_gradient_opacity' => '1.0',
                'vcrbp_min_speed_x' => '1',          
                'vcrbp_max_speed_x' => '7',          
                'vcrbp_min_speed_y' => '1',          
                'vcrbp_max_speed_y' => '7',          
                'vcrbp_direction_x' => 'center',
                'vcrbp_direction_y' => 'center',
                'vcrbp_density' => '10000',
                'vcrbp_particle_radius' => '7',
                'vcrbp_line_width' => '1',
                'vcrbp_proximity' => '100',
                'vcrbp_parallax' => '',
                'vcrbp_parallax_multiplier' => '5',
                'vcrbp_grab_hover' => '',
                'vcrbp_grab_distance' => '100',
                                
                'vcrbp_twinkle_random_color' => '',             // Twinkle
                'vcrbp_twinkle_color' => '#ff0000',
                'vcrbp_twinkle_opacity' => '1.0',
                
                'vcrbp_shocking_min_speed_x' => '1',            // Shocking      
                'vcrbp_shocking_max_speed_x' => '7',          
                'vcrbp_shocking_min_speed_y' => '1',          
                'vcrbp_shocking_max_speed_y' => '7',          
                'vcrbp_shocking_direction_x' => 'center',
                'vcrbp_shocking_direction_y' => 'center',
                'vcrbp_shocking_density' => '10000',
                'vcrbp_shocking_dot_opacity' => '1.0',
                'vcrbp_shocking_dot_color' => '#666666',
                'vcrbp_shocking_line_color' => '#666666',
                'vcrbp_shocking_particle_radius' => '7',
                'vcrbp_shocking_line_width' => '1',
                'vcrbp_shocking_proximity' => '100',
                'vcrbp_shocking_parallax' => '',
                'vcrbp_shocking_parallax_multiplier' => '5',
                'vcrbp_shocking_grab_hover' => 'true',
                'vcrbp_shocking_grab_distance' => '100',
                
			    'vcrbp_pg_min_speed_x' => '1',                    // ParticleGround 
                'vcrbp_pg_max_speed_x' => '7',          
                'vcrbp_pg_min_speed_y' => '1',          
                'vcrbp_pg_max_speed_y' => '7',          
                'vcrbp_pg_direction_x' => 'center',
                'vcrbp_pg_direction_y' => 'center',
                'vcrbp_pg_density' => '10000',
                'vcrbp_pg_dot_opacity' => '1.0',
                'vcrbp_pg_dot_color' => '#666666',
                'vcrbp_pg_line_opacity' => '1.0',
                'vcrbp_pg_line_color' => '#666666',
                'vcrbp_pg_particle_radius' => '7',
                'vcrbp_pg_line_width' => '1',
                'vcrbp_pg_proximity' => '100',
                'vcrbp_pg_parallax' => '',
                'vcrbp_pg_parallax_multiplier' => '5',
                'vcrbp_pg_grab_hover' => 'true',
                'vcrbp_pg_grab_distance' => '100',
			), $atts ) );			
			
            if ($vcrbp_active == "lw_particle") {                
                $this->front_lw_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="wpvclwp_vc_row" style="' . $style . '" data-number="' . $vcrbp_lw_number . '" data-activeDensity="' . $vcrbp_lw_active_density . '" data-density="' . $vcrbp_lw_density . '" data-color="' . $vcrbp_lw_color . '" data-shapeType="' . $vcrbp_lw_shape_type . '" data-polygonSides="' . $vcrbp_lw_shape_polygon_nb_sides . '" data-sizeRandom="' . $vcrbp_lw_active_size_random . '" data-sizeValue="' . $vcrbp_lw_size_value . '" data-opacityRandom="' . $vcrbp_lw_active_opacity_random . '" data-opacityValue="' . $vcrbp_lw_opacity_value . '" data-enableLineLinked="' . $vcrbp_lw_enable_line_linked . '" data-lineLinkedDistance="' . $vcrbp_lw_line_linked_distance . '" data-lineLinkedColor="' . $vcrbp_lw_line_linked_color . '" data-lineLinkedOpacity="' . $vcrbp_lw_line_linked_opacity . '" data-lineLinkedWidth="' . $vcrbp_lw_line_linked_width . '" data-enableMove="' . $vcrbp_lw_enable_move . '" data-moveDirection="' . $vcrbp_lw_move_direction . '" data-moveoutmode="' . $vcrbp_lw_move_out_mode . '" data-moveSpeed="' . $vcrbp_lw_move_speed . '" data-enableAttract="' . $vcrbp_lw_enable_attract . '" data-attractRotateX="' . $vcrbp_lw_attract_rotate_x . '" data-attractRotateY="' . $vcrbp_lw_attract_rotate_y . '" data-interactionDetectOn="' . $vcrbp_lw_interaction_detect_on . '" data-interactionEnableOnhover="' . $vcrbp_lw_interaction_enable_onhover . '" data-interactionOnhoverMode="' . $vcrbp_lw_interaction_onhover_mode . '" data-interactionEnableOnclick="' . $vcrbp_lw_interaction_enable_onclick . '" data-interactionOnclickMode="' . $vcrbp_lw_interaction_onclick_mode . '" data-modeGrabDistance="' . $vcrbp_lw_mode_grab_distance . '" data-modeGrabLineLinkedOpacity="' . $vcrbp_lw_mode_grab_line_linked_opacity . '" data-modeBubbleDistance="' . $vcrbp_lw_mode_bubble_distance . '" data-modeBubbleSize="' . $vcrbp_lw_mode_bubble_size . '" data-modeBubbleDuration="' . $vcrbp_lw_mode_bubble_duration . '" data-modeBubbleOpacity="' . $vcrbp_lw_mode_bubble_opacity . '" data-modeRepulseDistance="' . $vcrbp_lw_mode_repulse_distance . '" data-modeRepulseDuration="' . $vcrbp_lw_mode_repulse_duration . '" data-modePushParticles="' . $vcrbp_lw_mode_push_particles_nb . '" data-modeRemoveParticles="' . $vcrbp_lw_mode_remove_particles_nb . '"></div>';
            }
			else if ($vcrbp_active == "lwd_particle") {
                $this->front_lwd_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="wpvclwdp_vc_row" style="' . $style . '" data-number="' . $vcrbp_lwd_number . '" data-color="' . $vcrbp_lwd_color . '" data-sizeRandom="' . $vcrbp_lwd_active_size_random . '" data-sizeValue="' . $vcrbp_lwd_size_value . '" data-opacityRandom="' . $vcrbp_lwd_active_opacity_random . '" data-opacityValue="' . $vcrbp_lwd_opacity_value . '" data-moveDirection="' . $vcrbp_lwd_move_direction . '" data-moveSpeed="' . $vcrbp_lwd_move_speed . '" data-interactionDetectOn="' . $vcrbp_lwd_interaction_detect_on . '" data-interactionEnableOnhover="' . $vcrbp_lwd_interaction_enable_onhover . '" data-interactionOnhoverMode="' . $vcrbp_lwd_interaction_onhover_mode . '" data-interactionEnableOnclick="' . $vcrbp_lwd_interaction_enable_onclick . '" data-interactionOnclickMode="' . $vcrbp_lwd_interaction_onclick_mode . '" data-modeBubbleDistance="' . $vcrbp_lwd_mode_bubble_distance . '" data-modeBubbleSize="' . $vcrbp_lwd_mode_bubble_size . '" data-modeBubbleDuration="' . $vcrbp_lwd_mode_bubble_duration . '" data-modeBubbleOpacity="' . $vcrbp_lwd_mode_bubble_opacity . '" data-modeRepulseDistance="' . $vcrbp_lwd_mode_repulse_distance . '" data-modeRepulseDuration="' . $vcrbp_lwd_mode_repulse_duration . '"></div>';
            }
            else if ($vcrbp_active == "lwst_particle") {
                $this->front_lwst_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="wpvclwstp_vc_row" style="' . $style . '" data-number="' . $vcrbp_lwst_number . '" data-color="' . $vcrbp_lwst_color . '" data-sizeRandom="' . $vcrbp_lwst_active_size_random . '" data-sizeValue="' . $vcrbp_lwst_size_value . '" data-opacityRandom="' . $vcrbp_lwst_active_opacity_random . '" data-opacityValue="' . $vcrbp_lwst_opacity_value . '" data-moveDirection="' . $vcrbp_lwst_move_direction . '" data-moveSpeed="' . $vcrbp_lwst_move_speed . '" data-interactionDetectOn="' . $vcrbp_lwst_interaction_detect_on . '" data-interactionEnableOnhover="' . $vcrbp_lwst_interaction_enable_onhover . '" data-interactionOnhoverMode="' . $vcrbp_lwst_interaction_onhover_mode . '" data-interactionEnableOnclick="' . $vcrbp_lwst_interaction_enable_onclick . '" data-interactionOnclickMode="' . $vcrbp_lwst_interaction_onclick_mode . '" data-modeBubbleDistance="' . $vcrbp_lwst_mode_bubble_distance . '" data-modeBubbleSize="' . $vcrbp_lwst_mode_bubble_size . '" data-modeBubbleDuration="' . $vcrbp_lwst_mode_bubble_duration . '" data-modeBubbleOpacity="' . $vcrbp_lwst_mode_bubble_opacity . '" data-modeRepulseDistance="' . $vcrbp_lwst_mode_repulse_distance . '" data-modeRepulseDuration="' . $vcrbp_lwst_mode_repulse_duration . '"></div>';
            }
            else if ($vcrbp_active == "lws_particle") {
                $this->front_lws_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="wpvclwsp_vc_row" style="' . $style . '" data-number="' . $vcrbp_lws_number . '" data-imageSrc="' . VCRBP_PLUGIN_URL . '/img/snow.png' . '" data-sizeRandom="' . $vcrbp_lws_active_size_random . '" data-sizeValue="' . $vcrbp_lws_size_value . '" data-opacityRandom="' . $vcrbp_lws_active_opacity_random . '" data-opacityValue="' . $vcrbp_lws_opacity_value . '" data-moveDirection="' . $vcrbp_lws_move_direction . '" data-moveSpeed="' . $vcrbp_lws_move_speed . '" data-interactionDetectOn="' . $vcrbp_lws_interaction_detect_on . '" data-interactionEnableOnhover="' . $vcrbp_lws_interaction_enable_onhover . '" data-interactionOnhoverMode="' . $vcrbp_lws_interaction_onhover_mode . '" data-interactionEnableOnclick="' . $vcrbp_lws_interaction_enable_onclick . '" data-interactionOnclickMode="' . $vcrbp_lws_interaction_onclick_mode . '" data-modeBubbleDistance="' . $vcrbp_lws_mode_bubble_distance . '" data-modeBubbleSize="' . $vcrbp_lws_mode_bubble_size . '" data-modeBubbleDuration="' . $vcrbp_lws_mode_bubble_duration . '" data-modeBubbleOpacity="' . $vcrbp_lws_mode_bubble_opacity . '" data-modeRepulseDistance="' . $vcrbp_lws_mode_repulse_distance . '" data-modeRepulseDuration="' . $vcrbp_lws_mode_repulse_duration . '"></div>';
            }
            else if ($vcrbp_active == "rain_particle") {
                $this->front_rain_scripts();
                
                return '<div id="wpvcrp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-rain="' . $vcrbp_rain . '" data-multi="' . $vcrbp_rain_multicolor . '" data-rainColor="' . vcrbp_hex2rgb( $vcrbp_rain_color, $vcrbp_rain_opacity ) . '"></div>';
            }
            else if ($vcrbp_active == "plasma_particle") {
                $this->front_plasma_scripts();
                
                return '<div id="wpvcpp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-centerPoint="' . $vcrbp_plasma_center_point . '" data-ray="' . $vcrbp_plasma_ray . '" data-radiantSpan="' . $vcrbp_plasma_radiant_span . '" data-interactionActive="' . $vcrbp_plasma_interaction_active . '"></div>';
            }
            else if ($vcrbp_active == "organic_lines_particle") {
                $this->front_organic_lines_scripts();
                
                return '<div id="wpvcolp_vc_row" data-bgActive="' . $vcrbp_organic_lines_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_organic_lines_bg_color, $vcrbp_organic_lines_bg_opacity ) . '" data-speed="' . $vcrbp_organic_lines_speed . '" data-range="' . $vcrbp_organic_lines_range . '" data-lineAlpha="' . $vcrbp_organic_lines_line_alpha . '"></div>';
            }
            else if ($vcrbp_active == "rainbow_curves_particle") {
                $this->front_rainbow_curves_scripts();
                
                return '<div id="wpvcrcp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-centerPoint="' . $vcrbp_rcp_center_point . '" data-jitter="' . $vcrbp_rcp_jitter . '" data-lineWidth="' . $vcrbp_rcp_line_width . '" data-radApart="' . $vcrbp_rcp_rad_apart . '" data-accApart="' . $vcrbp_rcp_acc_apart . '" data-RepaintAlpha="' . $vcrbp_rcp_repaint_alpha . '" data-interactionActive="' . $vcrbp_rcp_interaction_active . '"></div>';
            }
            else if ($vcrbp_active == "fireflies_particle") {
                $this->front_rainbow_fireflies_scripts();
                
                return '<div id="wpvcrffp_vc_row" data-bgActive="' . $vcrbp_rffp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_rffp_bg_color, $vcrbp_rffp_bg_opacity ) . '" data-particleCount="' . $vcrbp_rffp_particle_count . '" data-particleSpeed="' . $vcrbp_rffp_particle_speed . '" data-particleAngularSpeed="' . $vcrbp_rffp_particle_angular_speed . '" data-particleRayBehaviourProb="' . $vcrbp_rffp_particle_ray_behaviour_prob . '" data-connectionCount="' . $vcrbp_rffp_connection_count . '" data-connectionLife="' . $vcrbp_rffp_connection_life . '" data-lineMidPoints="' . $vcrbp_rffp_connection_added_life . '" data-connectionSplits="' . $vcrbp_rffp_connection_splits . '" data-connectionJitter="' . $vcrbp_rffp_connection_jitter . '" data-connectionSpanMultiplier="' . $vcrbp_rffp_connection_span_multiplier . '" data-particleCircleBehaviourProb="' . $vcrbp_rffp_particle_circle_behaviour_prob . '"></div>';
            }
            else if ($vcrbp_active == "grid_particle") {
                $this->front_rainbow_grid_scripts();
                
                return '<div id="wpvcrgp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-lineMaxCount="' . $vcrbp_rbg_line_max_count . '" data-lineSpawnProb="' . $vcrbp_rbg_line_spawn_prob . '" data-lineMaxLength="' . $vcrbp_rbg_line_max_length . '" data-lineIncrementProb="' . $vcrbp_rbg_line_increment_prob . '" data-lineDecrementProb="' . $vcrbp_rbg_line_decrement_prob . '" data-lineSafeTime="' . $vcrbp_rbg_line_safe_time . '" data-lineMidJitter="' . $vcrbp_rbg_line_mid_jitter . '" data-lineMidPoints="' . $vcrbp_rbg_line_mid_points . '" data-lineHueVariation="' . $vcrbp_rbg_line_hue_variation . '" data-lineAlpha="' . $vcrbp_rbg_line_alpha . '" data-gridSideNum="' . $vcrbp_rbg_side_num . '" data-gridSide="' . $vcrbp_rbg_side . '" data-gridRotationVel="' . $vcrbp_rbg_rotation_vel . '" data-gridScalingInputMultiplier="' . $vcrbp_rbg_scaling_input_multiplier . '" data-gridScalingMultiplier="' . $vcrbp_rbg_scaling_multiplier . '" data-gridHueChange="' . $vcrbp_rbg_hue_change . '" data-gridRepaintAlpha="' . $vcrbp_rbg_repaint_alpha . '"></div>';
            }
            else if ($vcrbp_active == "centaurus_particle") {
                $this->front_centaurus_scripts();
                
                return '<div id="wpvccp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-Number="' . $vcrbp_centaurus_number . '" data-innerColor="' . vcrbp_hex2rgb( $vcrbp_centaurus_inner_color, $vcrbp_centaurus_inner_opacity ) . '" data-outerColor="' . vcrbp_hex2rgb( $vcrbp_centaurus_outer_color, $vcrbp_centaurus_outer_opacity ) . '" data-MouseActive="' . $vcrbp_centaurus_mouse_active . '"></div>';
            }
            else if ($vcrbp_active == "gradient_particle") {
                $this->front_gradient_particle_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="vcrbgcp_vc_row" style="' . $style . '" data-randomDotColor="' . $vcrbp_random_dot_color . '" data-dotColor="' . $vcrbp_dot_color . '" data-dotOpacity="' . $vcrbp_dot_opacity . '" data-randomGradientColor="' . $vcrbp_random_gradient_color . '" data-gradientColor="' . $vcrbp_gradient_color . '" data-gradientOpacity="' . $vcrbp_gradient_opacity . '" data-minSpeedX="' . $vcrbp_min_speed_x . '" data-maxSpeedX="' . $vcrbp_max_speed_x . '" data-minSpeedY="' . $vcrbp_min_speed_y . '" data-maxSpeedY="' . $vcrbp_max_speed_y . '" data-directionX="' . $vcrbp_direction_x . '" data-directionY="' . $vcrbp_direction_y . '" data-density="' . $vcrbp_density . '" data-particleRadius="' . $vcrbp_particle_radius . '" data-lineWidth="' . $vcrbp_line_width . '" data-proximity="' . $vcrbp_proximity . '" data-parallax="' . ($vcrbp_parallax == "vcrbp_parallax_value" ? "true" : "false") . '" data-parallaxMultiplier="' . $vcrbp_parallax_multiplier . '" data-grabHover="' . ($vcrbp_grab_hover == "vcrbp_grab_hover_value" ? "true" : "false") . '" data-grabDistance="' . $vcrbp_grab_distance . '"></div>';
            }
            else if ($vcrbp_active == "twinkle_particle") {
                $this->front_twinkle_scripts();
                
                return '<div id="vcrbtp_vc_row" data-bgActive="' . $vcrbp_bg_color_active . '" data-bgColor="' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '" data-twinkleRandomColor="' . $vcrbp_twinkle_random_color . '" data-twinkleColor="' . vcrbp_hex2rgb( $vcrbp_twinkle_color, $vcrbp_twinkle_opacity ) . '"></div>';
            }
            else if ($vcrbp_active == "shocking_particle") {
                $this->front_shocking_particle_scripts();

                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="vcrbscp_vc_row" style="' . $style . '" data-minSpeedX="' . $vcrbp_shocking_min_speed_x . '" data-maxSpeedX="' . $vcrbp_shocking_max_speed_x . '" data-minSpeedY="' . $vcrbp_shocking_min_speed_y . '" data-maxSpeedY="' . $vcrbp_shocking_max_speed_y . '" data-directionX="' . $vcrbp_shocking_direction_x . '" data-directionY="' . $vcrbp_shocking_direction_y . '" data-density="' . $vcrbp_shocking_density . '" data-dotColor="' . vcrbp_hex2rgb( $vcrbp_shocking_dot_color, $vcrbp_shocking_dot_opacity ) . '" data-lineColor="' . $vcrbp_shocking_line_color . '" data-particleRadius="' . $vcrbp_shocking_particle_radius . '" data-lineWidth="' . $vcrbp_shocking_line_width . '" data-proximity="' . $vcrbp_shocking_proximity . '" data-parallax="' . ($vcrbp_shocking_parallax == "vcrbp_shocking_parallax_value" ? "true" : "false") . '" data-parallaxMultiplier="' . $vcrbp_shocking_parallax_multiplier . '" data-grabHover="' . ($vcrbp_shocking_grab_hover == "vcrbp_shocking_grab_hover_value" ? "true" : "false") . '" data-grabDistance="' . $vcrbp_shocking_grab_distance . '"></div>';
            }
            else if ($vcrbp_active == "particle_ground") {
                $this->front_particleground_scripts();
                
                $style = "position: absolute; left=0; top=0; width: 100%; height: 100%;";
                
                if ( $vcrbp_bg_color_active ) {
                    $style .= 'background: ' . vcrbp_hex2rgb( $vcrbp_bg_color, $vcrbp_bg_opacity ) . '';
                }
                
                return '<div id="wpvcpg_vc_row" style="' . $style . '" data-minSpeedX="' . $vcrbp_pg_min_speed_x . '" data-maxSpeedX="' . $vcrbp_pg_max_speed_x . '" data-minSpeedY="' . $vcrbp_pg_min_speed_y . '" data-maxSpeedY="' . $vcrbp_pg_max_speed_y . '" data-directionX="' . $vcrbp_pg_direction_x . '" data-directionY="' . $vcrbp_pg_direction_y . '" data-density="' . $vcrbp_pg_density . '" data-dotColor="' . vcrbp_hex2rgb( $vcrbp_pg_dot_color, $vcrbp_pg_dot_opacity ) . '" data-lineColor="' . vcrbp_hex2rgb( $vcrbp_pg_line_color, $vcrbp_pg_line_opacity ) . '" data-particleRadius="' . $vcrbp_pg_particle_radius . '" data-lineWidth="' . $vcrbp_pg_line_width . '" data-proximity="' . $vcrbp_pg_proximity . '" data-parallax="' . ($vcrbp_pg_parallax == "vcrbp_pg_parallax_value" ? "true" : "false") . '" data-parallaxMultiplier="' . $vcrbp_pg_parallax_multiplier . '" data-grabHover="' . ($vcrbp_pg_grab_hover == "vcrbp_pg_grab_hover_value" ? "true" : "false") . '" data-grabDistance="' . $vcrbp_pg_grab_distance . '"></div>';
            }
            
            return false;
		}
		function vcrbp_particle_init(){
			$group_vcrbp_particle = 'Background Particles';
            
			if( function_exists( 'vc_remove_param' ) ){
				vc_remove_param( 'vc_row', 'bg_image_repeat' );
			}
            
			if( function_exists( 'vc_add_param' ) ){
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Background Style", "vcrbp_particle"),
						"param_name" => "vcrbp_active",
						"value" => array(
							__("None" , "vcrbp_particle") => "no_pg",
							__("Lightweight Particle" , "vcrbp_particle") => "lw_particle",
							__("Lightweight Dust Particle" , "vcrbp_particle") => "lwd_particle",
							__("Lightweight Star Particle" , "vcrbp_particle") => "lwst_particle",
							__("Lightweight Snow Particle" , "vcrbp_particle") => "lws_particle",
							__("ParticleGround" , "vcrbp_particle") => "particle_ground",
							__("Plasma Particle" , "vcrbp_particle") => "plasma_particle",
							__("Organic Lines Particle" , "vcrbp_particle") => "organic_lines_particle",
							__("Rainbow Curves Particle" , "vcrbp_particle") => "rainbow_curves_particle",
							__("Rainbow Fireflies Particle" , "vcrbp_particle") => "fireflies_particle",
							__("Rainbow Grid Particle" , "vcrbp_particle") => "grid_particle",
							__("Centaurus" , "vcrbp_particle") => "centaurus_particle",
							__("Gradient Connection" , "vcrbp_particle") => "gradient_particle",
							__("Shocking Connection" , "vcrbp_particle") => "shocking_particle",
							__("Twinkle" , "vcrbp_particle") => "twinkle_particle",
							__("Rain Particle (Deprecated)" , "vcrbp_particle") => "rain_particle",
							),
						"description" => __("Select the Background Particles to active particle background for this row", "vcrbp_particle"),
						"group" => $group_vcrbp_particle,
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Background Color", "vcrbp_particle"),
						"param_name" => "vcrbp_bg_color_active",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle", "lwd_particle", "lwst_particle", "lws_particle", "rain_particle", "plasma_particle", "rainbow_curves_particle", "grid_particle", "centaurus_particle", "gradient_particle", "twinkle_particle", "shocking_particle", "particle_ground", ) ),
						"description" => __("")
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Background Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_bg_color",
						"value" => "#000000",
                        "dependency" => array( "element" => "vcrbp_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Background Opacity", "vcrbp_particle"),						
						"param_name" => "vcrbp_bg_opacity",
						"value" => "1.0",
                        "dependency" => array( "element" => "vcrbp_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle")
					)
				);	
                
                // Background for Organic
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Background Color", "vcrbp_particle"),
						"param_name" => "vcrbp_organic_lines_bg_color_active",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "organic_lines_particle",) ),
						"description" => __("")
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Background Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_organic_lines_bg_color",
						"value" => "#000000",
                        "dependency" => array( "element" => "vcrbp_organic_lines_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Background Opacity", "vcrbp_particle"),						
						"param_name" => "vcrbp_organic_lines_bg_opacity",
						"value" => "0.05",
                        "dependency" => array( "element" => "vcrbp_organic_lines_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle")
					)
				);
                
                // Background for Fireflies Particle
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Background Color", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_bg_color_active",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle",) ),
						"description" => __("")
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Background Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_bg_color",
						"value" => "#000000",
                        "dependency" => array( "element" => "vcrbp_rffp_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Background Opacity", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_bg_opacity",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_rffp_bg_color_active" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle")
					)
				);
                
                // Lightweight
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Number", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_number",
						"value" => "80",
                        "min" => "0",
                        "max" => "600",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active density", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_active_density",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Density Area", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_density",
						"value" => "800",
                        "min" => "0",
                        "max" => "10000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_active_density" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Particle Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_lw_color",
						"value" => "#ffffff",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Shape Type", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_shape_type",
						"value" => array(
							__("Circle" , "vcrbp_particle") => "circle",
							__("Edge" , "vcrbp_particle") => "edge",
							__("Triangle" , "vcrbp_particle") => "triangle",
							__("Polygon" , "vcrbp_particle") => "polygon",
							__("Star" , "vcrbp_particle") => "star",
							),
						"description" => __("Select the Shape Type for this row", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Polygon Number Sides", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_shape_polygon_nb_sides",
						"value" => "5",
                        "min" => "3",
                        "max" => "12",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_active_size_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Size Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_size_value",
						"value" => "3",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_active_opacity_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Opacity Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_opacity_value",
						"value" => "0.5",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Line Linked
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Line Linked", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_enable_line_linked",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Linked Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_line_linked_distance",
						"value" => "150",
                        "min" => "0",
                        "max" => "2000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_line_linked" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Line Linked Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_lw_line_linked_color",
						"value" => "#ffffff",
                        "dependency" => array( "element" => "vcrbp_lw_enable_line_linked" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Linked Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_line_linked_opacity",
						"value" => "0.4",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_line_linked" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Linked Width", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_line_linked_width",
						"value" => "1",
                        "min" => "0",
                        "max" => "20",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_line_linked" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Move
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Move", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_enable_move",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Move Direction", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_move_direction",
						"value" => array(
							__("None" , "vcrbp_particle") => "none",
							__("Top" , "vcrbp_particle") => "top",
							__("Top-Right" , "vcrbp_particle") => "top-right",
							__("Right" , "vcrbp_particle") => "right",
							__("Bottom-Right" , "vcrbp_particle") => "bottom-right",
							__("Bottom" , "vcrbp_particle") => "bottom",
							__("Bottom-Left" , "vcrbp_particle") => "bottom-left",
							__("Left" , "vcrbp_particle") => "left",
							__("Top-Left" , "vcrbp_particle") => "top-left",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_move" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Move Out Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_move_out_mode",
						"value" => array(
							__("Out" , "vcrbp_particle") => "out",
							__("Bounce" , "vcrbp_particle") => "bounce",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_move" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);	
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Move Speed", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_move_speed",
						"value" => "6",
                        "min" => "0.0",
                        "max" => "200.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_move" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Attract
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Attract", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_enable_attract",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Attract Rotate X", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_attract_rotate_x",
						"value" => "600",
                        "min" => "0",
                        "max" => "10000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_attract" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Attract Rotate Y", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_attract_rotate_y",
						"value" => "1200",
                        "min" => "0",
                        "max" => "10000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_enable_attract" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Interaction
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Detect On", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_interaction_detect_on",
						"value" => array(
							__("Canvas" , "vcrbp_particle") => "canvas",
							__("Window" , "vcrbp_particle") => "window",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onhover", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_interaction_enable_onhover",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onhover Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_interaction_onhover_mode",
						"value" => array(
							__("Grab" , "vcrbp_particle") => "grab",
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_interaction_enable_onhover" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onclick", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_interaction_enable_onclick",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onclick Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_interaction_onclick_mode",
						"value" => array(
							__("Push" , "vcrbp_particle") => "push",
							__("Remove" , "vcrbp_particle") => "remove",
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lw_interaction_enable_onclick" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Grab Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grab Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_grab_distance",
						"value" => "400",
                        "min" => "0",
                        "max" => "1500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grab Line Linked Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_grab_line_linked_opacity",
						"value" => "1",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Bubble Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_bubble_distance",
						"value" => "400",
                        "min" => "0",
                        "max" => "1500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_bubble_size",
						"value" => "40",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_bubble_duration",
						"value" => "2",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_bubble_opacity",
						"value" => "8.0",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Repulse Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_repulse_distance",
						"value" => "200",
                        "min" => "0",
                        "max" => "1000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_repulse_duration",
						"value" => "1.2",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Push 
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Push Particles", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_push_particles_nb",
						"value" => "4",
                        "min" => "0",
                        "max" => "100",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Remove 
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Remove Particles", "vcrbp_particle"),
						"param_name" => "vcrbp_lw_mode_remove_particles_nb",
						"value" => "4",
                        "min" => "0",
                        "max" => "100",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lw_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Lightweight Dust
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Number", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_number",
						"value" => "1000",
                        "min" => "0",
                        "max" => "6000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Dust Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_lwd_color",
						"value" => "#ffffff",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_active_size_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Size Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_size_value",
						"value" => "3",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_active_opacity_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Opacity Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_opacity_value",
						"value" => "0.5",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Move
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Move Direction", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_move_direction",
						"value" => array(
							__("None" , "vcrbp_particle") => "none",
							__("Top" , "vcrbp_particle") => "top",
							__("Top-Right" , "vcrbp_particle") => "top-right",
							__("Right" , "vcrbp_particle") => "right",
							__("Bottom-Right" , "vcrbp_particle") => "bottom-right",
							__("Bottom" , "vcrbp_particle") => "bottom",
							__("Bottom-Left" , "vcrbp_particle") => "bottom-left",
							__("Left" , "vcrbp_particle") => "left",
							__("Top-Left" , "vcrbp_particle") => "top-left",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Move Speed", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_move_speed",
						"value" => "1",
                        "min" => "0.0",
                        "max" => "200.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Interaction
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Detect On", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_interaction_detect_on",
						"value" => array(
							__("Canvas" , "vcrbp_particle") => "canvas",
							__("Window" , "vcrbp_particle") => "window",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onhover", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_interaction_enable_onhover",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onhover Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_interaction_onhover_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lwd_interaction_enable_onhover" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onclick", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_interaction_enable_onclick",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onclick Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_interaction_onclick_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lwd_interaction_enable_onclick" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Bubble Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_bubble_distance",
						"value" => "150",
                        "min" => "0",
                        "max" => "1500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_bubble_size",
						"value" => "0",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_bubble_duration",
						"value" => "2",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_bubble_opacity",
						"value" => "0",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Repulse Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_repulse_distance",
						"value" => "200",
                        "min" => "0",
                        "max" => "1000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lwd_mode_repulse_duration",
						"value" => "0.4",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwd_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);                
                
                // Lightweight Star
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Number", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_number",
						"value" => "100",
                        "min" => "0",
                        "max" => "600",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Star Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_lwst_color",
						"value" => "#ffffff",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_active_size_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Size Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_size_value",
						"value" => "3",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_active_opacity_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Opacity Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_opacity_value",
						"value" => "0.5",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Move
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Move Direction", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_move_direction",
						"value" => array(
							__("None" , "vcrbp_particle") => "none",
							__("Top" , "vcrbp_particle") => "top",
							__("Top-Right" , "vcrbp_particle") => "top-right",
							__("Right" , "vcrbp_particle") => "right",
							__("Bottom-Right" , "vcrbp_particle") => "bottom-right",
							__("Bottom" , "vcrbp_particle") => "bottom",
							__("Bottom-Left" , "vcrbp_particle") => "bottom-left",
							__("Left" , "vcrbp_particle") => "left",
							__("Top-Left" , "vcrbp_particle") => "top-left",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Move Speed", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_move_speed",
						"value" => "5",
                        "min" => "0.0",
                        "max" => "200.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                
                // Interaction
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Detect On", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_interaction_detect_on",
						"value" => array(
							__("Canvas" , "vcrbp_particle") => "canvas",
							__("Window" , "vcrbp_particle") => "window",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onhover", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_interaction_enable_onhover",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onhover Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_interaction_onhover_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lwst_interaction_enable_onhover" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onclick", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_interaction_enable_onclick",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onclick Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_interaction_onclick_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lwst_interaction_enable_onclick" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Bubble Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_bubble_distance",
						"value" => "400",
                        "min" => "0",
                        "max" => "1500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_bubble_size",
						"value" => "3",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_bubble_duration",
						"value" => "2",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_bubble_opacity",
						"value" => "1.5",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Repulse Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_repulse_distance",
						"value" => "200",
                        "min" => "0",
                        "max" => "1000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lwst_mode_repulse_duration",
						"value" => "0.4",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lwst_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);                
                
                // Lightweight Snow
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Number", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_number",
						"value" => "400",
                        "min" => "0",
                        "max" => "6000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_active_size_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Size Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_size_value",
						"value" => "10",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Random Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_active_opacity_random",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Opacity Value", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_opacity_value",
						"value" => "0.5",
                        "min" => "0.0",
                        "max" => "1.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Move
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Move Direction", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_move_direction",
						"value" => array(
							__("None" , "vcrbp_particle") => "none",
							__("Top" , "vcrbp_particle") => "top",
							__("Top-Right" , "vcrbp_particle") => "top-right",
							__("Right" , "vcrbp_particle") => "right",
							__("Bottom-Right" , "vcrbp_particle") => "bottom-right",
							__("Bottom" , "vcrbp_particle") => "bottom",
							__("Bottom-Left" , "vcrbp_particle") => "bottom-left",
							__("Left" , "vcrbp_particle") => "left",
							__("Top-Left" , "vcrbp_particle") => "top-left",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Move Speed", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_move_speed",
						"value" => "6",
                        "min" => "0.0",
                        "max" => "200.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                
                // Interaction
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Detect On", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_interaction_detect_on",
						"value" => array(
							__("Canvas" , "vcrbp_particle") => "canvas",
							__("Window" , "vcrbp_particle") => "window",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onhover", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_interaction_enable_onhover",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onhover Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_interaction_onhover_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lws_interaction_enable_onhover" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Enable Onclick", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_interaction_enable_onclick",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Onclick Mode", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_interaction_onclick_mode",
						"value" => array(
							__("Bubble" , "vcrbp_particle") => "bubble",
							__("Repulse" , "vcrbp_particle") => "repulse",
							),
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_lws_interaction_enable_onclick" , "value" => array( "true" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Bubble Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_bubble_distance",
						"value" => "400",
                        "min" => "0",
                        "max" => "1500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Size", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_bubble_size",
						"value" => "3",
                        "min" => "0",
                        "max" => "500",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_bubble_duration",
						"value" => "0.3",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Bubble Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_bubble_opacity",
						"value" => "1.0",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                
                // Repulse Mode
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_repulse_distance",
						"value" => "200",
                        "min" => "0",
                        "max" => "1000",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Repulse Duration", "vcrbp_particle"),
						"param_name" => "vcrbp_lws_mode_repulse_duration",
						"value" => "0.4",
                        "min" => "0.0",
                        "max" => "10.0",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "lws_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);                
                
                // Rain
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Rain Intensity", "vcrbp_particle"),
						"param_name" => "vcrbp_rain",
						"value" => "2",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rain_particle" ) ),
						"description" => __("Enter value between 0 to 10", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Multi-color", "vcrbp_particle"),
						"param_name" => "vcrbp_rain_multicolor",
						"value" => array( "Enable" => "true"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rain_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_rain_color",
						"value" => "#0000ff",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rain_particle" ) ),
						"group" => $group_vcrbp_particle,
                        "description" => __("Please <strong>Deactive Multi-color</strong> to use this color.", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_rain_opacity",
						"value" => "1.0",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rain_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                //Plasma	
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Interaction", "vcrbp_particle"),
						"param_name" => "vcrbp_plasma_interaction_active",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "plasma_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Center Point", "vcrbp_particle"),
						"param_name" => "vcrbp_plasma_center_point",
						"value" => array(
                            __("Left - Top" , "vcrbp_particle") => "1",
                            __("Left - Center" , "vcrbp_particle") => "4",
                            __("Left - Bottom" , "vcrbp_particle") => "7",
                            __("Right - Top" , "vcrbp_particle") => "3",
                            __("Right - Center" , "vcrbp_particle") => "6",
                            __("Right - Bottom" , "vcrbp_particle") => "9",
                            __("Top - Center" , "vcrbp_particle") => "2",
                            __("Bottom - Center" , "vcrbp_particle") => "8",
                            __("HCenter - VCenter" , "vcrbp_particle") => "5",
                            __("Random" , "vcrbp_particle") => "10",
							),
						"description" => __("Select the position for center point", "vcrbp_particle"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "plasma_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Ray", "vcrbp_particle"),
						"param_name" => "vcrbp_plasma_ray",
						"value" => "30",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "plasma_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Radiant Span", "vcrbp_particle"),						
						"param_name" => "vcrbp_plasma_radiant_span",
						"value" => "0.4",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "plasma_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                //Organic Lines
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Speed", "vcrbp_particle"),
						"param_name" => "vcrbp_organic_lines_speed",
						"value" => "4",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "organic_lines_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Range", "vcrbp_particle"),
						"param_name" => "vcrbp_organic_lines_range",
						"value" => "80",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "organic_lines_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Alpha", "vcrbp_particle"),						
						"param_name" => "vcrbp_organic_lines_line_alpha",
						"value" => "0.4",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "organic_lines_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                // Rainbow Curves
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Interaction", "vcrbp_particle"),
						"param_name" => "vcrbp_rcp_interaction_active",
						"value" => array( "Enable" => "true"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("")
					)
				);
                
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Center Point", "vcrbp_particle"),
						"param_name" => "vcrbp_rcp_center_point",
						"value" => array(
                            __("Left - Top" , "vcrbp_particle") => "1",
                            __("Left - Center" , "vcrbp_particle") => "4",
                            __("Left - Bottom" , "vcrbp_particle") => "7",
                            __("Right - Top" , "vcrbp_particle") => "3",
                            __("Right - Center" , "vcrbp_particle") => "6",
                            __("Right - Bottom" , "vcrbp_particle") => "9",
                            __("Top - Center" , "vcrbp_particle") => "2",
                            __("Bottom - Center" , "vcrbp_particle") => "8",
                            __("HCenter - VCenter" , "vcrbp_particle") => "5",
                            __("Random" , "vcrbp_particle") => "10",
							),
						"description" => __("Select the position for center point", "vcrbp_particle"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Jitter", "vcrbp_particle"),
						"param_name" => "vcrbp_rcp_jitter",
						"value" => "20",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Width", "vcrbp_particle"),						
						"param_name" => "vcrbp_rcp_line_width",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Rad Apart", "vcrbp_particle"),						
						"param_name" => "vcrbp_rcp_rad_apart",
						"value" => "0.5",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Acc Apart", "vcrbp_particle"),						
						"param_name" => "vcrbp_rcp_acc_apart",
						"value" => "0.001",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Repaint Alpha", "vcrbp_particle"),						
						"param_name" => "vcrbp_rcp_repaint_alpha",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "rainbow_curves_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                // Rainbow Fireflies
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Particle Count", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_particle_count",
						"value" => "40",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Particle Speed", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_particle_speed",
						"value" => "-2",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Particle Angular Speed", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_particle_angular_speed",
						"value" => "0.03",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Particle Ray Behaviour Prob", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_particle_ray_behaviour_prob",
						"value" => "0.05",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Particle Circle Behaviour Prob", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_particle_circle_behaviour_prob",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                // Connection
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Connection Count", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_connection_count",
						"value" => "10",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Connection Life", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_connection_life",
						"value" => "10",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Connection Added Life", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_connection_added_life",
						"value" => "10",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Connection Splits", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_connection_splits",
						"value" => "3",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Connection Jitter", "vcrbp_particle"),
						"param_name" => "vcrbp_rffp_connection_jitter",
						"value" => "5",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Connection Span Multiplier", "vcrbp_particle"),						
						"param_name" => "vcrbp_rffp_connection_span_multiplier",
						"value" => "0.2",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "fireflies_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                // Rainbow Grid
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Max Count", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_max_count",
						"value" => "40",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Spawn Prob", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_line_spawn_prob",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Max Length", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_max_length",
						"value" => "10",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Increment Prob", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_line_increment_prob",
						"value" => "0.5",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Decrement Prob", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_line_decrement_prob",
						"value" => "0.7",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Safe Time", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_safe_time",
						"value" => "150",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Mid Jitter", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_mid_jitter",
						"value" => "7",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Mid Points", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_mid_points",
						"value" => "3",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Hue Variation", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_hue_variation",
						"value" => "30",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Alpha", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_line_alpha",
						"value" => "1",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
                
                // Grid
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grid Side Num", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_side_num",
						"value" => "6",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grid Side", "vcrbp_particle"),
						"param_name" => "vcrbp_rbg_side",
						"value" => "30",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Grid Rotation Vel", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_rotation_vel",
						"value" => "0.002",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Grid Scaling Input Multiplier", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_scaling_input_multiplier",
						"value" => "0.01",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Grid Scaling Multiplier", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_scaling_multiplier",
						"value" => "0.3",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Grid Hue Change", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_hue_change",
						"value" => "0.6",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Grid Repaint Alpha", "vcrbp_particle"),						
						"param_name" => "vcrbp_rbg_repaint_alpha",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "grid_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
                
                // Centaurus
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Number", "vcrbp_particle"),
						"param_name" => "vcrbp_centaurus_number",
						"value" => "8000",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Inner Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_centaurus_inner_color",
						"value" => "#ff0000",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Inner Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_centaurus_inner_opacity",
						"value" => "1.0",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Outer Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_centaurus_outer_color",
						"value" => "#ff0000",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Outer Opacity", "vcrbp_particle"),						
						"param_name" => "vcrbp_centaurus_outer_opacity",
						"value" => "0.1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "vcrbp_particle"),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Active Mouse Interaction", "vcrbp_particle"),
						"param_name" => "vcrbp_centaurus_mouse_active",
						"value" => array( "Enable" => "true"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "centaurus_particle" ) ),
						"description" => __(""),
					)
				);

                // Gradient
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Active Random Color", "vcrbp_particle"),
						"param_name" => "vcrbp_random_dot_color",
						"value" => array(
							__("Single Color" , "vcrbp_particle") => "false",
							__("Random" , "vcrbp_particle") => "true",
							),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Dot Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_dot_color",
						"value" => "#ff0000",
						"dependency" => array( "element" => "vcrbp_random_dot_color" , "value" => array( "false" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Dot Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_dot_opacity",
						"value" => "1.0",
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_random_dot_color" , "value" => array( "false" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Active Random Gradient Connection Color", "vcrbp_particle"),
						"param_name" => "vcrbp_random_gradient_color",
						"value" => array(
							__("Single Color" , "vcrbp_particle") => "false",
							__("Random" , "vcrbp_particle") => "true",
							),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Gradient Connection Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_gradient_color",
						"value" => "#0000ff",
						"dependency" => array( "element" => "vcrbp_random_gradient_color" , "value" => array( "false" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Gradient Connection Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_gradient_opacity",
						"value" => "1.0",
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_random_gradient_color" , "value" => array( "false" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed X", "vcrbp_particle"),
						"param_name" => "vcrbp_min_speed_x",
						"value" => "1",
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed X", "vcrbp_particle"),
						"param_name" => "vcrbp_max_speed_x",
						"value" => "7",                        
						"description" => __("Enter value", "vcrbp_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed Y", "vcrbp_particle"),
						"param_name" => "vcrbp_min_speed_y",
						"value" => "1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "vcrbp_particle")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed Y", "vcrbp_particle"),
						"param_name" => "vcrbp_max_speed_y",
						"value" => "7",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "vcrbp_particle")
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction X", "vcrbp_particle"),
						"param_name" => "vcrbp_direction_x",
						"value" => array(
							__("Left", "vcrbp_particle") => "wpvc_left",
							__("Center", "vcrbp_particle") => "wpvc_center",
							__("Right", "vcrbp_particle") => "wpvc_right",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction Y", "vcrbp_particle"),
						"param_name" => "vcrbp_direction_y",
						"value" => array(
							__("Up", "vcrbp_particle") => "wpvc_up",
							__("Center", "vcrbp_particle") => "wpvc_center",
							__("Down", "vcrbp_particle") => "wpvc_down",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Density", "vcrbp_particle"),
						"param_name" => "vcrbp_density",
						"value" => "10000",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Particle Radius", "vcrbp_particle"),
						"param_name" => "vcrbp_particle_radius",
						"value" => "7",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Width", "vcrbp_particle"),
						"param_name" => "vcrbp_line_width",
						"value" => "1",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Parallax", "vcrbp_particle"),
						"param_name" => "vcrbp_parallax",
						"value" => array( "Enable" => "vcrbp_parallax_value"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Proximity", "vcrbp_particle"),
						"param_name" => "vcrbp_proximity",
						"value" => "100",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Parallax Multiplier", "vcrbp_particle"),
						"param_name" => "vcrbp_parallax_multiplier",
						"value" => "5",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Grab Hover", "vcrbp_particle"),
						"param_name" => "vcrbp_grab_hover",
						"value" => array( "Enable" => "vcrbp_grab_hover_value"),
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grab Distance", "vcrbp_particle"),
						"param_name" => "vcrbp_grab_distance",
						"value" => "100",
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "gradient_particle" ) ),
						"description" => __(""),
					)
				);
                
                // Twinkle
				vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"admin_label" => true,
						"heading" => __("Active Random Color", "vcrbp_particle"),
						"param_name" => "vcrbp_twinkle_random_color",
						"value" => array(
							__("Single Color" , "vcrbp_particle") => "false",
							__("Random Color" , "vcrbp_particle") => "true",
							),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "twinkle_particle" ) ),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Color", "vcrbp_particle"),						
						"param_name" => "vcrbp_twinkle_color",
						"value" => "#ff0000",
                        "dependency" => array( "element" => "vcrbp_twinkle_random_color" , "value" => array( "false" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Opacity", "vcrbp_particle"),
						"param_name" => "vcrbp_twinkle_opacity",
						"value" => "1.0",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_twinkle_random_color" , "value" => array( "false" ) ),
						"description" => __(""),
					)
				);
                
                // Shocking
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed X", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_min_speed_x",
						"value" => "1",
						"description" => __("Enter value", "wpvc_shocking_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed X", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_max_speed_x",
						"value" => "7",                        
						"description" => __("Enter value", "wpvc_shocking_particle"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed Y", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_min_speed_y",
						"value" => "1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "wpvc_shocking_particle")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed Y", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_max_speed_y",
						"value" => "7",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "wpvc_shocking_particle")
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction X", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_direction_x",
						"value" => array(
							__("Left", "wpvc_shocking_particle") => "wpvc_left",
							__("Center", "wpvc_shocking_particle") => "wpvc_center",
							__("Right", "wpvc_shocking_particle") => "wpvc_right",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction Y", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_direction_y",
						"value" => array(
							__("Up", "wpvc_shocking_particle") => "wpvc_up",
							__("Center", "wpvc_shocking_particle") => "wpvc_center",
							__("Down", "wpvc_shocking_particle") => "wpvc_down",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Density", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_density",
						"value" => "10000",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Dot Opacity", "wpvc_particleground"),						
						"param_name" => "vcrbp_shocking_dot_opacity",
						"value" => "1.0",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "wpvc_particleground")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Dot Color", "wpvc_particleground"),						
						"param_name" => "vcrbp_shocking_dot_color",
						"value" => "#666666",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Line Color", "wpvc_particleground"),						
						"param_name" => "vcrbp_shocking_line_color",
						"value" => "#666666",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Particle Radius", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_particle_radius",
						"value" => "7",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Width", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_line_width",
						"value" => "1",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Parallax", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_parallax",
						"value" => array( "Enable" => "vcrbp_shocking_parallax_value"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Proximity", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_proximity",
						"value" => "100",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Parallax Multiplier", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_parallax_multiplier",
						"value" => "5",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Grab Hover", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_grab_hover",
						"value" => array( "Enable" => "vcrbp_shocking_grab_hover_value"),
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grab Distance", "wpvc_shocking_particle"),
						"param_name" => "vcrbp_shocking_grab_distance",
						"value" => "100",
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "shocking_particle" ) ),
						"description" => __(""),
					)
				);
                
                // ParticleGround
                vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed X", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_min_speed_x",
						"value" => "1",
						"description" => __("Enter value", "wpvc_particleground"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed X", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_max_speed_x",
						"value" => "7",                        
						"description" => __("Enter value", "wpvc_particleground"),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Min Speed Y", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_min_speed_y",
						"value" => "1",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "wpvc_particleground")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Max Speed Y", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_max_speed_y",
						"value" => "7",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value", "wpvc_particleground")
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction X", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_direction_x",
						"value" => array(
							__("Left", "wpvc_particleground") => "wpvc_left",
							__("Center", "wpvc_particleground") => "wpvc_center",
							__("Right", "wpvc_particleground") => "wpvc_right",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
                vc_add_param('vc_row', array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Direction Y", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_direction_y",
						"value" => array(
							__("Up", "wpvc_particleground") => "wpvc_up",
							__("Center", "wpvc_particleground") => "wpvc_center",
							__("Down", "wpvc_particleground") => "wpvc_down",
							),
						"description" => __(""),
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Density", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_density",
						"value" => "10000",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Dot Opacity", "wpvc_particleground"),						
						"param_name" => "vcrbp_pg_dot_opacity",
						"value" => "1.0",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "wpvc_particleground")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Dot Color", "wpvc_particleground"),						
						"param_name" => "vcrbp_pg_dot_color",
						"value" => "#666666",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "textfield",
						"class" => "",
						"heading" => __("Line Opacity", "wpvc_particleground"),						
						"param_name" => "vcrbp_pg_line_opacity",
						"value" => "1.0",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
						"description" => __("Enter value between 0 to 1.0", "wpvc_particleground")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "colorpicker",
						"class" => "",
						"heading" => __("Line Color", "wpvc_particleground"),						
						"param_name" => "vcrbp_pg_line_color",
						"value" => "#666666",
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"group" => $group_vcrbp_particle,
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Particle Radius", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_particle_radius",
						"value" => "7",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Line Width", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_line_width",
						"value" => "1",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Parallax", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_parallax",
						"value" => array( "Enable" => "vcrbp_pg_parallax_value"),
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Proximity", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_proximity",
						"value" => "100",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Parallax Multiplier", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_parallax_multiplier",
						"value" => "5",
						"group" => $group_vcrbp_particle,
                        "dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
				vc_add_param('vc_row', array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Grab Hover", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_grab_hover",
						"value" => array( "Enable" => "vcrbp_pg_grab_hover_value"),
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __("")
					)
				);
				vc_add_param('vc_row', array(
						"type" => "number",
						"class" => "",
						"heading" => __("Grab Distance", "wpvc_particleground"),
						"param_name" => "vcrbp_pg_grab_distance",
						"value" => "100",
						"group" => $group_vcrbp_particle,
						"dependency" => array( "element" => "vcrbp_active" , "value" => array( "particle_ground" ) ),
						"description" => __(""),
					)
				);
			}
		}
	}

	new vc_row_background_particles;
}

if ( !function_exists( 'vc_theme_after_vc_row' ) ) {
	function vc_theme_after_vc_row($atts, $content = null) {
		return apply_filters( 'vcrbp_row_particle', '', $atts, $content );
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Check VC Plugin Dependdencies 
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'vcrbp_dependencies' ) ) {
    function vcrbp_dependencies() {
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            $plugin_data = get_plugin_data( __FILE__ );
            echo '
            <div class="updated">
              <p>'.sprintf('<strong>%s</strong> requires <strong><a href="http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', $plugin_data['Name']).'</p>
            </div>';
        }
    }
}

add_action( 'admin_notices', 'vcrbp_dependencies' );

// Convert Hex & Opacity to RGBA
if(! function_exists('vcrbp_hex2rgb')){
	function vcrbp_hex2rgb( $hex, $opacity=1 ) {
	   $hex = str_replace("#", "", $hex);
	   if( strlen($hex) == 3 ) {
		  $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
		  $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
		  $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
	   }
	   else {
		  $r = hexdec( substr( $hex, 0, 2 ) );
		  $g = hexdec( substr( $hex, 2, 2 ) );
		  $b = hexdec( substr( $hex, 4, 2 ) );
	   }
	   $rgba = 'rgba('.$r.', '.$g.', '.$b.', '.$opacity.')';
	   return $rgba;
	}
}
?>