<?php

namespace Puzzle\StaticBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Puzzle\StaticBundle\Form\Model\AbstractPageType;

/**
 * 
 * @author AGNES Gnagne Cédric <cecenho55@gmail.com>
 * 
 */
class PageUpdateType extends AbstractPageType
{
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefault('csrf_token_id', 'page_update');
        $resolver->setDefault('validation_groups', ['Update']);
    }
    
    public function getBlockPrefix() {
        return 'puzzle_admin_static_page_create';
    }
}