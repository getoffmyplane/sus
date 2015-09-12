<?php
/*
Title: Demographics
Post Type: persona
Order: 20
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
 * Age Range
 * First function shows Age Range
 * Second function controls visibility of age range field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Age Range',
    'description' => 'Enter your persona\'s age range',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_age_range',
            'attributes' => array(
                'min' => '10',
                'max' => '70',
                'step' => '10',
                'id' => 'persona_age_range',
                'oninput' => 'ageOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_age_range',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_age_range',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_age_range" id="persona_age_range_value">
                    <script>
                    </script>
                </output>
                <script>
                    function ageOutputUpdate(par)
                    {
                        if (par == 10)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'Under 18\';
                        }
                        else if (par == 20)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'18 to 24\';
                        }
                        else if (par == 30)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'25 to 34\';
                        }
                        else if (par == 40)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'35 to 44\';
                        }
                        else if (par == 50)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'45 to 54\';
                        }
                        else if (par == 60)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'55 to 64\';
                        }
                        else if (par == 70)
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'65 or older\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_age_range_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_age_range',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 12
        )
    )
));

/*
 * Gender
 * First function shows gender field
 * Second function controls visibility of gender field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Gender',
    'description' => 'Select a gender for your persona',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_gender',
            'choices' => array(
                'male' => 'Male',
                'female' => 'Female'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_gender',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_gender',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Marital Status
 * First function shows gender field
 * Second function controls visibility of gender field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Marital Status',
    'description' => 'Select a gender for your persona',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_marital_status',
            'choices' => array(
                'single' => 'Single (never married)',
                'married' => 'Married',
                'seperated' => 'Separated',
                'divorced' => 'Divorced',
                'widowed' => 'Widowed'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_marital_status',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_marital_status',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Sexual Orientation
 * First function shows Sexual Orientation field
 * Second function controls visibility of Sexual Orientation field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Sexual Orientation',
    'description' => 'Select a sexual orientation for your persona',
    'fields' => array(
        array(
            'type' => 'radio',
            'field' => 'persona_sexual_orientation',
            'choices' => array(
                'heterosexual' => 'Heterosexual',
                'homosexual' => 'Homosexual',
                'bisexual' => 'Bisexual',
                'transsexual' => 'Transsexual',
                'curious' => 'Curious'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_sexual_orientation',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_sexual_orientation',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )
    )
));

/*
 * Nationality
 * First function shows Nationality field
 * Second function controls visibility of Nationality field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Location(s)',
    'description' => 'Enter the area(s) where the persona would be. These can be physical, virtual, etc. Use + / - to add or remove locations',
    'fields' => array(
        array(
            'type' => 'group',
            'field' => 'persona_locations',
            'add_more' => true,
            'fields' => array(
                array(
                    'type' => 'text',
                    'field' => 'location_name',
                    'attributes' => array(
                        'class' => 'text',
                        'placeholder' => 'Location Name'
                    ),
                    'columns' => 10
                ),
                array(
                    'type' => 'radio',
                    'field' => 'location_type',
                    'choices' => array(
                        'urban' => 'Urban',
                        'suburban' => 'Suburban',
                        'town' => 'Town',
                        'village' => 'Village',
                        'open_countryside' => 'Open Countryside',
                        'virtual' => 'Virtual'
                    ),
                    'columns' => 5
                ),
                array(
                    'type' => 'radio',
                    'field' => 'location_public_private',
                    'choices' => array(
                        'private' => 'Private (residence, etc.)',
                        'public' => 'Public (open to the public)',
                        'work' => 'Work'
                    ),
                    'columns' => 5
                )
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_locations',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            )
        )/*,
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_locations',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )*/
    )
));

