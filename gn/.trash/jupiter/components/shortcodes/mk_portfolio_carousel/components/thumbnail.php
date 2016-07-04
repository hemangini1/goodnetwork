<?php

if ($view_params['image_size'] == 'crop') {
    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true );
    $image_output_src = mk_image_generator($image_src_array[ 0 ], $view_params['width'], $view_params['height']);
} 
else {
    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), $view_params['image_size'], true);
    $image_output_src = $image_src_array[0];
    $image_width = $image_src_array[1];
    $image_height = $image_src_array[2];    
}
?>

<img width="<?php echo $view_params['width']; ?>" height="<?php echo $view_params['height']; ?>" src="<?php echo $image_output_src; ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>"  class="item-featured-image" />
