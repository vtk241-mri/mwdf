<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    private const PRODUCTS = [
        [
            'id'          => 1,
            'name'        => 'Laptop',
            'description' => '16 GB RAM, 512 GB SSD',
            'price'       => 32999,
        ],
        [
            'id'          => 2,
            'name'        => 'Smartphone',
            'description' => '8/256 GB',
            'price'       => 18499,
        ],
        [
            'id'          => 3,
            'name'        => 'Headphones',
            'description' => 'Wireless, noise cancelling',
            'price'       => 4299,
        ],
    ];

    private const PRODUCT_FIELDS = ['name', 'description', 'price'];

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
    #[Route('/products/{id}', name: 'get_product_item', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET])]
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

        if (!is_array($requestData)) {
            return new JsonResponse(['data' => ['error' => 'Invalid JSON body']], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validateProductData($requestData);

        if ($errors) {
            return new JsonResponse(['data' => ['error' => 'Validation failed', 'fields' => $errors]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newProductData = [
            'id'          => max(array_column(self::PRODUCTS, 'id')) + 1,
            'name'        => $requestData['name'],
            'description' => $requestData['description'],
            'price'       => $requestData['price'],
        ];

        return new JsonResponse(['data' => $newProductData], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити дані продукту
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'put_products', requirements: ['id' => '\d+'], methods: [Request::METHOD_PUT, Request::METHOD_PATCH])]
    public function updateProduct(string $id, Request $request): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (!is_array($requestData)) {
            return new JsonResponse(['data' => ['error' => 'Invalid JSON body']], Response::HTTP_BAD_REQUEST);
        }

        $errors = $this->validateProductData($requestData, $request->isMethod(Request::METHOD_PATCH));

        if ($errors) {
            return new JsonResponse(['data' => ['error' => 'Validation failed', 'fields' => $errors]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $updatedProductData = array_merge($product, array_intersect_key($requestData, array_flip(self::PRODUCT_FIELDS)));

        return new JsonResponse(['data' => $updatedProductData], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити продукт
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'delete_products', requirements: ['id' => '\d+'], methods: [Request::METHOD_DELETE])]
    public function deleteProduct(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => ['message' => 'Product deleted', 'id' => $product['id']]], Response::HTTP_OK);
    }

    /**
     * @param array $products
     * @param string $id
     * @return array|null
     */
    private function getProductItemById(array $products, string $id): ?array
    {
        foreach ($products as $product) {
            if ((string) $product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param bool $partial
     * @return array<string, string>
     */
    private function validateProductData(array $data, bool $partial = false): array
    {
        $errors = [];

        if ($partial && !array_intersect_key($data, array_flip(self::PRODUCT_FIELDS))) {
            return ['body' => 'At least one of the fields is required: ' . implode(', ', self::PRODUCT_FIELDS)];
        }

        foreach (self::PRODUCT_FIELDS as $field) {
            if (!array_key_exists($field, $data)) {
                if (!$partial) {
                    $errors[$field] = 'This field is required';
                }
                continue;
            }

            $value = $data[$field];

            if ($field === 'price') {
                if ((!is_int($value) && !is_float($value)) || $value < 0) {
                    $errors[$field] = 'Must be a non-negative number';
                }
            } elseif (!is_string($value) || trim($value) === '') {
                $errors[$field] = 'Must be a non-empty string';
            }
        }

        return $errors;
    }
}
