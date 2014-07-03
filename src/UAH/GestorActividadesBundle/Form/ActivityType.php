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
                ->add('name', null, array('label' => 'Nombre', 'attr' => array('help_text' => 'Título que tiene la actividad')))
                ->add('englishName', null, array('label' => 'Nombre (Inglés)'))
                ->add('organizerName', null, array('label' => 'Nombre de la organización', 'attr' => array('help_text' => 'Ej. Facultad de..., Cátedra Vodafone...')))
                ->add('numberOfECTSCreditsMin', 'integer', array('label' => 'Créditos ECTS mínimos', 'attr' => array('step' =>'0.01','label_col' => 3, 'widget_col' => 3,'col_size' => 'xs', )))
                //->add('numberOfECTSCreditsMin','integer',array('attr'=>array('step' => '0.01' )))step2
                ->add('numberOfECTSCreditsMax', 'integer', array('label' => 'Créditos ECTS máximos', 'attr' => array('step' =>'0.01','label_col' => 3, 'widget_col' => 3,'col_size' => 'xs' )))
                ->add('numberOfCreditsMin', 'integer', array('label' => 'Créditos de libre mínimos', 'attr' => array('step' =>'0.01','label_col' => 3, 'widget_col' => 3,'col_size' => 'xs' )))
                ->add('numberOfCreditsMax', 'integer', array('label' => 'Créditos de libre máximos', 'attr' => array('step' =>'0.01','label_col' => 3, 'widget_col' => 3,'col_size' => 'xs' )))
                //->add('isPublic')
                ->add('celebrationDatesUnencoded', 'text', array('label' => 'Fecha(s) en las que se va a celebrar la actividad', 'attr' => array('class' => 'celebration_dates', 'help_text' => 'Haz click y selecciona varias')))
                ->add('numberOfHours', 'integer', array('label' => 'Número de horas', 'attr' => array('step' => '0.01')))
                ->add('assistanceControl', 'text', array('label' => 'Metodo de control de asistencia', 'attr' => array('help_text' => 'Hoja de firmas, escaneo de tarjeta...')))
                ->add('fecha_publicidad', 'text', array('label' => 'Fecha en la que quieres que empiece a ser pública la actividad', 'attr' => array('class' => 'publicity_date', 'help_text' => 'Esta fecha es la fecha en la que la actividad estará disponible para el público una vez aprobada por la comisión. Vacío significa ahora mismo'), 'mapped' => false, 'required' => false))
                //->add('extraInformationFile')
                ->add('url', null, array('label' => 'Página web', 'required' => false))
                //->add('slug')
                ->add('numberOfPlacesOffered', 'number', array('label' => 'Número de plazas', 'attr' => array('help_text' => 'Vacío para ilimitadas', 'required' => false)))
                //->add('numberOfPlacesOccupied')
                //->add('approvedByComitee')
                //->add('isActive')
                ->add('cost', 'integer', array('label' => 'Precio','attr' => array('step' => '0.01')))
                ->add('description', 'textarea', array('label' => 'Descripción', 'attr' => array('rows' => 20)))
                ->add('hasAdditionalWorkload', null, array('label' => '¿Tiene trabajo adicional?'))
                ->add('image_blob', 'file', array('required' => false))//array('required' => false)))
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
