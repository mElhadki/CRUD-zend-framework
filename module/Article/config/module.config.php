<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Article\Controller\Article' => 'Article\Controller\ArticleController',
             'Article\Controller\Category' => 'Article\Controller\CategoryController',
         ),
     ),
    
    // Article Routes
    
     'router' => array(
         'routes' => array(
             'article' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/article[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Article\Controller\Article',
                         'action'     => 'index',
                     ),
                 ),
             ),                      
        'category' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/category[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Article\Controller\Category',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
    
     'view_manager' => array(
         'template_path_stack' => array(
             'article' => __DIR__ . '/../view',
         ),
     ),
 );