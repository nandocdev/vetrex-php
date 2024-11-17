<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        EncryptionHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:05:03
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \Exception;
class EncryptionHelper {
   private const METHOD = 'AES-256-CBC'; // Método de cifrado

   /**
    * Encripta datos usando un algoritmo de encriptación simétrica (AES-256-CBC).
    *
    * @param string $data Datos a encriptar.
    * @param string $key Clave de encriptación.
    * @return string Datos encriptados en base64 (incluye el IV).
    * @throws Exception Si ocurre un error en la generación del IV.
    */
   public static function encrypt(string $data, string $key): string {
      $key = hash('sha256', $key, true); // Asegura que la clave sea de 256 bits
      $iv = random_bytes(openssl_cipher_iv_length(self::METHOD)); // Genera un IV seguro
      $encrypted = openssl_encrypt($data, self::METHOD, $key, 0, $iv);
      if ($encrypted === false) {
         throw new Exception('Error en la encriptación');
      }
      return base64_encode($iv . $encrypted); // Incluye el IV al inicio para desencriptar
   }

   /**
    * Desencripta datos previamente cifrados con el método `encrypt`.
    *
    * @param string $data Datos encriptados en base64.
    * @param string $key Clave de desencriptación.
    * @return string Datos desencriptados.
    * @throws Exception Si ocurre un error en la desencriptación.
    */
   public static function decrypt(string $data, string $key): string {
      $key = hash('sha256', $key, true);
      $data = base64_decode($data);
      $ivLength = openssl_cipher_iv_length(self::METHOD);
      $iv = substr($data, 0, $ivLength);
      $encryptedData = substr($data, $ivLength);
      $decrypted = openssl_decrypt($encryptedData, self::METHOD, $key, 0, $iv);
      if ($decrypted === false) {
         throw new Exception('Error en la desencriptación');
      }
      return $decrypted;
   }

   /**
    * Genera un hash seguro a partir de una cadena usando un algoritmo específico.
    *
    * @param string $data Datos a hashear.
    * @param string $algo Algoritmo de hashing (por defecto 'sha256').
    * @return string Hash generado en formato hexadecimal.
    * @throws Exception Si el algoritmo de hashing no es válido.
    */
   public static function generateHash(string $data, string $algo = 'sha256'): string {
      if (!in_array($algo, hash_algos())) {
         throw new Exception('Algoritmo de hash no válido');
      }
      return hash($algo, $data);
   }

   /**
    * Verifica si un hash coincide con los datos proporcionados.
    *
    * @param string $data Datos originales.
    * @param string $hash Hash con el que comparar.
    * @param string $algo Algoritmo de hashing (por defecto 'sha256').
    * @return bool True si coinciden, false en caso contrario.
    */
   public static function verifyHash(string $data, string $hash, string $algo = 'sha256'): bool {
      return hash_equals(self::generateHash($data, $algo), $hash);
   }

   /**
    * Genera una clave aleatoria segura para usar en encriptación.
    *
    * @param int $length Longitud de la clave (por defecto 32 bytes).
    * @return string Clave generada en formato hexadecimal.
    * @throws Exception Si ocurre un error al generar la clave.
    */
   public static function generateKey(int $length = 32): string {
      return bin2hex(random_bytes($length));
   }
}