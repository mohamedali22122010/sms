<?php

namespace App\Controller;

use App\Form\SingleForm;
use App\Message\SingleMessage;
use Symfony\Component\Messenger\MessageBus;
use App\MessageHandler\SingleMessageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class MessageController extends AbstractController
{
    #[Route('/single', name: 'app_single')]
    public function single(Request $request): Response
    {
        $form = $this->createForm(SingleForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the form data
            $formData = $form->getData();

            $handler = new SingleMessageHandler();

            $bus = new MessageBus([
                new HandleMessageMiddleware(new HandlersLocator([
                    SingleMessage::class => [$handler],
                ])),
            ]);
            $bus->dispatch(new SingleMessage($formData['phone'], $formData['message']));
       
            $this->addFlash('success', 'message added to queue sucessfully');

            return $this->redirectToRoute('app_single');

    
            // Handle form submission, e.g., persist data to the database
            // Redirect to a success page or do something else
        }

        return $this->render('campaign/single.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
