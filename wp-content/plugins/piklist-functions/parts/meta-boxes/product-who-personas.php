<?php
/*
 * Title: Who is the product for?
 * Post Type: product-or-service
 * Order: 10
 */

piklist('field', array(
    'type' => 'post-relate',
    'label' => __('Link to Personas'),
    'description' => 'Link your product to persona(s)',
    'scope' => 'persona',
    'template' => 'field'
));