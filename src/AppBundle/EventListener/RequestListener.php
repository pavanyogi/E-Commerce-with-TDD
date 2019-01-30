<?php
/**
 * Request listener class for authenticating api requests.
 *
 * @category Listener
 * @author Prafulla Meher<prafullam@mindfiresolutions.com>
 */
namespace AppBundle\EventListener;

use AppBundle\Service\BaseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class RequestListener extends BaseService
{
    /**
     * @var LoggerInterface
     */
    private $apiLogger;

    /**
     * RequestListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->apiLogger = $logger;
    }

    /**
     * Function for api request authorizations
     *
     *  @param GetResponseEvent $event
     *
     *  @throws UnauthorizedHttpException | AccessDeniedHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        // Checking if Request Method is OPTIONS.
        if (Request::METHOD_OPTIONS === $request->getMethod()) {
            $event->setResponse(new JsonResponse(['status' => true]));
            return;
        }

        // Checking if the request is a SubRequest then return it
        if (!$event->isMasterRequest()) {
            return;
        }

        // Checking the Proxies
        $request = $event->getRequest();
        if ($request::getTrustedProxies()) {
            $request->getClientIps();
        }
        $request->getHost();

        $request = $this->setRequestContent($request);
        $authService = $this->serviceContainer->get('app.authenticate_autherize_service');
        $routeName = $request->attributes->get('_route');

        /*
         * Checking if Request is for getting access token or refresh token then authentication
         * and autherization is not required.
        */
        if ($routeName === 'agent_login') {
            return;
        }

        // Log the request in ApiRequest.log File.
        $this->logRequestDetails($request);
        // Checking API Request Authentication.
        $authResult = $authService->authenticateApiRequest($request);
        $request->attributes->set('username', $authResult['message']['username']);
        $request->attributes->set('roles', $authResult['message']['roles']);
    }

    /**
     *  Function to log request.
     *
     *  @param Request $request
     *
     *  @return void
     */
    private function logRequestDetails(Request $request)
    {
        $this->apiLogger->debug('API Request', array_merge($request->headers->all(),
                [
                    'host' =>  $request->getSchemeAndHttpHost(), 'uri' => $request->getRequestUri(),
                    'method' => $request->getMethod(),
                    'content' => $request->getContent()
                ])
        );
    }

    /**
     *  In case of Get request, we are updating the request content as data is received in url parameter instead of body.
     *
     *  @param Request $request
     *
     *  @return Request
     */
    private function setRequestContent(Request $request)
    {
        $content = $request->getContent();
        if ($request->isMethod(Request::METHOD_GET)  && empty($content)) {
            $content = base64_decode($request->get('data'));
            $request->initialize($request->query->all(), array(), $request->attributes->all()
                , $request->cookies->all(), array(), $request->server->all(), $content);
            $request->headers->set('Content-Length', strlen($content));
        }
        return $request;
    }
}