/*
 * Nationality
 * First function shows Nationality field
 * Second function controls visibility of Nationality field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Persona Nationality',
    'description' => 'Enter the persona\'s nationalities here. Use + / - to add or remove nationalities',
    'fields' => array(
        array(
            'type' => 'select',
            'field' => 'persona_nationalities',
            'choices' => array(
                '' => '',
                'afghanistan' => 'Afghanistan',
                'albania' => 'Albania',
                'algeria' => 'Algeria',
                'american_samoa' => 'American Samoa',
                'andorra' => 'Andorra',
                'angola' => 'Angola',
                'anguilla' => 'Anguilla',
                'antarctica' => 'Antarctica',
                'antigua_and_barbuda' => 'Antigua and Barbuda',
                'argentina' => 'Argentina',
                'armenia' => 'Armenia',
                'aruba' => 'Aruba',
                'australia' => 'Australia',
                'austria' => 'Austria',
                'azerbaijan' => 'Azerbaijan',
                'bahamas' => 'Bahamas',
                'bahrain' => 'Bahrain',
                'bangladesh' => 'Bangladesh',
                'barbados' => 'Barbados',
                'belarus' => 'Belarus',
                'belgium' => 'Belgium',
                'belize' => 'Belize',
                'benin' => 'Benin',
                'bermuda' => 'Bermuda',
                'bhutan' => 'Bhutan',
                'bolivia' => 'Bolivia',
                'bosnia_and_herzegovina' => 'Bosnia and Herzegovina',
                'botswana' => 'Botswana',
                'brazil' => 'Brazil',
                'brunei_darussalam' => 'Brunei Darussalam',
                'bulgaria' => 'Bulgaria',
                'burkina_faso' => 'Burkina Faso',
                'burundi' => 'Burundi',
                'cambodia' => 'Cambodia',
                'cameroon' => 'Cameroon',
                'canada' => 'Canada',
                'cape_verde' => 'Cape Verde',
                'cayman_islands' => 'Cayman Islands',
                'central_african_republic' => 'Central African Republic',
                'chad' => 'Chad',
                'chile' => 'Chile',
                'china' => 'China',
                'christmas_island' => 'Christmas Island',
                'cocos_(keeling)_islands' => 'Cocos (Keeling) Islands',
                'colombia' => 'Colombia',
                'comoros' => 'Comoros',
                'congo,_republic_of_(brazzaville)' => 'Congo, Republic of (Brazzaville)',
                'cook_islands' => 'Cook Islands',
                'costa_rica' => 'Costa Rica',
                'croatia' => 'Croatia',
                'cuba' => 'Cuba',
                'cyprus' => 'Cyprus',
                'czech_republic' => 'Czech Republic',
                'democratic_republic_of_the_congo_(kinshasa)' => 'Democratic Republic of the Congo (Kinshasa)',
                'denmark' => 'Denmark',
                'djibouti' => 'Djibouti',
                'dominica' => 'Dominica',
                'dominican_republic' => 'Dominican Republic',
                'east_timor_(timor-leste)' => 'East Timor (Timor-Leste)',
                'ecuador' => 'Ecuador',
                'egypt' => 'Egypt',
                'el_salvador' => 'El Salvador',
                'equatorial_guinea' => 'Equatorial Guinea',
                'eritrea' => 'Eritrea',
                'estonia' => 'Estonia',
                'ethiopia' => 'Ethiopia',
                'falkland_islands' => 'Falkland Islands',
                'faroe_islands' => 'Faroe Islands',
                'fiji' => 'Fiji',
                'finland' => 'Finland',
                'france' => 'France',
                'french_guiana' => 'French Guiana',
                'french_polynesia' => 'French Polynesia',
                'french_southern_territories' => 'French Southern Territories',
                'gabon' => 'Gabon',
                'gambia' => 'Gambia',
                'georgia' => 'Georgia',
                'germany' => 'Germany',
                'ghana' => 'Ghana',
                'gibraltar' => 'Gibraltar',
                'great_britain' => 'Great Britain',
                'greece' => 'Greece',
                'greenland' => 'Greenland',
                'grenada' => 'Grenada',
                'guadeloupe' => 'Guadeloupe',
                'guam' => 'Guam',
                'guatemala' => 'Guatemala',
                'guinea' => 'Guinea',
                'guinea-bissau' => 'Guinea-Bissau',
                'guyana' => 'Guyana',
                'haiti' => 'Haiti',
                'holy_see' => 'Holy See',
                'honduras' => 'Honduras',
                'hong_kong' => 'Hong Kong',
                'hungary' => 'Hungary',
                'iceland' => 'Iceland',
                'india' => 'India',
                'indonesia' => 'Indonesia',
                'iran_(islamic_republic_of)' => 'Iran (Islamic Republic of)',
                'iraq' => 'Iraq',
                'ireland' => 'Ireland',
                'israel' => 'Israel',
                'italy' => 'Italy',
                'ivory_coast' => 'Ivory Coast',
                'jamaica' => 'Jamaica',
                'japan' => 'Japan',
                'jordan' => 'Jordan',
                'kazakhstan' => 'Kazakhstan',
                'kenya' => 'Kenya',
                'kiribati' => 'Kiribati',
                'korea,_democratic_people\'s_rep._(north_korea)' => 'Korea, Democratic People\'s Rep. (North Korea)',
                'korea,_republic_of_(south_korea)' => 'Korea, Republic of (South Korea)',
                'kosovo' => 'Kosovo',
                'kuwait' => 'Kuwait',
                'kyrgyzstan' => 'Kyrgyzstan',
                'lao,_people\'s_democratic_republic' => 'Lao, People\'s Democratic Republic',
                'latvia' => 'Latvia',
                'lebanon' => 'Lebanon',
                'lesotho' => 'Lesotho',
                'liberia' => 'Liberia',
                'libya' => 'Libya',
                'liechtenstein' => 'Liechtenstein',
                'lithuania' => 'Lithuania',
                'luxembourg' => 'Luxembourg',
                'macau' => 'Macau',
                'micronesia,_federal_states_of' => 'Micronesia, Federal States of',
                'moldova,_republic_of' => 'Moldova, Republic of',
                'monaco' => 'Monaco',
                'mongolia' => 'Mongolia',
                'montenegro' => 'Montenegro',
                'montserrat' => 'Montserrat',
                'morocco' => 'Morocco',
                'mozambique' => 'Mozambique',
                'myanmar,_burma' => 'Myanmar, Burma',
                'namibia' => 'Namibia',
                'nauru' => 'Nauru',
                'nepal' => 'Nepal',
                'netherlands' => 'Netherlands',
                'netherlands_antilles' => 'Netherlands Antilles',
                'new_caledonia' => 'New Caledonia',
                'new_zealand' => 'New Zealand',
                'nicaragua' => 'Nicaragua',
                'niger' => 'Niger',
                'nigeria' => 'Nigeria',
                'niue' => 'Niue',
                'northern_mariana_islands' => 'Northern Mariana Islands',
                'norway' => 'Norway',
                'oman' => 'Oman',
                'pakistan' => 'Pakistan',
                'palau' => 'Palau',
                'palestinian_territories' => 'Palestinian territories',
                'panama' => 'Panama',
                'papua_new_guinea' => 'Papua New Guinea',
                'paraguay' => 'Paraguay',
                'peru' => 'Peru',
                'philippines' => 'Philippines',
                'pitcairn_island' => 'Pitcairn Island',
                'poland' => 'Poland',
                'portugal' => 'Portugal',
                'puerto_rico' => 'Puerto Rico',
                'qatar' => 'Qatar',
                'reunion_island' => 'Reunion Island',
                'romania' => 'Romania',
                'russian_federation' => 'Russian Federation',
                'rwanda' => 'Rwanda',
                'saint_kitts_and_nevis' => 'Saint Kitts and Nevis',
                'saint_lucia' => 'Saint Lucia',
                'saint_vincent_and_the_grenadines' => 'Saint Vincent and the Grenadines',
                'samoa' => 'Samoa',
                'san_marino' => 'San Marino',
                'sao_tome_and_principe' => 'Sao Tome and Principe',
                'saudi_arabia' => 'Saudi Arabia',
                'senegal' => 'Senegal',
                'serbia' => 'Serbia',
                'seychelles' => 'Seychelles',
                'sierra_leone' => 'Sierra Leone',
                'singapore' => 'Singapore',
                'slovakia_(slovak_republic)' => 'Slovakia (Slovak Republic)',
                'slovenia' => 'Slovenia',
                'solomon_islands' => 'Solomon Islands',
                'somalia' => 'Somalia',
                'south_africa' => 'South Africa',
                'south_sudan' => 'South Sudan',
                'spain' => 'Spain',
                'sri_lanka' => 'Sri Lanka',
                'sudan' => 'Sudan',
                'suriname' => 'Suriname',
                'swaziland' => 'Swaziland',
                'sweden' => 'Sweden',
                'switzerland' => 'Switzerland',
                'syria,_syrian_arab_republic' => 'Syria, Syrian Arab Republic',
                'taiwan_(republic_of_china)' => 'Taiwan (Republic of China)',
                'tajikistan' => 'Tajikistan',
                'tanzania;_officially_the_united_republic_of_tanzania' => 'Tanzania; officially the United Republic of Tanzania',
                'thailand' => 'Thailand',
                'tibet' => 'Tibet',
                'timor-leste_(east_timor)' => 'Timor-Leste (East Timor)',
                'togo' => 'Togo',
                'tokelau' => 'Tokelau',
                'tonga' => 'Tonga',
                'trinidad_and_tobago' => 'Trinidad and Tobago',
                'tunisia' => 'Tunisia',
                'turkey' => 'Turkey',
                'turkmenistan' => 'Turkmenistan',
                'turks_and_caicos_islands' => 'Turks and Caicos Islands',
                'tuvalu' => 'Tuvalu',
                'ugandax' => 'Ugandax',
                'ukraine' => 'Ukraine',
                'united_arab_emirates' => 'United Arab Emirates',
                'united_kingdom' => 'United Kingdom',
                'united_states' => 'United States',
                'uruguay' => 'Uruguay',
                'uzbekistan' => 'Uzbekistan',
                'vanuatu' => 'Vanuatu',
                'vatican_city_state (holy see)' => 'Vatican City State (Holy See)',
                'venezuela' => 'Venezuela',
                'vietnam' => 'Vietnam',
                'virgin_islands_(british)' => 'Virgin Islands (British)',
                'virgin_islands_(u.s.)' => 'Virgin Islands (U.S.)',
                'wallis_and_futuna_islands' => 'Wallis and Futuna Islands',
                'western_sahara' => 'Western Sahara',
                'yemen' => 'Yemen',
                'zambia' => 'Zambia',
                'zimbabwe' => 'Zimbabwe'
            ),
            'add_more' => true,
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_nationalities',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        )/*,
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_nationalities',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        )*/
    )
));

