<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre email",
                'attr' => [
                    'placeholder' => "exemple@gmail.com"
                ],
                'help' => "Nous ne vous enverrons pas de pub, promis!",
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => "Entrez votre message",
                'attr' => [
                    'placeholder' => "Bonjour ...",
                    'rows' => 7,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
