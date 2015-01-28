<?php

namespace iMarc\Auth;

interface EntityInterface
{
	public function getRoles();
	public function getPermissions();
}
