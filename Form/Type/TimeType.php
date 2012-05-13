<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\FormView;

class TimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $parts  = array('hour', 'minute');
        $format = 'H:i';

        if ($options['with_seconds']) {
            $format  = 'H:i:s';
            $parts[] = 'second';
        }

        if ('single_text' === $options['widget']) {
            $builder->appendClientTransformer(new DateTimeToStringTransformer($options['data_timezone'], $options['user_timezone'], $format));
        } else {
            $hourOptions = $minuteOptions = $secondOptions = array();

            if ('choice' === $options['widget']) {
                if (is_array($options['empty_value'])) {
                    $options['empty_value'] = array_merge(array('hour' => null, 'minute' => null, 'second' => null, 'meridian' => 'am'), $options['empty_value']);
                } else {
                    $options['empty_value'] = array('hour' => $options['empty_value'], 'minute' => $options['empty_value'], 'second' => $options['empty_value'], 'meridian' => $options['empty_value']);
                }

                $hours = $minutes = array();

                if ($options['with_meridian']) {
                    foreach ($options['hours'] as $hour) {
                        if ($hour > 0 && $hour <= 12) {
                            $hours[$hour] = str_pad($hour, 2, '0', STR_PAD_LEFT);
                        }
                    }
                } else {
                    foreach ($options['hours'] as $hour) {
                        $hours[$hour] = str_pad($hour, 2, '0', STR_PAD_LEFT);
                    }
                }

                foreach ($options['minutes'] as $minute) {
                    if ($minute % $options['minute_step'] == 0) {
                        $minutes[$minute] = str_pad($minute, 2, '0', STR_PAD_LEFT);
                    }
                }

                // Only pass a subset of the options to children
                $hourOptions = array(
                    'choices' => $hours,
                    'empty_value' => $options['empty_value']['hour'],
                );
                $minuteOptions = array(
                    'choices' => $minutes,
                    'empty_value' => $options['empty_value']['minute'],
                );

                if ($options['with_seconds']) {
                    $seconds = array();

                    foreach ($options['seconds'] as $second) {
                        $seconds[$second] = str_pad($second, 2, '0', STR_PAD_LEFT);
                    }

                    $secondOptions = array(
                        'choices' => $seconds,
                        'empty_value' => $options['empty_value']['second'],
                    );
                }

                if ($options['with_meridian']) {
                    $meridianOptions = array(
                        'choices' => $options['meridian_choices'],
                        'empty_value' => $options['empty_value']['second'],
                    );
                }

                // Append generic carry-along options
                foreach (array('required', 'translation_domain') as $passOpt) {
                    $hourOptions[$passOpt] = $minuteOptions[$passOpt] = $options[$passOpt];
                    if ($options['with_seconds']) {
                        $secondOptions[$passOpt] = $options[$passOpt];
                    }
                    if ($options['with_meridian']) {
                        $meridianOptions[$passOpt] = $options[$passOpt];
                    }

                }
            }

            $builder
                ->add('hour', $options['widget'], $hourOptions)
                ->add('minute', $options['widget'], $minuteOptions)
            ;

            if ($options['with_seconds']) {
                $builder->add('second', $options['widget'], $secondOptions);
            }

            if ($options['with_meridian']) {
                $builder->add('meridian', $options['widget'], $meridianOptions);
            }


            $builder->appendClientTransformer(new DateTimeToArrayTransformer($options['data_timezone'], $options['user_timezone'], $parts, 'text' === $options['widget']));
        }

        if ('string' === $options['input']) {
            if ($options['with_meridian']) {
                $builder->appendNormTransformer(new ReversedTransformer(
                    new DateTimeToStringTransformer($options['data_timezone'], $options['data_timezone'], 'H:i:s')
                ));
            } else {
                $builder->appendNormTransformer(new ReversedTransformer(
                    new DateTimeToStringTransformer($options['data_timezone'], $options['data_timezone'], 'H:i:s')
                ));
            }
        } elseif ('timestamp' === $options['input']) {
            $builder->appendNormTransformer(new ReversedTransformer(
                new DateTimeToTimestampTransformer($options['data_timezone'], $options['data_timezone'])
            ));
        } elseif ('array' === $options['input']) {
            $builder->appendNormTransformer(new ReversedTransformer(
                new DateTimeToArrayTransformer($options['data_timezone'], $options['data_timezone'], $parts)
            ));
        }

        $builder
            ->setAttribute('widget', $options['widget'])
            ->setAttribute('with_seconds', $options['with_seconds'])
            ->setAttribute('with_meridian', $options['with_meridian'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('widget', $form->getAttribute('widget'))
            ->set('with_seconds', $form->getAttribute('with_seconds'))
            ->set('with_meridian', $form->getAttribute('with_meridian'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'hours'          => range(0, 23),
            'minutes'        => range(0, 59),
            'seconds'        => range(0, 59),
            'meridian_choices' => array('am', 'pm'),
            'widget'         => 'choice',
            'input'          => 'datetime',
            'with_seconds'   => false,
            'with_meridian'   => false,
            'minute_step' => 1,
            'data_timezone'  => null,
            'user_timezone'  => null,
            'empty_value'    => null,
            // Don't modify \DateTime classes by reference, we treat
            // them like immutable value objects
            'by_reference'   => false,
            'error_bubbling' => false,
            // If initialized with a \DateTime object, FormType initializes
            // this option to "\DateTime". Since the internal, normalized
            // representation is not \DateTime, but an array, we need to unset
            // this option.
            'data_class'     => null,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedOptionValues()
    {
        return array(
            'input' => array(
                'datetime',
                'string',
                'timestamp',
                'array',
            ),
            'widget' => array(
                'single_text',
                'text',
                'choice',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'time';
    }
}
