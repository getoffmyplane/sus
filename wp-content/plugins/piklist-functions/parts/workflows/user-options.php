<?php
/*
Title: Options
Order: 20
Flow: User
*/

piklist('include_user_profile_fields', array(
    'meta_boxes' => array(
        'Personal Options',
        'About the user',
        'About Yourself'
    )
  ));

  piklist('shared/code-locater', array(
    'location' => __FILE__
    ,'type' => 'Workflow Tab'
  ));

?>