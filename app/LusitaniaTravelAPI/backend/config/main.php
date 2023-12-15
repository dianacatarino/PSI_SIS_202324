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
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fornecedor'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET tipo/{tipo}' => 'fornecedorportipo',
                        'GET localizacao/{localizacao_alojamento}' => 'fornecedorporlocalizacao',
                        'GET {id}/comentarios/{data}' => 'comentariospordata',
                        'GET {id}/avaliacoes' => 'avaliacoesmedia',
                        'GET {id}/reservas' => 'reservasfornecedor',
                        // Adicione outros endpoints personalizados conforme necessário
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
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fatura'],
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fatura'],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET count' => 'count',
                    ],
                ]

            ],
        ],
    ],
    'params' => $params,
];
