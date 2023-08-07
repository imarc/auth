<?php

use PHPUnit\Framework\TestCase;

final class GuardTest extends TestCase
{
	/**
	 * @var Auth\Guard|null
	 */
	protected $guard = NULL;


	/**
	 * Setup
	 */
	public function setUp(): void
	{
		$this->guard = new Auth\Guard();
	}


	/**
	 *
	 */
	public function testVoid()
	{
		$this->assertEquals(TRUE, TRUE);
	}
}
