<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/todos')]
class TodoController extends AbstractController{

const TODOS = [
    [
        'id' => 1,
        'slug' => 'hello-world',
        'title' => 'Hello World',
        'published' => true,
    ],
    [
        'id' => 2,
        'slug' => 'another-post',
        'title' => 'This is another post',
        'published' => false,
    ],
    [
        'id' => 3,
        'slug' => 'last-post',
        'title' => 'This is the last post',
        'published' => true,
    ],  
];


    #[Route( name: 'app_todo_list' , path: '/' )]
    public function index(): JsonResponse   {

        $resourceIds = array_map( fn($post) => $this->generateUrl('app_todo_by_id' , ['id' => $post['id']] ) , SELF::TODOS );
        $resourceSlugs = array_map( fn($post) => $this->generateUrl('app_todo_by_slug' , ['slug' => $post['slug']] ), SELF::TODOS );
        
        return $this->json([
            'message' => 'Welcome to your new controller! he5a',
            'todos_ids' => $resourceIds,
            'todos_slugs' => $resourceSlugs,
        ]);
    } 

    #[Route('/{id}', name: 'app_todo_by_id')]
    public function todoById(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TodoController.php',
        ]);
    }

    #[Route('/{slug}', name:'app_todo_by_slug')]
    public function todoBySlug(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TodoController.php',
        ]);
    }

}
