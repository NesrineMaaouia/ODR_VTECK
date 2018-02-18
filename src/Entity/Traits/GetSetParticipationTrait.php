<?php

namespace App\Entity\Traits;

use App\Entity\Participation;

trait GetSetParticipationTrait
{
    /**
     * @return Participation
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param Participation $participation
     */
    public function setParticipation(Participation $participation)
    {
        $this->participation = $participation;
    }
}