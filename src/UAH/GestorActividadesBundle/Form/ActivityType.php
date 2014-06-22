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
                ->add('name', null, array('label' => 'Nombre'))
                ->add('englishName', null, array('label' => 'Nombre (Inglés)'))
                ->add('organizerName', null, array('label' => 'Nombre de la organización', 'attr' => array('help_text' => 'Ej. Facultad de..., Cátedra Vodafone...')))
                ->add('numberOfECTSCreditsMin', 'number', array('precision' => 2, 'label' => 'Créditos ECTS mínimos'))
                ->add('numberOfECTSCreditsMax', 'number', array('precision' => 2, 'label' => 'Créditos ECTS máximos'))
                ->add('numberOfCreditsMin', 'number', array('precision' => 2, 'label' => 'Créditos de libre mínimos'))
                ->add('numberOfCreditsMax', 'number', array('precision' => 2, 'label' => 'Créditos de libre máximos'))
                //->add('isPublic')
                ->add('celebrationDates')
                ->add('celebrationDatesUnencoded','text', array('label' => 'Fecha(s) en las que se va a celebrar la actividad', 'attr' => array('class' => 'celebration_dates','help_text' => 'Haz click y selecciona varias')))
                ->add('numberOfHours', 'number', array('precision' => 2, 'label' => 'Número de horas'))
                ->add('assistanceControl', 'text', array('label' => 'Metodo de control de asistencia', 'attr' => array('help_text' => 'Hoja de firmas, escaneo de tarjeta...')))
                ->add('fecha_publicidad', 'text', array('label' => 'Fecha en la que quieres que empiece a ser pública la actividad', 'attr' => array('class' => 'publicity_date','help_text' => 'Esta fecha es la fecha en la que la actividad estará disponible para el público una vez aprobada por la comisión. Vacío significa ahora mismo'), 'mapped' => false ,'required' => false))
                //->add('extraInformationFile')
                ->add('url', null, array('label' => 'Página web','required' => false))
                //->add('slug')
                ->add('numberOfPlacesOffered', 'number', array('label' => 'Número de plazas', 'attr' => array('help_text' => 'Vacío para ilimitadas','required' => false)))
                //->add('numberOfPlacesOccupied')
                //->add('approvedByComitee')
                //->add('isActive')
                ->add('cost', null, array('label' => 'Precio'))
                ->add('description', 'textarea', array('label' => 'Descripción', 'attr' => array('rows' => 20)))
                ->add('hasAdditionalWorkload', null, array('label' => '¿Tiene trabajo adicional?'))
                ->add('image_blob', 'file',array('required' => false))//array('required' => false)))
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
            'data_class' => 'UAH\GestorActividadesBundle\Entity\Activity',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention' => 'activity_item',
            'edit' => false
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'uah_gestoractividadesbundle_activity';
    }

}
