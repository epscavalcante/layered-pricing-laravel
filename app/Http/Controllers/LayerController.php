<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLayerRequest;
use Illuminate\Http\JsonResponse;
use Src\Application\UseCases\CreateLayer\CreateDiscountLayer\CreateDiscountLayer;
use Src\Application\UseCases\CreateLayer\CreateLayer;
use Src\Application\UseCases\CreateLayer\CreateLayerInput;
use Src\Application\UseCases\CreateLayer\CreateSimpleLayer\CreateSimpleLayer;
use Src\Application\UseCases\GetLayer\GetLayer;
use Src\Application\UseCases\GetLayer\GetLayerInput;

class LayerController extends Controller
{
    public function __construct(
        private readonly GetLayer $getLayerUseCase,
        private readonly CreateLayer $createLayerUseCase,
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

    public function store(CreateLayerRequest $request): JsonResponse
    {
        $input = new CreateLayerInput(
            code: $request->validated('code'),
            type: $request->validated('type'),
            layerId: $request->validated('layer_id'),
            discountType: $request->validated('discount_type'),
            discountValue: $request->validated('discount_value'),
        );
        $output = $this->createLayerUseCase->execute(
            input: $input,
        );
        return response()->json(
            status: 201,
            data: ['layer_id' => $output->layerId]
        );
    }
}
