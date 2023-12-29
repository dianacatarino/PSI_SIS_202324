<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => \backend\modules\api\ModuleAPI::class,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'api',
                    'route'   => 'api/site/index',
                    'suffix'  => '',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fornecedor'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET tipo/{tipo}' => 'fornecedorportipo',
                        'GET localizacao/{localizacao_alojamento}' => 'fornecedorporlocalizacao',
                        'GET {id}/comentariospordata/{data}' => 'comentariospordata',
                        'GET {id}/avaliacoesmedia' => 'avaliacoesmedia',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{tipo}' => '<tipo:[\w\-]+>',
                        '{localizacao_alojamento}' => '<localizacao_alojamento:[^/]+>',
                        '{data}' => '<data:[^/]+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/reserva'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET reservasconfirmadas' => 'reservasconfirmadas',
                        'PUT {id}/confirmar' => 'confirmarreserva',
                        'PUT {id}/cancelar' => 'cancelarreserva',
                        'GET taxareservas' => 'taxareservas',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fatura'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/carrinho'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET calculartotal/{nomecliente}' => 'calculartotal',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{nomecliente}' => '<nomecliente:\w+>',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
