<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Product;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductService extends BaseService
{

    public function getAll()
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    public function getOne($id)
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }

    public function createOne(array $payload)
    {
        $product = new Product();
        $product->setProductCode($payload['productCode']);
        $product->setProductName($payload['productName']);
        $product->setProductDescription($payload['productDescription']);
        $product->setQuantity($payload['quantity']);
        $product->setPricePerUnit($payload['pricePerUnit']);
        $product->setStockAvialable($payload['stockAvialable']);
        $product->setUnit($payload['unit']);
        $product->setStatus($payload['status']);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product->getId();
    }

    public function updateOne(array $payload, $id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product instanceof Product) {
            throw new BadRequestHttpException(sprintf('Product with id [%s] not found', $id));
        }

        $product->setQuantity($payload['quantity']);
        $product->setPricePerUnit($payload['pricePerUnit']);
        $product->setStockAvialable($payload['stockAvialable']);

        $this->entityManager->flush();
    }

    public function deleteOne($id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product instanceof Product) {
                throw new BadRequestHttpException(sprintf('Product with id [%s] not found', $id));
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}