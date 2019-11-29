<?php
global $fael_editor_actions;

$fael_editor_actions = [
    'fael_post_author' => [
        'value' => function() {
            $this->users = get_users();
            $users = array();
            foreach ( $this->users as $k => $user ) {
                $users[$user->ID] = $user->user_nicename;
            }
            return $users;
        }
    ],
    'fael_user_list' => [
        'post_type' => function() {
            return get_post_types(array(
                'public' => true
            ));
        }
    ],
    'fael_form' => [
        'form' => function() {
            $fael_forms = get_posts(array(
                'post_type' => 'fael_form',
                'post_status' => 'publish'
            ));

            $forms = [];
            foreach ( $fael_forms as $k => $form ) {
                $forms[$form->ID] = $form->post_title;
            }
            return $forms;
        }
    ],
    'fael_post_list' => [
        'post_type' => function() {
            return get_post_types(array(
                'public' => true
            ));
        },
        'post_status' => function() {
            return get_post_statuses();
        }
    ],
    'fael_post_status' => [
        'value' => function() {
            return get_post_statuses();
        }
    ],
    'fael_submit' => [
        'post_type' => function() {
            return get_post_types(array(
                'public' => true
            ));
        }
    ]
];