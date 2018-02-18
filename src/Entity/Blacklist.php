<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ItemTrait;

/**
 * Blacklist
 *
 * @ORM\Table(name="blacklist")
 * @ORM\Entity()
 */
class Blacklist
{
    use ItemTrait;
}

