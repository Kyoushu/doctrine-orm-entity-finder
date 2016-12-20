<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MockChildEntity
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
     * @var MockEntity|null
     * @ORM\ManyToOne(targetEntity="Kyoushu\DoctrineORMEntityFinder\Test\MockEntity", inversedBy="children")
     */
    protected $parent;

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
     * @return MockEntity|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MockEntity|null $parent
     * @return $this
     */
    public function setParent(MockEntity $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

}