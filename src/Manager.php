<?php

namespace Auth;

/**
 * Manages access control lists and entity authorization against them
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
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
	 * @var EntityInterface|null
	 */
	private $entity = NULL;



	/**
	 * The composite permissions for the entity based on all provided ACLs, custom permissions, etc
	 *
	 * @access private
	 * @var array
	 */
	private $permissions = array();


	/**
	 * Roles of the currently authorized entity
	 *
	 * @access private
	 * @var array
	 */
	private $roles = array();


	/**
	 * A list of registered auth services
	 *
	 * @access private
	 * @var array
	 */
	private $services = array();



	/**
	 * Add an access control list to the manager
	 *
	 * @access public
	 * @param ACLInterface $acl The access control list to add
	 * @return Manager The called instance for method chaining
	 */
	public function add(ACLInterface $acl): Manager
	{
		$this->acls[] = $acl;

		if ($this->entity) {
			$this->importAcl($acl);
			$this->refresh();
		}


		return $this;
	}


	/**
	 * Check whether or not the managed entity has a permission on another context
	 *
	 * The provided `$context` can be a string (usually a class name) or object, which will be
	 * converted to its classname, or an object implementing `AuthInterface` for custom
	 * authorization logic.
	 *
	 * @access public
	 * @param string $permission The permission to check for
	 * @param string|object|AuthInterface $context A context to check permissions on
	 * @return bool TRUE if the managed entity has the permission, FALSE othrewise
	 */
	public function can($permission, $context): bool
	{
		$can    = NULL;
		$target = $this->resolve($context);

		if ($can === NULL && isset($this->services[$target])) {
			$can = $this->services[$target]($this, $context, $permission);
		}

		if ($can === NULL && isset($this->services['*'])) {
			$can = $this->services['*']($this, $context, $permission);
		}

		if ($can === NULL && $context instanceof AuthInterface) {
			$can = $context->can($this, $permission);
		}

		if ($can === NULL) {
			$can = $this->check($permission, $target);
		}

		return $can;
	}


	/**
	 * Get the current authorized entity
	 *
	 * @return EntityInterface|null The current authorized entity, NULL if none has been set
	 */
	public function getEntity(): ?EntityInterface
	{
		return $this->entity;
	}



	/**
	 * Check whether or not the managed entity has a certain role
	 *
	 * @access public
	 * @param string $role The role to check the managed entity for
	 * @return bool TRUE if the managed entity has that role, FALSE otherwise
	 */
	public function is($role): bool
	{
		return in_array(strtolower($role), $this->roles);
	}


	/**
	 * Register a callable (service) for auth checking on a given target
	 *
	 * @access public
	 * @param string $target The target on which the service operates
	 * @param callable $service The callable service which checks permissions
	 * @return Manager The called instance for method chaining
	 */
	public function register(string $target, callable $service): Manager
	{
		$target = strtolower($target);

		if (!isset($this->services[$target])) {
			$this->services[$target] = array();
		}

		$this->services[$target] = $service;

		return $this;
	}


	/**
	 * Resolve a context to its target name
	 *
	 * @access private
	 * @param string|object $context The context to resolve
	 * @return string The lowercase target name representing the context
	 */
	public function resolve($context): string
	{
		if (is_object($context)) {
			if ($context instanceof ContextInterface) {
				$target = $context->getAuthContext();
			} else {
				$target = get_class($context);
			}

		} else {
			$target = (string) $context;

		}

		return strtolower($target);
	}


	/**
	 * Set the authorized entity
	 *
	 * @access public
	 * @param EntityInterface $entity The entity which is authorized
	 * @return Manager The called instance for method chaining
	 */
	public function setEntity(EntityInterface $entity): Manager
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
	 * @return bool TRUE if the permissions is granted in the ACL, FALSE otherwise
	 */
	private function check($permission, $target): bool
	{
		if (!isset($this->permissions[$target])) {
			return FALSE;
		}

		return in_array($permission, $this->permissions[$target]);
	}


	/**
	 *
	 * @access private
	 * @return Manager The called instance for method chaining
	 */
	private function importAcl(ACLInterface $acl): Manager
	{
		$acl_roles    = $acl->getRoles();
		$import_roles = array_intersect($acl_roles, $this->roles);

		foreach ($import_roles as $import_role) {
			$this->permissions = array_merge_recursive(
				$this->permissions,
				$acl->getPermissions($import_role)
			);
		}

		return $this;
	}


	/**
	 * Compile all permissions using the managed entities permissions as static overrides
	 *
	 * @access private
	 * @return Manager The called instance for method chaining
	 */
	private function refresh(): Manager
	{
		if ($this->entity) {
			foreach ($this->entity->getPermissions() as $target => $permissions) {
				$this->permissions[strtolower($target)] = array_map('strtolower', $permissions);
			}

			foreach ($this->permissions as $target => $permissions) {
				$this->permissions[$target] = array_unique($permissions);
			}
		}

		return $this;
	}
}
