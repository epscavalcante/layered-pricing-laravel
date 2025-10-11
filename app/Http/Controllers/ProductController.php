<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSimpleProductRequest;
use Illuminate\Http\JsonResponse;
use Src\Application\UseCases\CreateSimpleProduct\CreateSimpleProduct;
use Src\Application\UseCases\CreateSimpleProduct\CreateSimpleProductInput;
use Src\Application\UseCases\GetProduct\GetProduct;
use Src\Application\UseCases\GetProduct\GetProductInput;

class ProductController extends Controller
{
    public function show(string $productId, GetProduct $useCase): JsonResponse
    {
        $input = new GetProductInput(
            productId: $productId
        );
        $output = $useCase->execute(
            input: $input
        );

        return response()->json(
            status: 200,
            data: [
                'id' => $output->productId,
                'name' => $output->name,
            ]
        );
    }

    public function storeSimple(CreateSimpleProductRequest $request, CreateSimpleProduct $useCase): JsonResponse
    {
        $input = new CreateSimpleProductInput(
            name: $request->validated('name'),
        );
        $output = $useCase->execute(
            input: $input,
        );

        return response()->json(
            status: 201,
            data: [
                'product_id' => $output->productId,
            ]
        );
    }
}
