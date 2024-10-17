<?php 
namespace App\Services;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FormHelper
{
    public function addImageField(FormBuilderInterface $builder): void
    {
        $builder->add('photo', FileType::class, [
            'label' => 'Add Photo',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png'
                    ],
                    'maxSizeMessage' => "l'image ne doit pas depasser 1024ko",
                    'mimeTypesMessage' => "Votre photo doit être du type JPEG ou PNG"
                ])
            ]
        ]);
    }
}
