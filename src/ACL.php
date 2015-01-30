<?php

namespace iMarc\Auth;

/**
 *
 */
class ACL implements ACLInterface
{
	/**
	 *
	 */
	private $aliases = array();


	/**
	 *
	 */
	private $data = array();


	/**
	 *
	 */
	public function alias($action, Array $actions)
	{
		$this->aliases[$action] = $actions;

		return $this;
	}


	/**
	 *
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
	 *
	 */
	public function getRoles()
	{
		return array_keys($this->data);
	}


	/**
	 *
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
	 *
	 */
	private function resolve($actions)
	{
		foreach ($actions as $i => $action) {
			if (isset($this->aliases[$action])) {
				unset($actions[$i]);

				$actions = array_merge(	$actions, $this->resolve($this->aliases[$action]));
			}
		}

		return array_map('strtolower', $actions);
	}
}
