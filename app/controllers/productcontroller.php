<?php

namespace Controllers;

use Exception;
use Services\ProductService;
use function Sodium\library_version_minor;

class ProductController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll()
    {
        $offset = $_GET['offset'] ?? null; //optional
        $limit = $_GET['limit'] ?? null;
        $products = $this->service->getAll($offset, $limit);

        $this->respond($products);
    }

    public function getOne($id)
    {
        $product = $this->service->getOne($id);

        //Check
        $product
            ? $this->respond($product)
            : $this->respondWithError(404, 'Product not found');
    }

    public function create()
    {
        try {
            $product = $this->createObjectFromPostedJson("Models\Product");
            $this->service->insert($product);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }

    public function update($id)
    {
        $product = $this->service->getOne($id);

        $product
            ? $this->service->update($product, $id)
            : $this->respondWithError(404, 'Product not found');

        $this->respond($product);
    }

    public function delete($id) {
        $product = $this->service->getOne($id);

        $product
            ? $this->service->delete($id)
            : $this->respondWithError(404, 'Product could not be deleted, maybe it does not exist');

        $this->respond($product);
    }
}
