<?php

namespace Auth;

use InvalidArgumentException;

/**
 *
 */
class Guard
{
	/**
	 *
	 */
	protected $acceptRules = array();


	/**
	 *
	 */
	protected $defaultRule = 'accept';


	/**
	 *
	 */
	protected $granted = FALSE;


	/**
	 *
	 */
	protected $userRole = NULL;


	/**
	 *
	 */
	protected $rejectRules = array();


	/**
	 *
	 */
	public function addAcceptRules(array $rules): Guard
	{
		foreach ($rules as $path => $roles) {
			$rules[$path] = array_map('trim', $roles);
		}

		$this->acceptRules = array_merge($this->acceptRules, $rules);

		return $this;
	}


	/**
	 *
	 */
	public function addRejectRules(array $rules)
	{
		foreach ($rules as $path => $roles) {
			$rules[$path] = array_map('trim', $roles);
		}

		$this->rejectRules = array_merge($this->rejectRules, $rules);

		return $this;
	}


	/**
	 *
	 */
	public function check(string $request_path, array $roles): ?bool
	{
		$roles = array_map('strtolower', $roles);

		if (!$roles) {
			return $this->defaultRule == 'accept';
		}

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
	 *
	 */
	public function setDefaultRule($rule): Guard
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
	 *
	 */
	public function setUserRole(string $role): Guard
	{
		$this->userRole = strtolower($role);
		return $this;
	}


	/**
	 *
	 */
	protected function isLoggedIn(array $roles)
	{
		return in_array($this->userRole, $roles);
	}


	/**
	 *
	 */
	protected function processAcceptRules($request_path, $roles)
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
	 *
	 */
	protected function processRejectRules($request_path, $roles)
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
