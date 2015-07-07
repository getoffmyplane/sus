<?php
/*
 * Title: Group
 * Post Type: group
 * Order: 10
 */

/*
 * Select Group Type
 */

piklist('field', array(
    'type' => 'select',
    'field' => 'group_type',
    'label' => 'Group Type',
    'choices' => array(
        'market' => 'Market',
        'organisation' => 'Organisation',
        'portfolio' => 'Portfolio'
    ),
    'columns' => 12
));

//description
piklist('field', array(
    'type' => 'editor',
    'field' => 'group_description',
    'label' => 'Group Description',
    'columns' => 12
));

//Market - related personas
piklist('field', array(
    'type' => 'post-relate',
    'field' => 'group_main_related_personas',
    'label' => __('Related Personas'),
    'description' => 'Select the persona(s) that make up your market',
    'scope' => 'persona',
    'template' => 'field',
    'conditions' => array(
        array(
            'field' => 'group_type',
            'value' => 'market'
        )
    )
));

//Organisation - core values
piklist('field', array(
    'type' => 'group',
    'field' => 'org_core_values',
    'label' => __('Organisation\'s Core Values'),
    'description' => 'Enter the organisation\'s core values here. Use + / - to add or remove events',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'org_core_value',
            'label' => 'Core Value',
            'columns' => 2
        ),
        array(
            'type' => 'textarea',
            'field' => 'org_core_value_definition',
            'label' => 'Definition',
            'attributes' => array(
                'class' => 'text'
            ),
            'columns' => 10
        )
    ),
    'conditions' => array(
        array(
            'field' => 'group_type',
            'value' => 'organisation'
        )
    )
));

//Organisation - related people
piklist('field', array(
    'type' => 'post-relate',
    'label' => __('Related People'),
    'description' => 'Select the people that make up the organisation',
    'scope' => 'person',
    'template' => 'field',
    'conditions' => array(
        array(
            'field' => 'group_type',
            'value' => 'organisation'
        )
    )
));

//Portfolio - related products
piklist('field', array(
    'type' => 'post-relate',
    'label' => __('Related Products'),
    'description' => 'Select the product(s) that make up your portfolio',
    'scope' => 'product',
    'template' => 'field',
    'conditions' => array(
        array(
            'field' => 'group_type',
            'value' => 'product'
        )
    )
));


