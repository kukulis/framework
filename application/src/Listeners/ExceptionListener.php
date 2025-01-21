<?php

namespace App\Listeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        // TODO skip security exceptions

        $contentType = $event->getRequest()->headers->get('Content-Type', 'text/plain');

        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        if ( $contentType == 'application/json' ) {
            $errorResponse = [
                'error' => get_class($exception),
                'message' => $message,
            ];

            $event->setResponse( new Response(json_encode($errorResponse), 500, ['Content-Type' => 'application/json']) );

            return;
        }

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);
        // the exception message can contain unfiltered user input;
        // set the content-type to text to avoid XSS issues
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}