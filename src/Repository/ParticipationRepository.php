<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Participation;

/**
 * ParticipationRepository
 */
class ParticipationRepository extends EntityRepository
{
    /**
     * @param Participation $participation
     */
    public function persistAndFlush(Participation $participation)
    {
        $this->_em->persist($participation);
        $this->_em->flush();
    }

    /**
     * @param Participation $participation
     */
    public function persistAndRefresh(Participation $participation)
    {
        $this->persistAndFlush($participation);
        $this->_em->refresh($participation);
    }

    /**
     * Get participation by num and user email.
     *
     * @param $email
     * @param $num
     *
     * @return mixed
     */
    public function findOneByEmailAndNum($email, $num)
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.user', 'u', 'WITH', 'u.email = :email')
            ->where('p.num = :num')
            ->setParameter('num', $num)
            ->setParameter('email', $email)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Update participation state
     *
     * @param Participation $participation
     *
     * @param array $apiResponse
     */
    public function updateState(Participation $participation, array $apiResponse)
    {
        $participation->setIsConform($apiResponse['is_conform']);
        $participation->setReasonNotConformity($apiResponse['reason_not_conformity']);

        $this->persistAndRefresh($participation);
    }

    /**
     * @param Participation $participation
     */
    public function remove(Participation $participation)
    {
        $this->_em->remove($participation);
        $this->_em->flush();
    }

    /**
     * Count participation.
     *
     * @return int
     */
    public function countParticipation()
    {
        $query = $this->_em
            ->createQuery("SELECT count(p) as nbr FROM App:Participation p WHERE p.num is not null");

        return (int) $query->getSingleScalarResult();
    }

    /**
     * Count all participation.
     *
     * @return int
     */
    public function countAllParticipation()
    {
        $query = $this->_em
            ->createQuery("SELECT count(p) FROM App:Participation p");

        return (int) $query->getSingleScalarResult();
    }
}
