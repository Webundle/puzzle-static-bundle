<?php

namespace Puzzle\StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Puzzle\UserBundle\Traits\PrimaryKeyTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Sluggable\Sluggable;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;

/**
 * Template
 *
 * @ORM\Table(name="puzzle_static_template")
 * @ORM\Entity(repositoryClass="Puzzle\StaticBundle\Repository\TemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Template
{
    use Timestampable, Sluggable, Blameable;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @var string
     */
    private $name;
   
    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    public function getSluggableFields() {
        return ['name'];
    }
    
    public function getId() :?int {
        return $this->id;
    }
    
    public function setName($name) :self {
        $this->name = $name;
        return $this;
    }
    
    public function getName() :?string {
        return $this->name;
    }
    
    public function setContent($content) : self {
        $this->content = $content;
        return $this;
    }
    
    public function getContent() :? string {
        return $this->content;
    }
}
