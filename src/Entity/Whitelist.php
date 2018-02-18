<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ItemTrait;

/**
 * Whitelist
 *
 * @ORM\Table(name="whitelist")
 * @ORM\Entity()
 */
class Whitelist
{
    use ItemTrait;
}

