<?php
namespace tests\AppBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\ProductRepository;
use AppBundle\Service\ProductService;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Constants\ErrorConstants;

class ProductServiceTest extends KernelTestCase
{
    /** @var ProductRepository|PHPUnit_Framework_MockObject_MockObject */
    private $productRepositoryMock;
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $entityManagerInterfaceMock;
    /** @var ProductService */
    private $productService;

    protected function setUp()
    {
        parent::setUp();
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->productService = new ProductService();
        $this->productService->setServiceContainer($container->get('service_container'));
        $this->productService->setEntityManager($this->entityManagerInterfaceMock);
        $this->productService->setLogger($container->get('monolog.logger.exception'));
        $this->productService->setTranslator($container->get('translator.default'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->productRepositoryMock = null;
        $this->entityManagerInterfaceMock = null;
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

        $result = $this->productService->processFetchProductList($requestContent);
        $this->assertEquals($expected, $result);
    }

    public function getFetchProductListDataProvider()
    {
        $requestContent['filter'] = [];
        $requestContent['pagination'] = [];

        $p0 = [];
        $product0 = $p0;
        $expectedResult0['status'] = true;
        $expectedResult0['message']['response'] = [
            'products' => $product0,
            'count' => count($product0)
        ];

        $p1 = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'Good shoes',
            'quantity' => 12.0,
            'pricePerunit' => 12.0,
            'status' => 1,
            'stockAvialable' => 1,
            'unit' => 'pair'
        ];
        $product1 = [$p1];
        $expectedResult1['status'] = true;
        $expectedResult1['message']['response'] = [
            'products' => $product1,
            'count' => count($product1)
        ];

        $p2 = [
            'productCode' => 'P002',
            'productName' => 'shirt',
            'productDescription' => 'Good shirt',
            'quantity' => 12.0,
            'pricePerunit' => 12.0,
            'status' => 1,
            'stockAvialable' => 1,
            'unit' => 'piece'
        ];
        $product2 = [$p1, $p2];
        $expectedResult2['status'] = true;
        $expectedResult2['message']['response'] = [
            'products' => $product2,
            'count' => count($product2)
        ];

        return [
            [$requestContent, $product0, $expectedResult0],
            [$requestContent, $product1, $expectedResult1],
            [$requestContent, $product2, $expectedResult2]
        ];
    }

    public function testCreateProduct()
    {
        $product = new Product();
        $product->setProductCode('P001');
        $product->setProductName('shoes');
        $product->setProductDescription('Good Shoes');
        $product->setQuantity(12.0);
        $product->setPricePerUnit(200);
        $product->setStockAvialable(1);
        $product->setUnit('pair');
        $product->setStatus(1);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('persist')
            ->with($product);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->productService->createProduct([
            'productCode' => 'P001', 'productName' => 'shoes','productDescription' => 'Good Shoes', 'quantity' => 12.0,
            'pricePerUnit' => 200, 'unit' => 'pair', 'stockAvialable' => 1, 'status' => 1]);

        $expectedMessage['status'] = true;
        $expectedMessage['message']['response'] = 'Product Created Successfully.';

        $this->assertEquals($result, $expectedMessage);
    }

    /**
     * @dataProvider getProductDetailProvider
     */
    public function testGetProductDetail($productCode, $product, $expected)
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

        $result = $this->productService->getProductDetail($productCode);
        $this->assertEquals($expected, $result);
    }

    public function getProductDetailProvider()
    {
        $productCode = 'P001';

        $product = new Product();
        $product->setProductCode('P001');
        $product->setProductName('shoes');
        $product->setProductDescription('Good shoes');
        $product->setQuantity(12.0);
        $product->setPricePerUnit(12.0);
        $product->setStockAvialable(1);
        $product->setUnit('pair');
        $product->setStatus(1);

        $expected['status'] = true;
        $expected['message']['response'] = [
            'productCode' => $product->getProductCode(),
            'productName' => $product->getProductName(),
            'quantity' => $product->getQuantity(),
            'pricePerUnit' => $product->getPricePerUnit(),
            'stockAvialable' => $product->getStockAvialable()
        ];

        return [
            [$productCode, $product, $expected],
        ];
    }

    public function testGetProductDetailThrowExceptionForInvalidProductCode()
    {
        $productCode = 'XXXX';

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['productCode' => $productCode])
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(ErrorConstants::INVALID_PROUCT_CODE);

        $this->productService->getProductDetail($productCode);
    }

    public function testUpdateProductThrowExceptionForInvalidProductCode()
    {
        $updateParameter = ['productCode' => 'P001', 'quantity' => 12.0, 'pricePerUnit' => 12.0, 'stockAvialable' => 0];

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

    /**
     * @dataProvider getUpdateOneDataProvider
     */
    public function testUpdateOne($product, $updateParameter, $expextedResult)
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

        $this->productService->updateProduct($updateParameter);

        $this->assertEquals($product->getQuantity(), $expextedResult['message']['response']->getQuantity());
        $this->assertEquals($product->getPricePerUnit(), $expextedResult['message']['response']->getPricePerUnit());
    }

    public function getUpdateOneDataProvider()
    {
        $product = new Product();
        $product->setProductCode('P001');
        $product->setProductName('shoes');
        $product->setProductDescription('Good shoes');
        $product->setQuantity(12.0);
        $product->setPricePerUnit(12.0);
        $product->setStockAvialable(0);
        $product->setUnit('pair');
        $product->setStatus(1);

        $updateParameter = ['productCode' => 'P001', 'quantity' => 12.0, 'pricePerUnit' => 12.0, 'stockAvialable' => 0];
        $expextedResult['status'] = true;
        $expextedResult['message']['response'] = $product;

        return [
            [$product, $updateParameter, $expextedResult],
        ];
    }
}