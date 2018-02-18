<?php

namespace App\Entity\Traits;

/**
 * Trait ItemTrait
 */
trait ItemTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=250, unique=true)
     * @ORM\Id
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     *
     */

    private $value;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set item
     *
     * @param string $item
     *
     * @return $this
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }
}