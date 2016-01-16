/*
TTITLEBAR SETTINGS
titlebar background
*/


function st_theme_titlebar_bg($list_bg){
   return array(
        'pattern1' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern1.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern2' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern2.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern3' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern3.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern4' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern4.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern5' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern5.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern6' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern6.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
        'pattern7' => array('img'=>ST_FRAMEWORK_IMG . 'patterns/pattern7.png', 'color'=>'', 'position'=>'','repeat'=>'', 'attachment'=>''),
    );
}

// add to titlebar defined
add_filter('st_titlebar_list_bg','st_theme_titlebar_bg');