<?php

namespace App\Form\Type;

use App\Entity\Address;
use App\Services\CityService;
use App\Services\CountryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddressType extends AbstractType
{
    private $countryService;
    private $cityService;
    public function __construct(CountryService $countryService, CityService $cityService)
    {
        $this->countryService = $countryService;
        $this->cityService = $cityService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryList = $this->countryService->getAll();
        $cityList = $this->cityService->getAll();
        $countries=[];
        $cities=[];
        foreach ($countryList as $country){
            array_push($countries,[$country->getTitle() => $country->getId()]);
        }
        foreach ($cityList as $city){
            array_push($cities,[$city->getTitle() => $city->getId()]);
        }

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('streetAndNumber', TextType::class)
            ->add('zip', TextType::class)
            ->add('chosenCountry', ChoiceType::class, ['choices' => $countries,'mapped' => false,'label' => 'Country',
                'placeholder' => 'select your country'])
            ->add('chosenCity', ChoiceType::class, ['choices' => $cities,'mapped' => false,'label' => 'City',
                'placeholder' => 'select your city'])
            ->add('phoneNumber', TextType::class)
            ->add('emailAddress', TextType::class)
            ->add('birthday', BirthdayType::class)
            ->add('pictureFile', FileType::class, ['required' => false, 'mapped'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
                ])
            ->add('save', SubmitType::class,  ['label' => 'save'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}