<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Prafulla Meher
 */
namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Entity\User;
use AppBundle\Constants\GeneralConstants;

class AgentService extends BaseService
{
    public function processLoginRequest($requestContent)
    {
        $processingResult['status'] = false;
        try {
            $credentials = $requestContent['credentials'];

            $validationResult = $this->validateUserCredentials($credentials);

            // Fetching returned User object on Success Case.
            $user = $validationResult['message']['user'];
            $accessTokenResult = $this->createAuthenticationTokenForUser($user);

            // Creating Response Array to be returned.
            $response = [
                'authenticationToken' => $accessTokenResult['message']['token']
            ];
            $processingResult['message']['response'] = $response;
            $processingResult['status'] = true;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (HttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('OAuth Request could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processingResult;
    }

    public function validateUserCredentials($credentials)
    {
        $validateResult['status'] = false;
        try {
            $user = $this->getUser($credentials['username']);
            // checking if username is valid or not.
            if (empty($user)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CRED);
            }

            $encoder = $this->serviceContainer
                ->get('security.encoder_factory')
                ->getEncoder($user);
            if(!$user->isEnabled()) {
                throw new UnprocessableEntityHttpException(ErrorConstants::DISABLEDUSER);
            }
            if ($user->getPassword() !== $encoder->encodePassword($credentials['password'], $user->getSalt())) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CRED);
            }

            $validateResult['message']['user'] = $user;
            $validateResult['status'] = true;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('User credentials validation failed due to Error : '. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $validateResult;
    }

    public function getUser($username, $password = null)
    {
        $userManager = $this->serviceContainer->get('fos_user.user_manager');

        $params = [
            'username' => $username,
        ];

        // Checking if password is set then adding the password to the params.
        if (!empty($password)) {
            $params['password'] = $password;
        }

        return $userManager->findUserBy($params);
    }

    public function createAuthenticationTokenForUser($user)
    {
        $processingResult['status'] = false;
        try {
            $loop = true;
            while($loop) {
                $authenticationToken = $this->generateAuthenticationToken();
                $existAuthenticationToken = $this->entityManager->getRepository(User::class)
                    ->findOneBy(['authenticationToken' => $authenticationToken]);
                if(!$existAuthenticationToken) {
                    $loop = false;
                }
            }
            $user->setAuthenticationToken($authenticationToken);
            $this->entityManager->flush();

            $processingResult['status'] = true;
            $processingResult['message']['token'] = $authenticationToken;
        } catch (\Exception $ex) {
            print_r($ex->getMessage()); die();
            $this->logger->error('Authentication Request could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processingResult;
    }

    public function generateAuthenticationToken()
    {
        $microtime = microtime(true);
        $apiKey = str_replace('.', '', $microtime);
        $apiKey = strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4 )).
            mt_rand(100,500).
            substr(str_shuffle($apiKey), 0, 4).
            mt_rand(501,999);

        return $apiKey;
    }
}
