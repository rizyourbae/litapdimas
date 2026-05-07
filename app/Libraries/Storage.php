<?php

namespace App\Libraries;

use Config\Storage as StorageConfig;
use stdClass;

/**
 * $ ID: Storage.php 2022-2026 GMT+8 rudi.pratm@gmail.com $
 *
 * Hak cipta (c) 2022-2026, Rudi Pratama. Seluruh hak cipta.
 *
 * Pendistribusian ulang dan penggunaan dalam bentuk sumber dan biner,
 * dengan atau tanpa modifikasi, diizinkan asalkan kondisi berikut terpenuhi:
 *
 * - Distribusi ulang kode sumber harus mempertahankan pemberitahuan
 *   hak cipta di atas, daftar ketentuan ini, dan penafian berikut.
 * - Distribusi ulang dalam bentuk biner harus mereproduksi pemberitahuan
 *   hak cipta di atas, daftar ketentuan ini dan penafian berikut dalam
 *   dokumentasi dan/atau materi lain yang disediakan bersama distribusi.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN OLEH PEMEGANG HAK CIPTA DAN KONTRIBUTOR
 * "SEBAGAIMANA ADANYA" DAN SETIAP JAMINAN TERSURAT MAUPUN TERSIRAT, TERMASUK,
 * NAMUN TIDAK TERBATAS PADA, JAMINAN TERSIRAT UNTUK DIPERDAGANGKAN DAN
 * KESESUAIAN UNTUK TUJUAN TERTENTU. DALAM KEADAAN APAPUN PEMILIK HAK CIPTA
 * ATAU KONTRIBUTOR TIDAK BERTANGGUNG JAWAB ATAS KERUSAKAN LANGSUNG, TIDAK
 * LANGSUNG, INSIDENTAL, KHUSUS, TELADAN, ATAU AKIBAT (TERMASUK, NAMUN TIDAK
 * TERBATAS PADA, PENGADAAN BARANG PENGGANTI, LAYANAN PENGGANTI; ATAU GANGGUAN
 * BISNIS) NAMUN DISEBABKAN DAN TEORI TANGGUNG JAWAB APA PUN, BAIK DALAM KONTRAK,
 * TANGGUNG JAWAB KETAT, ATAU KERUGIAN (TERMASUK KELALAIAN ATAU LAINNYA) YANG
 * TIMBUL DALAM CARA APA PUN DARI PENGGUNAAN PERANGKAT LUNAK INI, BAHKAN JIKA
 * PERANGKAT LUNAK INI.
 *
 * UINSI Storage adalah layanan milik UIN Sultan Aji Muhammad Idris Samarinda.
 */

/**
 * Storage PHP class
 *
 * @link https://storage.uinsi.ac.id/
 * @author rudi.pratm@gmail.com
 * @version 2.0.0
 */
class Storage
{

    // Shared flags
    const SHR_PRIVATE = '0';
    const SHR_PUBLIC = '1';

    public static $use_ssl = false;
    public static $verify_peer = true;
    public static $access_key = '';
    public static $secret_key = '';

    private $app_name;
    private $bucket_name;
    private $storage_url;
    private $max_size;
    private $allowed_ext;
    private $expires;

    public function __construct($config = [])
    {
        // Load Storage config
        $storageConfig = new StorageConfig();

        self::$access_key = $storageConfig->accessKey;
        self::$secret_key = $storageConfig->secretKey;
        self::$use_ssl = $storageConfig->useSsl;
        self::$verify_peer = $storageConfig->verifyPeer;

        $this->app_name = $storageConfig->appName;
        $this->bucket_name = $storageConfig->bucketName;
        $this->storage_url = $storageConfig->storageUrl;
        $this->max_size = $storageConfig->maxObjectSize;
        $this->allowed_ext = $storageConfig->allowedExt;
        $this->expires = $storageConfig->expires;

        if (!empty($config)) {
            $this->initialize($config);
        }
    }

