<?php

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );


if ( $images == '' ) return null;

$id = uniqid();

$direction = $direction == 'horizontal' ? 'slide' : 'vertical_slide';

?>

<div class="mk-swipe-slideshow mk-slider">
	<div id="mk-swiper-<?php echo $id; ?>" class="mk-swiper-container <?php echo $el_class; ?>   js-el"
			data-mk-component='SwipeSlideshow'
			data-swipeSlideshow-config='{
				"effect" : "<?php echo $direction ?>",
				"displayTime" : "<?php echo $slideshow_speed ?>",
				"transitionTime" : "<?php echo $animation_speed ?>",
				"nav" : ".mk-swipe-slideshow-nav-<?php echo $id ?>",
				"hasNav" : "<?php echo $direction_nav; ?>" }'>

		<div class="mk-swiper-wrapper mk-slider-holder">
			<?php
			$images = explode( ',', $images );
			foreach ( $images as $attach_id ) {
				
				$image_src_array = wp_get_attachment_image_src( $attach_id, 'full', true );

				if ($image_size == 'crop') {
				    $image_src_array = wp_get_attachment_image_src( $attach_id, 'full', true );
				    $image_output_src = mk_image_generator($image_src_array[ 0 ], $image_width, $image_height, true);
				} 
				else {
				        $image_src_array = wp_get_attachment_image_src($attach_id , $image_size, true);
				        $image_output_src = $image_src_array[0];
				        $image_width = $image_src_array[1];
				        $image_height = $image_src_array[2];    
				}


				if(!empty($attach_id)) {
					?>

					<div class="swiper-slide mk-slider-slide">
						<img alt="<?php echo trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) )); ?>" 
							 src="<?php echo $image_output_src; ?>" 
							 width="<?php echo $image_width; ?>" 
							 height="<?php echo $image_height; ?>" />
					</div>

					<?php 
				} ?>
			<?php } ?>

		</div>

		<?php if( $direction_nav == 'true' ) { ?>
		<div class="mk-swipe-slideshow-nav-<?php echo $id ?>">
			<a class="mk-swiper-prev swiper-arrows" data-direction="prev"><i class="mk-jupiter-icon-arrow-left"></i></a>
			<a class="mk-swiper-next swiper-arrows" data-direction="next"><i class="mk-jupiter-icon-arrow-right"></i></a>
		</div>
		<?php } ?>

		<!-- empty PNG to stretch slider and make it responsive outside of js as the slider adjusts height and width to container sizes  -->
		<img src="<?php echo mk_image_generator('', $image_width, $image_height) ; ?>" class="mk-slider-holder-img" />

	</div>
</div>