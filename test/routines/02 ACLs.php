<?php

namespace Dotink\Lab;

use iMarc\Auth\ACL;

return [
	'setup' => function($data, $shared) {
		needs($data['root'] . '/src/ACL.php');

		$shared->acl = new ACL();

		$shared->acl->allow('Admin',  'User',    ['create', 'read', 'update', 'delete', 'permit']);
		$shared->acl->allow('Admin',  'Article', ['create', 'read', 'UPDATE', 'delete', 'permit']);
		$shared->acl->allow('editor', 'Article', ['create', 'read', 'update', 'delete']);
	},

	'tests' => [

		/**
		 *
		 */
		'Allowing (Check Roles)' => function($data, $shared) {
			assert('iMarc\Auth\ACL::getRoles')
				-> using($shared->acl)
				-> equals(['admin', 'editor']);
		},

		/**
		 *
		 */
		'Allowing (Check Permissions)' => function($data, $shared) {
			assert('iMarc\Auth\ACL::getPermissions')
				-> with('admin')
				-> using($shared->acl)
				-> equals([
					'user'    => ['create', 'read', 'update', 'delete', 'permit'],
					'article' => ['create', 'read', 'update', 'delete', 'permit']
				])

				-> with('EDITOR')
				-> using($shared->acl)
				-> equals([
					'article' => ['create', 'read', 'update', 'delete']
				]);
		},

		/**
		 *
		 */
		'Alias' => function($data, $shared) {
			$shared->acl->alias('manage', ['create', 'read', 'update', 'delete']);
			$shared->acl->allow('editor', 'Alert', ['manage']);

			assert('iMarc\Auth\ACL::getPermissions')
				-> with('editor')
				-> using($shared->acl)
				-> equals([
					'article' => ['create', 'read', 'update', 'delete'],
					'alert'   => ['create', 'read', 'update', 'delete']
				]);

		},

		/**
		 *
		 */
		'Recursive Alias' => function($data, $shared) {
			$shared->acl->alias('admin', ['manage', 'permit']);
			$shared->acl->allow('editor', 'Article', ['admin']);

			assert('iMarc\Auth\ACL::getPermissions')
				-> with('editor')
				-> using($shared->acl)
				-> equals([
					'article' => ['create', 'read', 'update', 'delete', 'permit'],
					'alert'   => ['create', 'read', 'update', 'delete']
				]);
		}
	]
];
