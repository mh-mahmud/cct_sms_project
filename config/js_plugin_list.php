<?php
$jsplist = [];

/**
 * jQuery Bootstrap Datepicker
 */
$jsplist["JSP_BOOTSTRAP_DATEPICKER"] = [
    'js' => ['public/assets/plugins/datetimepicker/bootstrap-datetimepicker.min.js'],
    'css' => ['public/assets/plugins/datetimepicker/bootstrap-datetimepicker.css']
];
/**
 * jQuery Bootstrap Select2
 */
$jsplist["JSP_BOOTSTRAP_SELECT2"] = [
    'css' => ['public/assets/plugins/select2/select2-bootstrap.min.css'],
];
/**
 * Bootstrap form wizard
 */
$jsplist["JSP_BOOTSTRAP_FORM_WIZARD"] = [
    'css' => ['public/assets/demo/default/custom/crud/wizard/wizard.js'],
];

/**
 * Bootstrap bootbox which is used for confirmation modal
 */
$jsplist["JSP_BOOTSTRAP_BOOTBOX"] = [
    'js' => ['public/assets/plugins/bootbox/bootbox.min.js'],
];
/**
 * jplayer
 */
$jsplist["JSP_JPLAYER"] = [
    'js' => [
        'public/assets/plugins/jPlayer/dist/jplayer/jquery.jplayer.min.js',
        'public/assets/plugins/jPlayer/dist/add-on/jplayer.playlist.min.js',
    ],
    'css' => ['public/assets/plugins/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.min.css']
];
/**
 * jQuery Bootstrap Taginput
 */
$jsplist["JSP_BOOTSTRAP_TAGINPUT"] = [
    'js' => [
        'public/assets/plugins/bootstrap-taginput/tagify.js',
        'public/assets/plugins/bootstrap-taginput/jQuery.tagify.min.js'
    ],
    'css' => [
        'public/assets/plugins/bootstrap-taginput/tagify.css',
        'public/assets/plugins/bootstrap-taginput/style.css',
    ]
];
/**
 * jQuery Sortable
 */
$jsplist["JSP_SORTABLE"] = [
    'js' => [
        'public/assets/plugins/sortable/sortable.js'        
    ],
];

return $jsplist;
?>