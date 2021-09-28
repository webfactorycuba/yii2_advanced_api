<?php

namespace common\models;

class ConfigServerConstants
{
    //Local URL
    const BASE_URL_BACKEND_LOCAL = 'http://backend.yii2advanced.local'; //Backend
    const BASE_URL_FRONTEND_LOCAL = 'http://frontend.yii2advanced.local'; //Frontend

    //Real Domain URL
    const BASE_URL_BACKEND = 'http://admin.mydomain.com'; //Backend
    const BASE_URL_FRONTEND = 'http://www.mydomain.com'; //Frontend

    //To control change languages in system
    const LANGUAGE_COOKIE_KEY_BACKEND = 'lang_cookie_back';
    const LANGUAGE_COOKIE_KEY_FRONTEND = 'lang_cookie_frontend';

    /**
     * To define Timezone of system
     * https://www.php.net/manual/en/timezones.php
     */
    const TIMEZONE = 'America/Havana';

    //To define default language of system(es, en)
    const DEFAULT_LANGUAGE = 'es'; //Spanish
    //const DEFAULT_LANGUAGE = 'en'; //English

    //To define name of system
    const SITE_NAME = 'Advanced Yii2';
}