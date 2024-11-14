<?php
/**
 * @package     srv/vertex
 * @subpackage  public
 * @file        index
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 21:38:41
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

$vendor = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor)) {
   die('Please run: composer install');
}

require $vendor;