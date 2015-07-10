<?php

namespace UAH\GestorActividadesBundle\Security\User;

use Fp\OpenIdBundle\Model\UserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Doctrine\ORM\EntityManager;
use UAH\GestorActividadesBundle\Entity\OpenIdIdentity;
use UAH\GestorActividadesBundle\Entity\DefaultPermit as DefaultPermit;
use UAH\GestorActividadesBundle\Entity\User as User;
use DateTime;
use Psr\Log\LoggerInterface;

class OpenIdUserManager extends UserManager
{
    protected $entityManager;
    protected $logger;

    // we will use an EntityManager, so inject it via constructor
    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager, LoggerInterface $logger)
    {
        parent::__construct($identityManager);

        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param string $identity
     *                           an OpenID token. With Google it looks like:
     *                           https://www.google.com/accounts/o8/id?id=SOME_RANDOM_USER_ID
     * @param array  $attributes
     *                           requested attributes (explained later). At the moment just
     *                           assume there's a 'contact/email' key
     */
    public function createUserFromIdentity($identity, array $attributes = array())
    {
        $this->logger->debug('Creando usuario...');
        //Capture the username and the domain
        $reg_ex = '/^https?:\/\/yo\.rediris\.es\/soy\/((.+)\.(.+))@(\w+)\.+[a-z]{2,4}\/?/';
        preg_match($reg_ex, $identity, $matches);
        $email = $matches[1] . '@edu.uah.es';
        $name = $matches[2];
        $apellido = $matches[3];
// put your user creation logic here
// what follows is a typical example
//        if (false === isset($attributes['mail'])) {
//            throw new \Exception('Wea need your e-mail address!'.$attributes);
//        }
// in this example, we fetch User entities by e-mail
        $user = $this->entityManager->getRepository('UAHGestorActividadesBundle:User')->findOneBy(array(
            'id_usuldap' => $identity,
        ));

        if (null === $user) {
            $this->logger->debug('Usuario no encontrado. Creandolo desde 0');
            $user = new User();
            $user->setEmail($email);
            $user->setName($name);
            $user->setApellido1($apellido);
            $user->setIdUsuldap($identity);
            $user->setDateCreated(new DateTime());
            $user->setDateUpdated(new DateTime());

            $default_permits = $this->entityManager->getRepository('UAHGestorActividadesBundle:DefaultPermit')->findOneBy(
                    array('id_usuldap' => $identity));
            if ($default_permits) {
                $roles = $default_permits->getRoles();
                foreach ($roles as $role) {
                    $user->addRole($role);
                }
            } else {
                $role = $this->entityManager
                                ->getRepository('UAHGestorActividadesBundle:Role')->findOneBy(
                        array('role' => 'ROLE_UAH_STUDENT'));
                $user->addRole($role);
            }
        }
        // we create an OpenIdIdentity for this User
        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->persist($openIdIdentity);
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (\Exception $e) {
            $this->logger->error("Excepcion al guardar identidad.Mensaje: {$e->getMessage()}, codigo: {$e->getCode()}");
        }

        return $user; // you must return an UserInterface instance (or throw an exception)
    }
}
