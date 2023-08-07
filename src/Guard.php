<?php

namespace Auth;

use InvalidArgumentException;

/**
 * Manages request path based access control lists and entity authorization against them
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
 *
 * @license MIT
 *
 * @package Auth
 */
class Guard
{
	/**
	 * Rules for accepting a request
	 *
	 * @access protected
	 * @var array<string,array<string>>
	 */
	protected $acceptRules = array();


	/**
	 * The default rule to apply to a request
	 *
	 * @access protected
	 * @var string
	 */
	protected $defaultRule = 'accept';


	/**
	 * Whether or not last checked request has been granted access
	 *
	 * @var bool
	 */
	protected $granted = FALSE;


	/**
	 * The role which indicates someone is an authorized user (is logged in)
	 *
	 * @access protected
	 * @var string|null
	 */
	protected $userRole = NULL;


	/**
	 * Rules for rejecting a request
	 *
	 * @access protected
	 * @var array<string,array<string>>
	 */
	protected $rejectRules = array();


	/**
	 * Add accept rules to the guard
	 *
	 * @access public
	 * @param array<string,array<string>> $rules
	 * @return self
	 */
	public function addAcceptRules(array $rules): self
	{
		foreach ($rules as $path => $roles) {
			$rules[$path] = array_map('strtolower', $roles);
		}

		$this->acceptRules = array_merge($this->acceptRules, $rules);

		return $this;
	}


	/**
	 * Add reject rules to the guard
	 *
	 * @access public
	 * @param array<string,array<string>> $rules
	 * @return self
	 */
	public function addRejectRules(array $rules): self
	{
		foreach ($rules as $path => $roles) {
			$rules[$path] = array_map('strtolower', $roles);
		}

		$this->rejectRules = array_merge($this->rejectRules, $rules);

		return $this;
	}


	/**
	 * Check if a set of roles authorizes a user for a configured request path
	 *
	 * @access public
	 * @param string $request_path
	 * @param array<string> $roles
	 * @return bool|null
	 */
	public function check(string $request_path, array $roles): ?bool
	{
		$roles = array_map('strtolower', $roles);

		if ($this->defaultRule == 'accept') {
			$this->granted = TRUE;

			$this->processRejectRules($request_path, $roles);
			$this->processAcceptRules($request_path, $roles);

		} else {
			$this->granted = FALSE;

			$this->processAcceptRules($request_path, $roles);
			$this->processRejectRules($request_path, $roles);
		}

		if ($this->granted) {
			return TRUE;
		}

		if ($this->isLoggedIn($roles)) {
			return FALSE;
		}

		return NULL;
	}


	/**
	 * Set the default rule
	 *
	 * @access public
	 * @param string $rule
	 * @return self
	 */
	public function setDefaultRule(string $rule): self
	{
		$rule = strtolower($rule);

		if (!in_array($rule, ['accept', 'reject'])) {
			throw new InvalidArgumentException(sprintf(
				'Default rule must be one of "accept" or "reject"'
			));
		}

		$this->defaultRule = $rule;

		return $this;
	}


	/**
	 * Set the user role
	 *
	 * @access public
	 * @param string $role
	 * @return self
	 */
	public function setUserRole(string $role): self
	{
		$this->userRole = strtolower($role);

		return $this;
	}


	/**
	 * Check if a set of roles represent an authorized user
	 *
	 * @access public
	 * @param array<string> $roles
	 * @return bool
	 */
	protected function isLoggedIn(array $roles): bool
	{
		return in_array($this->userRole, $roles);
	}


	/**
	 * Modify granted access according to accept rules
	 *
	 * @access protected
	 * @param string $request_path
	 * @param array<string> $roles
	 * @return void
	 */
	protected function processAcceptRules(string $request_path, array $roles): void
	{
		$accept = FALSE;

		foreach ($this->acceptRules as $path => $authorized_roles) {
			if (!preg_match('#^' . $path. '$#i', $request_path, $matches)) {
				continue;
			}

			foreach ($authorized_roles as $authorized_role) {
				if ($authorized_role[0] == '!') {
					$accept = $accept || !in_array(substr($authorized_role, 1), $roles);
				} else {
					$accept = $accept ||  in_array($authorized_role, $roles);
				}
			}
		}

		if ($accept) {
			$this->granted = TRUE;
		}
	}


	/**
	 * Modify granted access according to reject rules
	 *
	 * @access protected
	 * @param string $request_path
	 * @param array<string> $roles
	 * @return void
	 */
	protected function processRejectRules(string $request_path, array $roles): void
	{
		$reject = FALSE;

		foreach ($this->rejectRules as $path => $unauthorized_roles) {
			if (!preg_match('#^' . $path. '$#i', $request_path, $matches)) {
				continue;
			}

			foreach ($unauthorized_roles as $unauthorized_role) {
				if ($unauthorized_role[0] == '!') {
					$reject = $reject || !in_array(substr($unauthorized_role, 1), $roles);
				} else {
					$reject = $reject ||  in_array($unauthorized_role, $roles);
				}
			}
		}

		if ($reject) {
			$this->granted = FALSE;
		}
	}
}
