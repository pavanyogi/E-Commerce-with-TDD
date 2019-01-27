<?php
namespace Tests\AppBundle\Service;

use AppBundle\Service\AuthenticateAuthorizeService;
use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Request;

class AuthenticateAuthorizeServiceTest extends BaseServiceTest
{
    /** @var AuthenticateAuthorizeService */
    private $authenticateAutherizeService;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    protected function setUp()
    {
        parent::setUp();
        $this->authenticateAutherizeService = new AuthenticateAuthorizeService();
        $this->authenticateAutherizeService->setServiceContainer($this->serviceContainerMock);
        $this->authenticateAutherizeService->setEntityManager($this->entityManagerInterfaceMock);
        $this->authenticateAutherizeService->setLogger($this->logger);
        $this->authenticateAutherizeService->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->authenticateAutherizeService = null;
    }

    /**
     * @dataProvider authenticateApiRequestDataProvider
     */
    public function testAuthenticateApiRequest($headerData, $user, $expectedResult)
    {
        $request = new Request();
        foreach ($headerData as $key => $value) {
            $request->headers->set($key, $value);
        }
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with($user->getUSerName())
            ->willReturn($user);
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);

        // Calling the function
        $result = $this->authenticateAutherizeService->authenticateApiRequest($request);

        $this->assertEquals($result, $expectedResult);
    }

    public function authenticateApiRequestDataProvider()
    {
        $authenticateAutherizeApiTest = new ServiceTestCase();
        $authenticateAutherizeApiTestCases = $authenticateAutherizeApiTest->authenticateApiRequestTestCase();

        return $authenticateAutherizeApiTestCases;
    }

    /**
     * @dataProvider authenticateApiRequestInvalidContentTypeDataProvider
     */
    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidContentType($headerData)
    {
        $request = new Request();
        foreach ($headerData as $key => $value) {
            $request->headers->set($key, $value);
        }
        $request->setMethod(Request::METHOD_POST);
        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function authenticateApiRequestInvalidContentTypeDataProvider() {
        $authenticateAutherizeApiTest = new ServiceTestCase();
        $authenticateAutherizeApiInvalidContentTypeTestCases = $authenticateAutherizeApiTest
            ->authenticateApiRequestInvalidContentTypeTestCase();

        return $authenticateAutherizeApiInvalidContentTypeTestCases;
    }

    /**
     * @dataProvider authenticateApiRequestInvalidAuthorizationDataProvider
     */
    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidAuthorization($headerdata)
    {
        $request = new Request();
        foreach ($headerdata as $key => $value) {
            $request->headers->set($key, $value);
        }
        $request->setMethod(Request::METHOD_POST);
        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function authenticateApiRequestInvalidAuthorizationDataProvider() {
        $authenticateAutherizeApiTest = new ServiceTestCase();
        $authenticateAutherizeApiInvalidAuthorizationTestCases = $authenticateAutherizeApiTest
            ->authenticateApiRequestInvalidAuthorizationTestCase();

        return $authenticateAutherizeApiInvalidAuthorizationTestCases;
    }

    /**
     * @dataProvider authenticateApiRequestInvalidUserDataProvider
     */
    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidUser($headerData)
    {
        $request = new Request();
        foreach ($headerData as $key => $value) {
            $request->headers->set($key, $value);
        }
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with($headerData['username'])
            ->willReturn(null);
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);

        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function authenticateApiRequestInvalidUserDataProvider() {
        $authenticateAutherizeApiTest = new ServiceTestCase();
        $authenticateAutherizeApiInvalidUserTestCases = $authenticateAutherizeApiTest
            ->authenticateApiRequestInvalidUserTestCase();

        return $authenticateAutherizeApiInvalidUserTestCases;
    }


    /**
     * @dataProvider authenticateApiRequestDataProviderForDisabledUser
     */
    public function testAuthenticateApiRequestShouldThrowExceptionForDisabledUser($headerData, $user)
    {
        $request = new Request();
        foreach ($headerData as $key => $value) {
            $request->headers->set($key, $value);
        }
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with($user->getUSerName())
            ->willReturn($user);
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);

        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function authenticateApiRequestDataProviderForDisabledUser() {
        $authenticateAutherizeApiTest = new ServiceTestCase();
        $authenticateAutherizeApiDisabledUserTestCases = $authenticateAutherizeApiTest
            ->authenticateApiRequestDisabledUserTestCase();

        return $authenticateAutherizeApiDisabledUserTestCases;
    }

}