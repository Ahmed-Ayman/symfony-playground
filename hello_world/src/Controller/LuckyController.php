<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LuckyController
{
  public function number()
  {
    $number = random_int(0, 20);
    return new Response(
        '<html> <body> Lucky Number: ' . $number . '</body></html>'
    );
  }
}