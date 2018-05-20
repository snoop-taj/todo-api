<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\ApiBundle\Controller;


use DateTimeImmutable;
use Etechnologia\Platform\Todo\Core\Response\Serializer\TodoSerializer;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoEmptyPayloadException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskExistAlreadyException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskDoesNotExistException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoValidateIdNotFoundException;
use Etechnologia\Platform\Todo\Core\Todo\TodoService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class TodoController
{
    /** @var TodoSerializer */
    private $todoSerializer;

    /** @var TodoService */
    private $todoService;

    /**
     * @param TodoSerializer $todoSerializer
     * @param TodoService $todoService
     */
    public function __construct(TodoSerializer $todoSerializer, TodoService $todoService)
    {
        $this->todoSerializer = $todoSerializer;
        $this->todoService = $todoService;
    }

    /**
     * ### Payload ###
     *
     *     {
     *         "title": "Task Title"
     *     }
     *
     * ### Payload schema ###
     *
     *     {
     *         "type": "object",
     *         "properties": {
     *             "title": {
     *                 "type": "string"
     *             }
     *         },
     *         "required": [
     *             "title"
     *         ]
     *     }
     *
     * @ApiDoc(
     *     description = "Create a todo task",
     *     statusCodes = {
     *         200 = "Todo task created successfully",
     *         400 = {
     *             "TodoEmptyPayloadException",
     *             "TodoTaskExistAlreadyException",
     *             "TodoValidateEmptyTitleException"
     *         }
     *     }
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws TodoEmptyPayloadException
     * @throws TodoTaskExistAlreadyException
     * @throws TodoTaskDoesNotExistException
     */
    public function create(Request $request)
    {
        return ResponseFormatter::json(
            $this->todoSerializer->toArray(
                $this->todoService->create(
                    Uuid::uuid4()->toString(),
                    json_decode($request->getContent(), true),
                    new DateTimeImmutable($request->headers->get('request-time'))
                )
            )
        );
    }

    /**
     * ### Payload ###
     *
     *     {
     *         "title": "Task Title"
     *     }
     *
     * ### Payload schema ###
     *
     *     {
     *         "type": "object",
     *         "properties": {
     *             "title": {
     *                 "type": "string"
     *             }
     *         },
     *         "required": [
     *             "title"
     *         ]
     *     }
     *
     * @ApiDoc(
     *     description = "Update a todo task",
     *     requirements={
     *         {
     *             "name"="id", "dataType"="string", "description"="Task id"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Todo task updated successfully",
     *         400 = "TodoValidateEmptyTitleException",
     *         500 = "TodoValidateIdNotFoundException"
     *     }
     * )
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws TodoTaskDoesNotExistException
     * @throws TodoValidateIdNotFoundException
     */
    public function update(string $id, Request $request)
    {
        return ResponseFormatter::json(
            $this->todoSerializer->toArray(
                $this->todoService->update(
                    $id,
                    json_decode($request->getContent(), true),
                    new DateTimeImmutable($request->headers->get('request-time'))
                )
            )
        );
    }

    /**
     * @ApiDoc(
     *     description = "List all todo tasks",
     *     statusCodes = {
     *         200 = "Todo task listed successfully"
     *     }
     * )
     *
     * @return JsonResponse
     */
    public function list()
    {
        return ResponseFormatter::json(
            $this->todoSerializer->collectionToArray(
                $this->todoService->list()
            )
        );
    }

    /**
     * @ApiDoc(
     *     description = "Delete a todo task",
     *     requirements={
     *         {
     *             "name"="id", "dataType"="string", "description"="Task id"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Todo task updated successfully",
     *         500 = "TodoValidateIdNotFoundException"
     *     }
     * )
     *
     * @param string $id
     * @return JsonResponse
     * @throws TodoValidateIdNotFoundException
     */
    public function delete(string $id)
    {
        return ResponseFormatter::json(
            $this->todoSerializer->toArray(
                $this->todoService->delete($id)
            )
        );
    }

    /**
     * @ApiDoc(
     *     description = "Get a todo task",
     *     requirements={
     *         {
     *             "name"="id", "dataType"="string", "description"="Task id"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Todo task updated successfully",
     *         500 = "TodoValidateIdNotFoundException"
     *     }
     * )
     *
     * @param string $id
     * @return JsonResponse
     * @throws TodoValidateIdNotFoundException
     */
    public function getById(string $id)
    {
        return ResponseFormatter::json(
            $this->todoSerializer->toArray(
                $this->todoService->getById($id)
            )
        );
    }
}