/*
 * Income Range
 * First function shows Income Range
 * Second function controls visibility of Income range field
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Household Income (Annual Gross)',
    'description' => 'Enter your persona\'s income range',
    'fields' => array(
        array(
            'type' => 'range',
            'field' => 'persona_income_range',
            'attributes' => array(
                'min' => '1',
                'max' => '8',
                'step' => '1',
                'id' => 'persona_income_range',
                'oninput' => 'incomeOutputUpdate(value)'
            ),
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_income_range',
                    'value' => 'irrelevant',
                    'compare' => '!='
                )
            ),
            'columns' => 10
        ),
        array(
            'type' => 'checkbox',
            'field' => 'show_hide_persona_income_range',
            'choices' => array(
                'irrelevant' => 'Flag as irrelevant'
            ),
            'columns' => 2
        ),
        array(
            'type' => 'html',
            'value' =>
                '<output for="persona_income_range" id="persona_income_range_value">
                    <script>
                    </script>
                </output>
                <script>
                    function incomeOutputUpdate(pir)
                    {
                        if (pir == 1)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'Less than 25,000\';
                        }
                        else if (pir == 2)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'25,000 to 34,999\';
                        }
                        else if (pir == 3)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'35,000 to 49,999\';
                        }
                        else if (pir == 4)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'50,000 to 74,999\';
                        }
                        else if (pir == 5)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'75,000 to 99,999\';
                        }
                        else if (pir == 6)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'100,000 to 149,999\';
                        }
                        else if (pir == 7)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'149,999 to 199,999\';
                        }
                        else if (pir == 8)
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'200,000 or more\';
                        }
                        else
                        {
                            document.querySelector(\'#persona_income_range_value\').value = \'A Programming Error Has Occurred\';
                        }
                    }
                </script>',
            'conditions' => array(
                array(
                    'field' => 'show_hide_persona_income_range',
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
