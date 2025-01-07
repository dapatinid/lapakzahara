<?php $title = ""; 
   switch ($_SERVER['PHP_SELF']) {
     case '/www/lapaku/index.php':
       $title = 'Home'; 
       break;
    case '/www/lapaku/about.php':
       $title = 'About';
       break;
    case '/www/lapaku/services.php':
       $title = 'Services';
       break;
    case '/www/lapaku/portfolio.php':
      $title = 'Portfolio';
      break;
   case '/www/lapaku/staff.php':
      $title = 'Staff';
      break;
   case '/www/lapaku/contact.php':
      $title = 'Contact us';
      break; 
   }
   ?>
