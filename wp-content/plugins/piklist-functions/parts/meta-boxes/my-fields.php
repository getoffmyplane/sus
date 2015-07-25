<?php
/*
 * Title: My Custom Fields
 * Post Type: post
 */

piklist('field', array(
    'type' => 'text',
    'field' => 'field_one',
    'label' => 'First Field'
));

piklist('field', array(
    'type' => 'colorpicker',
    'field' => 'field_two',
    'label' => 'Second Field'
));

piklist('field', array(
    'type' => 'editor',
    'scope' => 'post',
    'field' => 'post_content',
    'options' => array(
        'wpautop' => true,
        'media_buttons' => true,
        'tabindex' => '',
        'editor_css' => '',
        'teeny' => '',
        'dfw' => false,
        'tinymce' => true,
        'quicktags' => true
    )
));

piklist('field', array(
    'type' => 'radio',
    'field' => 'show_excerpt',
    'label' => 'Would you like to see the post excerpt field?',
    'attributes' => array(
        'class' => 'text'
    ),
    'choices' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
    'value' => 'no'
));

piklist('field', array(
    'type' => 'editor',
    'field' => 'post_excerpt',
    'label' => 'Excerpt',
    'options' => array (
        'media_buttons' => false,
        'teeny' => true
    ),
    'conditions' => array(
        array(
            'field' => 'show_excerpt',
            'value' => 'yes'
        )
    )
));



