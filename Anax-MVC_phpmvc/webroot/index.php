<?php
require __DIR__.'/config_with_app.php';

$di->set('CommentController', function() use ($di) {
    $controller = new Sofa15\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('FormController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionController', function () use ($di) {
    $controller = new \Anax\Question\QuestionController();
    $controller->setDI($di);
    return $controller;
});

$di->set('AnswerController', function () use ($di) {
    $controller = new \Anax\Answer\AnswerController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagsController', function () use ($di) {
    $controller = new \Anax\Tags\TagsController();
    $controller->setDI($di);
    return $controller;
});


$di->set('userInfo', function() use ($di){
	$userInfo = null;
	if ($di->session->get('user') != null) {
		$userInfo = ['id' => $di->session->get('user'), 'acronym' => $di->session->get('username')];
		
		if ($di->session->get('userLevel') == 2) {
			$userInfo['admin'] = true;
		}
	}
	return $userInfo;
});

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');

// Add extra assets
$app->theme->addStylesheet('css/dice.css');
$app->theme->addStylesheet('css/comment.css');


// Routes
$app->router->add('', function() use ($app, $di) {
    $app->theme->setTitle("Home");

    $content = $app->fileContent->get('start.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$users = new Anax\Users\Users();
	$users->setDI($di);
	
	$mostActive = $users->getMostActive();
	
	$tags = new Anax\Tags\Tags();
	$tags->setDI($di);
	
	$mostUsed = $tags->getMostUsed();

	$question = new Anax\Question\Question();
	$question->setDI($di);

	$mostRecent = $question->getMostRecent();
	
    $app->views->add('default/page', ['content' => $content]);
	$app->views->add('users/list-simple', ['users' => $mostActive, 'text' => '<h2>Most active users</h2>'], 'sidebar');
	$app->views->add('tags/view-tags', ['tags' => $mostUsed, 'text' => '<br><h2>Most used tags</h2>'], 'sidebar');
	$app->views->add('question/list', ['questions' => $mostRecent, 'text' => '<h2>Newest questions</h2>']);

});


$app->router->add('source', function() use ($app) {

    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);

});


$app->router->add('setup', function() use ($app) {
	
	$app->theme->setTitle('Setting up');
	
	//if (isset($app->userInfo['admin'])) {
		$app->dispatcher->forward([
			'controller' => 'question',
			'action'     => 'setup',
		]);
		
		$app->dispatcher->forward([
			'controller' => 'tags',
			'action'     => 'setup',
		]);
		
		$app->dispatcher->forward([
			'controller' => 'answer',
			'action'     => 'setup',
		]);
		
		$app->dispatcher->forward([
			'controller' => 'comment',
			'action'     => 'setup',
		]);
		
		$app->dispatcher->forward([
			'controller' => 'users',
			'action'     => 'setup',
		]);
	/* } else {
		die("You don't have clearance to do a reset of the database.");
	} */

});

$app->router->add('about', function() use ($app) {
	
	$app->theme->setTitle('About page');
	
	$content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');

    $app->views->add('default/page', [
        'content' => $content,
        'byline' => $byline,
    ]);

});

$app->router->add('admin', function() use ($app) {
	
	$app->theme->setTitle('Admin page');
	
	if (isset($app->userInfo['admin'])) 
	{
		$app->views->add('admin/admin', []);
	} else {
		$app->redirectTo('');
	}

});

$app->router->handle();
$app->theme->render();
