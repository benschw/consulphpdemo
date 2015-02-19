<?php

namespace MyApp\Example;

use Fliglio\Flfc\Context;
use Fliglio\Routing\Routable;

use Fliglio\Fltk\View;
use Fliglio\Fltk\JsonView;

class Services implements Routable {

	private $context;

	public function __construct(Context $context) {
		$this->context = $context;
	}
	
	public function foo() {
		return new JsonView(array("foo"));
	}

	public function bar() {
		return new JsonView(array("bar"));
	}

	public function health() {
		return new JsonView(array("status" => "UP"));
	}
	
}