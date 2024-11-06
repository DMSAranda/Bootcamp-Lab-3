<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('location', TextType::class, [
                'required' => true
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text', // Usar un único input para fecha
                'required' => true,
                'input' => 'datetime_immutable'
                
            ])
            ->add('time', TimeType::class, [
                'widget' => 'single_text', // Usar un único input para hora
                'required' => true,
                'input' => 'datetime_immutable'
                
            ])
            ->add('details', TextareaType::class, [
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
