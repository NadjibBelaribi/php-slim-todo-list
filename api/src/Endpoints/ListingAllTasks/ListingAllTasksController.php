<?php


namespace Amir_nadjib\Todo_list\Endpoints\ListingAllTasks;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ListingAllTasksController
{
    private ListingAllTasksService $service ;

    /**
     * ListingAllTasksController constructor.
     * @param ListingAllTasksService $service
     */
    public function __construct(ListingAllTasksService $service)
    {
        $this->service = $service;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface {
        // TODO: Implement __invoke() method.
        echo date('l jS \of F Y h:i:s A');
        try {
            $tasksTitles = $this->service->getTasks() ;
            var_dump($tasksTitles);
            $response->getBody()->write(json_encode($tasksTitles));
             return $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8003')->withStatus(200,
                 sprintf('all tasks %s',json_encode($tasksTitles)));
        }
        catch (NoTasksFoundException $exception) {
            return $response->withStatus(409,'no tasks found') ;
        }
    }


}