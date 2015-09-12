<?php
/*
Title: Identifiers
Post Type: persona
Order: 40
*/

// Any field with the scope set to the field name of the upload field will be treated as related
// data to the upload. Below we see we are setting the post_status and post_title, where the
// post_status is pulled dynamically on page load, hence the current status of the content is
// applied. Have fun! ;)
//
// NOTE: If the post_status of an attachment is anything but inherit or private it will NOT be
// shown on the Media page in the admin, but it is in the database and can be found using query_posts
// or get_posts or get_post etc....

/*
 * Persona Appearance
 * First function shows Appearance field
 * Second function controls visibility of Appearance field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Persona Images',
    'description' => 'Upload the image(s) that best represent your persona to you',
    'fields' => array(
        array(
            'type' => 'file',
            'field' => 'persona_image',
            'scope' => 'post_meta',
            'options' => array(
                'modal_title' => __('Add Image(s)','market'),
                'button' => __('Upload Image','market')
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_image',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_image',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_appearance',
    'label' => __('Persona\'s Appearance'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_appearance_type',
            'label' => 'Appearance Type',
            'columns' => 3
        ),
        array(
            'type' => 'text',
            'field' => 'persona_appearance',
            'label' => 'Appearance',
            'columns' => 9
        )
    )
));

/*
 * Behaviours
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_behaviours',
    'label' => __('Persona\'s Behaviours'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_behaviour_type',
            'label' => 'Behaviour Type',
            'columns' => 3
        ),
        array(
            'type' => 'text',
            'field' => 'persona_behaviour',
            'label' => 'Behaviour',
            'columns' => 9
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));