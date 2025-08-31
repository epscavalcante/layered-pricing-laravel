<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountLayerRequest;
use App\Http\Requests\StoreSimpleLayerRequest;
use Illuminate\Http\JsonResponse;
use Src\Application\UseCases\CreateDiscountLayer\CreateDiscountLayer;
use Src\Application\UseCases\CreateDiscountLayer\CreateDiscountLayerInput;
use Src\Application\UseCases\CreateSimpleLayer\CreateSimpleLayer;
use Src\Application\UseCases\CreateSimpleLayer\CreateSimpleLayerInput;
use Src\Application\UseCases\GetLayer\GetLayer;
use Src\Application\UseCases\GetLayer\GetLayerInput;

class LayerController extends Controller
{
    public function __construct(
        private readonly GetLayer $getLayerUseCase,
        private readonly CreateSimpleLayer $createSimpleLayerUseCase,
        private readonly CreateDiscountLayer $createDiscountLayerUseCase,
    ) {}

    public function show(string $layerId): JsonResponse
    {
        $input = new GetLayerInput(
            layerId: $layerId
        );
        $output = $this->getLayerUseCase->execute(
            input: $input
        );
        return response()->json(
            status: 200,
            data: [
                'id' => $output->layerId,
                'parent_id' => $output->parentId,
                'code' => $output->code,
                'type' => $output->type,
                'discount_type' => $output->discountType,
                'discount_value' => $output->discountValue,
            ]
        );
    }

    public function storeSimple(StoreSimpleLayerRequest $request): JsonResponse
    {
        $input = new CreateSimpleLayerInput(
            code: $request->validated('code'),
        );
        $output = $this->createSimpleLayerUseCase->execute(
            input: $input,
        );
        return response()->json(
            status: 201,
            data: [
                'layer_id' => $output->layerId,
            ]
        );
    }

    public function storeDiscount(StoreDiscountLayerRequest $request): JsonResponse
    {
        $input = new CreateDiscountLayerInput(
            layerId: $request->validated('parent_id'),
            code: $request->validated('code'),
            type: $request->validated('type'),
            value: $request->validated('value')
        );
        $output = $this->createDiscountLayerUseCase->execute(
            input: $input,
        );
        return response()->json(
            status: 201,
            data: [
                'layer_id' => $output->layerId,
            ]
        );
    }
}
