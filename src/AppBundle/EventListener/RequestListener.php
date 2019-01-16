<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace AppBundle\EventListener;

use AppBundle\Service\BaseService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
/**
 * Validates Requests.
 *
 * @author Magnus Nordlander <magnus@fervo.se>
 */
class RequestListener extends BaseService
{
    /**
     * Performs the validation.
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $request = $event->getRequest();
        if ($request::getTrustedProxies()) {
            $request->getClientIps();
        }
        $request->getHost();
    }
}