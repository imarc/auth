<?php

namespace iMarc\Auth;

/**
 * The interface for providing access control list information
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
 *
 * @license MIT
 *
 * @package Auth
 */
interface ACLInterface
{
	/**
	 * Get the permissions allowed by the access control list for a given role
	 *
	 * @access public
	 * @param string $role The role to get permissions for
	 * @return array The allowed permissions
	 */
	public function getPermissions($role);


	/**
	 * Get the roles supported by the access control list
	 *
	 * @access public
	 * @return array The supported roles
	 */
	public function getRoles();
}
