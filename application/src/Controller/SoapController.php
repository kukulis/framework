<?php

namespace App\Controller;

use App\DTO\Book;
use App\Soap\BookService;
use Psr\Log\LoggerInterface;
use SoapFault;
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
        private LoggerInterface $logger,
        private string $wsdlPath
    )
    {
    }

    #[Route('/book-service', name: 'book-service')]
    public function bookService(Request $request): Response
    {
        $content = $request->getContent();
        $this->logger->info('received request with content: '.$content);

//        $wsdl = null;
        $wsdl = $this->wsdlPath;
        $this->logger->info('Wsdl path: '.$wsdl);

        // initialize SOAP Server
        $server = new SoapServer($wsdl, [
            'uri' => "http://symf-web/book-service"
        ]);

//        $server->setObject($this->bookService);
        $server->setClass(BookService::class);

//        header_register_callback(
//            function (... $args) {
//                $this->logger->info( 'headers callback: '. json_encode($args));
//            }
//        );


        $responseContent = 'asdf';
        ob_start();
        try {
            $server->handle($content);
        } catch (SoapFault $e) {
            $this->logger->error('SoapFault: '.$e->getMessage());
        }

        $responseContent = ob_get_clean();

        $this->logger->info('Response content: '.$responseContent);

        return new Response($responseContent, Response::HTTP_OK);

//        exit();
    }
}