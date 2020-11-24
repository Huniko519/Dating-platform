<?php
Class Loadmore extends Aj {
    function random_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array  = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
                ''
            );
            $liked_user_array    = (array_keys(LikedUsers())) ? array_keys(LikedUsers()) : array(
                ''
            );
            $disliked_user_array = (array_keys(DisLikedUsers())) ? array_keys(DisLikedUsers()) : array(
                ''
            );
            $db->objectBuilder()->where('verified', '1')
                ->where('privacy_show_profile_random_users', '1')
                ->orWhere( 'is_boosted', '1' )
                ->orderBy('boosted_time','DESC')
                ->orderBy('xlikes_created_at', 'DESC')
                ->orderBy('xvisits_created_at', 'DESC')
                ->orderBy('user_buy_xvisits', 'DESC')
                ->orderBy('is_pro', 'DESC')
                ->orderBy('id', 'DESC');

            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('id', $blocked_user_array, 'NOT IN');
                }
            }
            if (is_array($liked_user_array)) {
                if (count($liked_user_array) > 0) {
                    $db->where('id', $liked_user_array, 'NOT IN');
                }
            }
            if (is_array($disliked_user_array)) {
                if (count($disliked_user_array) > 0) {
                    $db->where('id', $disliked_user_array, 'NOT IN');
                }
            }

            $random_users        = $db->get('users', array(
                $page * $perpage,
                $perpage
            ), array(
                'id',
                'online',
                'lastseen',
                'username',
                'avater',
                'country',
                'first_name',
                'last_name',
                'location',
                'birthday',
                'language',
                'relationship',
                'height',
                'body',
                'smoke',
                'ethnicity',
                'pets'
            ));
            $theme_path          = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template            = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'random_users.php';
            if (file_exists($template)) {
                foreach ($random_users as $random_user) {
                    ob_start();
                    require($template);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function liked_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
                ''
            );

            $db->objectBuilder()->join('users u', 'l.like_userid=u.id', 'LEFT')
                ->where('l.user_id', self::ActiveUser()->id)
                ->where('l.is_like', '1')
                ->where('u.verified', '1')
                ->where('l.like_userid', self::ActiveUser()->id, '<>')
                ->groupBy('l.like_userid')
                ->orderBy('l.created_at', 'DESC');
            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('l.like_userid', $blocked_user_array, 'NOT IN');
                }
            }
            $liked_users        = $db->get('likes l', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'liked.php';
            if (file_exists($template)) {
                foreach ($liked_users as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function likes_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
                ''
            );

            $db->objectBuilder()->join('users u', 'l.user_id=u.id', 'LEFT')
                ->where('l.like_userid', self::ActiveUser()->id)
                ->where('l.is_like', "1")
                ->where('l.user_id', self::ActiveUser()->id, '<>')
                ->groupBy('l.user_id')
                ->orderBy('l.created_at', 'DESC');

            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('l.user_id', $blocked_user_array, 'NOT IN');
                }
            }


            $liked_users        = $db->get('likes l', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'likes.php';
            if (file_exists($template)) {
                foreach ($liked_users as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function disliked_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
                ''
            );

            $db->objectBuilder()->join('users u', 'l.like_userid=u.id', 'LEFT')
                ->where('l.user_id', self::ActiveUser()->id)
                ->where('l.is_dislike', '1')
                ->where('u.verified', '1')
                ->where('l.like_userid', self::ActiveUser()->id, '<>')
                ->groupBy('l.like_userid')
                ->orderBy('l.created_at', 'DESC');

            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('l.like_userid', $blocked_user_array, 'NOT IN');
                }
            }

            $disliked_users     = $db->get('likes l', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($disliked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'disliked.php';
            if (file_exists($template)) {
                foreach ($disliked_users as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function visits() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array( '' );

            $db->objectBuilder()->join('users u', 'v.user_id=u.id', 'LEFT')
                ->where('v.view_userid', self::ActiveUser()->id)
                ->where('v.user_id', self::ActiveUser()->id, '<>')
                ->where('u.verified', '1')
                ->orderBy('v.created_at', 'DESC');

            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('v.user_id', $blocked_user_array, 'NOT IN');
                }
            }

            $visits             = $db->get('views v', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'v.created_at'
            ));
            foreach ($visits as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'visits.php';
            if (file_exists($template)) {
                foreach ($visits as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function match_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error     = '';
        $page      = 0;
        $perpage   = 7;
        $html      = '';
        $html_imgs = '';
        $template  = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $where_and = array();
            $distance  = '';
            if (isset($_SESSION[ '_lat' ]) && !empty($_SESSION[ '_lat' ]) && isset($_SESSION[ '_lng' ]) && !empty($_SESSION[ '_lng' ]) && !empty($_SESSION[ '_located' ])) {
                $distance    = 'ROUND( ( 6371 * acos(cos(radians(' . Secure($_SESSION[ '_lat' ]) . ')) * cos(radians(`lat`)) * cos(radians(`lng`) - radians(' . Secure($_SESSION[ '_lng' ]) . ')) + sin(radians(' . Secure($_SESSION[ '_lat' ]) . ')) * sin(radians(`lat`)))) ,1) ';
                $where_and[] = $distance . ' <= ' . Secure($_SESSION[ '_located' ]);
            }
            if (isset($_SESSION[ '_location' ]) && !empty($_SESSION[ '_location' ])) {
                $where_or[] = '`location` like "%' . Secure($_SESSION[ '_location' ]) . '%"';
            }
            if (isset($_SESSION[ '_age_from' ]) && !empty($_SESSION[ '_age_from' ]) && isset($_SESSION[ '_age_to' ]) && !empty($_SESSION[ '_age_to' ])) {
                $where_or[] = ' ( DATEDIFF(CURDATE(), `birthday`)/365 >= ' . Secure($_SESSION[ '_age_from' ]) . ' AND DATEDIFF(CURDATE(), `birthday`)/365 <= ' . Secure($_SESSION[ '_age_to' ]) . ' ) ';
            }
            if (isset($_SESSION[ '_gender' ]) && $_SESSION[ '_gender' ] !== '') {
                $gender = implode(',', $_SESSION[ '_gender' ]);
                if ($gender == '')
                    $gender = '0,1';
                if (strpos(Secure($gender), ',') === false) {
                    $where_and[] = '`gender` = "' . Secure($gender) . '"';
                } else {
                    $where_and[] = '`gender` IN (' . Secure($gender) . ')';
                }
            }
            $blocked_user_array = (array_keys(BlokedUsers(self::ActiveUser()->id))) ? array_keys(BlokedUsers(self::ActiveUser()->id)) : null;
            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $where_and[] = '`id` NOT IN (' . implode($blocked_user_array, ',') . ')';
                }
            }
            $liked_user_array = (array_keys(LikedUsers(self::ActiveUser()->id))) ? array_keys(LikedUsers(self::ActiveUser()->id)) : null;
            if (is_array($liked_user_array)) {
                if (count($liked_user_array) > 0) {
                    $where_and[] = '`id` NOT IN (' . implode($liked_user_array, ',') . ')';
                }
            }
            $disliked_user_array = (array_keys(DisLikedUsers(self::ActiveUser()->id))) ? array_keys(DisLikedUsers(self::ActiveUser()->id)) : null;
            if (is_array($disliked_user_array)) {
                if (count($disliked_user_array) > 0) {
                    $where_and[] = '`id` NOT IN (' . implode($disliked_user_array, ',') . ')';
                }
            }
            $where_and[] = '`id` <> "' . self::ActiveUser()->id . '"';
            $where_and[] = '`verified` = "1"';
            $where_and[] = '`privacy_show_profile_match_profiles` = "1"';
            $query       = 'SELECT DISTINCT `id` FROM `users`';
            $where       = '';
            if (!empty($where_and)) {
                $where = ' WHERE ' . implode($where_and, ' AND ');
            }
            $orderBy = 'ORDER BY ';
            $orderBy .= '`xlikes_created_at` DESC';
            $orderBy .= ',`boosted_time` DESC';
            $orderBy .= ',`is_boosted` DESC';
            $orderBy .= ',`is_pro` DESC';
            $orderBy .= ',`id` DESC';
            $query             = $query . $where . ' ' . $orderBy . ' LIMIT ' . $perpage . ' OFFSET ' . ($page * $perpage) . ';';



            if( self::ActiveUser()->show_me_to !== '' ) {
                $query = "SELECT DISTINCT `id` FROM `users` WHERE `id` <> " . self::ActiveUser()->id . " AND (`country` = '" . self::ActiveUser()->show_me_to ."' OR `show_me_to` = '" . self::ActiveUser()->show_me_to ."') ";
                if (is_array($blocked_user_array)) {
                    if (count($blocked_user_array) > 0) {
                        $query .= ' AND `id` NOT IN (' . implode($blocked_user_array, ',') . ') ';
                    }
                }
                if (is_array($liked_user_array)) {
                    if (count($liked_user_array) > 0) {
                        $query .= ' AND `id` NOT IN (' . implode($liked_user_array, ',') . ') ';
                    }
                }
                if (is_array($disliked_user_array)) {
                    if (count($disliked_user_array) > 0) {
                        $query .= ' AND `id` NOT IN (' . implode($disliked_user_array, ',') . ')';
                    }
                }
                $query .= ' AND `id` <> "' . self::ActiveUser()->id . '"';
                $query .= ' AND `verified` = "1"';
                $query .= ' AND `privacy_show_profile_match_profiles` = "1"';
                $orderBy = ' ORDER BY ';
                $orderBy .= '`xlikes_created_at` DESC';
                $orderBy .= ',`boosted_time` DESC';
                $orderBy .= ',`is_boosted` DESC';
                $orderBy .= ',`is_pro` DESC';
                $orderBy .= ',`id` DESC';
                $query = $query . $orderBy . ' LIMIT '.$perpage.' OFFSET '.($page * $perpage).';';
//        $where_or[] = '`show_me_to` = "' . $u->show_me_to .'"';
//        $where_or[] = '`country` = "' . $u->show_me_to .'"';
            }

            $limit  = $perpage;
            $offset = $page * $perpage;
            $query  = GetFindMatcheQuery(self::ActiveUser()->id, $limit, $offset);
            $match_users       = $db->rawQuery($query);
            $match_users_array = array();
            $_users            = LoadEndPointResource('users');
            if ($_users) {
                foreach ($match_users as $key => $value) {
                    $user             = $_users->get_user_profile($value[ 'id' ], array(
                        'id',
                        'online',
                        'lastseen',
                        'username',
                        'avater',
                        'country',
                        'first_name',
                        'last_name',
                        'birthday',
                        'language',
                        'relationship',
                        'height',
                        'body',
                        'smoke',
                        'ethnicity',
                        'pets'
                    ));
                    $media            = $user->mediafiles;
                    $user->mediafiles = array();
                    $img              = 0;
                    foreach ($media as $k => $v) {
                        if ($img < 4) {
                            $user->mediafiles[] = $v;
                        }
                        $img++;
                    }
                    $match_users_array[ $user->id ] = $user;
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'matches.php';
            $template1  = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'matches_imgs.php';
            if (file_exists($template)) {
                $matche_first     = false;
                $matche_img_first = false;
                if ($page == 0) {
                    $matche_first     = true;
                    $matche_img_first = true;
                }
                foreach ($match_users_array as $matche) {
                    ob_start();
                    include $template;
                    $matche_first = false;
                    $html .= ob_get_contents();
                    ob_end_clean();
                    ob_start();
                    include $template1;
                    $matche_img_first = false;
                    $html_imgs .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'html_imgs' => $html_imgs
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function interest() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error        = '';
        $page         = 0;
        $perpage      = 8;
        $interest_tag = '';
        $html         = '';
        $template     = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
            if (isset($_POST[ 'tags' ]) && !empty($_POST[ 'tags' ])) {
                $interest_tag = strtolower(Secure($_POST[ 'tags' ]));
            }
        }
        if ($interest_tag == '') {
            $interest_tag = Secure(route(2));
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
                ''
            );
            $liked_user_array   = (array_keys(LikedUsers())) ? array_keys(LikedUsers()) : array(
                ''
            );


            $db->objectBuilder()->where('verified', '1')
                ->where('interest', '%' . $interest_tag . '%', 'like')
                ->orderBy('xlikes_created_at', 'DESC')
                ->orderBy('boosted_time', 'DESC')
                ->orderBy('is_boosted', 'DESC')
                ->orderBy('is_pro', 'DESC');

            if (is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $db->where('id', $blocked_user_array, 'NOT IN');
                }
            }

            if (is_array($liked_user_array)) {
                if (count($liked_user_array) > 0) {
                    $db->where('id', $liked_user_array, 'NOT IN');
                }
            }


            $interest           = $db->get('users', array(
                $page * $perpage,
                $perpage
            ), array(
                'id',
                'online',
                'lastseen',
                'username',
                'avater',
                'country',
                'first_name',
                'last_name',
                'birthday',
                'language',
                'relationship',
                'height',
                'body',
                'smoke',
                'ethnicity',
                'pets'
            ));
            foreach ($interest as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'interest.php';
            if (file_exists($template)) {
                foreach ($interest as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function find_matches() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $data     = array();
        $last_id  = 0;
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST[ '_where' ])) {
            foreach (json_decode($_POST[ '_where' ]) as $key => $value) {
                if ($key !== 'page') {
                    $_POST[ $key ] = $value;
                }
            }
        }
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]);
            }
        }
        $limit  = $perpage;
        $offset = $page * $perpage;
        $query  = GetFindMatcheQuery(self::ActiveUser()->id, $limit, $offset);

        if (isset($_POST[ '_age_from' ])) {
            $_SESSION[ '_age_from' ] = (int) $_POST[ '_age_from' ];
        }
        if (isset($_POST[ '_age_to' ])) {
            $_SESSION[ '_age_to' ] = (int) $_POST[ '_age_to' ];
        }
        if (isset($_POST[ '_located' ])) {
            $_SESSION[ '_located' ] = (int) $_POST[ '_located' ];
        }
        if (isset($_POST[ '_gender' ])) {
            $d = array();
            $c = explode(',', $_POST[ '_gender' ]);
            foreach ($c as $key) {
                $d[ $key ] = $key;
            }
            $_SESSION[ '_gender' ] = $d;
        }
        $data       = $db->objectBuilder()->rawQuery($query);
        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'search.php';
        if (file_exists($template)) {
            foreach ($data as $row) {
                ob_start();
                include $template;
                $html .= ob_get_contents();
                ob_end_clean();
            }
        }
        if ($error == '') {
            return array(
                'status' => 200,
                'page' => $page + 1,
                'post' => json_encode($_POST),
                'query' => $query,
                'html' => $html,
                'where' => (isset($_POST[ '_where' ]) ? json_decode($_POST[ '_where' ]) : '')
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function matches() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array();
            $notin = '';
            if(is_array($blocked_user_array)) {
                if (count($blocked_user_array) > 0) {
                    $notin .= ' users.`id` NOT IN (' . implode($blocked_user_array, ',') . ') AND ';
                }
            }

            $sql = 'SELECT 
              users.id,
              users.username,
              users.avater,
              users.country,
              users.first_name,
              users.last_name,
              users.location,
              users.birthday,
              users.language,
              notifications.created_at,
              users.relationship,
              users.pets,
              users.ethnicity,
              users.smoke,
              users.height,
              users.online,
              users.lastseen
            FROM
              users
              INNER JOIN notifications ON (users.id = notifications.recipient_id)
            WHERE
              '. $notin .'
              notifications.notifier_id = ' . self::ActiveUser()->id . ' AND 
              notifications.`type` = \'got_new_match\' AND 
              users.verified = \'1\' AND 
              notifications.recipient_id <> ' . self::ActiveUser()->id . '
            ORDER BY
              notifications.created_at DESC
            LIMIT ' . $perpage . ' OFFSET ' . $page * $perpage;

            $matches            = $db->objectBuilder()->rawQuery($sql);
            foreach ($matches as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'matches.php';
            if (file_exists($template)) {
                foreach ($matches as $row) {
                    ob_start();
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
}