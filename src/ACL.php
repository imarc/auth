<?php

namespace Auth;

/**
 * A static ACL implementation which allows for aliasing and simple access registration
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
 *
 * @license MIT
 *
 * @package Auth
 */
class ACL implements ACLInterface
{
	/**
	 * Aliased permissions
	 *
	 * @access private
	 * @var array<string,array<string>>
	 */
	private $aliases = array();


	/**
	 * Permissions data
	 *
	 * @access private
	 * @var array<string,array<string,array<string>>>
	 */
	private $data = array();


	/**
	 * Alias a number of actions as a single action
	 *
	 * It is important to note that the alias itself is not stored in the permissions but is
	 * expanded to the permissions allowed by it.  Aliasing is simply for use with `allow()`
	 *
	 * @access public
	 * @param string $action The alias action name
	 * @param array<string> $actions The actions which the action alias should resolve to
	 * @return ACL The called instance for method chaining
	 */
	public function alias(string $action, array $actions): ACL
	{
		$this->aliases[strtolower($action)] = array_map('strtolower', $actions);

		return $this;
	}


	/**
	 * Allow a particular role to take actions on a given target
	 *
	 * @access public
	 * @param string $role The role to allow
	 * @param string $target The target to allow permissions on (usually a classname)
	 * @param array<string> $actions The permission(s) to allow
	 * @return ACL The called instance for method chaining
	 */
	public function allow(string $role, string $target, array $actions): ACL
	{
		$role    = strtolower($role);
		$target  = strtolower($target);
		$actions = $this->resolve($actions);


		if (!isset($this->data[$role])) {
			$this->data[$role] = array();
		}

		if (!isset($this->data[$role][$target])) {
			$this->data[$role][$target] = array();
		}

		$this->data[$role][$target] = array_unique(array_merge(
			$this->data[$role][$target],
			$actions
		));

		return $this;
	}


	/**
	 * Get the permissions allowed by the access control list for a given role
	 *
	 * @access public
	 * @param string $role The role to get permissions for
	 * @return array<string,array<string>> The allowed permissions
	 */
	public function getPermissions(string $role): array
	{
		$role = strtolower($role);

		if (isset($this->data[$role])) {
			return $this->data[$role];
		}

		return array();
	}


	/**
	 * Get the roles supported by the access control list
	 *
	 * @access public
	 * @return array<string> The supported roles
	 */
	public function getRoles(): array
	{
		return array_keys($this->data);
	}


	/**
	 * Resolve action aliases
	 *
	 * @access private
	 * @param array<string> $actions The action(s) to resolve
	 * @return array<string> The resolved actions
	 */
	private function resolve(array $actions): array
	{
		$actions  = array_map('strtolower', $actions);
		$resolved = array();

		foreach ($actions as $i => $action) {
			$resolved[] = $action;

			if (isset($this->aliases[$action])) {
				$resolved = array_merge($resolved, $this->resolve($this->aliases[$action]));
			}
		}

		return $resolved;
	}
}
