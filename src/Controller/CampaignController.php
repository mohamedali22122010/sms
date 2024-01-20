<?php

namespace App\Controller;

use App\Form\SingleForm;
use App\Form\CampaignForm;
use App\Message\CampaignFileMessage;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\MessageHandler\CampaignFileMessageHandler;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class CampaignController extends AbstractController
{
    #[Route('/bulk', name: 'app_bulk')]
    public function bulk(Request $request): Response
    {
        $form = $this->createForm(CampaignForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the form data
            $formData = $form->getData();

            $handler = new CampaignFileMessageHandler();

            $bus = new MessageBus([
                new HandleMessageMiddleware(new HandlersLocator([
                    CampaignFileMessage::class => [$handler],
                ])),
            ]);
            $bus->dispatch(new CampaignFileMessage($formData['csv_file'], $formData['message']));
       
            $this->addFlash('success', 'campaign added to queue sucessfully');

            return $this->redirectToRoute('app_bulk');

    
            // Handle form submission, e.g., persist data to the database
            // Redirect to a success page or do something else
        }

        return $this->render('campaign/bulk.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
