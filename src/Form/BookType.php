<?php
/**
 * Book type.
 */

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Publisher;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TagsDataTransformer;

/**
 * Class BookType.
 */
class BookType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var TagsDataTransformer
     */
    private TagsDataTransformer $tagsDataTransformer;

    /**
     * TaskType constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'bookName',
            TextType::class,
            [
                'label' => 'label_book_name',
                'required' => true,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->add(
            'bookDesc',
            TextareaType::class,
            [
                'label' => 'label_book_desc',
                'attr' => ['max_length' => 1000],
            ]
        );

        $builder->add(
            'releaseYear',
            IntegerType::class,
            [
                'label' => 'label_release_year',
            ]
        );

        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getCategoryName();
                },
                'label' => 'label_category_name',
                'required' => true,
            ]
        );

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label_tag_name',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );
        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );

        $builder->add(
            'author',
            EntityType::class,
            [
                'class' => Author::class,
                'choice_label' => function (Author $author) {
                    return $author->getAuthorName();
                },
                'label' => 'label_author_name',
                'required' => true,
            ]
        );

        $builder->add(
            'publisher',
            EntityType::class,
            [
                'class' => Publisher::class,
                'choice_label' => function (Publisher $publisher) {
                    return $publisher->getPublisherName();
                },
                'label' => 'label_publisher_name',
                'required' => true,
            ]
        );

        $builder->add(
            'amount',
            IntegerType::class,
            [
                'label' => 'label_amount',
                'required' => true,
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'book';
    }
}
