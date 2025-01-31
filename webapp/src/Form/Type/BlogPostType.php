<?php declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\BlogPost;
use App\Entity\Executable;
use App\Entity\Problem;
use App\Utils\Utils;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
        $builder->add('subtitle', TextType::class);
        $builder->add('author', TextType::class, [
            'required' => false
        ]);
        $builder->add('thumbnail', FileType::class, [
            'label' => 'Thumbnail',
            'mapped' => false,
            'required' => $options['thumbnail_required']]);
        $builder->add('body', HiddenType::class);
        $builder->add('publishtime', DateTimeType::class, ['label' => 'Publish time']);

        $builder->add('send', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => BlogPost::class, 'thumbnail_required' => false]);

        $resolver->setAllowedTypes('thumbnail_required', 'bool');
    }
}
