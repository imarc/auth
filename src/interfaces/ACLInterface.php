<?php

namespace Auth;

/**
 * The interface for providing access control list information
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
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
	 * @return array<string, array<string>> The allowed permissions
	 */
	public function getPermissions(string $role): array;


	/**
	 * Get the roles supported by the access control list
	 *
	 * @access public
	 * @return array<string> The supported roles
	 */
	public function getRoles(): array;
}
