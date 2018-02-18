<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Participation;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_homepage")
     */
    public function indexAction()
    {
        $doctrine = $this->getDoctrine();
        $contacts = $doctrine->getRepository(Contact::class)->countAllContact();
        $users = $doctrine->getRepository(User::class)->countAllUser();
        $participations = $doctrine->getRepository(Participation::class)->countAllParticipation();

        return $this->render('admin/dashboard.html.twig', [
            'contacts' => $contacts,
            'users'    => $users,
            'participations' => $participations
        ]);
    }
}
