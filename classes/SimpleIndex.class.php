<?php
require_once 'IndexInterface.php';

// Define constants if not already defined
if (!defined('SINGLEINDEX_DOCUMENTCOUNT')) {
    define('SINGLEINDEX_DOCUMENTCOUNT', 3);
}
if (!defined('SINGLEINDEX_DOCUMENTBYTESIZE')) {
    define('SINGLEINDEX_DOCUMENTBYTESIZE', 12); // 3 integers × 4 bytes each
}
if (!defined('SINGLEINDEX_DOCUMENTINTEGERBYTESIZE')) {
    define('SINGLEINDEX_DOCUMENTINTEGERBYTESIZE', 4);
}
if (!defined('SINGLEINDEX_DOCUMENTFILEEXTENTION')) {
    define('SINGLEINDEX_DOCUMENTFILEEXTENTION', '.idx');
}
if (!defined('INDEXLOCATION')) {
    define('INDEXLOCATION', dirname(__FILE__) . '/../index_storage/');
}


class SimpleIndex implements iindex {

    public function validateDocument(?array $document = null) {
        if (!is_array($document)) return false;
        if (count($document) != SINGLEINDEX_DOCUMENTCOUNT) return false;
        foreach ($document as $value) {
            if (!is_int($value) || $value < 0) return false;
        }
        return true;
    }

    public function storeDocuments($name, ?array $documents = null) {
        if ($name === null || $documents === null || trim($name) == '') return false;
        if (!is_string($name) || !is_array($documents)) return false;

        foreach ($documents as $doc) {
            if (!$this->validateDocument($doc)) return false;
        }

        $fp = fopen($this->_getFilePathName($name), 'w');
        foreach ($documents as $doc) {
            foreach ($doc as $val) {
                fwrite($fp, pack('i', intval($val)));
            }
        }
        fclose($fp);
        return true;
    }

    public function getDocuments($name) {
        $file = $this->_getFilePathName($name);
        if (!file_exists($file)) return [];

        $fp = fopen($file, 'r');
        $filesize = filesize($file);
        if ($filesize % SINGLEINDEX_DOCUMENTBYTESIZE != 0) {
            throw new Exception('Filesize not correct - index is corrupt!');
        }

        $result = [];
        while (!feof($fp)) {
            $bindata1 = fread($fp, SINGLEINDEX_DOCUMENTINTEGERBYTESIZE);
            if (strlen($bindata1) < 4) break; // end of file
            $bindata2 = fread($fp, SINGLEINDEX_DOCUMENTINTEGERBYTESIZE);
            $bindata3 = fread($fp, SINGLEINDEX_DOCUMENTINTEGERBYTESIZE);
            $data1 = unpack('i', $bindata1);
            $data2 = unpack('i', $bindata2);
            $data3 = unpack('i', $bindata3);
            $result[] = [$data1[1], $data2[1], $data3[1]];
        }

        fclose($fp);
        return $result;
    }

    public function clearIndex() {
        $files = glob(INDEXLOCATION . '*');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
    }

    private function _getFilePathName($name) {
        return INDEXLOCATION . $name . SINGLEINDEX_DOCUMENTFILEEXTENTION;
    }
}
