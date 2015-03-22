<?php

namespace UAH\GestorActividadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $degrees = $options['degrees'];
        $builder
                ->add('name', null, array(
                    'required' => true,
                    'label' => 'Nombre',
                    'attr' => array(
                        'help_text' => 'Tu nombre',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ), ))
                ->add('apellido_1', null, array(
                    'required' => true,
                    'label' => 'Primer apellido',
                    'attr' => array(
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ), ))
                ->add('apellido_2', null, array(
                    'label' => 'Segundo apellido',
                    'attr' => array(
                        'help_text' => 'Si no tienes un segundo apellido, déjalo en blanco. Si tienes más de dos apellidos pon aquí el segundo y sucesivos',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ), ))
                ->add('email', null, array(
                    'required' => true,
                    'label' => 'E-mail',
                    'attr' => array(
                        'help_text' => 'Un email que consultes habitualmente',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ), ))
                ->add('documento_identidad', null, array(
                    'required' => true,
                    'label' => 'Nº DNI/ NIE/ Pasaporte',
                    'attr' => array(
                        'help_text' => 'El que tengas registrado en la universidad. Si es DNI o NIE incluye la(s) letra(s)',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ), ))
                ->add('tipo_documento_identidad', 'choice', array('label' => 'Tipo de documento', 'expanded' => true, 'choices' => array(
                        'D.N.I.' => 'D.N.I.',
                        'N.I.E.' => 'N.I.E.',
                        'Pasaporte' => 'Pasaporte',
                    ), 'attr' => array('help_text' => 'DNI, NIE o Pasaporte',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs',
                        'multiple' => true, )))
                ->add('degree', 'entity', array(
                    'required' => true,
                    'class' => 'UAHGestorActividadesBundle:Degree',
                    'choices' => $degrees,
                    'property' => 'name',
                    'group_by' => 'knowledgeArea',
                    'label' => 'Plan de estudio',
                    'attr' => array(
                        'help_text' => 'Elige tu plan de estudio',
                        'label_col' => 4,
                        'widget_col' => 8,
                        'col_size' => 'xs', ),
                ))
                ->add('Guardar', 'submit', array('attr' => array('type' => 'default', 'class' => 'btn-block col-sm-offset-9 col-sm-2 col-xs-offset-5 col-xs-5', 'col_size' => 'xs', 'label_col' => 5, 'widget_col' => 4)))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UAH\GestorActividadesBundle\Entity\User',
            'degrees' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'uah_gestoractividadesbundle_user';
    }
}
