<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MockEntity
{

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var ArrayCollection|MockChildEntity[]
     * @ORM\OneToMany(targetEntity="Kyoushu\DoctrineORMEntityFinder\Test\MockChildEntity", mappedBy="parent", cascade={"all"})
     */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection|MockChildEntity[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param MockChildEntity $child
     * @return $this
     */
    public function addChild(MockChildEntity $child)
    {
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    /**
     * @param MockChildEntity $child
     */
    public function removeChild(MockChildEntity $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
    }

}