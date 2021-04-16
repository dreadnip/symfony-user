<?php

namespace App\Controller;

use Dreadnip\ChromePdfBundle\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function __invoke(PdfGenerator $pdfGenerator): Response {
        $html = $this->render('pdf.html.twig')->getContent();

        $options = [
            'printBackground' => true,
            'displayHeaderFooter' => true,
            'preferCSSPageSize' => true,
            'headerTemplate'=> "<div></div>",
            'footerTemplate' => "<div></div>",
            'scale' => 1.0,
        ];

        $path = $pdfGenerator->generate($html, 'files/test.pdf', $options);

        return new BinaryFileResponse($path);
    }
}
