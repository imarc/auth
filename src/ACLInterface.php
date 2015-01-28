<?php

namespace iMarc\Auth;

interface ACLInterface
{
	public function getRoles();
	public function getPermissions($role);
}
