<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\User;

final class PositionQrController extends AbstractController
{
    public function __construct(
        readonly private Pdf $pdf,
    ) {
    }

    #[Route(path: '/admin/position/{id}/qr', name: 'position_qr')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Position $position): Response
    {
        $html = $this->renderView('position/qr_label_25_95.html.twig', array(
            'position' => $position,
        ));

        $pdfOptions = [
            'page-width' => '90mm',
            'page-height' => '25mm',
            'margin-left' => '1mm',
            'margin-right' => '1mm',
            'margin-bottom' => '0.5mm',
            'margin-top' => '0.5mm',
        ];

        return new PdfResponse($this->pdf->getOutputFromHtml($html, $pdfOptions),'label.pdf');
    }
}
