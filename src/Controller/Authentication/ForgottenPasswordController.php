<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TheDevs\WMS\Exceptions\UserNotRegistered;
use TheDevs\WMS\FormData\RequestPasswordResetFormData;
use TheDevs\WMS\FormType\RequestPasswordResetFormType;
use TheDevs\WMS\Message\User\RequestPasswordReset;

final class ForgottenPasswordController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/forgotten-password', name: 'forgotten_password')]
    public function __invoke(Request $request, #[CurrentUser] null|UserInterface $user): Response
    {
        if ($user !== null) {
            return $this->redirectToRoute('dashboard');
        }

        $formData = new RequestPasswordResetFormData();
        $form = $this->createForm(RequestPasswordResetFormType::class, $formData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->bus->dispatch(
                    new RequestPasswordReset($formData->email),
                );

                $this->addFlash('success', 'Poslali jsme vám e-mail s instrukcemi pro obnovu vašeho zapomenutého hesla. Pokud Vám zpráva nedorazí, zkontrolujte pro jistotu složku SPAM.');

                return $this->redirectToRoute('dashboard');
            } catch (HandlerFailedException $failedException) {
                $realException = $failedException->getPrevious();

                if ($realException instanceof UserNotRegistered) {
                    $this->addFlash('danger', 'Tento e-mail u nás není zaregistrován.');

                    return $this->redirectToRoute('forgotten_password');
                }

                throw $realException ?? $failedException;
            }
        }

        return $this->render('forgotten_password.html.twig', [
            'form' => $form,
        ]);
    }
}
