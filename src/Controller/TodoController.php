<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TodoController extends AbstractController
{
    private TodoRepository $todoRepository;

    private UserRepository $userRepo;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;


    function __construct(TodoRepository $todoRepo, UserRepository $userRepo, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->todoRepository = $todoRepo;
        $this->userRepo = $userRepo;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/todos', name: 'app_todo_list', methods: 'GET')]

    public function listTodos(
        Request $req,
        #[MapQueryParameter] ?int $page = 1,
        #[MapQueryParameter] ?int $size = 20
    ): JsonResponse {


        $userId = $req->cookies->get('user_id');
        // $offset = $size * $page;

        if (empty($userId)) {
            return $this->json([
                'message' => 'No todos found'
            ], 404);
        }

        $todos = $this->todoRepository->listOwnTodos($userId);

        $response = [
            'data' => $todos,
            'page' => $page,
            'limit' => $size
        ];
        return $this->json($response, 200, [], ['groups' => 'user-todos']);
    }



    #[Route('/todos', name: 'app_todo_add', methods: 'POST' )]
    public function addTodo(Request $request): JsonResponse
    {
        $userId = $request->cookies->get('user_id');
        $todoBody = $request->getContent();

        if (empty($userId)) {
            return $this->json([
                'message' => 'No todos found'
            ], 404);
        }

        $todo = new Todo();
        $user = $this->userRepo->find($userId);

        if (!$user) {
            return $this->json([
                'message' => 'User not found'
            ], 404);
        }

        $todo->setAssignee($user);

        $this->serializer->deserialize(
            $todoBody,
            Todo::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $todo]

        );

        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        return $this->json($todo, 200, [], ['groups' => 'user-todos']);
    }

    #[Route('/todos/{id}', name: 'app_todo_show', methods: ['GET'])]
    public function getTodoByID(int $id, Request $request): JsonResponse
    {
        $userId = $request->cookies->get('user_id');

        if (empty($userId)) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }

        $todo = $this->todoRepository->getOwnTodoByID($userId, $id);
        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        return $this->json($todo, 200, [], ['groups' => 'user-todos']);
    }

    #[Route('/todos/{id}', name: 'app_todo_update', methods: ['PATCH'])]
    public function updateTodoById(int $id, Request $request): JsonResponse
    {
        $userId = $request->cookies->get('user_id');
        $todoBody = $request->getContent();

        if (empty($userId)) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }

        $todo = $this->todoRepository->getOwnTodoByID($userId, $id);

        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        $this->serializer->deserialize(
            $todoBody,
            Todo::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $todo]

        );

        $this->entityManager->flush();


        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        return $this->json($todo, 200, [], ['groups' => 'user-todos']);
    }

    #[Route('/todos/{id}', name: 'app_todo_update', methods: "DELETE")]
    public function deleteTodoByID(int $id, Request $request): JsonResponse
    {
        $userId = $request->cookies->get('user_id');

        if (empty($userId)) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }

        $todo = $this->todoRepository->getOwnTodoByID($userId, $id);

        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        
        $this->entityManager->remove($todo);

        $this->entityManager->flush();


        if (!$todo) {
            return $this->json([
                'message' => 'Todo not found'
            ], 404);
        }
        return $this->json($todo, 200, [], ['groups' => 'user-todos']);
    }
}
