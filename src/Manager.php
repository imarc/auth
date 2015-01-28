<?php

namespace iMarc\Auth;

/**
 *
 */
class Manager
{
	/**
	 *
	 */
	private $acls = array();


	/**
	 *
	 */
	private $entity = NULL;


	/**
	 *
	 */
	private $overrides = array();


	/**
	 *
	 */
	public function __construct(EntityInterface $entity)
	{
		$this->entity = $entity;
		$this->roles  = array_map('strtolower', $this->entity->getRoles());
	}


	/**
	 *
	 */
	public function __get($property)
	{
		return $this->$property;
	}


	/**
	 *
	 */
	public function add(ACLInterface $acl)
	{
		$acl_roles    = $acl->getRoles();
		$import_roles = array_intersect($acl_roles, $this->roles);

		foreach ($import_roles as $import_role) {
			$this->acls = array_merge_recursive($this->acls, $acl->getPermissions($import_role));
		}

		$this->refresh();
	}


	/**
	 *
	 */
	public function can($permission, $entity)
	{
		$target = $this->resolve($entity);

		if (isset($this->overrides[$target][$permission])) {
			return (bool) $this->overrides[$target][$permission]($this, $entity);
		}

		return $this->check($permission, $target);
	}


	/**
	 *
	 */
	public function has($permission, $entity)
	{
		$target = $this->resolve($entity);

		return $this->check($permission, $target);
	}


	/**
	 *
	 */
	public function is($role)
	{
		return in_array(strtolower($role), $this->roles);
	}


	/**
	 *
	 */
	public function override($target, $permission, Callable $callback)
	{
		$target     = strtolower($target);
		$permission = strtolower($permission);

		if (!isset($this->overrides[$target])) {
			$this->overrides[$target] = array();
		}

		$this->overrides[$target][$permission] = $callback;

		return $this;
	}


	/**
	 *
	 */
	private function check($permission, $target)
	{
		if (!isset($this->acls[$target])) {
			return FALSE;
		}

		return in_array($permission, $this->acls[$target]);
	}


	/**
	 *
	 */
	private function refresh()
	{
		foreach ($this->entity->getPermissions() as $target => $permissions) {
			$this->acls[strtolower($target)] = array_map('strtolower', $permissions);
		}

		foreach ($this->acls as $target => $permissions) {
			$this->acls[$target] = array_unique($permissions);
		}

	}


	/**
	 *
	 */
	private function resolve($entity)
	{
		if (is_object($entity)) {
			$target = get_class($entity);
		} else {
			$target = (string) $entity;
		}

		return strtolower($target);
	}
}
