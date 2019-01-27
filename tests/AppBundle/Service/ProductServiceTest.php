<?php
namespace Tests\AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\ProductRepository;
use AppBundle\Service\ProductService;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Constants\ErrorConstants;

class ProductServiceTest extends BaseServiceTest
{
    /** @var ProductRepository|PHPUnit_Framework_MockObject_MockObject */
    private $productRepositoryMock;
    /** @var ProductService */
    private $productService;

    protected function setUp()
    {
        parent::setUp();

        // Initiating the ProductRepository Mock
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Initiating entity manager interface mock
        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Initiating Product Service
        $this->productService = new ProductService();
        $this->productService->setServiceContainer($this->serviceContainerMock);
        $this->productService->setEntityManager($this->entityManagerInterfaceMock);
        $this->productService->setLogger($this->logger);
        $this->productService->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->productRepositoryMock = null;
        $this->productService = null;
    }

    /**
     * @dataProvider getFetchProductListDataProvider
     */
    public function testFetchProductList($requestContent, $products, $expected)
    {
        $requestContent['filter'] = !empty($requestContent['filter']) ? $requestContent['filter'] : null;
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('fetchProductListData')
            ->with($requestContent['filter'], $requestContent['pagination'])
            ->willReturn($products);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('countProductRecords')
            ->with($requestContent['filter'])
            ->willReturn(count($products));

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        // Calling the function and asserting the result
        $result = $this->productService->processFetchProductList($requestContent);
        $this->assertEquals($expected, $result);
    }

    public function getFetchProductListDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $fetchProductTestCases = $serviceTest->fetchProductListTestCase();

        return $fetchProductTestCases;
    }

    /**
     * @dataProvider createProductDataProvider
     */
    public function testCreateProduct($createProductData, $product, $expectedMessage)
    {
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('persist')
            ->with($product);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');
        $result = $this->productService->createProduct($createProductData);

        // Asserting the expected message
        $this->assertEquals($result, $expectedMessage);
    }

    public function createProductDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $createProductTestCases = $serviceTest->createProductTestCase();

        return $createProductTestCases;
    }

    /**
     * @dataProvider getProductDetailProvider
     */
    public function testGetProductDetail($productCode, $product, $expectedResult)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['productCode' => $productCode])
            ->willReturn($product);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        // Calling the function and asserting the result
        $result = $this->productService->getProductDetail($productCode);
        $this->assertEquals($expectedResult, $result);
    }

    public function getProductDetailProvider()
    {
        $serviceTest = new ServiceTestCase();
        $getProductDetailTestCases = $serviceTest->getProductDetailTestCase();

        return $getProductDetailTestCases;
    }

    /**
     * @dataProvider getProductDetailInvalidProductCodeProvider
     */
    public function testGetProductDetailThrowExceptionForInvalidProductCode($productCode)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['productCode' => $productCode])
            ->willReturn(null);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        // Making call to the function and assering exception type
        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(ErrorConstants::INVALID_PROUCT_CODE);
        $this->productService->getProductDetail($productCode);
    }

    public function getProductDetailInvalidProductCodeProvider() {
        $serviceTest = new ServiceTestCase();
        $getProductDetailInvalidProductCodeTestCases = $serviceTest->getProductDetailInvaidProductCodeTestCase();

        return $getProductDetailInvalidProductCodeTestCases;
    }

    /**
     * @dataProvider updateProductDetailInvalidProductCodeProvider
     */
    public function testUpdateProductThrowExceptionForInvalidProductCode($updateParameter)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['productCode' => $updateParameter['productCode']])
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(ErrorConstants::INVALID_PROUCT_CODE);
        $this->productService->updateProduct($updateParameter);
    }

    public function updateProductDetailInvalidProductCodeProvider() {
        $serviceTest = new ServiceTestCase();
        $updateProductDetailInvalidProductCodeTestCases = $serviceTest->updateProductDetailInvaidProductCodeTestCase();

        return $updateProductDetailInvalidProductCodeTestCases;
    }

    /**
     * @dataProvider getUpdateOneDataProvider
     */
    public function testUpdateOne($updateParameter, $product, $expextedResult)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['productCode' => $updateParameter['productCode']])
            ->willReturn($product);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->productService->updateProduct($updateParameter);
        $this->assertEquals($expextedResult, $result);
    }

    public function getUpdateOneDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $updateProductTestCases = $serviceTest->updateProductTestCase();

        return $updateProductTestCases;
    }
}