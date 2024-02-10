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
                        'GET countportipoelocalizacao/{tipo}/{localizacao_alojamento}' => 'countportipoelocalizacao',
                        'GET alojamentos' => 'alojamentos',
                        'GET alojamento/{id}' => 'detalhesalojamento',
                        'GET tipo/{tipo}' => 'tipo',
                        'GET localizacao/{localizacao_alojamento}' => 'localizacao',
                        'GET {id}/comentariospordata/{data}' => 'comentariospordata',
                        'GET {id}/avaliacoesmedia' => 'avaliacoesmedia',
                        'GET favoritos' => 'favoritos',
                        'POST adicionarfavorito/{fornecedorId}' => 'adicionarfavorito',
                        'DELETE removerfavorito/{fornecedorId}' => 'removerfavorito',
                        'GET detalhesfavorito/{fornecedorId}' => 'detalhesfavoritos',
                        'GET comentarios' => 'comentarios',
                        'GET comentario/{comentarioId}' => 'detalhescomentario',
                        'GET comentariosalojamento/{fornecedorId}' => 'comentariosalojamento',
                        'POST adicionarcomentario/{fornecedorId}' => 'adicionarcomentario',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{tipo}' => '<tipo:[\w\-]+>',
                        '{localizacao_alojamento}' => '<localizacao_alojamento:[^/]+>',
                        '{data}' => '<data:[^/]+>',
                        '{fornecedorId}' => '<fornecedorId:\\d+>',
                        '{comentarioId}' => '<comentarioId:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/reserva'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET reservasconfirmadas' => 'reservasconfirmadas',
                        'PUT {id}/confirmar' => 'confirmarreserva',
                        'PUT {id}/cancelar' => 'cancelarreserva',
                        'GET taxareservas' => 'taxareservas',
                        'GET detalhes/{id}' => 'detalhesreserva',
                        'GET mostrar/{username}' => 'mostrarreserva',
                        'POST verificar/{reserva_id}' => 'verificar',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{reserva_id}' => '<reserva_id:\\d+>',
                        '{username}' => '<username:\w+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/fatura'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET gerarfatura/{nomecliente}/{reserva_id}' => 'gerarfatura',
                        'GET mostrar/{nomecliente}' => 'mostrarfatura',
                        'GET detalhes/{id}' => 'detalhesfatura',
                        'GET ver/{username}' => 'verfatura',
                        'GET download/{id}' => 'download',

                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{reserva_id}' => '<reserva_id:\d+>',
                        '{nomecliente}' => '<nomecliente:\w+>',
                        '{username}' => '<username:\w+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/carrinho'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET calculartotal/{nomecliente}' => 'calculartotal',
                        'POST adicionarcarrinho/{fornecedorId}' => 'adicionarcarrinho',
                        'DELETE removercarrinho/{fornecedorId}' => 'removercarrinho',
                        'GET finalizarcarrinho/{reservaid}' => 'finalizarcarrinho',
                        'GET mostrar' => 'mostrarcarrinho',
                        'GET download/{reservaid}' => 'downloadpagamento',

                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{nomecliente}' => '<nomecliente:\w+>',
                        '{fornecedorId}' => '<fornecedorId:\d+>',
                        '{reservaId}' => '<reservaId:\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET login/{username}/{password}' => 'login',
                        'POST register' => 'register',
                        'GET mostrar/{username}' => 'mostraruser',
                        'PUT atualizar' => 'atualizaruser',

                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{username}' => '<username:[\w\d]+>',
                        '{password}' => '<password:[\w\d]+>',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
