<?php

namespace Dotink\Lab;

use iMarc\Auth\ACL;
use iMarc\Auth\Manager;
use Dotink\Parody\Mime;

return [
	'setup' => function($data, $shared) {

		Mime::define('User')->implementing('iMarc\Auth\EntityInterface');

		needs($data['root'] . '/src/ACLInterface.php');
		needs($data['root'] . '/src/ACL.php');
		needs($data['root'] . '/src/Manager.php');

		$shared->user    = Mime::create('User')
				->onCall('getRoles')->give(['Editor', 'User'])
				->onCall('getPermissions')->give(['user' => ['read']])
				->resolve();

		$shared->manager = new Manager($shared->user);
		$shared->acl     = new ACL();

		$shared->acl->allow('Admin',  'User',    ['create', 'read', 'update', 'delete', 'permit']);
		$shared->acl->allow('Admin',  'Article', ['create', 'read', 'UPDATE', 'delete', 'permit']);
		$shared->acl->allow('editor', 'Article', ['create', 'read', 'update', 'delete']);
		$shared->acl->allow('user',   'Article', ['read']);

		$shared->manager->add($shared->acl);


	},

	'tests' => [

		/**
		 *
		 */
		'Can' => function($data, $shared) {
			assert('iMarc\Auth\Manager::can')
				-> using($shared->manager)
				-> with('create', 'user')
				-> equals(FALSE)

				-> with('read', 'article')
				-> equals(TRUE)

				-> with('read', 'user')
				-> equals(TRUE)
			;

			$shared->manager->override('User', 'create', function($auth, $user) {
					return TRUE;
			});

			assert('iMarc\Auth\Manager::can')
				-> using($shared->manager)
				-> with('create', 'user')
				-> equals(TRUE)
			;

		},

		/**
		 *
		 */
		'Has' => function($data, $shared) {
			assert('iMarc\Auth\Manager::has')
				-> using($shared->manager)
				-> with('create', 'user')
				-> equals(FALSE)

				-> with('read', 'article')
				-> equals(TRUE)
			;
		},

		/**
		 *
		 */
		'Is' => function($data, $shared) {
			assert('iMarc\Auth\Manager::is')
				-> using($shared->manager)
				-> with('Admin')
				-> equals(FALSE)

				-> with('EDITOR')
				-> equals(TRUE)
			;
		},

	]
];