    /**
     * Initialize preferences
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function initialize($config = array())
    {
        if (isset($config['access_key']) && !empty($config['access_key'])) {
            self::$access_key = $config['access_key'];
        }

        if (isset($config['secret_key']) && !empty($config['secret_key'])) {
            self::$secret_key = $config['secret_key'];
        }

        if (isset($config['use_ssl'])) {
            self::$use_ssl = $config['use_ssl'];
        }

        if (isset($config['verify_peer'])) {
            self::$verify_peer = $config['verify_peer'];
        }

        if (isset($config['storage_url']) && !empty($config['storage_url'])) {
            $this->storage_url = $config['storage_url'];
        }
    }

    /**
     * Put an Object (S3-style method)
     * Upload file dengan validasi otomatis
     *
     * @param string $object Variabel $_FILES
     * @param string $object_dir Target Directory
     * @param string $object_name Object Name
     * @param integer $shared Shared flags (1 = public, 0 = private)
     * @param string $bucket_name Bucket Name (optional)
     * @return array
     */
    public function putObject($object, $object_dir, $object_name = '', $shared = 1, $bucket_name = '')
    {
        $tmp = $object['tmp_name'];
        if (!file_exists($tmp) || !is_file($tmp) || !is_readable($tmp)) {
            $response = ['status' => 0, 'message' => 'Invalid file!'];
            return $response;
        }

        $size = $object["size"];
        $ext = strtolower(pathinfo($object["name"], PATHINFO_EXTENSION));

        if (empty($object_name)) {
            $object_name = $this->genUuid() . '.' . $ext;
        } else {
            $clear_dots = str_replace('.', '_', $object_name);
            $clean_name = str_replace(' ', '_', $clear_dots);
            $object_name = $clean_name . '.' . $ext;
        }

        if (empty($bucket_name)) {
            $bucket = $this->bucket_name;
        } else {
            $bucket = $bucket_name;
        }

        //Cek ukuran dan ekstensi
        if ($size <= $this->max_size && in_array($ext, $this->allowed_ext)) {
            $act = $this->uploadObject([
                'Bucket' => $bucket,
                'Directory' => $object_dir,
                'Name' => $object_name,
                'Data' => $object["tmp_name"],
                'Shared' => $shared
            ]);

            //Get response
            if ($act['code'] == '000') {
                $response = ['status' => 1, 'message' => 'Success', 'data' => ['bucket' => $bucket, 'directory' => $object_dir, 'object_name' => $object_name]];
            } else {
                $response = ['status' => 0, 'message' => 'Error uploading file! ' . $act['message']];
            }
        } else {
            $response = ['status' => 0, 'message' => 'File size or extension not allowed!'];
        }

        return $response;
    }

    /**
     * Launch an Object (Alias untuk backward compatibility)
     * @deprecated Use putObject() instead
     * @see putObject()
     */
    public function launchObject($object, $object_dir, $object_name = '', $shared = 1, $bucket_name = '')
    {
        return $this->putObject($object, $object_dir, $object_name, $shared, $bucket_name);
    }

    /**
     * Get Object URL (S3-style method)
     * Generate URL untuk mengakses object
     *
     * @param string $object_dir Object Directory
     * @param string $object_name Object Name
     * @param string $bucket_name Bucket Name (optional)
     * @param integer $shared Shared flag (1 = public, 0 = private)
     * @return string
     */
    public function getObjectUrl($object_dir, $object_name, $bucket_name = '', $shared = 0)
    {
        $raw_url = parse_url($this->storage_url);
        $scheme = $raw_url['scheme'];
        $host = $raw_url['host'];
        $url = $scheme . '://' . $host;
        $access_time = time();

        if (empty($bucket_name)) {
            $bucket = $this->bucket_name;
        } else {
            $bucket = $bucket_name;
        }

        $params = '';
        if ($shared == 0) {
            $payload = [
                'access_key' => self::$access_key,
                'object_name' => $object_name,
                'origin' => $this->app_name,
                'access_time' => $access_time,
                'expires' => $this->expires
            ];
            $signature = hash_hmac('SHA256', json_encode($payload), self::$secret_key);

            $params = '?AccessKey=' . self::$access_key . '&X-Stg-Origin=' . $this->app_name . '&X-Stg-Access=' . $access_time . '&X-Stg-Expires=' . $this->expires . '&Signature=' . $signature;
        }

        return $url . '/' . $bucket . '/' . $object_dir . '/' . $object_name . $params;
    }

    /**
     * Catch an Object (Alias untuk backward compatibility)
     * @deprecated Use getObjectUrl() instead
     * @see getObjectUrl()
     */
    public function catchObject($object_dir, $object_name, $bucket_name = '', $shared = 0)
    {
        return $this->getObjectUrl($object_dir, $object_name, $bucket_name, $shared);
    }

    /**
     * Delete Object (S3-style method)
     * Hapus object dari storage
     *
     * @param string $object_name Nama berkas
     * @return array
     */
    public function deleteObject($object_name)
    {
        $act = $this->removeObject(['Name' => $object_name]);

        //Get response
        if ($act['code'] == '000') {
            $response = ['status' => 1, 'message' => 'Success'];
        } else {
            $response = ['status' => 0, 'message' => 'Error deleting file! ' . $act['message']];
        }

        return $response;
    }

    /**
     * Burn an Object (Alias untuk backward compatibility)
     * @deprecated Use deleteObject() instead
     * @see deleteObject()
     */
    public function burnObject($object_name)
    {
        return $this->deleteObject($object_name);
    }

