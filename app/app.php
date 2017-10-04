<?php

use Symfony\Component\Debug\ErrorHandler;
ErrorHandler::register();
use Symfony\Component\Debug\ExceptionHandler;
ExceptionHandler::register();


// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new ChicAndCheap\DAO\VisiteurDAO($app['db']);
            }),
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));


// Register services.

$app['dao.panier'] = $app->share(function ($app) 
{
    return new ChicAndCheap\DAO\PanierDAO($app['db']);
});
$app['dao.achat'] = $app->share(function ($app) 
{
    return new ChicAndCheap\DAO\AchatDAO($app['db']);
});
$app['dao.article_femme'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\Article_femmeDAO($app['db']);
});

$app['dao.article_enfant'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\Article_enfantDAO($app['db']);
});

$app['dao.article_homme'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\Article_hommeDAO($app['db']);
});

$app['dao.typearticle'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\TypearticleDAO($app['db']);
});

$app['dao.couleurarticle'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\CouleurarticleDAO($app['db']);
});

$app['dao.article'] = $app->share(function ($app) {
    $articleDAO = new ChicAndCheap\DAO\articleDAO($app['db']);
    $articleDAO->setTypearticleDAO($app['dao.typearticle']);
    $articleDAO->setCouleurarticleDAO($app['dao.couleurarticle']);
    return $articleDAO;
});

$app['dao.visiteur'] = $app->share(function ($app) {
    return new ChicAndCheap\DAO\VisiteurDAO($app['db']);
});

$app['dao.comment'] = $app->share(function ($app) {
    $commentDAO = new ChicAndCheap\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setVisiteurDAO($app['dao.visiteur']);
    return $commentDAO;
});


