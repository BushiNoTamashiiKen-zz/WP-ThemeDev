<?php

    global $post;

    $args = array();
    $args['number_of_posts']    = blt_get_option('related_posts_count', 4);
    $args['priority']           = blt_get_option('related_posts_by', 'tags');
    $args['columns']            = blt_get_option('related_posts_columns', 2);

    if($args['number_of_posts']){
        
        # Get tags
        $tags = wp_get_post_tags($post->ID);

        # Get categories
        $categories = get_the_category();

        if($tags or $categories){

            # Prepare tags and categories
            $cat_ids = array();

            if($categories){
                foreach($categories as $category){
                    $cat_ids[] = $category->cat_ID;
                } 
            }

            $tag_ids = array();
            if($tags){
                foreach($tags as $individual_tag){
                    $tag_ids[] = $individual_tag->term_id;
                }    
            }


            $query_args = array(
                'post__not_in' => array($post->ID),
                'showposts' => $args['number_of_posts'],
                'ignore_sticky_posts' => 1
            );

            if($args['priority'] == 'category'){

                $query_args['category__in'] = $cat_ids;
                $query = new wp_query($query_args);

                if(!$query->have_posts() and !empty($tags)){

                    unset($query_args['category__in']);
                    $query_args['tag__in'] = $tag_ids;
                    $query = new wp_query($query_args);

                }

            }else{

                $query_args['tag__in'] = $tag_ids;
                $query = new wp_query($query_args);

                if(!$query->have_posts() and !empty($tags)){

                    unset($query_args['tag__in']);
                    $query_args['category__in'] = $cat_ids;
                    $query = new wp_query($query_args);

                }
            }

        }
    }


    if($args['number_of_posts'] and ($tags or $categories)){

        if($query->have_posts()){ ?>

            <section class="blu-related-posts">
                    
                <h3 class="blu-related-title"><?php _e('Related Posts', 'bluthemes'); ?></h3>
                <div class="row"><?php

                    while($query->have_posts()){ $query->the_post(); ?>
                    
                        <article class="col-md-<?php echo 12/$args['columns']; ?> col-lg-<?php echo 12/$args['columns']; ?>">
                            <a href="<?php esc_url( the_permalink() ) ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php

                            	if(has_post_thumbnail()){
                            		the_post_thumbnail('md-crop');
                            	} ?>
                                <h4 class="post-title"><?php the_title(); ?></h4>
                            </a>
                        </article><?php
                    } ?>
                </div>
                    
            </section><?php
        }

        wp_reset_query();

    } ?>