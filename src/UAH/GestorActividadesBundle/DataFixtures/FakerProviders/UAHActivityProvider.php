<?php

namespace UAH\GestorActividadesBundle\DataFixtures\FakerProviders;

//UAH\GestorActividadesBundle\DataFixtures\FakerProviders;

class UAHActivityProvider extends \Faker\Provider\Base
{
    protected static $status = array(
        "Activo",
        "Pendiente de Aprobación",
        "Publicado",
        "Cerrada",
    );

    public function statusActivity(){
        return static::randomElement(static::$status);
    }
}
