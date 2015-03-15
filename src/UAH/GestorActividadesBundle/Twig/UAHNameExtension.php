<?php

namespace UAH\GestorActividadesBundle\Twig;

use Twig_Extension;

class UAHNameExtension extends Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('uahuser', array($this, 'uahUserFilter')),
        );
    }

    public function uahUserFilter($identity) {
        $pattern = '/^https?:\/\/yo\.rediris\.es\/soy\/(.+)@\w+\.+[a-z]{2,4}\/?/';
        
        if (preg_match($pattern, $identity, $matches) === 1) {
            return $matches[1];
        } else {
            return 'No encontrado';
            //return 'http://yo.rediris.es/soy/adrian.bolonio@uah.es';
        }
    }

    public function getName() {
        return 'uahname_extension';
    }

}
