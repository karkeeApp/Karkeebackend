<?php
return [
    // 'server/<controller:\w+>/<action:\w+>'          =>'server/<controller>/<action>',
    // 'server/<controller:\w+>/<action:\w+>/<id:\d+>' =>'server/<controller>/<action>',
    // '<controller:\w+>/<action:\w+>/<id:\d+>'        =>'<controller>/<action>',
    // '<controller:\w+>/<id:\d+>'                     =>'<controller>/view',
    // '<controller:\w+>/<action:\w+>'                 =>'<controller>/<action>',
    'login'                                         =>'site/login',
    'server/<controller:\w+>/<action:\w+>'          =>'server/<controller>/<action>',
    'server/<controller:\w+>/<action:\w+>/<id:\d+>' =>'server/<controller>/<action>',
    'server/<controller:[\w\-]+>/<action:[\w\-]+>'  =>'server/<controller>/<action>',
    '<controller:\w+>/<action:\w+>/<id:\d+>'        =>'<controller>/<action>',
    '<controller:\w+>/<id:\d+>'                     =>'<controller>/view',
    '<controller:\w+>/<action:\w+>'                 =>'<controller>/<action>',
    // '<controller:[\w\-]+>/<action:[\w\-]+>'         =>'<controller>/<action>',
];

