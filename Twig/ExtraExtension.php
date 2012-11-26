<?php

namespace Avro\ExtraBundle\Twig;

class ExtraExtension extends \Twig_Extension {

    protected $environment;

    public function __construct (\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'back_button' => new \Twig_Function_Method($this, 'backButton', array('is_safe' => array('html'))),
        );
    }
    public function getFilters()
    {
        return array(
            'allowQuotes'    => new \Twig_Filter_Function(
                '\Avro\ExtraBundle\Twig\ExtraExtension::allowQuotesFilter'
            ),
            'yn'    => new \Twig_Filter_Function(
                '\Avro\ExtraBundle\Twig\ExtraExtension::yesNo'
            ),
            'reset'    => new \Twig_Filter_Function(
                '\Avro\ExtraBundle\Twig\ExtraExtension::reset'
            ),
            'camelCaseToTitle'   => new \Twig_Filter_Function(
                '\Avro\ExtraBundle\Twig\ExtraExtension::camelCaseToTitle'
            ),
            'daysAgo'   => new \Twig_Filter_Function(
                '\Avro\ExtraBundle\Twig\ExtraExtension::daysAgoFilter'
            ),
        );
    }

    public function getName()
    {
        return 'ExtraExtension';
    }

    public static function allowQuotesFilter($var)
    {
        return 'xx';
        return str_replace("&#039;", "'", $var);
    }

    public static function reset(array $arr)
    {
        return reset($arr);
    }

    public static function camelCaseToTitle($input)
    {
        if (is_array($input)) {
            throw new \Exception('ucFirst twig filter input must be a string');
        }

        return trim(implode(" ", preg_split('/(?=[A-Z])/', ucfirst($input))));
    }


    public static function daysAgoFilter($date)
    {
        $display = array('Year', 'Month', 'Day', 'Hour', 'Minute', 'Second');
        $ago = 'Ago';

        $date = getdate($date->getTimestamp());
        $current = getdate();
        $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
        $factor = array(0, 12, 30, 24, 60, 60);

        for ($i = 0; $i < 6; $i++) {
            if ($i > 0) {
                $current[$p[$i]] += $current[$p[$i - 1]] * $factor[$i];
                $date[$p[$i]] += $date[$p[$i - 1]] * $factor[$i];
            }
            if ($current[$p[$i]] - $date[$p[$i]] > 1) {
                $value = $current[$p[$i]] - $date[$p[$i]];
                return $value . ' ' . $display[$i] . (($value != 1) ? 's' : '') . ' ' . $ago;
            }
        }

        return 'just now';
    }

    public static function yesNo($input)
    {
        if ($input) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function backButton($route)
    {
        $template = 'AvroExtraBundle:Templates:buttons.html.twig';

        $globals = $this->environment->getGlobals();
        $referer = $globals['app']->getRequest()->headers->get('referer');

        if (!$template instanceof \Twig_Template) {
            $template = $this->environment->loadTemplate($template);
        }

        return $template->renderBlock('back_button', array('route' => $route, 'referer' => $referer));
    }

}
