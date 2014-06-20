<?php

namespace UAH\GestorActividadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActivityType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('englishName')
                ->add('numberOfECTSCreditsMin')
                ->add('numberOfECTSCreditsMax')
                ->add('numberOfCreditsMin')
                ->add('numberOfCreditsMax')
                //->add('isPublic')
                ->add('celebrationDates')
                ->add('hasAdditionalWorkload')
                ->add('numberOfHours')
                ->add('assistanceControl')
                ->add('publicityStartDate')
                ->add('registrationManagement')
                //->add('extraInformationFile')
                ->add('url')
                //->add('slug')
                ->add('numberOfPlacesOffered')
                //->add('numberOfPlacesOccupied')
                //->add('approvedByComitee')
                //->add('isActive')
                ->add('cost')
                ->add('description')
                ->add('image_blob', 'file')
                //->add('Organizer')
                //->add('studentProfile')
                //->add('status')
                ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'UAH\GestorActividadesBundle\Entity\Activity'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'uah_gestoractividadesbundle_activity';
    }

}
