<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Component\EventListener;

/**
 * Description of SelectController
 *
 * @author Kévin Ziemianski <kevin.ziemianski@canaltp.fr>
 */
class SelectController
{

    protected $router;
    protected $routePrefix;

    public function __construct($router, $routePrefix)
    {
        $this->router = $router;
        $this->routePrefix = $routePrefix;
    }

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $routeName = array();
        $route = $this->router->getRouteCollection()->get('sam_' . $this->routePrefix . '_' . $event->getRequest()->attributes->get('_route'));

        if ($event->getRequestType() == \Symfony\Component\HttpKernel\HttpKernel::SUB_REQUEST && !is_null($route)) {
            $routeDefaults = $route->getDefaults();
            $event->getRequest()->attributes->set('_controller', $routeDefaults['_controller']);
            $event->getRequest()->attributes->set('path', $route->getPath());
        }
    }
}
