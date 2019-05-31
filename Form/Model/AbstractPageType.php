<?php

namespace Puzzle\StaticBundle\Form\Model;

use Puzzle\StaticBundle\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Puzzle\StaticBundle\Entity\Template;

/**
 * @author AGNES Gnagne Cédric <cecenho55@gmail.com>
 */
class AbstractPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class)
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('content', TextareaType::class, ['required' => false])
            ->add('picture', HiddenType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Page::class,
            'validation_groups' => array(
                Page::class,
                'determineValidationGroups',
            ),
        ));
    }
}