<?php

namespace Unica\Bundle\InnovareBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UnicaInnovareBundle extends Bundle
{
	public function getParent()
	{
		return 'SonataDemoBundle';
	}
}
