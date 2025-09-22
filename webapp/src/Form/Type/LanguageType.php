<?php declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Executable;
use App\Entity\Language;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractExternalIdEntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addExternalIdField($builder, Language::class);
        $builder->add('langid', TextType::class, [
            'label' => 'Language ID',
        ]);
        $builder->add('name', TextType::class, [
            'empty_data' => ''
        ]);
        $builder->add('requireEntryPoint', ChoiceType::class, [
            'expanded' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
        $builder->add('entryPointDescription', TextType::class, [
            'required' => false,
        ]);
        $builder->add('allowSubmit', ChoiceType::class, [
            'expanded' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
        $builder->add('allowJudge', ChoiceType::class, [
            'expanded' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
        $builder->add('timeFactor', TextType::class, [
            'input_group_after' => '&times;',
        ]);
        $builder->add('compileExecutable', EntityType::class, [
            'label' => 'Compile script',
            'class' => Executable::class,
            'required' => false,
            'placeholder' => '-- no executable --',
            'choice_label' => 'execid',
            'query_builder' => fn(EntityRepository $er) => $er
                ->createQueryBuilder('e')
                ->where('e.type = :compile')
                ->setParameter('compile', 'compile')
                ->orderBy('e.execid'),
        ]);
        $builder->add('extensions', CollectionType::class, [
            'error_bubbling' => false,
            'entry_type' => TextType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
        ]);
        $builder->add('filterCompilerFiles', ChoiceType::class, [
            'label' => 'Filter files passed to compiler by extension list',
            'expanded' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
        $builder->add('compilerVersionCommand', TextType::class, [
            'label' => 'Compiler version command',
            'required' => false,
        ]);
        $builder->add('runnerVersionCommand', TextType::class, [
            'label' => 'Runner version command',
            'required' => false,
        ]);
        $builder->add('defaultEditorTemplateFilename', TextType::class, [
            'label' => 'Default editor template filename',
            'required' => false,
            'attr' => [
                'placeholder' => 'e.g., main.java, main.cpp, main.py',
            ],
            'help' => 'The default filename that will be used in the editor for this language.',
        ]);
        $builder->add('defaultEditorTemplateContent', TextareaType::class, [
            'label' => 'Default editor template content',
            'required' => false,
            'attr' => [
                'rows' => 15,
                'placeholder' => 'Enter the default code template for this language...',
                'class' => 'code-editor',
                'style' => 'font-family: monospace;',
            ],
            'help' => 'The default code template that will appear in the editor when this language is selected.',
        ]);
        $builder->add('save', SubmitType::class);

        // Remove ID field when doing an edit.
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Language|null $language */
            $language = $event->getData();
            $form     = $event->getForm();

            if ($language && $language->getLangid() !== null) {
                $form->remove('langid');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Language::class]);
    }
}
