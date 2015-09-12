<?php
/*
Title: Strategy Pane
Capability: read
Context: side
Priority: high
*/

/*
 * Check if there is already a strategy running
 */

select_another_strategy();
check_if_strategy_running();

function select_another_strategy()
{
    //list strategies if select another strategy link clicked
    if(isset($_GET['sas']))
    {
        list_strategies();
    }
    else // show [select another strategy link]
    {
        echo '<div class="act-header-container">';
        echo '<div class="select-another-strategy"><a href="?sas=true"><img src="'.wp_get_attachment_url('726').'"/></a></div>';
    }
};

function check_if_strategy_running()
{
    if(!isset($_GET['sas']))
    {
        //get current user's id
        $user_id = get_current_user_id();
        //key = column name in user_meta table to check
        $key = 'currently_running_strategy_id';
        //get currently running strategy
        $crsid = get_user_meta($user_id, $key, true);
        //check if user has just selected a strategy and then set the user-meta accordingly
        if(isset($_GET['sid']))
        {
            $sid = $_GET['sid'];
            $currently_running_strategy_array = get_term_by('id', absint($sid), 'strategy');

            //set user meta data (for persistent running strategy) in database
            //check if get $sid is int otherwise fail (SQL injection)
            if(is_numeric($sid))
            {
                update_user_meta($user_id, $key, $sid);
            }
            else
            {
                wp_die("injection detected");
            }

            //echo out the strategy name
            $currently_running_strategy = $currently_running_strategy_array->name;
            echo '<div class="currently-running-strategy">'.$currently_running_strategy.'</div>';
            echo '</div>';
            list_activities($sid);
        }
        //check if the user is currently running a strategy
        else if($crsid)
        {
            $currently_running_strategy_array = get_term_by('id', absint($crsid), 'strategy');
            //echo out the strategy name
            $currently_running_strategy = $currently_running_strategy_array->name;
            echo '<div class="currently-running-strategy">'.$currently_running_strategy.'</div>';
            list_activities($crsid);
        }
        else
        {
            echo "No currently running strategy - please select a strategy from the list below:";
            //list strategies
            list_strategies();
        }
    }
}

function list_strategies()
{
    //clear current activity step in db
    update_user_meta(get_current_user_id(), 'current_activity_step_id', '');

    //list strategies
    $terms = get_terms( 'strategy' );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            echo '<div class="strategy-container">';
            // We successfully got a link. Print it out.
            // Strategy Title
            echo '<div class="strategy-list-item"><a href="?sid='.$term->term_id.'">' . $term->name . '</a></div>';
            // Strategy Icon
            echo '<div class="strategy-icon">';
            $image_ids = get_term_meta($term->term_id,$key = 'strategy_icon');
            foreach ($image_ids as $image_id)
            {
                echo '<a href="?sid='.$term->term_id.'"><img src="'.wp_get_attachment_url($image_id).'"/></a>';
            }
            echo '</div>';
            // Strategy Description
            echo '<div class="strategy-list-item-description">'.$term->description.'</div>';
            echo '</div>';
        }
    }
}