    /**
     * Restore Object (S3-style method)
     * Restore object yang sudah dihapus
     *
     * @param string $object_name Nama berkas
     * @return array
     */
    public function restoreObject($object_name)
    {
        $act = $this->recoverObjectRaw(['Name' => $object_name]);

        //Get response
        if ($act['code'] == '000') {
            $response = ['status' => 1, 'message' => 'Success'];
        } else {
            $response = ['status' => 0, 'message' => 'Error restoring file! ' . $act['message']];
        }

        return $response;
    }

    /**
     * Upload Object (Low-level method)
     * Upload object tanpa validasi
     *
     * @param mixed $objectData Object Data
     * @return array
     */
    public function uploadObject($objectData)
    {
        $headers = "Content-Type:multipart/form-data";
        $object_bucket = strtolower($objectData['Bucket']);
        $object_dir = strtolower($objectData['Directory']);
        $object_name = strtolower($objectData['Name']);
        $object_data = curl_file_create($objectData['Data']); //create file
        $object_shared = $objectData['Shared'];
        $data = [
            'tray' => $object_bucket,
            'directory' => $object_dir,
            'name' => $object_name,
            'shared' => $object_shared,
            'object' => $object_data
        ];
        $rest = new StorageRequest('POST', $uri = 'object', $data, $headers);
        $rest->getResponse();

        return $rest->response;
    }

    /**
     * Post an Object (Alias untuk backward compatibility)
     * @deprecated Use uploadObject() instead
     * @see uploadObject()
     */
    public function postObject($objectData)
    {
        return $this->uploadObject($objectData);
    }

    /**
     * Remove Object (Low-level method)
     * Hapus object tanpa wrapper
     *
     * @param mixed $objectData Object Data
     * @return array
     */
    public function removeObject($objectData)
    {
        $data = ['name' => strtolower($objectData['Name'])];
        $rest = new StorageRequest('DELETE', $uri = 'object?name=' . $objectData['Name']);
        $rest->getResponse();

        return $rest->response;
    }

    /**
     * Recover Object Raw (Low-level method)
     * Restore object tanpa wrapper - method internal
     *
     * @param mixed $objectData Object Data
     * @return array
     */
    private function recoverObjectRaw($objectData)
    {
        $data = ['name' => strtolower($objectData['Name'])];
        $rest = new StorageRequest('PUT', $uri = 'object?name=' . $objectData['Name']);
        $rest->getResponse();

        return $rest->response;
    }

    /**
     * Generate UUID
     *
     * @return string
     */
    private function genUuid()
    {
        $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
        assert(strlen($data) == 16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Get Hash
     *
     * @param string $string
     * @return string
     */
    private function getHash($string)
    {
        return base64_encode(extension_loaded('hash') ?
            hash_hmac('sha1', $string, self::$secret_key, true) : pack('H*', sha1(
                (str_pad(self::$secret_key, 64, chr(0x00)) ^ (str_repeat(chr(0x5c), 64))) .
                    pack('H*', sha1((str_pad(self::$secret_key, 64, chr(0x00)) ^
                        (str_repeat(chr(0x36), 64))) . $string))
            )));
    }
}

final class StorageRequest
{

    public $data = false, $response;

    private $secret_key;
    private $storage_url;
    private $method;
    private $headers;
    private $uri;

    /**
     * Constructor
     *
     * @param string $method Metode
     * @param string $uri Object URI
     * @param mixed $data Object Data
     * @param mixed $headers Set Headers
     * @return mixed
     */
    public function __construct($method, $uri = '', $data = '', $headers = '')
    {
        $this->secret_key = Storage::$secret_key;

        // Load Storage config to get storage_url
        $storageConfig = new StorageConfig();
        $this->storage_url = $storageConfig->storageUrl;

        $this->method = $method;
        $this->headers = $headers;
        $this->uri = strtolower($uri);
        $this->data = $data;

        $this->response = new stdClass();
        $this->response->error = false;
        $this->response->message = false;
    }

    /**
     * Get the Storage response
     *
     * @return array
     */
    public function getResponse()
    {
        $url = $this->storage_url . '/' . $this->uri;

        $curl = curl_init();

        $header = array("secret:" . $this->secret_key, $this->headers);

        curl_setopt($curl, CURLOPT_USERAGENT, 'StorageLibrary/PHP');

        if (Storage::$use_ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            if (Storage::$verify_peer) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            } else {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);

        switch ($this->method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            default:
                break;
        }

        $exec = curl_exec($curl); // Execute the CURL
        if ($exec) { // Get API Response
            $resp = json_decode($exec); // Return as stdObject
            $this->response = array(
                'code' => $resp->code,
                'message' => $resp->message,
                'data' => $this->data
            );
        } else { // Get cURL Error Response
            $this->response = array(
                'code' => curl_errno($curl),
                'message' => curl_error($curl),
                'data' => $this->data
            );
        }

        @curl_close($curl);

        return $this->response;
    }
}
