<?php

namespace UAH\GestorActividadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use UAH\GestorActividadesBundle\Repository\CategoryRepository;

class ActivityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    private $cat_repo;

    public function __construct(CategoryRepository $cat_repo)
    {
        $this->cat_repo = $cat_repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fullyEditable = $options['fullyEditable'];
        $isAdmin = $options['isAdmin'];
        $categories = $this->cat_repo->getActive('qb');
        $builder->add('name', null, array(
                    'label' => 'Nombre',
                    'attr' => array(
                        'help_text' => 'Título que tiene la actividad', ), ))
                ->add('englishName', null, array(
                    'label' => 'Nombre (Inglés)', ))
                ->add('organizerName', null, array(
                    'label' => 'Nombre de la organización',
                    'attr' => array(
                        'help_text' => 'Ej. Facultad de..., Cátedra Vodafone...', ), ));
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($fullyEditable, $isAdmin) {
            $form = $event->getForm();
            /**
             * Compruebo que la actividad sea completamente editable o que el usuario sea Admin
             */
            if ($fullyEditable || $isAdmin) {
                $form->add('numberOfECTSCreditsMin', 'integer', array(
                            'label' => 'Créditos ECTS mínimos',
                            'attr' => array(
                                'step' => '0.01',
                                'label_col' => 3,
                                'widget_col' => 3,
                                'col_size' => 'xs', ), ))
                        ->add('numberOfECTSCreditsMax', 'integer', array(
                            'label' => 'Créditos ECTS máximos',
                            'attr' => array(
                                'step' => '0.01',
                                'label_col' => 3,
                                'widget_col' => 3,
                                'col_size' => 'xs', ), ))
                        ->add('numberOfCreditsMin', 'integer', array(
                            'label' => 'Créditos de libre mínimos',
                            'attr' => array(
                                'step' => '0.01',
                                'label_col' => 3,
                                'widget_col' => 3,
                                'col_size' => 'xs', ), ))
                        ->add('numberOfCreditsMax', 'integer', array(
                            'label' => 'Créditos de libre máximos',
                            'attr' => array(
                                'step' => '0.01',
                                'label_col' => 3,
                                'widget_col' => 3,
                                'col_size' => 'xs', ), ))
                        ->add('hasAdditionalWorkload', null, array(
                            'label' => '¿Tiene trabajo adicional?',
                            'attr' => array(
                                'align_with_widget' => true, ), ))
                        ->add('numberOfHours', 'integer', array(
                            'label' => 'Número de horas',
                            'attr' => array(
                                'step' => '0.01', ), ));
            }
        });

        $builder->add('celebrationDatesUnencoded', 'text', array(
            'label' => 'Fecha(s) en las que se va a celebrar la actividad',
            'attr' => array(
                'class' => 'celebration_dates',
                'help_text' => 'Haz click y selecciona varias', ), ));
        /* @var $activity \UAH\GestorActividadesBundle\Entity\Activity */
        $activity = $builder->getData();
        $builder->add('assistanceControl', 'text', array('label' => 'Metodo de control de asistencia', 'attr' => array('help_text' => 'Hoja de firmas, escaneo de tarjeta...')))
                ->add('publicityStartDateUnencoded', 'text', array(
                    'label' => 'Fecha en la que quieres que empiece a ser pública la actividad',
                    'attr' => array(
                        'class' => 'publicity_date',
                        'help_text' => 'Esta fecha es la fecha en la que la actividad estará disponible para el público una vez aprobada por la comisión. Vacío significa ahora mismo', ),
                    'required' => false, ))
                ->add('url', null, array(
                    'label' => 'Página web',
                    'required' => false, ))
                ->add('numberOfPlacesOffered', 'integer', array(
                    'label' => 'Número de plazas',
                    'attr' => array(
                        'step' => 1,
                        'help_text' => 'Vacío para ilimitadas', ),
                    'required' => false, ))
                ->add('cost', 'integer', array(
                    'label' => 'Precio',
                    'attr' => array(
                        'step' => '0.01', ),
                    'required' => false, ))
                ->add('description', 'textarea', array(
                    'label' => 'Descripción',
                    'attr' => array(
                        'rows' => 20,
                        'class' => 'tinymce', ), ))
                ->add('categories', 'entity', array(
                    'required' => false,
                    'class' => 'UAHGestorActividadesBundle:Category',
                    //'choices' => $categories,
                    'property' => 'name',
                    //'data' => $activity->getCategories(),
                    //'group_by' => 'parent_category.getname',
                    'multiple' => 'true',
                    'label' => 'Categoría(s)',
                    'attr' => array(
                        'help_text' => 'Elige la o las categorías', 
                        'title' => 'Ninguna categoría elegida',
                        'class' => 'selectpicker', 
                        'data-live-search' => 'true'),
                ))
                ->add('socialMessage', 'textarea', array(
                    'label' => 'Mensaje para Twitter',
                    'attr' => array(
                        'rows' => 2,
                        'help_text' => 'Escribe aquí un mensaje que quieras que se vea en FB/Twitter al compartir la actividad', ), ));

        $builder->add('image_blob', 'file', array(
                    'attr' => array('help_text' => 'Intenta que respete la proporcion 4/3 entre alto y ancho para que se vea bien', ),
                    'required' => false, ))
                ->add('Guardar', 'submit', array('attr' => array('type' => 'default', 'class' => 'btn-block', 'col_size' => 'xs', 'label_col' => 7, 'widget_col' => 3)));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UAH\GestorActividadesBundle\Entity\Activity',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention' => 'activity_item',
            'fullyEditable' => false,
            'isAdmin' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'uah_gestoractividadesbundle_activity';
    }
}
