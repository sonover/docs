<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Documentation Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Documentation will be accessible from. If this
    | setting is null, Documentation will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Documentation Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Documentation will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => 'docs',

    'disk' => 'docs',

    'contents' => 'contents',

    'start_page' => 'getting-started',

    'default' => 'master',

    'current' => 'master',

    'versions' => [
        'master' => 'Master',
    ]

];
