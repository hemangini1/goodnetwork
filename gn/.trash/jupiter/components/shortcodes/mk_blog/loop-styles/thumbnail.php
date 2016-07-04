<?php

    global $mk_options;
    
    $output = '';

    if ($view_params['thumbnail_align'] == 'left'){
        $align_class = ' content-align-right';
    }else{
        $align_class = ' content-align-left';
    }

    $image_height = $view_params['grid_image_height'];
    $image_width = 400;

    
    $post_type = get_post_meta($post->ID, '_single_post_type', true);
    $post_type = !empty($post_type) ? $post_type : 'image';

    $attachment_id = mk_get_blog_post_thumbnail($post_type);


    if ($view_params['image_size'] == 'crop') {
        $image_src_array = wp_get_attachment_image_src($attachment_id, 'full', true); 
        $image_output_src = mk_image_generator($image_src_array[0], $image_width, $image_height);
    } 
    else {
        if(!empty($attachment_id) && !mk_is_default_thumbnail(wp_get_attachment_image_src($attachment_id, 'full', true)[0])) {
            $image_src_array = wp_get_attachment_image_src($attachment_id , $view_params['image_size'], true); 
            $image_output_src = $image_src_array[0];
            $image_width = $image_src_array[1];
            $image_height = $image_src_array[2];    
        } else {
            $image_output_src = mk_image_generator('', $image_width, $image_height);
        }
    }
    
    $post_width = empty($attachment_id) ? 'full-width-post' : '';

    $output .= '<article id="' . get_the_ID() . '" class="mk-blog-thumbnail-item '.$post_type.'-post-type mk-isotop-item ' . $post_type . '-post-type'.$align_class.' '.$post_width.' clear">' . "\n";

    if (!empty($attachment_id)) {
        $output .= '<div class="featured-image" ><a href="' . get_permalink() . '" title="' . the_title_attribute(array('echo' => false)) . '">';
        $output .= '<img alt="' . the_title_attribute(array('echo' => false)) . '" title="' . the_title_attribute(array('echo' => false)) . '" width="'.$image_width.'" height="'.$image_height.'" src="' . $image_output_src . '" itemprop="image" />';
        $output .= '<div class="image-hover-overlay"></div>';
        $output .= '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-li-' . $post_type . '"></i></div>';
        $output .= '</a></div>';
    }

    $output .= '<div class="item-wrapper">';

        // start: [mk-blog-meta]
        $output .= '<div class="mk-blog-meta">';
        $output.= mk_get_shortcode_view('mk_blog', 'components/meta', true);   
        $output.= mk_get_shortcode_view('mk_blog', 'components/title', true);
        $output.= mk_get_shortcode_view('mk_blog', 'components/excerpt', true, ['excerpt_length' => $view_params['excerpt_length'], 'full_content' => false]);

        $output .= '<div class="mk-teader-button">';
        $output .= do_shortcode( '[mk_button dimension="outline" corner_style="pointed" outline_skin="dark" margin_bottom="0" size="medium" align="left" url="'.get_permalink().'"]'.__('READ MORE', 'mk_framework').'[/mk_button]' );
        $output .= '</div>';

        $output .= '</div>';
        // end: [mk-blog-meta]


    $output .= '</div>'; // end: [item-wrapper]

    $output .= '</article>' . "\n\n\n";
    echo $output;
