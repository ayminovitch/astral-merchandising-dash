<?php


namespace App\Form\Type;



use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
            ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
            ->add('retypedPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('telephone', NumberType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'label'=>false,
                'entry_options' => [
                    'by_reference' => false,
                    'label' => 'Rôle',
                    'choices' => [
                        'Agent' => 'ROLE_AGENT',
                        'Manager' => 'ROLE_MANAGER',
                        'Admin' => 'ROLE_ADMIN',
                    ],
                ],
            ])
            ->add('enabled',choiceType::class ,[
                    'choices'=> [
                        'true'=>'1',
                        'false'=>'0',
                ]
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);

    }

}