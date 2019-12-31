<?php


namespace App\Twig;

use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price_format', [$this, 'append_dollars'])
        ];
    }

    public function append_dollars(int $number)
    {
        return '$ ' . number_format($number, 3);
    }


    public function getGlobals()
    {
        return [
            'locale' => $this->locale
        ];
    }

    public function getTests()
    {
        return [
            new \Twig\TwigTest('like', function ($obj) {
                return $obj instanceof LikeNotification;
            })
        ];
    }
}