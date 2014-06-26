<?php

namespace UAH\GestorActividadesBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\SecurityContext;

class ActivityVoter implements VoterInterface {

    const VIEW = 'view';
    const EDIT = 'edit_activity';
    const ADMIN = 'admin_activity';

    private $em;
    private $security;

    public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Component\Security\Core\SecurityContext $security = null) {
        $this->em = $em;
        $this->security = $security;
    }

    public function supportsAttribute($attribute) {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::ADMIN
        ));
    }

    public function supportsClass($class) {
        $supportedClass = 'UAH\GestorActividadesBundle\Entity\Activity';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @var \UAH\GestorActividadesBundle\Entity\Activity $activity
     */
    public function vote(TokenInterface $token, $activity, array $attributes) {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($activity))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        //Compruebo que el votador se está usando correctamente
        if (1 !== count($attributes)) {
            throw new InvalidArgumentException(
            'Only one attribute is allowed for VIEW or EDIT or ADMIN'
            );
        }
        //Atributo que vamos a comprobar
        $attribute = $attributes[0];

        //Usuario logueado
        $user = $token->getUser();

        //Compruebo que el atributo que voy a comprobar esté soportado
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }
        //error_log(strlen(($user,true)));
        switch ($attribute) {
            case 'view_':
                // the data object could have for example a method isPrivate()
                // which checks the Boolean attribute $private
//                if (!$activity->getStatus()) {
                return VoterInterface::ACCESS_GRANTED;
//                }
                break;

            case 'edit_activity':
            case 'admin_activity':
                //Compruebo que es el organizador, si no es así miro si tiene el rol de admin
                if ($user->getId() === $activity->getOrganizer()->getId()) {
                    return VoterInterface::ACCESS_GRANTED;
                } else {
                    return VoterInterface::ACCESS_ABSTAIN;
                }
        }
    }
    
    

}
