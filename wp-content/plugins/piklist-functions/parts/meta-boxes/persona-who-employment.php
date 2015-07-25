<?php
/*
Title: Employment
Post Type: persona
Order: 50
*/

// Any field with the scope set to the field name of the upload field will be treated as related
// data to the upload. Below we see we are setting the post_status and post_title, where the
// post_status is pulled dynamically on page load, hence the current status of the content is
// applied. Have fun! ;)
//
// NOTE: If the post_status of an attachment is anything but inherit or private it will NOT be
// shown on the Media page in the admin, but it is in the database and can be found using query_posts
// or get_posts or get_post etc....
?>

    <h3 class="demo-highlight">
        <?php _e('Piklist comes standard with two upload fields: Basic and Media. The Media field works just like the standard WordPress media field, while the Basic uploader is great for simple forms.','piklist-demo');?>
        <?php _e('The metabox "look" can be removed to provide a different look.','piklist-demo');?>
    </h3>

<?php

/*
 * Type of Employment
 * First function shows Type of Employment field
 * Second function controls visibility of Type of Employment field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Employment Type',
    'description' => 'How many hours per week does your persona USUALLY work at their job?',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_employment_type',
            'choices' => array(
                'fulltime' => '35 hours per week or more',
                'parttime' => 'Fewer than 35 hours per week',
                'unemployed' => 'Unemployed'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_employment_type',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_employment_type',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Size of employer
 * First function shows Size of employer
 * Second function controls visibility of Size of employer field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Size of Employer',
    'description' => 'Counting all locations where your employer operates, what is the total number of persons who work there?',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_employer_size',
            'attributes' => array(
                'min' => '1',
                'max' => '8',
                'step' => '1',
                'id' => 'persona_employer_size_range',
                'oninput' => 'employerSizeOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_employer_size',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_employer_size',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_employer_size_range" id="persona_employer_size_range_value">
                    <script>
                    </script>
                </output>
                <script>
                    function employerSizeOutputUpdate(eso)
                    {
                        if (eso == 1)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'1\';
                        }
                        else if (eso == 2)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'2 to 9\';
                        }
                        else if (eso == 3)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'10 to 24\';
                        }
                        else if (eso == 4)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'25 to 99\';
                        }
                        else if (eso == 5)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'100 to 499\';
                        }
                        else if (eso == 6)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'500 to 999\';
                        }
                        else if (eso == 7)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'1,000 to 4,999\';
                        }
                        else if (eso == 8)
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'5000+\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_employer_size_range_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_employer_size',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 12
        )
    )
));

/*
 * Type of employer
 * First function shows Type of Employment field
 * Second function controls visibility of Type of Employment field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Type of Employer',
    'description' => 'What best describes the type of organisation that your persona would work for?',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_type_of_employer',
            'choices' => array(
                'for_profit' => 'For Profit',
                'non_profit' => 'Not-For-Profit (regious, arts, social assistance, etc.)',
                'government' => 'Government',
                'health_care' => 'Health Care',
                'education' => 'Education',
                'other' => 'Other'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_type_of_employer',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_type_of_employer',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Decision making authority
 * First function shows Decision making authority field
 * Second function controls visibility of Decision making authority field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Decision Making Authority',
    'description' => 'What level of decision-making authority does your persona have to purchase your products or services for their organisation?',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_authority_range',
            'attributes' => array(
                'min' => '1',
                'max' => '4',
                'step' => '1',
                'id' => 'persona_authority_range',
                'oninput' => 'authorityOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_authority_range',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_authority_range',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_authority_range" id="persona_authority_range_value">
                    <script>
                    </script>
                </output>
                <script>
                    function authorityOutputUpdate(ar)
                    {
                        if (ar == 1)
                        {
                            document.querySelector(\'#persona_authority_range_value\').value = \'No Input\';
                        }
                        else if (ar == 2)
                        {
                            document.querySelector(\'#persona_authority_range_value\').value = \'Minimal Decision-Making or Influence\';
                        }
                        else if (ar == 3)
                        {
                            document.querySelector(\'#persona_authority_range_value\').value = \'Significant decision-making or influence (individually or as part of a group)\';
                        }
                        else if (ar == 4)
                        {
                            document.querySelector(\'#persona_authority_range_value\').value = \'Final decision-making authority (individually or as part of a group\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_authority_range_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_authority_range',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 12
        )
    )
));


/*
 * Job Level
 * First function shows Job Level field
 * Second function controls visibility of Job Level field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Job Level',
    'description' => 'Which of the following most closely matches your persona\'s job level?',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_job_level',
            'attributes' => array(
                'min' => '1',
                'max' => '9',
                'step' => '1',
                'id' => 'persona_job_level',
                'oninput' => 'jobLevelOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_job_level',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_job_level',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_job_level" id="persona_job_level_value">
                    <script>
                    </script>
                </output>
                <script>
                    function jobLevelOutputUpdate(pjl)
                    {
                        if (pjl == 1)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Intern\';
                        }
                        else if (pjl == 2)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Entry Level\';
                        }
                        else if (pjl == 3)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Analyst or Associate\';
                        }
                        else if (pjl == 4)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Manager\';
                        }
                        else if (pjl == 5)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Senior Manager\';
                        }
                        else if (pjl == 6)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Director\';
                        }
                        else if (pjl == 7)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Vice President\';
                        }
                        else if (pjl == 8)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'President or CEO\';
                        }
                        else if (pjl == 9)
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'Owner\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_job_level_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_job_level',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 12
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));
