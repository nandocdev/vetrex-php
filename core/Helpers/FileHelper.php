<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        FileHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:27:14
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \Exception;
class FileHelper {
   /**
    * Obtiene la extensión de un archivo.
    *
    * @param string $filename El nombre del archivo.
    * @return string La extensión del archivo (incluyendo el punto).
    */
   public static function getFileExtension(string $filename): string {
      return pathinfo($filename, PATHINFO_EXTENSION);
   }

   /**
    * Verifica si un archivo existe en una ruta específica.
    *
    * @param string $path La ruta del archivo.
    * @return bool True si el archivo existe, false si no.
    */
   public static function fileExists(string $path): bool {
      return file_exists($path);
   }

   /**
    * Lee el contenido de un archivo y lo devuelve.
    *
    * @param string $file La ruta del archivo a leer.
    * @return string El contenido del archivo.
    * @throws Exception Si el archivo no puede ser leído.
    */
   public static function readFile(string $file): string {
      if (!self::fileExists($file)) {
         throw new Exception("El archivo no existe: $file");
      }

      return file_get_contents($file);
   }

   /**
    * Elimina un archivo del sistema.
    *
    * @param string $path La ruta del archivo a eliminar.
    * @return bool True si el archivo fue eliminado con éxito, false si no.
    */
   public static function deleteFile(string $path): bool {
      if (!self::fileExists($path)) {
         throw new Exception("El archivo no existe: $path");
      }

      return unlink($path);
   }

   /**
    * Maneja la subida de archivos desde un formulario.
    *
    * @param string $fileInput El nombre del input de archivo en el formulario (por ejemplo, 'file').
    * @param string $destination La ruta destino donde se debe guardar el archivo.
    * @return bool True si el archivo se subió correctamente, false si no.
    * @throws Exception Si hubo un error durante la subida del archivo.
    */
   public static function uploadFile(string $fileInput, string $destination): bool {
      // Verificar si se ha subido un archivo
      if (!isset($_FILES[$fileInput])) {
         throw new Exception("No se ha recibido ningún archivo.");
      }

      $file = $_FILES[$fileInput];

      // Verificar si no hubo error en la subida
      if ($file['error'] !== UPLOAD_ERR_OK) {
         throw new Exception("Error al subir el archivo. Código de error: " . $file['error']);
      }

      // Verificar que el archivo no sea demasiado grande
      if ($file['size'] > 10 * 1024 * 1024) { // 10 MB máximo
         throw new Exception("El archivo excede el tamaño máximo permitido.");
      }

      // Verificar que el archivo sea de un tipo permitido (esto puede ser ajustado según necesidades)
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
      $fileExtension = self::getFileExtension($file['name']);
      if (!in_array($fileExtension, $allowedExtensions)) {
         throw new Exception("El tipo de archivo no está permitido.");
      }

      // Mover el archivo a la ubicación de destino
      $destinationPath = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($file['name']);
      if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
         return true;
      }

      throw new Exception("Error al mover el archivo a la ubicación de destino.");
   }

   /**
    * Obtiene el tamaño de un archivo en bytes.
    *
    * @param string $path La ruta del archivo.
    * @return int El tamaño del archivo en bytes.
    * @throws Exception Si el archivo no existe.
    */
   public static function getFileSize(string $path): int {
      if (!self::fileExists($path)) {
         throw new Exception("El archivo no existe: $path");
      }

      return filesize($path);
   }

   /**
    * Obtiene el nombre de un archivo sin su extensión.
    *
    * @param string $filename El nombre completo del archivo.
    * @return string El nombre del archivo sin la extensión.
    */
   public static function getFileNameWithoutExtension(string $filename): string {
      return pathinfo($filename, PATHINFO_FILENAME);
   }

   /**
    * Crea un directorio si no existe.
    *
    * @param string $path La ruta del directorio a crear.
    * @return bool True si el directorio fue creado o ya existía, false si no.
    */
   public static function createDirectoryIfNotExists(string $path): bool {
      if (!is_dir($path)) {
         return mkdir($path, 0777, true);
      }
      return true;
   }
}