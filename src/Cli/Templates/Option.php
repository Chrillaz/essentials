<?php

namespace AppNameSpace;

use \Essentials\Essentials;

use \Essentials\Abstracts\Option;

use \Essentials\Contracts\OptionInterface;

final class ClassName extends Option {

  public static function register ( Essentials $container ): OptionInterface {

    return new Self( 
      $container[],
      $container[],
      $container[]
     );
  }
}