<?php

use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
	public function setUp(): void
	{
		$this->manager = new Auth\Manager();
		$acl           = new Auth\ACL();

		$acl->alias('manage', ['create', 'read', 'update', 'delete']);
		$acl->allow('admin', 'user', ['manage']);
		$acl->allow('admin', 'article', ['manage']);
		$acl->allow('admin', 'apple', ['read']);

		$this->manager->add($acl);
		$this->manager->setEntity(new class implements Auth\EntityInterface {
			public function getRoles(): array {
				return ['admin'];
			}

			public function getPermissions(): array {
				return array();
			}
		});
	}


	public function testIs()
	{
		$this->assertEquals($this->manager->is('admin'), TRUE);
		$this->assertEquals($this->manager->is('user'), FALSE);
	}


	public function testCan()
	{
		$this->assertEquals($this->manager->can('read', 'user'), TRUE);
		$this->assertEquals($this->manager->can('manage', 'user'), TRUE);
		$this->assertEquals($this->manager->can('update', 'apple'), FALSE);
	}
}
