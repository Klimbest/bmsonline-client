<?php

namespace BmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BmsBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }
}
