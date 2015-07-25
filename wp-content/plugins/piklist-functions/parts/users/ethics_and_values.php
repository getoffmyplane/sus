<?php
/*
 * Title: Beliefs & Values
 */

//Enabling beliefs
piklist('field', array(
    'type' => 'group',
    'field' => 'user_beliefs',
    'label' => 'Enter your personal beliefs here. Use + / - to add or remove beliefs',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'user_belief_question',
            'label' => 'Question',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => 'user_belief_answer',
            'label' => 'Answer',
            'attributes' => array(
                'class' => 'text'
            ),
            'columns' => 12
        )
    )
));

//Personal values

piklist('field', array(
    'type' => 'group',
    'field' => 'user_values',
    'label' => 'Enter your personal values here. Use + / - to add or remove values',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'user_value',
            'columns' => 2
        ),
        array(
            'type' => 'textarea',
            'field' => 'user_value_definition',
            'label' => 'Definition',
            'attributes' => array(
                'class' => 'text'
            ),
            'columns' => 10
        )
    )
));




