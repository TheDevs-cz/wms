<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Value\LabelType;

final class LocationQrBatchController extends AbstractController
{
    public function __construct(
        readonly private Pdf $pdf,
    ) {
    }

    #[Route(path: '/admin/location/{id}/qr-batch/{labelType}', name: 'location_qr_batch')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(Location $location, LabelType $labelType): Response
    {
        $html = $this->renderView('position/qr_label_' . $labelType->value . '.html.twig', [
            'positions' => $location->positions(),
            'location' => $location,
        ]);

        $pdfOptions = [
            'page-width' => $labelType->getWidth(),
            'page-height' => $labelType->getHeight(),
            'margin-left' => '1mm',
            'margin-right' => '1mm',
            'margin-bottom' => '0.5mm',
            'margin-top' => '0.5mm',
        ];

        return new PdfResponse($this->pdf->getOutputFromHtml($html, $pdfOptions),'labels.pdf');
    }
}
