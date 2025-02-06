<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api', name: 'api_')]
final class ProductApiController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: ['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {

        $products = $doctrine->getRepository(Product::class)->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/products', name: 'product_create', methods: ['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManaget = $doctrine->getManager();

        $product = new Product;

        $product->setName($request->request->get('name'));
        $product->setDescription($request->request->get('description'));
        $product->setSize($request->request->get('size'));

        $entityManaget->persist($product);
        $entityManaget->flush();

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id ' . $id, 404);
        }


        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_update', methods: ['PUT', 'PATCH'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $product = $entityManager->getRepository(Product::class)->find($id);

        $product->setName($request->request->get('name'));
        $product->setDescription($request->request->get('description'));
        $product->setSize($request->request->get('size'));

        $entityManager->flush();

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'size' => $product->getSize(),
        ];

        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_delete', methods: ['delete'])]
    public function delete(ManagerRegistry $doctrine, Product $product): JsonResponse
    {
        $id = $product->getId();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }
}
