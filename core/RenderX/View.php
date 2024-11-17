<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        View.php
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 20:21:53
 * @version     1.0.0
 * @description Clase principal de RenderX encargada de gestionar el proceso completo 
 *              de renderizado de vistas. Incluye el manejo de datos, parciales y layouts, 
 *              además de integrarse con el gestor de caché y recursos.
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX;

use Vertex\Core\Handler\Config;

class View {
   private Config $config;
   protected string $view;
   protected array $data = [];
   protected string $layout = 'default';
   private string $layoutPath = 'public/layouts/';
   private string $viewPath;

   public function __construct(string $view, array $data = [], string $layout = 'default') {
      $this->config = new Config();
      $this->view = $view;
      $this->data = $data;
      $this->viewPath = 'public/views/';
   }

   public function setViewPath(string $path) {
      $this->viewPath = $path;
   }

   public function setLayoutPath(string $path) {
      $this->layoutPath = $path;
   }

   public function render(): string {
      return "Rendered view: {$this->config->get('path.root')}/{$this->viewPath}{$this->view}.phtml";
   }

}