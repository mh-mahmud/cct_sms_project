<?php
return[
    'JS_SOURCE' => 'src',
    'SYS_THEME_ASSETS_PATH' => public_path().DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR,
    'SYS_THEME_ASSETS_URL' => env('APP_URL').'assets/',
    'DEFAULT_SITE_HEADER' => 'Sms Portal',
    'DEFAULT_SITE_TITLE' => 'Sms Portal',
    'DEVELOPER_TEAM' => 'Genusys Inc. All rights reserved.',
    'DEVELOPER_TEAM_WEB_URL' => 'http://www.genuitysystems.com',
    'BASE_URL' => '/',
    'PROJECT_PREFIX' => 'sms_blaster',
    'USER_TYPE_CUSTOMER' => 'CU',
    'USER_TYPE_ADMIN' => 'AU',
    'DISCOUNT_TYPE_FIXED' => 'DTF',
    'DISCOUNT_TYPE_PERCENTAGE' => 'DTP',

     /**
     * Status constant
     */
    'ACTIVE' => 'A',
    'INACTIVE' => 'I',
    'STATUS_ACTIVE' => 'A',
    'STATUS_INACTIVE' => 'I',
    'STATUS_PENDING' => 'P',
    'STATUS_EMAIL_CONFIRMED' => 'E',
    'STATUS_DELETED' => 'D',
    'STATUS_ARCHIVE' => 'AR',
    'PAID' => 'P',
    'DUE' => 'D',
    'YES' => 'Y',
    'NO' => 'N',
    'PENDING' => 'P',
    'SEND' => 'S',
    'FAILED' => 'F',
    'READ' => 'R',
    'UNREAD' => 'U',
    'INBOUND' => 'I',
    'OUTBLUND' => 'O',
    /**
     * email config
     */
    'FROM_EMAIL' => 'razu@genuitysystems.com',
    'TO_EMAIL' => 'razu@genuitysystems.com',
    'REPLY_TO_EMAIL' => 'razu@genuitysystems.com',
    'FROM_EMAIL_NAME' => "Email Blaster",
    'CAMPAIGN_FROM_EMAIL' => "razu@genuitysystems.com",
    'CAMPAIGN_TO_EMAIL' => "razu@genuitysystems.com",
    'CAMPAIGN_EMAIL_SUBJECT' => "Email Blaster Campaign",

    'PAGINATION_LIMIT' => 20,
    'PAGINATION_MIN_LIMIT' => 20,
    'CAMPAIGN_EMAIL_SEND_LIMIT' => 10,
    'CURRENT_DATE_TIME' => date('Y-m-d H:i:s'),
    'CURRENT_DATE' => date('Y-m-d'),
    'CURRENT_TIME' => date('H:i:s'),
    'DB_DATE_FORMAT' => 'Y-m-d',
    'DB_TIME_FORMAT' => 'H:i:s',
    'DATEFORMAT' => date('m/d/Y'),
    'CUSTOM_DATE_FORMAT' => 'm/d/Y',
    'SHOW_DATE_FORMAT' => 'M d, Y',
    'SHOW_DATETIME_FORMAT' => 'M d, Y, g:i a',
    'SHOW_CUSTOM_DATETIME_FORMAT' =>  'm-d-Y H:i:s',

    /*
    * Model Constance
    */
    'DATA_FETCH_UUID' => 'uuid',
    'DATA_FETCH_ID' => 'id',
    'DATA_FETCH_ALL' => 'all',
    'DATA_FETCH_LIST' => 'list',
    'DATA_FETCH_FIRST' => 'first',
    'DATA_FETCH_COUNT' => 'count',



    /*
    * Flash Message Type
    */
    'FLASH_MSG_INFO' => 'info',
    'FLASH_MSG_WARNING' => 'warning',
    'FLASH_MSG_SUCCESS' => 'success',
    'FLASH_MSG_ERROR' => 'error',


    /**
     * Database Field 
     */
    'DB_FIELD_UUID' => 'uuid', 
    'DB_FIELD_ID' => 'id',

    'USER_TYPE' => [
        'AU' => 'Admin',
        'MU' => 'Manager',
        'AG' => 'Agent',
        'SU' => 'Supervisor'
    ],
    'USER_STATUS' => [
        'A' => 'Active',
        'I' => 'Inactive'
    ],
    'DELETE_STATUS' => [
        '0' => 'Deleted',
        '1' => 'Not Deleted'
    ],
    'LOG_SMS_STATUS' => [
        'A' => 'Paused',
        'S' => 'Send',
        'D' => 'Delivered',
        'P' => 'Processing',
        'F' => 'Failed',
        'R' => 'Read',
        'U' => 'Unread',
    ],
    'SMS_DIRECTION' => [
        'I' => 'Inbound',
        'O' => 'Outbound',
    ],
    'AUDIT_LOG_CHNAGE_TYPE' => [
        'A' => 'ADD',
        'U' => 'UPDATE',
        'D' => 'DELETE',
        'L' => 'LOGIN',
        'O' => 'LOGOUT',
    ],
    /**
     * Database Field Value
     */    
    'SMS_TEXT_ASCII' => [160, 306, 459],
    'SMS_TEXT_UNICODE' => [70, 134, 201],
    'SMS_TEXT_SIZE' => 459,
    'SMS_TEXT_PART' => 3,
    'SMS_TEXT_PART_SIZE' => 160,
    'REPORT_MAX_DATE_DIFF' => 15,
    /**
     * Database Field Value
     */
    'CSV_EXAMPLE_FILE_PATH' => 'public/files/example.csv'
];

