<?php
namespace tests\PhpunitBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\ProductRepository;
use AppBundle\Service\ProductService;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
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

    public function testCreateOne()
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

        $result = $this->productService->createOne([
            'productCode' => 'P001', 'productName' => 'shoes','productDescription' => 'Good Shoes', 'quantity' => 12.0,
            'pricePerUnit' => 200, 'unit' => 'pair', 'stockAvialable' => 1, 'status' => 1]);
        $this->assertEquals($product->getId(), $result);
        $this->assertEquals($product->getProductCode(), 'P001');
        $this->assertEquals($product->getProductName(), 'shoes');
    }

    /**
     * @dataProvider getAllProductDataProvider
     */
    public function testGetAll($products, $expected)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($products);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $result = $this->productService->getAll();
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getOneProductDataProvider
     */
    public function testGetOne($id, $product, $expected)
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($id)
            ->willReturn($product);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $result = $this->productService->getOne($id);
        $this->assertEquals($expected, $result);
    }

    public function testUpdateOneShouldThrowExceptionForInvalidProductId()
    {
        $id = 666;

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(sprintf('Product with id [%s] not found', $id));

        $this->productService->updateOne(['quantity' => 12.0, 'pricePerUnit' => 12.0, 'stockAvialable' => 0], $id);
    }

    /**
     * @dataProvider getUpdateOneProductDataProvider
     */
    public function testUpdateOne($id, $product)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($product);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $this->productService->updateOne(['quantity' => 12.0, 'pricePerUnit' => 12.0, 'stockAvialable' => 0], $id);

        $this->assertEquals($product->getQuantity(), 12.0);
        $this->assertEquals($product->getPricePerUnit(), 12.0);
    }

    public function testDeleteOneShouldThrowExceptionForInvalidProductId()
    {
        $id = 666;

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(sprintf('Product with id [%s] not found', $id));

        $this->productService->deleteOne($id);
    }

    /**
     * @dataProvider getRemoveOneProductDataProvider
     */
    public function testDeleteOne($id, $product)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($product);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->productRepositoryMock);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('remove')
            ->with($product);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $this->productService->deleteOne($id);
    }

    public function getAllProductDataProvider()
    {
        $p0 = [];
        $product0 = [$p0];

        $p1 = new Product();
        $p1->setProductCode('P001');
        $p1->setProductName('shoes');
        $p1->setProductDescription('Good shoes');
        $p1->setQuantity(12.0);
        $p1->setPricePerUnit(12.0);
        $p1->setStockAvialable(1);
        $p1->setUnit('pair');
        $p1->setStatus(1);
        $product1 = [$p1];

        $p2 = new Product();
        $p2->setProductCode('P002');
        $p2->setProductName('watch');
        $p2->setProductDescription('Good watch');
        $p2->setQuantity(12.0);
        $p2->setPricePerUnit(12.0);
        $p2->setStockAvialable(1);
        $p2->setUnit('pieces');
        $p2->setStatus(1);
        $product2 = [$p1, $p2];

        return [
            [$product0, $product0],
            [$product1, $product1],
            [$product2, $product2]
        ];
    }

    public function getOneProductDataProvider()
    {
        $id0 = 0;
        $p0 = null;
        $product0 = $p0;

        $id1 = 1;
        $p1 = new Product();
        $p1->setProductCode('P001');
        $p1->setProductName('shoes');
        $p1->setProductDescription('Good shoes');
        $p1->setQuantity(12.0);
        $p1->setPricePerUnit(12.0);
        $p1->setStockAvialable(1);
        $p1->setUnit('pair');
        $p1->setStatus(1);
        $product1 = $p1;

        return [
            [$id0, $product0, $p0],
            [$id1, $product1, $p1],
        ];
    }

    public function getUpdateOneProductDataProvider()
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

        return [
            [1, $product],
        ];
    }

    public function getRemoveOneProductDataProvider()
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

        return [
            [1, $product],
        ];
    }
}