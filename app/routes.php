<?php

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use ChicAndCheap\Domain\typeArticle;
use ChicAndCheap\Domain\Article;
use ChicAndCheap\Domain\Panier;
use ChicAndCheap\Domain\Visiteur;
use ChicAndCheap\Domain\Comment;
use ChicAndCheap\Form\Type\CommentType;
use ChicAndCheap\Form\Type\UserType;
use ChicAndCheap\Form\Type\ProfilType;

// Page d'accueil
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('home');

$app->get('/faq', function () use ($app) {
    return $app['twig']->render('faq.html.twig');
})->bind('faq');

$app->get('/404', function () use ($app) {
    return $app['twig']->render('404.html.twig');
})->bind('errors');

// nous contacter
$app->get('/contact', function () use ($app) {
    return $app['twig']->render('contact.html.twig');
})->bind('contact');

// mentions legales
$app->get('/mentions', function () use ($app) {
	
    return $app['twig']->render('mentionsl.html.twig');
});

// CONDITIONS GENERALES D’UTILISATIONS ET DE LOCATIONS DES VËTEMENTS
$app->get('/conditions', function () use ($app) {
	
    return $app['twig']->render('conditions.html.twig');
});

// foire aux questions
$app->get('/faq', function () use ($app) {
	
    return $app['twig']->render('faq.html.twig');
});

// Liste de tous les articles femmes
$app->get('/article_femme/', function() use ($app) {
    $articles_femmes = $app['dao.article_femme']->findAll();
    return $app['twig']->render('articles_femmes.html.twig', array('articles_femmes' => $articles_femmes));
})->bind('articles_femmes');


// Article details with comments
$app->match('/article/{code}', function ($code, Request $request) use ($app) {
    $article = $app['dao.article']->find($code);
    $commentFormView = null;
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated : he can add comments
        $comment = new Comment();
        $comment->setArticle($article);
        $user = $app['user'];
        $comment->setAuthor($user);
        $commentForm = $app['form.factory']->create(new CommentType(), $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Your comment was succesfully added.');
        }
        $commentFormView = $commentForm->createView();
    }
    $comments = $app['dao.comment']->findAllByArticle($code);
    return $app['twig']->render('articles_details.html.twig', array(
        'article' => $article, 
        'comments' => $comments,
        'commentForm' => $commentFormView));
})->bind('article');

// Liste de tous les articles hommes
$app->get('/article_homme/', function() use ($app) {
    $articles_hommes = $app['dao.article_homme']->findAll();
    return $app['twig']->render('articles_hommes.html.twig', array('articles_hommes' => $articles_hommes));
})->bind('articles_hommes');


// Liste de tous les articles enfants
$app->get('/article_enfant/', function() use ($app) {
    $articles_enfants = $app['dao.article_enfant']->findAll();
    return $app['twig']->render('articles_enfants.html.twig', array('articles_enfants' => $articles_enfants));
})->bind('articles_enfants');


// Liste de tous les articles
$app->get('/article/', function() use ($app) {
    $articles = $app['dao.article']->findAllArticle();
    return $app['twig']->render('articles.html.twig', array('articles' => $articles));
})->bind('articles');


// ajout au panier
$app->get('/panier/ajouter/{id_visiteur}/{codeArt}', function ($id_visiteur, $codeArt) use ($app) {
	//
	$Articles = $app['dao.article']->findAllArticle();
	//
	if (!$app['dao.panier']->articleExistant($id_visiteur, $codeArt))
	{
		$app['dao.panier']->addProduct($id_visiteur, $codeArt);
		$app['session']->getFlashBag()->add('panier', '   Cet article a bien été ajouté au panier.');
	}
	else
	{
		$app['session']->getFlashBag()->add('panier_error', 'Vous avez déja cet article dans votre panier!');
	}
	$article = $app['dao.article']->find($codeArt);
	$typeArt = $article->getTypeArticle();
	$laRoute = RouteArt($typeArt);
	//redirection vers la section article definis
    return $app->redirect($app["url_generator"]->generate($laRoute, array('id' => $id_visiteur)));
});


// inscription d'un client
$app->match('/inscription', function(Request $request) use ($app) {
	$user = new Visiteur();
	$userForm = $app['form.factory']->create(new UserType(), $user);
	$userForm->handleRequest($request);
	if ($userForm->isSubmitted()) {
		if($app['dao.visiteur']->loadUserByUsername($user->getUsername()))
		{
			$app['session']->getFlashBag()->add('danger', 'Un client existe déja avec cette adresse');
		}
		else
		{
			// génère un salt aléatoirement
			$salt = substr(md5(time()), 0, 23);
			$user->setSalt($salt);
			$plainPassword = $user->getPassword();
			// trouve le codeur par defaut
			$encoder = $app['security.encoder.digest'];
			// compute the encoded password
			$password = $encoder->encodePassword($plainPassword, $visiteur->getSalt());
			$user->setPassword($password);
			$app['dao.visiteur']->save($user);
			$app['session']->getFlashBag()->add('success', 'compte crée avec succés.');
		}
	}
	else
	{
		echo " sa ne fonctionne pas";
	}
	return $app['twig']->render('User_Form.html.twig', array(
		'title' => 'Inscription',
		'userForm' => $userForm->createView()));
})->bind('inscription');

