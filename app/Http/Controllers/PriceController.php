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
use Src\Application\UseCases\ListPrices\ListPrices;
use Src\Application\UseCases\ListPrices\ListPricesInput;

class PriceController extends Controller
{
    public function list(Request $request, ListPrices $useCase): JsonResponse
    {
        $input = new ListPricesInput(
            productIds: $request->input('product_ids'),
            layerIds: $request->input('layer_ids'),
            page: $request->input('page'),
            perPage: $request->input('per_page'),
            sortBy: $request->input('sort_by'),
            sortDirection: $request->input('sort_direction'),
        );
        $output = $useCase->execute(
            input: $input
        );
        return response()->json(
            status: 200,
            data: [
                'total' => $output->total,
                'items' => array_map(
                    callback: fn($item) => [
                        'price_id' => $item->priceId,
                        'layer_id' => $item->layerId,
                        'product_id' => $item->productId,
                        'value' => $item->value,
                    ],
                    array: $output->items
                )
            ]
        );
    }

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
