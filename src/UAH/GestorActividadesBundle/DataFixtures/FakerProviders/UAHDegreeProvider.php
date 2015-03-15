<?php

namespace UAH\GestorActividadesBundle\DataFixtures\FakerProviders;

//UAH\GestorActividadesBundle\DataFixtures\FakerProviders;

class UAHDegreeProvider extends \Faker\Provider\Base
{
    protected static $knowledge_areas = array(
        "Ciencias",
        "Ciencias de la Salud",
        "Ciencias Sociales y Jurídicas",
        "Ingeniería y Arquitectura",
        "Artes y Humanidades",
    );

    /**
     * Converts Spanish characters to their ASCII representation using an standard
     * chars convert function
     *
     * @return string
     */
    private static function toAscii($string)
    {
        $from = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ü', 'Ü', 'ñ', 'Ñ');
        $to = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'u', 'U', 'n', 'N');

        return str_replace($from, $to, $string);
    }

    public function knowledge_areas() {
        return static::randomElement(static::$knowledge_areas);
    }

    public function statusDegree($array_of_statuses) {
        return static::randomElement($array_of_statuses);
    }

}
