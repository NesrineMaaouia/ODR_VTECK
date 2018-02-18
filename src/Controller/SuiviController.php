<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\User;
use App\Form\SuiviEmailType;
use App\Form\SuiviType;
use App\Service\SendInBlueProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SuiviController
 */
class SuiviController extends Controller
{
    /**
     * @Route("/suivi", name="suivi_statut")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function suiviAction(Request $request)
    {
        $form = $this->createForm(SuiviType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $repo = $this->getDoctrine()->getRepository(Participation::class);
            $participation = $repo->findOneByEmailAndNum($data['email'], $data['suivi']);

            return $this->render('suivi/status.html.twig', array(
                'user'   => $participation->getUser(),
                'participation' => $participation
            ));
        }

        return $this->render('suivi/suivi.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/numero-suivi", name="suivi_get_numero")
     */
    public function getNumeroSuiviAction(Request $request)
    {
        $form = $this->createForm(SuiviEmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneBy(['email' => $form->getData()['email']]);
            $isSent = $this->get(SendInBlueProvider::class)->sendEmailByOptions(
                $this->getParameter('email_numerosuivi'),
                ['user' => $user, 'participation' => $user->getParticipations()->last()]
            );
            if ($isSent) {
                $this->addFlash('info', SendInBlueProvider::API_SUCCESS_GET_NUM);
            } else {
                $this->addFlash('danger', SendInBlueProvider::API_ERR_OUT_OF);
            }
        }

        return $this->render('suivi/get_numero_suivi.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
