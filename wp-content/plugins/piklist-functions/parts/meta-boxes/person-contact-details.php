<?php
/*
 * Title: Contact Details
 * Post Type: person
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'contact_addresses',
    'label' => __('Contact Addresses'),
    'description' => 'Enter the contact\'s address(es) here. Use + / - to add or remove addresses',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'select',
            'field' => 'contact_type',
            'choices' => array(
                'mobile_phone' => 'Mobile Phone',
                'email_address' => 'Email Address',
                'landline' => 'Landline',
                'website' => 'Website'
            )
        ),
        array(
            'type' => 'radio',
            'field' => 'personal_or_work',
            'choices' => array(
                'personal' => 'Personal',
                'work' => 'Work'
            )
        ),
        array(
            'type' => 'text',
            'field' => 'address',
        )
    )

));

piklist('field', array(
    'type' => 'group',
    'field' => 'postal_addresses',
    'label' => __('Postal Addresses'),
    'description' => 'Enter the contact\'s address(es) here. Use + / - to add or remove addresses',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'textarea',
            'field' => 'postal_address',
            'label' => 'Postal Address(es)',
            'attributes' => array(
                'class' => 'text'
            )
        ),
        array(
            'type' => 'radio',
            'field' => 'personal_or_work',
            'choices' => array(
                'personal' => 'Personal',
                'work' => 'Work'
            )
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'field' => 'events',
    'label' => __('Events'),
    'description' => 'Enter events like birthdays, sales anniversaries, etc. here. Use + / - to add or remove events',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'event_type',
            'label' => 'Event Type'
        ),
        array(
            'type' => 'datepicker',
            'field' => 'date',
            'label' => 'Date',
            'attributes' => array(
                'class' => 'text'
            )
        ),
        array(
            'type' => 'textarea',
            'field' => 'notes',
            'label' => 'Notes',
            'attributes' => array(
                'class' => 'text'
            )
        )
    )
));