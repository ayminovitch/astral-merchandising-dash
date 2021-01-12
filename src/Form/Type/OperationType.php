<?php


namespace App\Form\Type;


use App\Entity\Operation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('station', EntityType::class, [
                'class'=> 'App\Entity\Station',
                'placeholder' => 'Select Station',
            ])

            ->add('panel', EntityType::class, [
                'class'=> 'App\Entity\Panel',
                'placeholder' => 'Select Panel',
                ])

            ->add('commentaireBefore', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))

            ->add('CommentaireAfter', TextType::class, array('attr' => array('class' => 'form-control', 'required' => true)))



            ->add('imageBefore', FileType::class,[
                'label' => 'Image Before',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
            ])

            ->add('imageAfter', FileType::class,[
                'label' => 'Image After',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }

}