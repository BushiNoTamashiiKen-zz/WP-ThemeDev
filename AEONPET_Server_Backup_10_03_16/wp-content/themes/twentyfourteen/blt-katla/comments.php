<?php

    if(blt_get_option('comments_display') == 'off' or post_password_required()){ 
        return;
    }
?>
<section class="comments-container"><?php

    if(strpos(blt_get_option('comments_display'), 'wordpress') > -1){

        $commenter = wp_get_current_commenter();

        $req = get_option('require_name_email');
        $aria_req = $req ? " aria-required='true'" : '';


        # Comment Form
        comment_form(array(
            'id_form'               => 'comment-form',
            'id_submit'             => 'submit',
            'class_submit'          => 'btn btn-default',
            'comment_notes_after'   => '',
            'title_reply'           => __('Leave a Reply' , 'bluthemes'),
            'title_reply_to'        => __('Leave a Reply to %s' , 'bluthemes'),
            'cancel_reply_link'     => __('Cancel Reply' , 'bluthemes'),
            'label_submit'          => __('Post Comment' , 'bluthemes'),
            'comment_field'         => '<div class="form-group" id="comment-textfield-area"><textarea placeholder="'.esc_attr__('Write your reply here...', 'bluthemes').'" id="comment" class="form-control input-lg" name="comment" cols="45" rows="5" aria-required="true"></textarea></div>',
            'must_log_in'           => '<p class="must-log-in">'.sprintf(__('You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url(apply_filters('the_permalink', get_permalink()))).'</p>', 'logged_in_as' => '<p class="logged-in-as">'.sprintf(__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))).'</p>',
            'fields'                => apply_filters('comment_form_default_fields', array(
                'author'    => '<div class="form-group comment-form-author"><input class="form-control input-lg" id="author" name="author" placeholder="'.esc_attr__( 'Name', 'bluthemes').($req ? '*' : '').'" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30"'.$aria_req.' /></div>',
                'email'     => '<div class="form-group comment-form-email"><input class="form-control input-lg" id="email" name="email" placeholder="'.esc_attr__( 'Email', 'bluthemes').($req ? '*' : '').'" type="text" value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.$aria_req.' /></div>',
                'url'       => '<div class="form-group comment-form-url"><input class="form-control input-lg" id="url" name="url" placeholder="'.esc_attr__( 'Website', 'bluthemes').'" type="text" value="'.esc_attr($commenter['comment_author_url']).'" size="30" /></div>'
            )),
        ));  
        

        # Comments
        if(have_comments()){ ?>

            <h3 class="comments-title">
                <?php printf( _n('%d comment', '%d comments', get_comments_number(), 'bluthemes'), number_format_i18n( get_comments_number() ) ); ?>
            </h3>
            <ul class="comment-list list-unstyled"><?php

                wp_list_comments(array(
                    'callback' => 'blt_get_comment',                        
                    'avatar_size' => 50,
                )); ?>
            </ul><?php    

            # Comment navigation
            blt_comment_nav();
        }

    }

    if(strpos(blt_get_option('comments_display'), 'facebook') > -1){
        echo '<div id="fb-comments" class="comments-area comments-area-facebook pad-xs-5 pad-sm-10 pad-md-20 pad-lg-20">';
        echo '<div class="fb-comments" data-href="'.get_permalink().'" data-width="100%" data-num-posts="10"></div>';
        echo '</div>';
    }

    # Comments Closed
    if(!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')){
        echo '<p class="nocomments">'. __('Comments are closed.', 'bluthemes').'</p>';
    }
    ?>
</section>