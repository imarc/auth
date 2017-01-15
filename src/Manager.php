<?php

namespace iMarc\Auth;

/**
 * Manages access control lists and entity authorization against them
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
 *
 * @license MIT
 *
 * @package Auth
 */
class Manager
{
	/**
	 * A list of added ACLs
	 *
	 * @access private
	 * @var array
	 */
	private $acls = array();


	/**
	 * The entity which we are authorizing
	 *
	 * @access private
	 * @var EntityInterface
	 */
	private $entity = NULL;


	/**
	 * Custom logic for specific authorization checks
	 *
	 * @access private
	 * @var array
	 */
	private $overrides = array();


	/**
	 * The composite permissions for the entity based on all provided ACLs, custom permissions, etc
	 *
	 * @access private
	 * @var array
	 */
	private $permissions = array();


	/**
	 * Create a new manager
	 *
	 * @access public
	 * @param EntityInterface $entity An object entity for which we're authorizing actions
	 * @return void
	 */
	public function __construct(EntityInterface $entity = NULL)
	{
		if ($entity) {
			$this->setEntity($entity);
		}
	}


	/**
	 * Allow one way access to all private properties
	 *
	 * @access public
	 * @param string $property The property to retrieve
	 * @return mixed The property
	 */
	public function __get($property)
	{
		return $this->$property;
	}


	/**
	 * Add an access control list to the manager
	 *
	 * @access public
	 * @param ACLInterface $acl The access control list to add
	 * @return Manager The called instance for method chaining
	 */
	public function add(ACLInterface $acl)
	{
		$this->acls[] = $acl;

		if ($this->entity) {
			$this->importAcl($acl);
			$this->refresh();
		}


		return $this;
	}


	/**
	 * Check whether or not the managed entity has a permission on another entity
	 *
	 * The provided `$entity` can be a string (usually a class name) or object, which will be
	 * converted to its classname, or an object implementing `AuthInterface` for custom
	 * authorization logic.
	 *
	 * @access public
	 * @param string $permission The permission to check for
	 * @param string|Object|AuthInterface $entity An entity to check permissions on
	 * @return boolean TRUE if the managed entity has the permission on the provided entity
	 */
	public function can($permission, $entity)
	{
		$target = $this->resolve($entity);

		if (isset($this->overrides[$target][$permission])) {
			return (bool) $this->overrides[$target][$permission]($this, $entity);
		}

		if ($entity instanceof AuthInterface) {
			return $entity->can($this, $permission);
		}

		return $this->check($permission, $target);
	}


	/**
 	 *
	 */
	public function getEntity()
	{
		return $this->entity;
	}


	/**
	 * Check whether the managed entity has a permission on another another entity per the ACLs
	 *
	 * Unlike `can()` this method is designed to check the ACLs alone.  It invokes no custom
	 * logic or overrides and is purely based on ACLs and permissions.
	 *
	 * The provided `$entity` can be a string (usually a class name) or object, which will be
	 * converted to its classname.
	 *
	 * @param string $permission The permission to check for
	 * @param string|Object $entity An entity to check permissions on	 *
	 * @access public
	 */
	public function has($permission, $entity)
	{
		$target = $this->resolve($entity);

		return $this->check($permission, $target);
	}


	/**
	 * Check whether or not the managed entity has a certain role
	 *
	 * @access public
	 * @param string $role The role to check the managed entity for
	 * @return boolean TRUE if the managed entity has that role, FALSE otherwise
	 */
	public function is($role)
	{
		return in_array(strtolower($role), $this->roles);
	}


	/**
	 * Override permission checks on a particular target with custom logic
	 *
	 * The callback will receive the calling auth manager and the entity to check against such
	 * as $callback(Manager $manager, $entity), note, the entity is passed unresolved so it could
	 * be a string, or object/AuthInterface implementation, the callback is responsible for
	 * determining which action it should take based on the type.
	 *
	 * @access public
	 * @param string $target The target to overide on
	 * @param string $permission The permission to provide logic for
	 * @param callable $callback The callback which handles the logic
	 * @return Manager The called instance for method chaining
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
	public function setEntity(EntityInterface $entity)
	{
		$this->permissions = array();
		$this->entity      = $entity;
		$this->roles       = array_map('strtolower', $this->entity->getRoles());

		foreach ($this->acls as $acl) {
			$this->importAcl($acl);
		}

		$this->refresh();

		return $this;
	}


	/**
	 * Check a permission is in the ACL.
	 *
	 * @access private
	 * @param string $permission The permission to check
	 * @param string $target The resolved target to check on
	 * @return boolean TRUE if the permissions is granted in the ACL, FALSE otherwise
	 */
	private function check($permission, $target)
	{
		if (!isset($this->permissions[$target])) {
			return FALSE;
		}

		return in_array($permission, $this->permissions[$target]);
	}


	/**
	 *
	 */
	private function importAcl($acl)
	{
		$acl_roles    = $acl->getRoles();
		$import_roles = array_intersect($acl_roles, $this->roles);

		foreach ($import_roles as $import_role) {
			$this->permissions = array_merge_recursive(
				$this->permissions,
				$acl->getPermissions($import_role)
			);
		}
	}


	/**
	 * Compile all permissions using the managed entities permissions as static overrides
	 *
	 * @access private
	 * @return void
	 */
	private function refresh()
	{
		foreach ($this->entity->getPermissions() as $target => $permissions) {
			$this->permissions[strtolower($target)] = array_map('strtolower', $permissions);
		}

		foreach ($this->permissions as $target => $permissions) {
			$this->permissions[$target] = array_unique($permissions);
		}
	}


	/**
	 * Resolve an entity to it's target name
	 *
	 * @access private
	 * @param string|Object $entity The entity to resolve
	 * @return string The lowercase target name representing the entity
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
