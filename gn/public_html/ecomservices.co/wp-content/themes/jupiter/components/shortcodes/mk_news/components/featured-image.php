<?php

$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);


if (has_post_thumbnail()) { ?>

    <img alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" width="<?php echo $view_params['image_width']; ?>" height="<?php echo $view_params['image_height']; ?>" src="<?php echo mk_image_generator($image_src_array[0], $view_params['image_width'], $view_params['image_height']); ?>" itemprop="image" />

<?php }