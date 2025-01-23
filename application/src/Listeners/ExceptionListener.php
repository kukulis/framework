<?php

namespace App\Listeners;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        // TODO skip security exceptions

        $contentType = $event->getRequest()->headers->get('Content-Type', 'text/plain');

        // You get the exception object from the received event
        $exception = $event->getThrowable();
//        if ( $exception instanceof AccessDeniedHttpException ) {
//            return;
//        }

        $message = sprintf(
            '%s; Code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $statusCode = null;

        if ( $exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        if ( $statusCode == null  && $exception instanceof AccessDeniedHttpException) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }

        if ( $statusCode == null ) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ( $contentType == 'application/json' ) {
            $errorResponse = [
                'error' => get_class($exception),
                'message' => $message,
            ];

            $event->setResponse( new Response(json_encode($errorResponse), $statusCode, ['Content-Type' => 'application/json']) );

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