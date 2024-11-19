<?php
/**
 * @package     core/VRouter
 * @subpackage  Http
 * @file        Response
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:54:17
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Http;
use Vertex\Core\Handler\Config;
use Vertex\Core\RenderX\Core\RenderView;
use Vertex\Core\RenderX\Core\TemplateLoader;
use Vertex\Core\RenderX\Core\DataHandler;
use Vertex\Core\RenderX\Render;
class Response {
   private string $body = '';
   private array $headers = [];
   private int $length = 0;
   private int $status = 200;
   private array $cookies = [];
   private array $sessions = [];
   private string $controllerNamespace;
   private Config $config;

   public function __construct() {
      $this->config = new Config();
      $this->headers['Content-Type'] = 'text/html; charset=utf-8';
      $this->headers['X-Powered-By'] = $this->config->get('app.name') . ' ' . $this->config->get('app.version');
      $this->sessions = $_SESSION;
   }

   public function classNamespace($controller): void {
      $controller = explode('\\', $controller);
      array_shift($controller);
      array_pop($controller);
      $controller = implode(DIRECTORY_SEPARATOR, $controller);
      $controller = lcfirst($controller);
      $this->controllerNamespace = $controller;
   }



   public function render(string $viewName, array $data = [], string $layout = 'default'): self {
      $dataHandler = new DataHandler();
      $viewLoader = new TemplateLoader($this->config);
      // $renderView = new ();
      $view = new Render($this->config, $dataHandler, $viewLoader);
      $view->render($this->controllerNamespace, $viewName, $data, $layout);
      return $this;
   }

   public function addHeader(string $key, string $value): self {
      $this->headers[$key] = $value;
      return $this;
   }

   public function removeHeader(string $key): self {
      unset($this->headers[$key]);
      return $this;
   }

   public function setStatus(int $status): self {
      $this->status = $status;
      return $this;
   }

   public function setCookie(
      string $key,
      string $value = '',
      int $time = 0,
      string $path = '/',
      string $domain = '',
      bool $secure = false,
      bool $httponly = false,
      string $sameSite = 'Strict'
   ): self {
      $this->cookies[$key] = compact('value', 'time', 'path', 'domain', 'secure', 'httponly', 'sameSite');
      return $this;
   }

   public function removeCookie(string $key): self {
      unset($this->cookies[$key]);
      return $this;
   }

   public function setSession(string $key, string $value, bool $override = false): self {
      if (!$override && isset($this->sessions[$key])) {
         return $this;
      }
      $this->sessions[$key] = $value;
      return $this;
   }

   public function removeSession(string $key): self {
      unset($this->sessions[$key]);
      return $this;
   }

   private function setContentType(string $type): void {
      $types = [
         'html' => 'text/html; charset=utf-8',
         'plain' => 'text/plain; charset=utf-8',
         'xml' => 'text/xml; charset=utf-8',
         'json' => 'application/json',
      ];
      $this->headers['Content-Type'] = $types[$type] ?? $types['html'];
   }

   public function setBody(string $body, bool $append = false): self {
      $this->body = $append ? $this->body . $body : $body;
      $this->length = strlen($this->body);
      $this->headers['Content-Length'] = (string) $this->length;
      return $this;
   }

   public function json(array $data): self {
      $this->setContentType('json');
      $this->setBody(json_encode($data));
      return $this;
   }

   public function html(string $html): self {
      $this->setContentType('html');
      $this->setBody($html);
      return $this;
   }

   public function text(string $text): self {
      $this->setContentType('plain');
      $this->setBody($text);
      return $this;
   }

   public function xml(string $xml): self {
      $this->setContentType('xml');
      $this->setBody($xml);
      return $this;
   }

   public function redirect(string $url, int $status = 302): self {
      $this->setStatus($status);
      $this->addHeader('Location', filter_var($url, FILTER_VALIDATE_URL) ? $url : $this->config->get('app.url') . ltrim($url, '/'));
      $this->setBody($this->generateRedirectScript($url));
      return $this;
   }

   public function back(): self {
      $referer = $_SERVER['HTTP_REFERER'] ?? $this->config->get('app.url');
      return $this->redirect($referer);
   }

   private function generateRedirectScript(string $url): string {
      return "<script>window.location.href='$url';</script><noscript><meta http-equiv='refresh' content='0;url=$url'/></noscript>";
   }

   private function buildHeaders(): void {
      foreach ($this->headers as $key => $value) {
         header("$key: $value");
      }
   }

   private function buildStatusCode(): void {
      http_response_code($this->status);
   }

   private function buildSessions(): void {
      foreach ($this->sessions as $key => $value) {
         $_SESSION[$key] = $value;
      }
   }

   private function buildCookies(): void {
      foreach ($this->cookies as $key => $cookie) {
         setcookie(
            $key,
            $cookie['value'],
            $cookie['time'],
            $cookie['path'],
            $cookie['domain'],
            $cookie['secure'],
            $cookie['httponly']
         );
      }
   }



   public function __destruct() {
      $this->buildHeaders();
      $this->buildStatusCode();
      $this->buildSessions();
      $this->buildCookies();
      echo $this->body;
   }
}