<?php
/*
 * Title: Strategy Pane
 * Order: 1
 * Context: side
*/

/*
 * Check if there is already a strategy running
 */

//select_another_strategy();
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
        echo '<div class="select_another_strategy"><a href="?sas=true">[Select another strategy]</a></div>';
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
            echo "The currently running strategy is: ".$currently_running_strategy;
            list_activities($sid);
        }
        //check if the user is currently running a strategy
        else if($crsid)
        {
            $currently_running_strategy_array = get_term_by('id', absint($crsid), 'strategy');
            //echo out the strategy name
            $currently_running_strategy = $currently_running_strategy_array->name;
            echo "The currently running strategy is: ".$currently_running_strategy;
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
        echo '<ul>';
        foreach ( $terms as $term ) {

            // We successfully got a link. Print it out.
            echo '<li><div class="strategy-list-item"><a href="?sid='.$term->term_id.'">' . $term->name . '</a></div></li>';
            echo '<div class="strategy-list-item-description">'.$term->description.'</div>';
        }
        echo '</ul>';
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

        echo '<ul>';
        foreach ( $activities as $activity ) : setup_postdata( $activity );
            //check if currently running activity step in user meta == div id. if yes, show. if no, hide.
            if($casid == 'activity_step_'.$activity->activity_step)
            {
                $display_toggle = '';
            }
            else
            {
                $display_toggle = 'style="display: none;"';
            }
            // We successfully got an activity. Print it out.
            echo '<li><div class="activity-title" id="activity_step_'.$activity->activity_step.'">'.$activity->activity_step.' - '.$activity->post_title.'</div>';
            echo '<div class="activity-content" '.$display_toggle.' >'.$activity->post_content.'</div></li>';
            //print_r($activity);
        endforeach;
        echo '</ul>';
        wp_reset_postdata();
    }
};

?>