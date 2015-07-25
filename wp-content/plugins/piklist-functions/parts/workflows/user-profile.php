<?php
/*
Title: Profile
Order: 10
Default: true
Flow: User
*/

piklist('include_user_profile_fields', array(
    'meta_boxes' => array(
        'Name',
        'Contact Info',
        'Beliefs & Values',
        'Skills',
        'Strategy Pane'
    )
));

  piklist('shared/code-locater', array(
    'location' => __FILE__
    ,'type' => 'Workflow Tab'
  ));

?>