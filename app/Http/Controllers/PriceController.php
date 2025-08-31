<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBasePriceRequest;
use App\Http\Requests\CreateDiscountPriceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Application\UseCases\CreateDiscountPrice\CreateDiscountPrice;
use Src\Application\UseCases\CreateDiscountPrice\CreateDiscountPriceInput;
use Src\Application\UseCases\CreatePrice\CreatePrice;
use Src\Application\UseCases\CreatePrice\CreatePriceInput;
use Src\Application\UseCases\GetPrice\GetPrice;
use Src\Application\UseCases\GetPrice\GetPriceInput;

class PriceController extends Controller
{
    public function show(string $priceId, GetPrice $useCase): JsonResponse
    {
        $input = new GetPriceInput(
            priceId: $priceId
        );
        $output = $useCase->execute(
            input: $input
        );
        return response()->json(
            status: 200,
            data: [
                'id' => $output->priceId,
                'value' => $output->value,
                'layer_id' => $output->layerId,
                'product_id' => $output->productId,
            ]
        );
    }

    public function storeSimple(CreateBasePriceRequest $request, CreatePrice $useCase): JsonResponse
    {
        $input = new CreatePriceInput(
            value: $request->validated('value'),
            layerId: $request->validated('layer_id'),
            productId: $request->validated('product_id'),
        );
        $output = $useCase->execute(
            input: $input,
        );
        return response()->json(
            status: 201,
            data: ['price_id' => $output->priceId]
        );
    }

    public function storeDiscount(CreateDiscountPriceRequest $request, CreateDiscountPrice $useCase): JsonResponse
    {
        $input = new CreateDiscountPriceInput(
            layerId: $request->validated('layer_id'),
            productId: $request->validated('product_id'),
        );
        $output = $useCase->execute(
            input: $input,
        );
        return response()->json(
            status: 201,
            data: ['price_id' => $output->priceId]
        );
    }
}
