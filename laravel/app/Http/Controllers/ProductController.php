<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private const PRODUCTS = [
        [
            'id' => 1,
            'name' => 'Laptop',
            'description' => '16 GB RAM, 512 GB SSD',
            'price' => 32999,
        ],
        [
            'id' => 2,
            'name' => 'Smartphone',
            'description' => '8/256 GB',
            'price' => 18499,
        ],
        [
            'id' => 3,
            'name' => 'Headphones',
            'description' => 'Wireless, noise cancelling',
            'price' => 4299,
        ],
    ];

    /**
     * READ - список усіх продуктів
     */
    public function getProducts(): JsonResponse
    {
        return response()->json(['data' => self::PRODUCTS], Response::HTTP_OK);
    }

    /**
     * READ - один продукт за id
     */
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * CREATE - створити новий продукт
     */
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = $request->validate($this->productRules('required'));

        $newProductData = [
            'id' => max(array_column(self::PRODUCTS, 'id')) + 1,
            'name' => $requestData['name'],
            'description' => $requestData['description'],
            'price' => $requestData['price'],
        ];

        return response()->json(['data' => $newProductData], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити дані продукту
     */
    public function updateProduct(Request $request, string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->validate($this->productRules($request->isMethod('patch') ? 'sometimes' : 'required'));

        if (!$requestData) {
            return response()->json(['data' => ['error' => 'At least one of the fields is required: name, description, price']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $updatedProductData = array_merge($product, $requestData);

        return response()->json(['data' => $updatedProductData], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити продукт
     */
    public function deleteProduct(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => ['message' => 'Product deleted', 'id' => $product['id']]], Response::HTTP_OK);
    }

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
     * @param  string  $presence
     */
    private function productRules(string $presence): array
    {
        return [
            'name' => [$presence, 'string', 'max:255'],
            'description' => [$presence, 'string'],
            'price' => [$presence, 'numeric', 'min:0'],
        ];
    }
}
