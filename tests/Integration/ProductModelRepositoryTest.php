<?php

use App\Repositories\ProductQueryBuilderRepository;
use Src\Application\Repositories\ProductRepository;
use Src\Domain\Entities\Product;
use Src\Domain\ValueObjects\ProductId;

beforeEach(function () {
    /** @var ProductRepository */
    $this->repository = new ProductQueryBuilderRepository;
});

test('Deve salvar um produto', function () {
    $product = Product::create(
        name: uniqid('Product ', true),
    );
    $this->repository->save($product);
    $productExists = $this->repository->findById(ProductId::restore($product->getId()));
    expect($productExists)->toBeInstanceOf(Product::class);
});

test('Deve encontar um produto pelo ID', function () {
    $product = Product::create(
        name: 'Produto Teste',
    );
    $this->repository->save($product);
    $product = $this->repository->findById(ProductId::restore($product->getId()));

    expect($product)->toBeInstanceOf(Product::class);
    expect($product->getName())->toBe('Produto Teste');
});

test('Deve retornar null quando não encontrar o produto pelo ID', function () {
    $product = $this->repository->findById(ProductId::create());
    expect($product)->toBeNull();
});
