<?php


namespace App\Form\Type;


use App\Entity\Station;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
//        ->add('Gouvernorat',choiceType::class ,[
//            'placeholder' => 'Select Gouvernorat',
//            'choices'=> [
//                    'Ariana' => 'Ariana', 'Béja' => 'Béja', 'Ben Arous' => 'Ben Arous',
//                    'Bizerte' => 'Bizerte', 'Gabès' => 'Gabès', 'Gafsa' => 'Gafsa',
//                    'Jendouba' => 'Jendouba',  'Kairouan' => 'Kairouan', 'Kasserine' => 'Kasserine',
//                    'Kébili' => 'Kébili', 'Le Kef' => 'Le Kef', 'Mahdia' => 'Mahdia',
//                    'La Manouba' => 'La Manouba', 'Médenine' => 'Médenine', 'Monastir' => 'Monastir',
//                    'Nabeul' => 'Nabeul', 'Sfax' => 'Sfax', 'Sidi Bouzid' => 'Sidi Bouzid',
//                    'Siliana' => 'Siliana', 'Sousse' => 'Sousse', 'Tataouine' => 'Tataouine',
//                    'Tozeur' => 'Tozeur',   'Tunis' => 'Tunis', 'Zaghouan' => 'Zaghouan'
//            ]
//        ])

        ->add('Nom', TextType::class, array(
            'attr' => array('class' => 'form-control', 'required' => true),
            'label'=>'Nom'))
//        ->add('Adresse', TextType::class, array(
//            'attr' => array('class' => 'form-control', 'required' => true),
//            'label'=>'Adresse'))
//        ->add('Longitude', NumberType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
//        ->add('Latitude', NumberType::class, array('attr' => array('class' => 'form-control', 'required' => true)))
        ->add('save', SubmitType::class, array(
            'label' => 'Enregistrer',
            'attr' => array('class' => 'btn btn-primary mt-3')
        ));
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Station::class,
        ]);
    }

}