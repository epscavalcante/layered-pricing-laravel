<?php

use App\Repositories\LayerModelRepository;
use App\Repositories\PriceModelRepository;
use App\Repositories\ProductModelRepository;
use Src\Application\Repositories\LayerRepository;
use Src\Application\Repositories\PriceRepository;
use Src\Application\UseCases\GetPrice\GetPrice;
use Src\Application\UseCases\GetPrice\GetPriceInput;
use Src\Application\UseCases\GetPrice\GetPriceOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Entities\Price;
use Src\Domain\Entities\Product;
use Src\Domain\Exceptions\PriceNotFoundException;
use Src\Domain\ValueObjects\PriceId;

beforeEach(function () {
    /** @var ProductRepository */
    $this->productRepository = new ProductModelRepository;
    /** @var LayerRepository */
    $this->layerRepository = new LayerModelRepository;
    /** @var PriceRepository */
    $this->priceRepository = new PriceModelRepository;
});

test('Deve lançar ProductNotFoundException', function () {
    $useCase = new GetPrice(
        priceRepository: $this->priceRepository
    );
    $input = new GetPriceInput(
        priceId: (string) PriceId::create(),
    );
    $useCase->execute($input);
})->throws(PriceNotFoundException::class);

test('Deve encontrar um price', function () {
    $product = Product::create(
        name: 'Product 1',
    );
    $this->productRepository->save($product);
    $layer = Layer::create(
        code: uniqid('layer_', true),
    );
    $this->layerRepository->save($layer);

    $price = Price::create(
        layerId: $layer->getId(),
        productId: $product->getId(),
        value: 12345,
    );
    $this->priceRepository->save($price);

    $useCase = new GetPrice(
        priceRepository: $this->priceRepository
    );
    $input = new GetPriceInput(
        priceId: $price->getId(),
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(GetPriceOutput::class);
    expect($output->priceId)->toBe($price->getId());
    expect($output->layerId)->toBe($price->getLayerId());
    expect($output->productId)->toBe($price->getProductId());
    expect($output->value)->toBe($price->getValue());
});
