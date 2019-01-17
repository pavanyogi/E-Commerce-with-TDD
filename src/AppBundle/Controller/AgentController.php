<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AgentController extends Controller
{
    /**
     * @Route("/agent/login", name="agent_list", methods={"POST", "OPTIONS"})
     */
    public function loginAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        // $response to be returned from API.
        $response = NULL;
        try {
            $content = json_decode(trim($request->getContent()), TRUE);

            $authService = $this->container->get('app.agent_service');
            // Processing Request Content and Getting Result.
            $authResult = $authService->processLoginRequest($content)
            ['message']['response'];

            // Creating and final array of response from API.
            $response = $this->container
                ->get('app.api_response_service')
                ->createAgentApiSuccessResponse('AgentResponse', $authResult)
            ;
        } catch (BadRequestHttpException $ex) {
            throw $ex;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (HttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__.' function failed due to Error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }
}
