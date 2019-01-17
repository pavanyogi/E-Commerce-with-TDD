<?php
namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use SensioLabs\Security\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Constants\ErrorConstants;

class ProductService extends BaseService
{

    public function processFetchProductList($requestContent)
    {
        $processResult['status'] = false;
        try {
            $filter = !empty($requestContent['filter']) ? $requestContent['filter'] : null;
            $productRepo = $this->entityManager->getRepository(Product::class);
            $products = $productRepo->fetchProductListData($filter, $requestContent['pagination']);
            $total = $productRepo->countProductRecords($filter);

            $processResult['message']['response'] = [
                'products' => $products,
                'count' => $total
            ];
            $processResult['status'] = true;
        } catch (\Exception $ex) {
            $this->logger->error(__FUNCTION__.' Function failed due to Error :'. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function getProductDetail($productCode)
    {
        $processResult['status'] = false;
        try {
            // Fetching the transaction corresponding to transactionId
            $productRepo = $this->entityManager->getRepository(Product::class);
            $product = $productRepo->findOneBy(['productCode' => $productCode]);

            if(empty($product)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROUCT_CODE);
            }

            $processResult['message']['response'] = [
                'productCode' => $product->getProductCode(),
                'productName' => $product->getProductName(),
                'quantity' => $product->getQuantity(),
                'pricePerUnit' => $product->getPricePerUnit(),
                'stockAvialable' => $product->getStockAvialable()
            ];
            $processResult['status'] = true;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error(__FUNCTION__.' Function failed due to Error :'. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function createProduct(array $productData)
    {
        $processResult['status'] = false;
        try {
            $product = new Product();
            $product->setProductCode($productData['productCode'])
                ->setProductName($productData['productName'])
                ->setProductDescription($productData['productDescription'])
                ->setQuantity($productData['quantity'])
                ->setPricePerUnit($productData['pricePerUnit'])
                ->setStockAvialable($productData['stockAvialable'])
                ->setUnit($productData['unit'])
                ->setStatus($productData['status']);

            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $processResult['status'] = true;
            $processResult['message']['response'] = $this->getTranslator()->trans('api.response.success.product_created');
        } catch (\Exception $ex) {
            $this->logger->error(__FUNCTION__.' Function failed due to Error :'. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function updateProduct(array $productData)
    {
        $processResult['status'] = false;
        try {
            $processResult['status'] = false;
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneBy(['productCode' => $productData['productCode']]);
            if (!$product instanceof Product) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROUCT_CODE);
            }

            if(!empty($productData['quantity'])) {
                $product->setQuantity($productData['quantity']);
            }
            if(!empty($productData['pricePerUnit'])) {
                $product->setPricePerUnit($productData['pricePerUnit']);
            }
            if(!empty($productData['stockAvialable'])) {
                $product->setStockAvialable($productData['stockAvialable']);
            }

            $this->entityManager->flush();
            $processResult['status'] = true;
            $processResult['message']['response'] = $this->translator->trans('api.response.success.product_updated');
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error(__FUNCTION__.' Function failed due to Error :'. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }
}