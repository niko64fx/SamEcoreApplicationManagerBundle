<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

use CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessComponentInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use CanalTP\SamEcoreApplicationManagerBundle\Exception\OutOfBoundsException;

class BusinessComponentRegistry
{
    private $em;
    private $session;
    private $appKey;
    private $appFinder;
    private $businessComponents = array();

    public function __construct(EntityManager $em, Session $session, $appKey, $appFinder)
    {
        $this->em = $em;
        $this->session = $session;
        $this->appKey = $appKey;
        $this->appFinder = $appFinder;
    }

    public function addBusinessComponent($application, BusinessComponentInterface $businessComponent)
    {
        $this->businessComponents[$application] = $businessComponent;
    }

    public function getBusinessComponent($application = null)
    {
        if ($application == null) {
            $application = $this->appFinder->findFromUrl();

            if (is_null($application)) throw new OutOfBoundsException(sprintf('business component for %s application not found', $application));

            $application = $application->getCanonicalName();
        }

        if (array_key_exists($application, $this->businessComponents)) {
           return $this->businessComponents[$application];
        }

        throw new OutOfBoundsException(sprintf('business component for %s application not found', $application));
    }
}
