<?php
/*
Title: Skills & Education
Post Type: persona
Order: 30
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
 * Education level
 * First function shows Education level
 * Second function controls visibility of Education level field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Education Level',
    'description' => 'Enter your persona\'s education level',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_education_level',
            'attributes' => array(
                'min' => '1',
                'max' => '6',
                'step' => '1',
                'id' => 'education_level',
                'oninput' => 'educationOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_education_level',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_education_level',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_education_level" id="persona_education_level_value">
                    <script>
                    </script>
                </output>
                <script>
                    function educationOutputUpdate(pel)
                    {
                        if (pel == 1)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'Less Than Secondary / High School Education\';
                        }
                        else if (pel == 2)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'Secondary Education / High School Graduate\';
                        }
                        else if (pel == 3)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'Some College, No Degree\';
                        }
                        else if (pel == 4)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'Diploma or Associate Degree\';
                        }
                        else if (pel == 5)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'Undergraduate or Bachelors Degree\';
                        }
                        else if (pel == 6)
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'PHD or Doctorate\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_education_level_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_education_level',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 12
        )
    )
));

/*
 * Skills
 * Repeater
 * Grouped text box with slider
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_skills',
    'label' => __('Persona\'s Skills'),
    'description' => 'Add the skills and skill levels that your persona will need to use your product or service. Use + / - to add or remove skills',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_skill_title',
        ),
        array
        (
            'type' => 'range',
            'field' => 'persona_skill_level',
            'label' => 'Skill Level',
            'help' => 'Enter your persona\'s skill level',
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

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));