<?php

namespace UAH\GestorActividadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $degrees = $options['degrees'];
        $builder
                ->add('name', null, array('label' => 'Nombre', 'attr' => array('help_text' => 'Tu nombre', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')))
                ->add('apellido_1', null, array('label' => 'Primer apellido', 'attr' => array('label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')))
                ->add('apellido_2', null, array('label' => 'Segundo apellido', 'attr' => array('help_text' => 'Si no tienes un segundo apellido, déjalo en blanco. Si tienes más de dos apellidos pon aquí el segundo y sucesivos', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')))
                ->add('email', null, array('label' => 'E-mail', 'attr' => array('help_text' => 'Un email que consultes habitualmente', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')))
                ->add('documento_identidad', null, array('label' => 'Nº DNI/NIE/Pasaporte', 'attr' => array('help_text' => 'El que tengas registrado en la universidad. Si es DNI o NIE incluye la(s) letra(s)', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')))
                ->add('tipo_documento_identidad', 'choice', array('label' => 'Tipo de documento', 'expanded' => true, 'choices' => array(
                        'D.N.I.' => 'D.N.I.',
                        'N.I.E.' => 'N.I.E.',
                        'Pasaporte' => 'Pasaporte'
                    ), 'attr' => array('help_text' => 'DNI, NIE o Pasaporte', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs',
                        'multiple' => true)))
                ->add('degree_id', 'entity', array(
                    'class' => 'UAHGestorActividadesBundle:Degree',
                    'choices' => $degrees,
                    'property' => 'name',
                    'group_by' => 'knowledgeArea',
                    'label' => 'Plan de estudio',
                    'attr' => array('help_text' => 'Elige tu plan de estudio', 'label_col' => 4, 'widget_col' => 6, 'col_size' => 'xs')
                ))
                ->add('Guardar', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'UAH\GestorActividadesBundle\Entity\User',
            'degrees' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'uah_gestoractividadesbundle_user';
    }

}
