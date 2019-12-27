<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullname', TextType::class, ['label' => 'Full Name'])
            ->add('username', TextType::class, ['label' => 'User Name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password field must match',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('agree', CheckboxType::class, [
                'label' => 'I agree on the terms and conditions',
                'mapped' => false,
                'invalid_message' => 'You cant register if you dont agree on the',
                'constraints' => [
                    new IsTrue()
                ]])
            ->add('register', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // what is the entity related to this form
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}