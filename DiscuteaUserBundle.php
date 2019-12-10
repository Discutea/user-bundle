<?php

namespace Discutea\UserBundle;

use Discutea\UserBundle\DependencyInjection\Compiler\DiscuteaUserExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DiscuteaUserBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new DiscuteaUserExtension();
    }
}
