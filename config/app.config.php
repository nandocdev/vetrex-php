<?php
/**
 * @package     srv/vertex
 * @subpackage  config
 * @file        app.config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:00:15
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

return [
   'app' => [
      'name' => 'Vertex',
      'version' => '1.0.0',
      'description' => 'Enterprise Software Engineering Corporation',
      'author' => 'Fernando Castillo <ferncastillov@outlook.com>',
      'timezone' => 'America/Panama',
      'locale' => 'es_PA',
      'charset' => 'UTF-8',
      'env' => 'development',
      'debug' => true,
      'methods_allowed' => ['GET', 'POST', 'PUT', 'DELETE'],
      'url' => 'http://localhost:8080',
   ],
   'path' => [
      'root' => dirname(__DIR__),
      'app' => dirname(__DIR__) . '/app' . DIRECTORY_SEPARATOR,
      'config' => dirname(__DIR__) . '/config' . DIRECTORY_SEPARATOR,
      'public' => dirname(__DIR__) . '/public' . DIRECTORY_SEPARATOR,
      'tmp' => dirname(__DIR__) . '/tmp' . DIRECTORY_SEPARATOR,
      'vendor' => dirname(__DIR__) . '/vendor' . DIRECTORY_SEPARATOR,
   ],
   'mail' => [
      'driver' => 'smtp',
      'host' => 'smtp.gmail.com',
      'port' => 587,
      'username' => '',
      'password' => '',
      'encryption' => 'tls',
      'from' => '',
      'from_name' => '',
   ],
   'system' => [
      'module' => 'home',
      'controller' => 'home',
      'action' => 'index',
      'breadcrumbs' => true,
      'namespace' => 'ESE\\App\\Modules\\',
   ],
   'hash' => [
      'algo' => PASSWORD_BCRYPT,
      'cost' => 10,
      'seed' => sha1('Vertex'),
   ],
   'session' => [
      'name' => 'session_Vertex',
      'notify' => 'notify_Vertex',
      'lifetime' => 3600,
      'path' => '/',
      'domain' => null,
      'secure' => false,
      'httponly' => true,
   ],
];