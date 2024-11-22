<?php
/**
 * @package     app/Modules
 * @subpackage  Users
 * @file        UserController
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 19:17:21
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\App\Modules\Users;

use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;

class UserController {
   public function index(Request $req, Response $res) {
      $data = [
         'title' => 'Users Module',
         'nombre' => 'Fernando Castillo',
      ];
      return $res->render('users/index', $data)
         ->setStatus(200)
         ->setSession('user', 'admin', true)
         ->setCookie('user', 'admin', time() + 5, '/', '', false, true, 'Strict');
   }
}