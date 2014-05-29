<?php

namespace UAH\GestorActividadesBundle\Security\User;

use Fp\OpenIdBundle\Model\UserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Doctrine\ORM\EntityManager;
use UAH\GestorActividadesBundle\Entity\OpenIdIdentity;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use UAH\GestorActividadesBundle\Entity\User as User;

class OpenIdUserManager extends UserManager {

    // we will use an EntityManager, so inject it via constructor
    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager) {
        parent::__construct($identityManager);

        $this->entityManager = $entityManager;
    }

    /**
     * @param string $identity
     *  an OpenID token. With Google it looks like:
     *  https://www.google.com/accounts/o8/id?id=SOME_RANDOM_USER_ID
     * @param array $attributes
     *  requested attributes (explained later). At the moment just
     *  assume there's a 'contact/email' key
     */
    public function createUserFromIdentity($identity, array $attributes = array()) {
        //Capture the username and the domain
        $reg_ex = '/^https?:\/\/yo\.rediris\.es\/soy\/((.+)\.(.+))@(\w+)\.+[a-z]{2,4}\/?/';
        preg_match($reg_ex, $identity, $matches);
        $email = $matches[0] . '@edu.uah.es';
        $name = $matches[1];
        $apellido = $matches[2];
        // put your user creation logic here
        // what follows is a typical example
//        if (false === isset($attributes['mail'])) {
//            throw new \Exception('Wea need your e-mail address!'.$attributes);
//        }
        // in this example, we fetch User entities by e-mail
        $user = $this->entityManager->getRepository('UAHGestorActividadesBundle:User')->findOneBy(array(
            'id_usuldap' => $identity
        ));

        if (null === $user) {
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            $user->setApellido1($apellido);
        } 
        error_log("Creando identidad para".$identity);
        // we create an OpenIdIdentity for this User
        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);

        $this->entityManager->persist($openIdIdentity);
        $this->entityManager->flush();
        error_log("Identidad creada:".$openIdIdentity->getId());
        $this->entityManager->clear();

        return $user; // you must return an UserInterface instance (or throw an exception)
    }

}
