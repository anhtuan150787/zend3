<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms;

use Cms\Service\Category;
use Cms\Service\Model;
use Cms\Service\Service\ModelFactory;
use Cms\Service\Service\ModelInvokableFactory;
use Cms\Service\User;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Cms\Service\Controller\ControllerInvokableFactory;

return [
    'router' => [
        'routes' => [
            'cms' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/cms/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'login' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'login',
                            'defaults' => [
                                'controller' => Controller\LoginController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'logout',
                            'defaults' => [
                                'controller' => Controller\LogoutController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'category' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'category[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'article' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'article[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => Controller\ArticleController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'page' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'page[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => Controller\PageController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => ControllerInvokableFactory::class,
            Controller\LoginController::class => ControllerInvokableFactory::class,
            Controller\LogoutController::class => ControllerInvokableFactory::class,
            Controller\MasterController::class => ControllerInvokableFactory::class,
            Controller\CategoryController::class => ControllerInvokableFactory::class,
            Controller\ArticleController::class => ControllerInvokableFactory::class,
            Controller\PageController::class => ControllerInvokableFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Model::class => ModelInvokableFactory::class,
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'layout/cms'           => __DIR__ . '/../view/layout/cms.phtml',
            'layout/cms_login'     => __DIR__ . '/../view/layout/cms_login.phtml',

            'cms/partial/form_button' =>  __DIR__ . '/../view/partial/form_button.phtml',
            'cms/partial/paginator' =>  __DIR__ . '/../view/partial/paginator.phtml',
            'cms/partial/message' =>  __DIR__ . '/../view/partial/message.phtml',
            'cms/partial/table_header' =>  __DIR__ . '/../view/partial/table_header.phtml',
            'cms/partial/form_header' =>  __DIR__ . '/../view/partial/form_header.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
