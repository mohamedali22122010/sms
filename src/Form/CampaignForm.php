<?php
// src/Form/CampaignForm.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CampaignForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campaign', TextType::class, [
                'label' => 'campaign name',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'campaign message',
            ])
            ->add('csv_file', FileType::class, [
                'label' => 'csv_file',
            ])

            // Add more fields as needed
        ;
    }
}
