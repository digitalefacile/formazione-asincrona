<?php
/*
@ccnRef: @theme_edumy/layout
*/

defined('MOODLE_INTERNAL') || die();
$templatecontext = [
    'lang_menu' =>  $langMenu,
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'ccnLogoUrl' => $ccnLogoUrl,
    'pageheading' => format_text($pageheading, FORMAT_HTML, array('filter' => true)),
    'sidepreblocks' => $blockshtml,
    'ccn_login' => $_ccnlogin,
    // 'ccn_registration' => $_ccnregistration,
    'ccn_activitynav' => $_ccnCourseSectionNav,
    'ccn_course_url' => $ccnCourseUrl,
    'ccn_dashboard_url' => $CFG->wwwroot . '/my',
    'ccn_globalsearch' => $_ccnglobalsearch,
    'ccn_globalsearch_navbar' => $_ccnglobalsearch_navbar,
    'ccn_librarylist' => $_ccnlibrarylist,
    'library_list_blocks' => $OUTPUT->blocks('library-list'),
    'has_library_list_blocks' => strpos($OUTPUT->blocks('library-list'), 'data-block=') !== false,
    'navbar_blocks' => $OUTPUT->blocks('navbar'),
    'has_navbar_blocks' => strpos($OUTPUT->blocks('navbar'), 'data-block=') !== false,
    'leftblocks' => $leftblocks,
    'hasblocks' => $hasblocks,
    'hasleftblocks' => $hasleftblocks,
    'hassideblocks' => $hassideblocks,
    'sidebar_single' => $sidebar_single,
    'sidebar_single_left' => $sidebar_single_left,
    'sidebar_single_right' => $sidebar_single_right,
    'sidebar_left' => $sidebar_left,
    'sidebar_right' => $sidebar_right,
    'sidebar_double' => $sidebar_double,
    'sidebar_none' => $sidebar_none,
    'participant_user_profile' => $userProfileFromCourseParticipants,
    'bodyattributes' => $bodyattributes,
    'headerlogo1' => $headerlogo1,
    'headerlogo2' => $headerlogo2,
    'headerlogo3' => $headerlogo3,
    'headerlogo4' => $headerlogo4,
    'headerlogo_mobile' => $headerlogo_mobile,
    'footerlogo1' => $footerlogo1,
    'heading_bg' => $heading_bg,
    'favicon' => $favicon,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'loginblocks' => $loginblocks,
    'hasloginblocks' => !empty($loginblocks),
    'searchblocks' => $searchblocks,
    'hassearchblocks' => !empty($searchblocks),
    'blocks_user_notifications' => $blocks_user_notifications,
    'blocks_user_messages' => $blocks_user_messages,
    'blocks_fullwidth_top' => $blocks_fullwidth_top,
    'blocks_fullwidth_bottom' => $blocks_fullwidth_bottom,
    'blocks_above_content' => $blocks_above_content,
    'has_blocks_above_content' => !empty($blocks_above_content),
    'blocks_below_content' => $blocks_below_content,
    'has_blocks_below_content' => !empty($blocks_below_content),
    'has_blocks_fullwidth_top' => !empty($blocks_fullwidth_top),
    'has_blocks_fullwidth_bottom' => !empty($blocks_fullwidth_bottom),
    'is_course' => $PAGE->bodyid == 'page-course-view-topics' || $PAGE->bodyid == 'page-course-view-tiles',
    'is_blog' => $PAGE->bodyid == 'page-blog-index',
    'is_frontpage' => $PAGE->bodyid == 'page-site-index',
    'courseid' => $courseid,
    'coursefullname' => $coursefullname,
    'courseshortname' => $courseshortname,
    'coursecategory' => $coursecategory,
    'coursesummary' => $coursesummary,
    'courseformat' => $courseformat,
    'coursecreated' => $coursecreated,
    'coursemodified' => $coursemodified,
    'coursestartdate' => $coursestartdate,
    'courseenddate' => $courseenddate,
    'numberofusers' => $numberofusers,
    'cocoon_facebook_url' => format_text(get_config('theme_edumy', 'cocoon_facebook_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_twitter_url' => format_text(get_config('theme_edumy', 'cocoon_twitter_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_instagram_url' => format_text(get_config('theme_edumy', 'cocoon_instagram_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_pinterest_url' => format_text(get_config('theme_edumy', 'cocoon_pinterest_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_dribbble_url' => format_text(get_config('theme_edumy', 'cocoon_dribbble_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_google_url' => format_text(get_config('theme_edumy', 'cocoon_google_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_youtube_url' => format_text(get_config('theme_edumy', 'cocoon_youtube_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_vk_url' => format_text(get_config('theme_edumy', 'cocoon_vk_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_500px_url' => format_text(get_config('theme_edumy', 'cocoon_500px_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_behance_url' => format_text(get_config('theme_edumy', 'cocoon_behance_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_digg_url' => format_text(get_config('theme_edumy', 'cocoon_digg_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_flickr_url' => format_text(get_config('theme_edumy', 'cocoon_flickr_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_foursquare_url' => format_text(get_config('theme_edumy', 'cocoon_foursquare_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_linkedin_url' => format_text(get_config('theme_edumy', 'cocoon_linkedin_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_medium_url' => format_text(get_config('theme_edumy', 'cocoon_medium_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_meetup_url' => format_text(get_config('theme_edumy', 'cocoon_meetup_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_snapchat_url' => format_text(get_config('theme_edumy', 'cocoon_snapchat_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_tumblr_url' => format_text(get_config('theme_edumy', 'cocoon_tumblr_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_vimeo_url' => format_text(get_config('theme_edumy', 'cocoon_vimeo_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_wechat_url' => format_text(get_config('theme_edumy', 'cocoon_wechat_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_whatsapp_url' => format_text(get_config('theme_edumy', 'cocoon_whatsapp_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_wordpress_url' => format_text(get_config('theme_edumy', 'cocoon_wordpress_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_weibo_url' => format_text(get_config('theme_edumy', 'cocoon_weibo_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_telegram_url' => format_text(get_config('theme_edumy', 'cocoon_telegram_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_moodle_url' => format_text(get_config('theme_edumy', 'cocoon_moodle_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_amazon_url' => format_text(get_config('theme_edumy', 'cocoon_amazon_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_slideshare_url' => format_text(get_config('theme_edumy', 'cocoon_slideshare_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_soundcloud_url' => format_text(get_config('theme_edumy', 'cocoon_soundcloud_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_leanpub_url' => format_text(get_config('theme_edumy', 'cocoon_leanpub_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_xing_url' => format_text(get_config('theme_edumy', 'cocoon_xing_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_bitcoin_url' => format_text(get_config('theme_edumy', 'cocoon_bitcoin_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_twitch_url' => format_text(get_config('theme_edumy', 'cocoon_twitch_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_github_url' => format_text(get_config('theme_edumy', 'cocoon_github_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_gitlab_url' => format_text(get_config('theme_edumy', 'cocoon_gitlab_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_forumbee_url' => format_text(get_config('theme_edumy', 'cocoon_forumbee_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_trello_url' => format_text(get_config('theme_edumy', 'cocoon_trello_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_weixin_url' => format_text(get_config('theme_edumy', 'cocoon_weixin_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_slack_url' => format_text(get_config('theme_edumy', 'cocoon_slack_url'), FORMAT_HTML, array('filter' => true)),
    'cocoon_copyright' => format_text(get_config('theme_edumy', 'cocoon_copyright'), FORMAT_HTML, array('filter' => true)),
    'footer_col_1_title' => format_text(get_config('theme_edumy', 'footer_col_1_title'), FORMAT_HTML, array('filter' => true)),
    'footer_col_1_body' => format_text(get_config('theme_edumy', 'footer_col_1_body'), FORMAT_HTML, array('filter' => true, 'noclean' => true)),
    'footer_col_2_title' => format_text(get_config('theme_edumy', 'footer_col_2_title'), FORMAT_HTML, array('filter' => true)),
    'footer_col_2_body' => format_text(get_config('theme_edumy', 'footer_col_2_body'), FORMAT_HTML, array('filter' => true, 'noclean' => true)),
    'footer_col_3_title' => format_text(get_config('theme_edumy', 'footer_col_3_title'), FORMAT_HTML, array('filter' => true)),
    'footer_col_3_body' => format_text(get_config('theme_edumy', 'footer_col_3_body'), FORMAT_HTML, array('filter' => true, 'noclean' => true)),
    'footer_col_4_title' => format_text(get_config('theme_edumy', 'footer_col_4_title'), FORMAT_HTML, array('filter' => true)),
    'footer_col_4_body' => format_text(get_config('theme_edumy', 'footer_col_4_body'), FORMAT_HTML, array('filter' => true, 'noclean' => true)),
    'footer_col_5_title' => format_text(get_config('theme_edumy', 'footer_col_5_title'), FORMAT_HTML, array('filter' => true)),
    'footer_col_5_body' => format_text(get_config('theme_edumy', 'footer_col_5_body'), FORMAT_HTML, array('filter' => true, 'noclean' => true)),
    'footer_menu' => format_text(get_config('theme_edumy', 'footer_menu'), FORMAT_HTML, array('filter' => true)),
    'footer_column_1' => $footer_column_1,
    'footer_column_2' => $footer_column_2,
    'footer_column_3' => $footer_column_3,
    'footer_column_4' => $footer_column_4,
    'footer_column_5' => $footer_column_5,
    'footer_col_1_class' => $footer_col_1_class,
    'footer_col_2_class' => $footer_col_2_class,
    'footer_col_3_class' => $footer_col_3_class,
    'footer_col_4_class' => $footer_col_4_class,
    'footer_col_5_class' => $footer_col_5_class,
    'cta_text' => format_text(get_config('theme_edumy', 'cta_text'), FORMAT_HTML, array('filter' => true)),
    'cta_link' => format_text(get_config('theme_edumy', 'cta_link'), FORMAT_HTML, array('filter' => true)),
    'cta_icon' => ($ccnIcon = get_config('theme_edumy', 'cta_icon_ccn_icon_class')) ? $ccnIcon : 'flaticon-megaphone',
    'email_address' => format_text(get_config('theme_edumy', 'email_address'), FORMAT_HTML, array('filter' => true)),
    'phone' => format_text(get_config('theme_edumy', 'phone'), FORMAT_HTML, array('filter' => true)),
    'custom_css' => $custom_css,
    'custom_css_dashboard' => $custom_css_dashboard,
    'custom_js' => $custom_js,
    'custom_js_dashboard' => $custom_js_dashboard,
    'display_library_list' => $display_library_list,
    'logotype' => $logotype,
    'logo_image' => $logo_image,
    'logo' => $logo,
    'logotype_footer' => $logotype_footer,
    'logo_image_footer' => $logo_image_footer,
    'logo_footer' => $logo_footer,
    'user_profile_picture' => new moodle_url('/user/pix.php/'.$USER->id.'/f1.jpg'),
    'profile_icon_username' => $ccnProfileIconUsername,
    'user_username' => $USER->username,
    'user_firstname' => $USER->firstname,
    'initial'=>substr( $USER->firstname, 0, 1).substr( $USER->lastname, 0, 1),
    'user_lastname' => $USER->lastname,
    'user_email' => $USER->email,
    'user_role' => current(get_user_roles(context_system::instance(), $USER->id))->name,
    'user_language' => $USER->lang,
    'user_id' => $USER->id,
    'isloggedin' => $isloggedin == 'TRUE',
    'notloggedin' => $isloggedin == 'FALSE',
    'signup_is_enabled' => $signup_is_enabled == true,
    'signup_is_disabled' => $signup_is_enabled == false,
    'header_1' => $headertype == 1,
    'header_2' => $headertype == 2,
    'header_3' => $headertype == 3,
    'header_4' => $headertype == 4,
    'header_5' => $headertype == 5,
    'header_6' => $headertype == 6,
    'header_7' => $headertype == 7,
    'header_8' => $headertype == 8,
    'header_9' => $headertype == 9,
    'header_10' => $headertype == 10,
    'header_11' => $headertype == 11,
    'header_12' => $headertype == 12,
    'header_13' => $headertype == 13,
    'header_14' => $headertype == 14,
    'footer_1' => $footertype == 1,
    'footer_2' => $footertype == 2,
    'footer_3' => $footertype == 3,
    'footer_4' => $footertype == 4,
    'footer_5' => $footertype == 5,
    'footer_6' => $footertype == 6,
    'footer_7' => $footertype == 7,
    'footer_8' => $footertype == 8,
    'footer_9' => $footertype == 9,
    'breadcrumb_default' => $breadcrumb_style == 0,
    'breadcrumb_m' => $breadcrumb_style == 1,
    'breadcrumb_s' => $breadcrumb_style == 2,
    'breadcrumb_xs' => $breadcrumb_style == 3,
    'breadcrumb_hidden' => $breadcrumb_style == 4,
    'breadcrumb_classes' => $breadcrumb_classes,
    'breadcrumb_clip_dash' => $breadcrumb_clip_dash,
    'preloader_load' => $preloader_duration == 0,
    'preloader_ready' => $preloader_duration == 1,
    'preloader_5' => $preloader_duration == 2,
    'preloader_4' => $preloader_duration == 3,
    'preloader_3' => $preloader_duration == 4,
    'preloader_2' => $preloader_duration == 5,
    'preloader_disable' => $preloader_duration == 6,
    'headertype_frontpage_only' => FALSE,
    'headertype_all_pages' => TRUE,
    'header_search_icon' => $header_search == 0,
    'header_search_bar' => $header_search == 1,
    'header_login_popup' => $header_login == 0,
    'header_login_link' => $header_login == 1,
    'back_to_top' => $back_to_top == 0,
    // 'dashboard_sticky_header' => $dashboard_sticky_header == 0,
    'logo_styles' => $logo_styles,
    'logo_styles_footer' => $logo_styles_footer,
    'gmaps_key' => get_config('theme_edumy', 'gmaps_key'),
    'messages_link' => $messages_link,
    'profile_link' => $profile_link,
    'preferences_link' => $preferences_link,
    'grades_link' => $grades_link,
    'dash_header_white' => get_config('theme_edumy', 'dashboard_header') == 1,
    'dash_tabs' => $dash_tablet_count != 0,
    'dash_tab_col_class' => $dash_tab_col_class,
    'dash_tablet_1' => $dash_tablet_1,
    'dash_tablet_1_title' => format_text($dash_tablet_1_title, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_1_subtitle' => format_text($dash_tablet_1_subtitle, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_1_link' => $dash_tablet_1_link,
    'dash_tablet_1_icon' => $dash_tablet_1_icon,
    'dash_tablet_2' => $dash_tablet_2,
    'dash_tablet_2_title' => format_text($dash_tablet_2_title, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_2_subtitle' => format_text($dash_tablet_2_subtitle, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_2_link' => $dash_tablet_2_link,
    'dash_tablet_2_icon' => $dash_tablet_2_icon,
    'dash_tablet_3' => $dash_tablet_3,
    'dash_tablet_3_title' => format_text($dash_tablet_3_title, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_3_subtitle' => format_text($dash_tablet_3_subtitle, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_3_link' => $dash_tablet_3_link,
    'dash_tablet_3_icon' => $dash_tablet_3_icon,
    'dash_tablet_4' => $dash_tablet_4,
    'dash_tablet_4_title' => format_text($dash_tablet_4_title, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_4_subtitle' => format_text($dash_tablet_4_subtitle, FORMAT_HTML, array('filter' => true)),
    'dash_tablet_4_link' => $dash_tablet_4_link,
    'dash_tablet_4_icon' => $dash_tablet_4_icon,
    'display_course_content' => $display_course_content == 1,
    'incourse_layout_dashboard' => $incourse_layout_dashboard == 1,
    'disable_stricky_dashboard' => $dashboard_scroll_header == 1,
    'dashboard_scroll_drawer' => $dashboard_scroll_drawer == 1,
    // 'dashboard_drawer_navigation' => $dashboard_left_drawer != (1 || 2),
    'dashboard_nav_user' => $dashboard_left_drawer != (1 || 2 || 3) /* Theme Setting equals '0' */,
    'dashboard_nav_flat' => $dashboard_left_drawer == 3,
    'disable_dashboard_drawer' => $dashboard_left_drawer == 2 || ($dashboard_left_drawer == 1 && !$sidebar_left),
    'incourse' => $incourse == 1,
    'in_course_activity'=>$inCourseActivity,
    'show_course_start' => $showCourseStartDate != 1,
    'show_course_category' => $showCourseCategory != 1,
    // 'user_profile_layout_dashboard' => $user_profile_layout_dashboard == 1,
    'social_target_href' => $social_target_href,
    'if_user_navigation_icon' => get_config('theme_edumy', 'navigation_icon_visibility') != 1,
    'if_user_notification_icon' => get_config('theme_edumy', 'notification_icon_visibility') != 1,
    'if_user_messages_icon' => get_config('theme_edumy', 'messages_icon_visibility') != 1,
    'if_user_dark_mode_icon' => get_config('theme_edumy', 'dark_mode_icon_visibility') != 1,
    'user_navigation_icon' => ($ccnIcon = get_config('theme_edumy', 'navigation_icon_ccn_icon_class')) ? $ccnIcon : 'flaticon-settings',
    'user_notification_icon' => ($ccnIcon = get_config('theme_edumy', 'notification_icon_ccn_icon_class')) ? $ccnIcon : 'flaticon-alarm',
    'user_messages_icon' => ($ccnIcon = get_config('theme_edumy', 'messages_icon_ccn_icon_class')) ? $ccnIcon : 'flaticon-speech-bubble',
    'user_dark_mode_icon' => ($ccnIcon = get_config('theme_edumy', 'dark_mode_icon_ccn_icon_class')) ? $ccnIcon : 'ccn-flaticon-hide',
    'show_settings_controls' => $ccn_page_settings_controls == 1,
    'if_breadcrumb_title' => get_config('theme_edumy', 'breadcrumb_title') != 1,
    'if_breadcrumb_trail' => get_config('theme_edumy', 'breadcrumb_trail') != 1,
    'edumy_focus_sidebar' => !empty(get_config('theme_edumy', 'edumy_focus_sidebar')) && get_config('theme_edumy', 'edumy_focus_sidebar') === '1' ? false : true,
    'lang_menu_icons' => !empty(get_config('theme_edumy', 'language_menu')) && get_config('theme_edumy', 'language_menu') === '1' ? false : true,
    'is_4' => (int)$ccnMdlVersion >= 400 ? true : false,

    'has_back2course_btn' => !empty($back2CourseBtn),
    'back2course_btn' => $back2CourseBtn,

    // Aggiunta due variabili una per tipo di pagina (non usata, solo per debug) ed
	// una per indicare se siamo su una pagina quiz
	'pagetype' => $PAGE->pagetype,
	'isquiz' => ( in_array( $PAGE->pagetype, [ 'mod-quiz-view', 'mod-quiz-attempt' ] ) ),
	'isblog' => ( $PAGE->pagetype == 'blog-index' ),

  // Aggiunte variabili per replicare l'indice del corso laterale di boost
  'courseindex' => $courseindex,
  'courseindexopen' => $courseindexopen,
  'blockdraweropen' => $blockdraweropen,
  'forceblockdraweropen' => $forceblockdraweropen,
  'hasprogress' => $hasprogress,
  'progress' => floor($progress ?? 0),
];

if((int)$ccnMdlVersion >= 400) {
  $templatecontext['primarymoremenu'] = $primarymenu['moremenu'];
  $templatecontext['secondarymoremenu'] = $secondarynavigation ? : false;
  $templatecontext['eithermoremenu'] = !empty($primarymenu['moremenu']) || $secondarynavigation ? true : false;
  $templatecontext['mobileprimarynav'] = $primarymenu['mobileprimarynav'];
  $templatecontext['headercontent'] = $headercontent;
  $templatecontext['overflow'] = $overflow;
  $templatecontext['addblockbutton'] = $addblockbutton;
  // 'primarymoremenu' => $primarymenu['moremenu'],
  // 'secondarymoremenu' => $secondarynavigation ? : false,
  // 'eithermoremenu' => !empty($primarymenu['moremenu']) || $secondarynavigation ? true : false,
  // 'mobileprimarynav' => $primarymenu['mobileprimarynav'],
  // 'headercontent' => $headercontent,
  // 'overflow' => $overflow,
  // 'addblockbutton' => $addblockbutton,
}


// var_dump($primarymenu['moremenu']);
$PAGE->requires->jquery();
$ccnLcVbCollection = array(
  "cocoon_about_1",
  "cocoon_about_2",
  "cocoon_accordion",
  "cocoon_action_panels",
  "cocoon_boxes",
  "cocoon_blog_recent_slider",
  "cocoon_faqs",
  "cocoon_event_list",
  "cocoon_event_list_2",
  "cocoon_featured_teacher",
  "cocoon_featured_posts",
  "cocoon_featured_video",
  "cocoon_features",
  "cocoon_gallery_video",
  "cocoon_parallax",
  "cocoon_parallax_apps",
  "cocoon_parallax_counters",
  "cocoon_parallax_features",
  "cocoon_parallax_testimonials",
  "cocoon_parallax_subscribe",
  "cocoon_parallax_subscribe_2",
  "cocoon_partners",
  "cocoon_parallax_white",
  "cocoon_pills",
  "cocoon_price_tables",
  "cocoon_price_tables_dark",
  "cocoon_services",
  "cocoon_services_dark",
  "cocoon_simple_counters",
  "cocoon_hero_1",
  "cocoon_hero_2",
  "cocoon_hero_3",
  "cocoon_hero_4",
  "cocoon_hero_5",
  "cocoon_hero_6",
  "cocoon_hero_7",
  "cocoon_slider_1",
  "cocoon_slider_1_v",
  "cocoon_slider_2",
  "cocoon_slider_3",
  "cocoon_slider_4",
  "cocoon_slider_5",
  "cocoon_slider_6",
  "cocoon_slider_7",
  "cocoon_slider_8",
  "cocoon_steps",
  "cocoon_steps_dark",
  "cocoon_subscribe",
  "cocoon_tablets",
  "cocoon_tabs",
  "cocoon_tstmnls",
  "cocoon_tstmnls_2",
  "cocoon_tstmnls_3",
  "cocoon_tstmnls_4",
  "cocoon_tstmnls_5",
  "cocoon_tstmnls_6",
  "cocoon_contact_form",
  "cocoon_course_categories",
  "cocoon_course_categories_2",
  "cocoon_course_categories_3",
  "cocoon_course_categories_4",
  "cocoon_course_categories_5",
  "cocoon_course_overview",
  "cocoon_course_instructor",
  "cocoon_course_rating",
  "cocoon_course_grid",
  "cocoon_course_grid_2",
  "cocoon_course_grid_3",
  "cocoon_course_grid_4",
  "cocoon_course_grid_5",
  "cocoon_course_grid_6",
  "cocoon_course_grid_7",
  "cocoon_course_grid_8",
  "cocoon_featuredcourses",
  "cocoon_courses_slider",
  "cocoon_more_courses",
  "cocoon_users_slider",
  "cocoon_users_slider_2",
  "cocoon_users_slider_2_dark",
  "cocoon_users_slider_round",
 );
$ccnControlBlockListUri = $CFG->wwwroot . '/theme/edumy/ccn/visualize/ccn_block/jpeg/large/';
$ccnControlBlockListUriThumb = $CFG->wwwroot . '/theme/edumy/ccn/visualize/ccn_block/jpeg/thumb/';
$PAGE->requires->js_init_call('ccnCommentHandler', array(get_string('add_comment', 'theme_edumy')));
$PAGE->requires->js_init_call('ccnControl', array($ccnControlBlockListUri, $ccnControlBlockListUriThumb, $ccnLcVbCollection, $ccnMdlVersion));
$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();

if($PAGE->pagetype == "admin-setting-themesettingedumy") {
  $PAGE->requires->css('/theme/edumy/style/cocoon.editor.theme.css');
  $PAGE->requires->js('/theme/edumy/javascript/cocoon.editor.theme.js', true);
}

if($PAGE->pagetype == 'course-view-flexsections') {
  $PAGE->requires->js('/theme/edumy/javascript/courseindex.js', true);
}