function list_activities($sid)
{
    $args = array(
        'posts_per_page'   => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'strategy',
                'terms'    => $sid
            ),
        ),
        'meta_key'         => 'activity_step',
        'orderby'          => 'meta_value meta_value_num',
        'order'            => 'ASC',
        'post_type'        => 'activity',
        'post_status'      => 'publish',
        'suppress_filters' => true
    );
    $activities = get_posts( $args );
    if ( ! empty( $activities ) && ! is_wp_error( $activities ) ){
        //get current user's id
        $user_id = get_current_user_id();
        //key = column name in user_meta table to check
        $key = 'current_activity_step_id';
        //get currently running strategy
        $casid = get_user_meta($user_id, $key, true);

        foreach ( $activities as $activity ) : setup_postdata( $activity );
            //check if currently running activity step in user meta == div id. if yes, show. if no, hide.
            if($casid == 'activity_step_'.$activity->activity_step)
            {
                //set as none - there is a bug here.
                //$display_toggle = '';
                $display_toggle = 'style="display: none;"';
            }
            else
            {
                $display_toggle = 'style="display: none;"';
            }
            // We successfully got an activity. Print it out.
            echo '<div class="activity-container">';

            echo '<div class="activity-header-container">';
            // Activity Icon
            echo '<div class="activity-icon">';
            $image_ids = get_post_meta($activity->ID,$key = 'activity_icon');
            foreach ($image_ids as $image_id)
            {
                //echo '<a href="?sid='.$activity->post_id.'"><img src="'.wp_get_attachment_url($image_id).'"/></a>';
                echo '<img src="'.wp_get_attachment_url($image_id).'"/>';
            }
            echo '</div>';

            // Activity name
            echo '<div class="activity-title" id="activity_step_'.$activity->activity_step.'">'.$activity->post_title.'</div>';
            echo '</div>';
            // Count number of paginated pages
            $num_pages = substr_count($activity->post_content, '<!--nextpage-->') + 1;
            //print_r($num_pages);

            // If pagination exists, add the page content with selectors at the bottom
            if ($num_pages > 1) {
                // break out each page of content into $pages array
                $pages = explode('<!--nextpage-->', $activity->post_content);
                //print_r($pages);
                echo '<div class="activity-content-container">';
                foreach ($pages as $key => $page)
                {
                    $page_num = $key + 1;
                    if($page_num == 1)
                    {
                        $display_toggle = '';
                    }
                    else
                    {
                        $display_toggle = 'style="display: none;"';
                    }
                    // echo page content
                    echo '<div class="activity-content" '.$display_toggle.' >'.$page;

                    // pagination buttons
                    // page x of y
                    echo '<div class="pagination"'.$display_toggle.'><span>Page '.$page_num.' of '.$num_pages.'</span>';
                    // first - to be implemented - see http://sgwordpress.com/teaches/how-to-add-wordpress-pagination-without-a-plugin/?utm_source=twitterfeed&utm_medium=twitter
                    // previous
                    if( $page_num > 1) echo '<span class="previous-page">Previous</span>';
                    // page numbers - to be implemented - see first
                    // next
                    if ($page_num != $num_pages) echo '<span class="next-page">Next</span>';
                    // last  - to be implemented - see first
                    echo '</div></div>';
                }
                echo '</div>';
            } else { // else just add content with no pagination
                echo '<div class="activity-content" '.$display_toggle.' >'.$activity->post_content.'</div>';
            }

            echo '</div>';
        endforeach;
        wp_reset_postdata();
    }
};

/*
 * If strategy is already running
 * check what step the user was on last time
 * If step is set, then display step
 * If step is not set, then display step 1
 */

/*
 * If there is no strategy running
 * Display strategy select menu
 */

/*
 * old, Piklist sample code below

include_once( ABSPATH . WPINC . '/feed.php' );

$rss = fetch_feed('http://piklist.com/feed/');

if (!is_wp_error($rss)) :

    $maxitems = $rss->get_item_quantity(5);

    $rss_items = $rss->get_items(0, $maxitems);

endif;
?>

    <div class="rss-widget">

        <ul>

            <?php if ($maxitems == 0) : ?>

                <li>

                    <?php _e('No items', 'piklist-demo'); ?>

                </li>

            <?php else : ?>

                <?php foreach ($rss_items as $item) : ?>

                    <?php $title = esc_html($item->get_title()); ?>

                    <?php $date = date_i18n(get_option('date_format'), $item->get_date('U')); ?>

                    <?php
                    $description = str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags( @html_entity_decode($item->get_description(), ENT_QUOTES, get_option('blog_charset')))));
                    $description = wp_html_excerpt( $description, 360 );

                    if ('[...]' == substr( $description, -5 ))
                    {
                        $description = substr($description, 0, -5) . '[&hellip;
                  ]';
                    }
                    elseif ('[&hellip;]' != substr($description, -10 ))
                    {
                        $description .= ' [&hellip;]';
                    }

                    $description = esc_html( $description );
                    ?>

                    <?php
                    $link = $item->get_link();
                    while (stristr($link, 'http') != $link)
                    {
                        $link = substr($link, 1);
                    }
                    $link = esc_url(strip_tags($link));
                    ?>

                    <li>

                        <a class='rsswidget' href='<?php echo esc_url($link); ?>' title='<?php echo $description;?>'>
                            <?php echo esc_html($title); ?>
                        </a>

                <span class="rss-date">
                  <?php echo esc_html($date); ?>
                </span>

                        <div class="rss-summary">
                            <?php echo esc_html($description); ?>
                        </div>

                    </li>

                <?php endforeach; ?>

            <?php endif; ?>

        </ul>

    </div>

    <hr>

<?php

*/

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Dashboard Widget'
));

?>