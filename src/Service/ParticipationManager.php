<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ParticipationManager
 */
class ParticipationManager
{
    /**
     * @var APISogec
     */
    private $apiSOGEC;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var array
     */
    private $tplEmailConfirm;

    /**
     * @var SendInBlueProvider
     */
    private $sendInBlue;

    /**
     * @var array
     */
    private $tplEmailReplay;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ParticipationManager constructor.
     *
     * @param array                  $tplEmailConfirm
     * @param array                  $tplEmailReplay
     * @param APISogec               $apiSOGEC
     * @param EntityManagerInterface $manager
     * @param SendInBlueProvider     $sendInBlue
     */
    public function __construct($tplEmailConfirm, $tplEmailReplay, APISogec $apiSOGEC, EntityManagerInterface $manager, SendInBlueProvider $sendInBlue, RouterInterface $router)
    {
        $this->apiSOGEC = $apiSOGEC;
        $this->manager = $manager;
        $this->tplEmailConfirm = $tplEmailConfirm;
        $this->sendInBlue = $sendInBlue;
        $this->tplEmailReplay = $tplEmailReplay;
        $this->router = $router;
    }

    /**
     * Bulk update participation state.
     */
    public function bulkUpdateState()
    {
        $batchSize = 20;
        $index = 0;
        $token = $this->apiSOGEC->getToken();
        $query = $this->manager->createQuery('SELECT p FROM App:Participation p WHERE p.num IS NOT NULL AND p.isConform IS NULL OR p.isConform = 0');
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            /** @var Participation $participation */
            $participation = $row[0];
            $states = $this->apiSOGEC->getParticipationState($participation, $token);
            if (!$states || is_null($states['is_conform'])) {
                continue;
            }
            if (false == $states['is_conform'] && $states['non_conformity_type'] != Participation::NON_COMPLIANT_DEFINITIVE && is_null($participation->getIsConform())) {
                $url = $this->router->generate('participation_modifier', ['token' => $participation->getUser()->getToken()], RouterInterface::ABSOLUTE_URL);
                $this->sendInBlue->sendEmailByOptions(
                    $this->tplEmailReplay,
                    ['user' => $participation->getUser(), 'participation' => $participation, 'url' => $url]
                );
            }
            $participation->hydrateByAPIState($states);
            $this->manager->persist($participation);
            if (($index % $batchSize) === 0) {
                $this->manager->flush();
                $this->manager->clear();
            }
            ++$index;
        }
        $this->manager->flush();
    }

    /**
     * Bulk resend participation.
     */
    public function bulkResendParticipation()
    {
        $batchSize = 20;
        $index = 0;
        $token = $this->apiSOGEC->getToken();
        $query = $this->manager->createQuery('SELECT p FROM App:Participation p WHERE p.num IS NULL');
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            /** @var Participation $participation */
            $participation = $row[0];
            $apiResponse = $this->apiSOGEC->sendParticipation($participation, $token);
            $isHydrated = $participation->hydrateByAPIResponse($apiResponse);
            if (!$isHydrated) {
                continue;
            }
            if (isset($apiResponse['participation_id'])) {
                $this->sendInBlue->sendEmailByOptions(
                    $this->tplEmailConfirm,
                    ['user' => $participation->getUser(), 'participation' => $participation]
                );
            }
            $this->manager->persist($participation);

            if (($index % $batchSize) === 0) {
                $this->manager->flush();
                $this->manager->clear();
            }
            ++$index;
        }
        $this->manager->flush();
    }
}