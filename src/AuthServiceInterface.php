<?php

namespace iMarc\Auth;

/**
 * An interface for objects which provide customized authorization logic for an external context
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
 *
 * @license MIT
 *
 * @package Auth
 */
interface AuthServiceInterface
{
	public function __invoke(Manager $manager, $context, $permission);
}
