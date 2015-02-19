<?php
namespace MyApp;

use Fliglio\Flfc\Context;
use Fliglio\Flfc\Request;
use Fliglio\Flfc\Response;
use Fliglio\Flfc as flfc;
use Fliglio\Flfc\NamespaceFcChainResolver;
use Fliglio\Flfc\DefaultFcChainResolver;
use Fliglio\Flfc\FcChainFactory;
use Fliglio\Flfc\FcChainRunner;
use Fliglio\Routing as routing;
use Fliglio\Routing\RouteMap;


require_once __DIR__ . '/../fliglio/bootstrap.php';



// Configure Routing
$routeMap = new RouteMap();
$routeMap
	->connect("api", new routing\PatternRoute('/api/:command', array(
		'ns'           => 'MyApp\Example',
		'commandGroup' => 'Services',
	)))
	->connect("error", new routing\CatchNoneRoute(array(
		'cmd' => 'MyApp\Example.Errors.handleError',
	)))
	->connect("404", new routing\CatchAllRoute(array(
		'cmd' => 'MyApp\Example.Errors.pageNotFound',
	)));






// Configure Front Controller Chain & Default Resolver
$htmlChain = new flfc\HttpApp(new flfc\ServeHtmlApp(dirname(__FILE__) . '/index.html'));
$apiChain  = new flfc\HttpApp(new routing\UriLintApp(new routing\RoutingApp(new routing\RestInvokerApp(), $routeMap)));

FcChainFactory::addResolver(new DefaultFcChainResolver($htmlChain));
FcChainFactory::addResolver(new NamespaceFcChainResolver($apiChain, 'api'));

// Run App
$context = new Context(Request::createDefault(), new Response());
$chainRunner = new FcChainRunner();
$chainRunner->dispatchRequest($context, "@404", "@error");
