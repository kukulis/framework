<?php

namespace App\Controller;

use App\Soap\BookService;
use Psr\Log\LoggerInterface;
use SoapServer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class SoapController
{
    public function __construct(
        private BookService $bookService,
        private LoggerInterface $logger

    )
    {
    }

    #[Route('/book-service', name: 'book-service')]
    public function bookService(Request $request): Response
    {
        $content = $request->getContent();
        $this->logger->info('received request with content: '.$content);

        $wsdl = NULL;

        // initialize SOAP Server
        $server = new SoapServer($wsdl, [
            'uri' => "http://symf-web/book-service"
        ]);


        $server->setObject($this->bookService);

        ob_start();
        $server->handle($content);

        return new Response(ob_get_clean());
    }
}