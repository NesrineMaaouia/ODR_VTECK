<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class KernelControllerSubscriber
 */
class KernelControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \DateTime
     */
    private $operationDateStart;

    /**
     * @var \DateTime
     */
    private $operationDateEnd;

    /**
     * @var \DateTime
     */
    private $today;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var array
     */
    private $options;

    public function __construct(array $options, RouterInterface $router, EntityManagerInterface $manager)
    {
        $this->options = $options;
        $this->router = $router;
        $this->manager = $manager;
        $this->today = new \DateTime();
        $this->operationDateStart = new \DateTime($options['operation_dates'][0]);
        $this->operationDateEnd = new \DateTime($options['operation_dates'][1]);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                array('onKernelController', 0),
            ]
        ];
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest() || $this->options['check_opening_date'] === false) {
            // don't do anything if it's not the master request
            return;
        }

        // si whitelist pour l'ouverture
        if ($this->options['check_whitelist'] === true) {
            if (in_array( $event->getRequest()->getClientIp(), $this->options['ip_whitelist'])) {
                return;
            }
        }

        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        if (isset($controller[0])) {
            $route = $event->getRequest()->get('_route');
            // pas de verif pour le debug tool bar
            if (preg_match('/^(_profiler|_wdt|_error)/', $route) || in_array($route, $this->options['route_whitelist'])) {
                return;
            }

            if ($this->today < $this->operationDateStart) {
                $this->redirectToRoute($event, 'cms_ouverture');
            } elseif ($this->today > $this->operationDateEnd) {
                $this->redirectToRoute($event, 'cms_fermeture');
            }
        }

        return;
    }

    /**
     * Check limit of participation.
     *
     * @param FilterControllerEvent $event
     */
    public function checkLimitParticipation(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->options['check_participation_limit']) {
            // don't do anything if it's not the master request
            return;
        }
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }
        $request = $event->getRequest();
        $route = $request->get('_route');
        if (preg_match('/^(participation)/', $route)) {
            $nbrParticipation = $this->manager
                ->getRepository(Participation::class)
                ->countParticipation();
            if ($this->options['participation_limit'] <= $nbrParticipation) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('danger', $this->options['participation_limit_msg']);

                $this->redirectToRoute($event, 'cms_homepage');
            }
        }
    }

    /**
     * Redirect controller
     *
     * @param KernelEvent $event
     * @param string      $route
     */
    private function redirectToRoute(KernelEvent $event, $route)
    {
        $redirectUrl = $this->router->generate($route);
        $event->setController(function () use ($redirectUrl) {
            return new RedirectResponse($redirectUrl);
        });
    }
}