// retrait du panier
$app->get('/panier/enlever/{id_visiteur}/{codeArt}', function ($id_visiteur, $codeArt) use ($app) {
	//
	$Articles = $app['dao.article']->findAllArticle();
	//
	if ($app['dao.panier']->articleExistant($id_visiteur, $codeArt))
	{
		$app['dao.panier']->removeProduct($id_visiteur, $codeArt);
		$app['session']->getFlashBag()->add('panier', 'L\'article a bien été enlevé du panier.');
	}
	else
	{
		$app['session']->getFlashBag()->add('panier_error', 'Impossible d\'enlever cet article du panier !');
    }
	$article = $app['dao.article']->find($codeArt);
	$typeArt = $article->getTypeArticle();
	$laRoute = RouteArt($typeArt);
	//redirection vers la section article definis
    return $app->redirect($app["url_generator"]->generate($laRoute, array('id' => $id_visiteur)));
});

// Le panier
$app->get('/monpanier/{id}', function ($id) use ($app) {
	//
	$categories = $app['dao.article']->findAllArticle();
	//
	$articles = $app['dao.panier']->findProductsByUser($id);
	$prix = $app['dao.panier']->totalPanier($id);
    return $app['twig']->render('Panier.html.twig', array('categories' => $categories, 'articles' => $articles, 'prix' => $prix));
})->bind("panier");


//vider le panier
$app->get('/panier/{id}', function ($id) use ($app) {
	//
	$categories = $app['dao.article']->findAllArticle();
	//
	$prix = $app['dao.panier']->totalPanier($id);
	$date = date("Y-m-d");
	$app['dao.achat']->addAchat($id, $prix, $date);
	$app['dao.panier']->supprimerPanier($id);

	$articles = $app['dao.panier']->findProductsByUser($id);
	$prix = $app['dao.panier']->totalPanier($id);
    return $app['twig']->render('Panier.html.twig', array('categories' => $categories, 'articles' => $articles, 'prix' => $prix));
});


// inscription d'un client 
$app->match('/inscription', function(Request $request) use ($app) {
    $user = new Visiteur();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {

    		// génère un salt aléatoirement
       		$salt = substr(md5(time()), 0, 23);
        	$user->setSalt($salt);
        	$plainPassword = $user->getPassword();
        	 // trouve le codeur par defaut
        	$encoder = $app['security.encoder.digest'];
        	// compute the encoded password
        	$password = $encoder->encodePassword($plainPassword, $user->getSalt());
        	$user->setPassword($password); 
        	$app['dao.visiteur']->save($user);
        	$app['session']->getFlashBag()->add('success', 'compte crée avec succés.');   
    }

    return $app['twig']->render('User_Form.html.twig', array(
        'title' => 'Inscription',
        'userForm' => $userForm->createView()));
})->bind('inscription');


// modifier utilisateur
$app->match('/modification_utilisateur/{id}', function($id, Request $request) use ($app) {
	//
	$categories = $app['dao.article']->findAllArticle();
	//
    $user = $app['dao.visiteur']->find($id);
    $userForm = $app['form.factory']->create(new ProfilType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) { 
        $app['dao.visiteur']->save($user);
        $app['session']->getFlashBag()->add('success', 'Les informations ont été modifiées avec succés.');
    }
    return $app['twig']->render('User_Profil.html.twig', array(
        'title' => 'Modification utilisateur',
        'userProfil' => $userForm->createView(),
        'categories' => $categories));
});


//affiche le profil
$app->match('/Commande/{id}', function($id, Request $request) use ($app) {
	//
	$categories = $app['dao.article']->findAllArticle();
	//
    $achats = $app['dao.achat']->findByUser($id);
    $nombre = $app['dao.achat']->NbAchat($id);

    
    return $app['twig']->render('commande.html.twig', array(
        'categories' => $categories, 'achats' => $achats, 'nombre' => $nombre));
});

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');


function RouteArt($typeArt){
	switch ($typeArt) {
		case "H":
			return "articles_hommes";
			break;
		case "F":
			return "articles_femmes";
			break;
		case "E":
			return "articles_enfants";
			break;
		default:
			return "articles";
	}
}
