<?php

$data = array();

$user_id = App\DB\get_user_id();

if ( isset($_POST['form_name']) && isset($_POST['tournaments']) ) {

    if ( $_POST['form_name'] === 'tournaments_join' ) {
        join_tournaments($user_id, $_POST['tournaments']);
    } else if ( $_POST['form_name'] === 'tournaments_leave' ) {
        leave_tournaments($user_id, $_POST['tournaments']);
    }

}

$data['matches'] = list_matches($user_id);

//$data['available'] = list_tournaments_available();

view_page($page, $data);
