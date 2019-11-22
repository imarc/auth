<?php

namespace Auth;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class GuardMiddleware implements Middleware, ManagedInterface
{
	/**
	 * @var Manager|null
	 */
	protected $auth = NULL;


	/**
	 * @var Guard|null
	 */
	protected $guard = NULL;


	/**
	 *
	 */
	public function __construct(Guard $guard, ResponseFactory $factory)
	{
		$this->guard   = $guard;
		$this->factory = $factory;
	}


	/**
	 *
	 */
	public function process(Request $request, Handler $handler): Response
	{
		$result = $this->guard->check(
			$request->getUri()->getPath(),
			$this->auth
				? $this->auth->getEntity()->getRoles()
				: array()
		);

		if ($result) {
			return $handler->handle($request);
		} elseif (is_null($result)) {
			return $this->factory->createResponse(401);
		} else {
			return $this->factory->createResponse(403);
		}
	}


	/**
	 *
	 */
	public function setAuthManager(Manager $manager): object
	{
		$this->auth = $manager;

		return $this;
	}
}
