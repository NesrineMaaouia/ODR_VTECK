<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\User;
use App\Form\EditParticipationType;
use App\Form\ParticipationType;
use App\Service\APIAboutGoods;
use App\Service\APISogec;
use App\Service\SendInBlueProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ParticipationController.
 */
class ParticipationController extends Controller
{
    /**
     * @Route("/enregistrement", name="participation_enregistrement")
     */
    public function enregistrementAction(Request $request)
    {
        $participation = $this->getParticipationToken();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            /** @var Participation $participation */
            $participation = $form->getData();
            $this->getDoctrine()
                ->getRepository(Participation::class)
                ->persistAndRefresh($participation);
            $this->setParticipationToken($participation);

            return $this->render('participation/recapitulatif.html.twig', array(
                'user' => $participation->getUser(),
                'participation' => $participation
            ));
        }

        return $this->render('participation/enregistrement.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/validation-enregistrement", name="participation_validation")
     */
    public function validationAction()
    {
        /** @var Participation $participation */
        $participation = $this->getValidParticipationToken();
        $apiResponse   = $this->get(APISogec::class)->sendParticipation($participation);
        $participation->hydrateByAPIResponse($apiResponse);
        $repo = $this->getDoctrine()->getRepository(Participation::class);
        $repo->persistAndRefresh($participation);
        $this->removeParticipationToken();
        if (isset($apiResponse['participation_id'])) {
            /* Send email validation */
            $response = $this->get(SendInBlueProvider::class)->sendEmailByOptions(
                $this->getParameter('email_confirmation'),
                ['user' => $participation->getUser(), 'participation' => $participation]
            );
            $participation->hydrateBySendinblueResponse($response);
            $repo->persistAndRefresh($participation);
            $this->get(APIAboutGoods::class)->sendParticipationTickets($participation);
        }

        return $this->render('participation/confirmation.html.twig');
    }

    /**
     * @Route("/modifier-participation/{token}", name="participation_modifier")
     */
    public function modifierAction(Request $request, User $user)
    {
        $participation = $this->getEditParticipationToken($user);
        $form = $this->createForm(EditParticipationType::class, $participation);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            /** @var Participation $participation */
            $participation = $form->getData();
            $repo = $this->getDoctrine()->getRepository(Participation::class);
            $repo->persistAndRefresh($participation);
            $this->setParticipationToken($participation, '_edit');

            return $this->render('participation/recapitulatif.html.twig', array(
                'user' => $participation->getUser(),
                'participation' => $participation
            ));
        }

        return $this->render('participation/enregistrement.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    private function getEditParticipationToken(User $user)
    {
        $part = $this->getParticipationSession('_edit');

        if (!($part instanceof Participation) || $part->getUser()->getToken() != $user->getToken()){
            $last = $user->getParticipations()->last();
            if (!$last || ($last instanceof Participation && $last->getNonConformityType() != Participation::NON_COMPLIANT_TEMPORARY)) {
                throw new NotFoundHttpException();
            }
            $part = clone $last;
        }

        return $part;
    }

    /**
     * @param string $edit
     *
     * @return mixed
     */
    private function getParticipationSession($edit = '')
    {
        $id = $this->get('session')->get('participation'.$edit);

        return $this->getDoctrine()
            ->getRepository(Participation::class)
            ->findOneById($id);
    }

    /**
     * @return mixed|Participation
     */
    private function getParticipationToken()
    {
        $part = $this->getParticipationSession();

        return $part ? $part : new Participation();
    }

    /**
     * @param Participation $participation
     * @param string        $edit
     */
    private function setParticipationToken(Participation $participation, $edit='')
    {
        $this->get('session')->set('participation'.$edit, $participation->getId());
    }

    /**
     * Remove user token
     */
    private function removeParticipationToken()
    {
        $this->get('session')->remove('participation');
        $this->get('session')->remove('participation_edit');
        $this->get('session')->remove('token');
    }

    /**
     * @return mixed|Participation
     */
    private function getValidParticipationToken()
    {
        $participation = $this->getParticipationSession() ? $this->getParticipationSession() : $this->getParticipationSession('_edit');
        if (!$participation) {
            throw new NotFoundHttpException("Participation non trouvée");
        }

        return $participation;
    }
}
