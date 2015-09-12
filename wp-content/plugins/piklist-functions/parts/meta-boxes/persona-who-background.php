<?php
/*
Title: Background
Post Type: persona
Order: 10
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
 * Persona alignment
 * First function shows  alignment field
 * Second function controls visibility of alignment field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Alignment',
    'description' => 'Personas can be positive or negative',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_alignment',
            'choices' => array(
                'positive' => 'Positive',
                'negative' => 'Negative'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_alignment',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_alignment',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Early Adopter flag
 * First function shows  early adopter flag
 * Second function controls visibility of early adopter flag
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Early Adopter?',
    'description' => 'Personas who are early adopters are more forgiving than those who are mainstream',
    'fields' => array(
        array(
            'type' => 'checkbox',
            'field' => 'early_adopter_bool',
            'choices' => array(
                'early_adopter' => 'Early Adopter?'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_early_adopter_bool',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_early_adopter_bool',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Persona Background
 * First function shows background field
 * Second function controls visibility of background field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Persona Background',
    'description' => 'Enter in your persona\'s general background information (e.g. career path, family, etc.)',
    'fields' => array(
        array(
            'type' => 'textarea',
            'field' => 'persona_backround',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_backround',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_backround',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));