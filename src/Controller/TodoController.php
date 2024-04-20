<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/blogs')]
class TodoController extends AbstractController{

const POSTS = [
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


    #[Route( name: 'app_blog_list' , path: '/' )]
    public function index(): JsonResponse   {

        $resourceIds = array_map( fn($post) => $this->generateUrl('app_blog_by_id' , ['id' => $post['id']] ) , SELF::POSTS );
        $resourceSlugs = array_map( fn($post) => $this->generateUrl('app_blog_by_slug' , ['slug' => $post['slug']] ), SELF::POSTS );
        
        return $this->json([
            'message' => 'Welcome to your new controller! he5a',
            'blogs_ids' => $resourceIds,
            'blogs_slugs' => $resourceSlugs,
        ]);
    } 

    #[Route('/{id}', name: 'app_blog_by_id')]
    public function blogById(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlogController.php',
        ]);
    }

    #[Route('/{slug}', name:'app_blog_by_slug')]
    public function blogBySlug(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlogController.php',
        ]);
    }

}
