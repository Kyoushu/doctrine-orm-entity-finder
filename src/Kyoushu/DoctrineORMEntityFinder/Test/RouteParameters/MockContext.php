<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters;

class MockContext
{

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var \DateTime|null
     */
    protected $date;

    /**
     * @return null|string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return $this
     */
    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;
        return $this;
    }

}