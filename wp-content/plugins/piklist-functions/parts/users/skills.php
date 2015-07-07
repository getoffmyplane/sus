<?php
/*
 * Title: Skills
 */

/*
 * Skills
 * Repeater
 * Grouped text box with slider
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'user_skills',
    'label' => __('Strengths & Weaknesses'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'user_skill_title',
            'columns' => 2
        ),
        array
        (
            'type' => 'range',
            'field' => 'user_skill_level',
            'label' => 'Skill Level',
            'help' => 'Enter your skill level',
            'attributes' => array(
                'min' => '1',
                'max' => '100',
                'step' => '1',
                'id' => 'skill_level',
            ),
            'on_post_status' => array(
                'value' => 'lock'
            ),
            'columns' => 10
        )
    )
));



