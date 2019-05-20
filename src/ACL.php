<?php

namespace iMarc\Auth;

/**
 * A static ACL implementation which allows for aliasing and simple access registration
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
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
	 * @var array
	 */
	private $aliases = array();


	/**
	 * Permissions data
	 *
	 * @access private
	 * @var array
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
	 * @param array $actions The actions which the action alias should resolve to
	 * @return ACL The called instance for method chaining
	 */
	public function alias($action, Array $actions)
	{
		$this->aliases[$action] = $actions;

		return $this;
	}


	/**
	 * Allow a particular role to take actions on a given target
	 *
	 * @access public
	 * @param string $role The role to allow
	 * @param string $target The target to allow permissions on (usually a classname)
	 * @param string|array $actions The permission(s) to allow
	 * @return ACL The called instance for method chaining
	 */
	public function allow($role, $target, $actions)
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
	 * @return array The allowed permissions
	 */
	public function getPermissions($role)
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
	 * @return array The supported roles
	 */
	public function getRoles()
	{
		return array_keys($this->data);
	}


	/**
	 * Resolve action aliases
	 *
	 * @access private
	 * @param string|array $actions The action(s) to resolve
	 * @return array The resolved actions
	 */
	private function resolve($actions)
	{
		settype($actions, 'array');

		foreach ($actions as $i => $action) {
			if (isset($this->aliases[$action])) {
				unset($actions[$i]);

				$actions = array_merge($actions, $this->resolve($this->aliases[$action]));
			}
		}

		return array_map('strtolower', $actions);
	}
}
