<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController
{
  /**
   * @Route("/lucky/number", name="lucky_number")
   */
  public function number()
  {
    $number = random_int(0, 20);
    return new Response(
        '<html> <body> Lucky Number: ' . $number . '</body></html>'
    );
  }
}