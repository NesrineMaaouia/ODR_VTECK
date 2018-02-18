<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Enseigne;
use App\Entity\Participation;
use App\Entity\Products;
use App\Entity\Whitelist;
use App\Form\ContactType;
use App\Form\ParticipationType;
use App\Service\SendInBlueProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CMSController
 */
class CMSController extends Controller
{
    /**
     * @Route("/", name="cms_homepage")
     */
    public function indexAction(Request $request)
    {

        $products = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findAll();

        $participation= new Participation();

        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            /** @var Participation $participation */
            $participation = $form->getData();

            $id = (int) $request->request->get('product');

            $product = $this->getDoctrine()
                ->getRepository(Products::class)
                ->find($id);

            $ean = $participation->getEanProduct();
            $ean2 = $participation->getEanProduct2();

            $ValueEan = $this->getDoctrine()
                ->getRepository(Whitelist::class)
                ->find($ean);
            
            $ValueEan2 = $this->getDoctrine()
                ->getRepository(Whitelist::class)
                ->find($ean2);

            $participation->setProduct($product);

            $this->getDoctrine()
                ->getRepository(Participation::class)
                ->persistAndRefresh($participation);

            return $this->render('participation/recapitulatif.html.twig', array(
                'user' => $participation->getUser(),
                'participation' => $participation,
                'book_name'=> $ValueEan->getValue(),
                'book_name2'=> $ValueEan2->getValue()

            ));
        }

        return $this->render('participation/enregistrement.html.twig',
                              array('form' => $form->createView(), 'products'=>$products));
    }

    /**
     * @Route("/ouverture", name="cms_ouverture")
     */
    public function ouvertureAction()
    {
        return $this->render('cms/ouverture.html.twig', [
            'date_ouverture' => new \DateTime($this->getParameter('operation_options')['operation_dates'][0])
        ]);
    }

    /**
     * @Route("/fermeture", name="cms_fermeture")
     */
    public function fermetureAction()
    {
        return $this->render('cms/fermeture.html.twig');
    }

    /**
     * @Route("/modalites", name="cms_modalite")
     */
    public function modaliteAction()
    {
        return $this->render('cms/modalite.html.twig');
    }

    /**
     * @Route("/nous-suivre", name="cms_nous_suivre")
     */
    public function nousSuivreAction()
    {
        return $this->render('cms/nous_suivre.html.twig');
    }

    /**
     * @Route("/contact", name="cms_contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, new Contact());
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Contact $contact */
            $contact = $form->getData();
            $em->persist($contact);
            $em->flush();
            $isSent = $this->get(SendInBlueProvider::class)->sendEmailByOptions(
                $this->getParameter('email_contact'),
                ['civilite' => ($contact->getCivility() === 1) ? 'Monsieur' : 'Madame', 'contact'  => $contact]
            );
            if ($isSent) {
                return $this->render('cms/confirmation_contact.html.twig');
            } else {
                $this->addFlash('danger', SendInBlueProvider::API_ERR_OUT_OF);
            }
        }

        return $this->render('cms/contact.html.twig', ['form' => $form->createView()]);
    }
}
