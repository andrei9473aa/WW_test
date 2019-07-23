<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetApplicationManagerType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Choose manager',
                'choice_label' => function(User $user) {
                    return $user->getEmail();
                },
                'choices' => $this->userRepository->findManagers('ROLE_MANAGER'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
