<?php
/**
 *  AuthenticateAuthorize Service to handle Authentication and Authorization
 *  Related tasks.
 *
 *  @category Service
 *  @author Prafulla Meher<prafulla.m@mindfiresolutions.com>
 */
namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateAuthorizeService extends BaseService
{
    /**
     *  Function to authenticate Request Header.
     *
     *  @param Request $request
     *
     *  @return array
     */
    public function authenticateApiRequest(Request $request)
    {
        $authenticateResult['status'] = false;
        try {
            // Checking Authorization Key for validating Token.
            $authorization = $request->headers->get('Authorization');
            if (strlen($authorization) !== 14) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }
            $userName = $request->headers->get('username');
            $userManager = $this->serviceContainer->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($userName);
            if(!$user || ($user->getAuthenticationToken() !== $authorization)) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION_OR_USER_NAME);
            }
            if(!$user->isEnabled()) {
                throw new UnauthorizedHttpException(null, ErrorConstants::DISABLEDUSER);
            }
            $authenticateResult['message'] = [
                'username' => $request->headers->get('username'),
                'roles' => $user->getRoles()
            ];
            $authenticateResult['status'] = true;
        } catch (UnauthorizedHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('Authentication could not be complete due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $authenticateResult;
    }
}