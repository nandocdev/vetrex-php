<?php
/**
 * @package     vertex/core
 * @subpackage  Bootstrap
 * @file        App
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:16:46
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Bootstrap;

use Vertex\Core\Handler\Config;

class App {
   private static bool $sessionStarted = false;
   private static function sessions(): void {
      if (self::$sessionStarted)
         return;

      if (session_status() === PHP_SESSION_NONE) {
         ini_set('session.cookie_httponly', 1);
         ini_set('session.use_only_cookies', 1);
         ini_set('session.use_strict_mode', 1);
         session_start();
      }

      self::$sessionStarted = true;
   }

   private static function setupEnvironment(): void {
      $cfg = new Config();
      date_default_timezone_set($cfg->get('app.timezone'));
      setlocale(LC_ALL, $cfg->get('app.locale'));
      mb_internal_encoding($cfg->get('app.charset'));
      mb_http_output($cfg->get('app.charset'));
      // mb_http_input(Config::get('app', 'charset'));
      mb_language('uni');
      mb_regex_encoding($cfg->get('app.charset'));
      mb_regex_set_options('u');
   }

   public static function run(): void {
      self::sessions();
      self::setupEnvironment();
   }
}