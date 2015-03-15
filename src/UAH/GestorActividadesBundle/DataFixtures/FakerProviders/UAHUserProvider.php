<?php

namespace UAH\GestorActividadesBundle\DataFixtures\FakerProviders;

class UAHUserProvider extends \Faker\Provider\Base {

    protected static $usuarios = array('estudiante', 'pas', 'profesor');

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

    public function nomprs() {
        $nomprs = $this->generator->firstname;

        return $nomprs;
    }

    public function uahDomain() {
        return "edu.uah.es";
    }

    public function ll1prs() {
        return $this->generator->lastname;
    }

    public function ll2prs()
    {
        return $this->generator->lastname;
    }

    public function tlfprs()
    {
        return $this->generator->phoneNumber;
    }

    public function type() {
        return static::randomElement(static::$usuarios);
    }

    public function id_usuldap()
    {
        $format = "http://yo.rediris.es/soy/{{nomprs}}.{{ll1prs}}@uah.es";
        $id_usuldap = $this->generator->parse($format);
        return static::toLower(static::toAscii($id_usuldap));
    }
}
