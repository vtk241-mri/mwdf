<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private const PRODUCTS = [
        [
            'id'          => 1,
            'name'        => 'Laptop',
            'description' => '16 GB RAM, 512 GB SSD',
            'price'       => 32999
        ],
        [
            'id'          => 2,
            'name'        => 'Smartphone',
            'description' => '8/256 GB',
            'price'       => 18499
        ],
        [
            'id'          => 3,
            'name'        => 'Headphones',
            'description' => 'Wireless, noise cancelling',
            'price'       => 4299
        ]
    ];

    /**
     * READ - список усіх продуктів
     *
     * @return JsonResponse
     */
    #[Route('/products', name: 'get_products', methods: [Request::METHOD_GET])]
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(['data' => self::PRODUCTS], Response::HTTP_OK);
    }

    /**
     * READ - один продукт за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'get_product_item', methods: [Request::METHOD_GET])]
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $product], Response::HTTP_OK);
    }

    /**
     * CREATE - створити новий продукт
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/products', name: 'post_products', methods: [Request::METHOD_POST])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $productId = random_int(1, 100);

        $newProductData = [
            'id'          => $productId,
            'name'        => $requestData['name'],
            'description' => $requestData['description'],
            'price'       => $requestData['price']
        ];

        // TODO insert to db

        return new JsonResponse(['data' => $newProductData], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити продукт за id
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'put_products', methods: [Request::METHOD_PUT])]
    public function updateProduct(string $id, Request $request): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $updatedProductData = array_merge($product, $requestData);

        // TODO update in db

        return new JsonResponse(['data' => $updatedProductData], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити продукт за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'delete_products', methods: [Request::METHOD_DELETE])]
    public function deleteProduct(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        // TODO delete from db

        return new JsonResponse(['data' => 'Deleted product by id ' . $id], Response::HTTP_OK);
    }

    /**
     * @param array $products
     * @param string $id
     * @return array|null
     */
    private function getProductItemById(array $products, string $id): ?array
    {
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }

        return null;
    }
}
