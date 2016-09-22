<?php

namespace CAS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CASUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
