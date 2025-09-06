<?php
/**
 * Index Interface
 * Based on the blog post requirements for record-level index
 */
interface iindex {
    /**
     * Store documents for a given term/word
     * @param string $name The term/word
     * @param array $documents Array of document records
     * @return bool Success status
     */
    public function storeDocuments($name, array $documents);
    
    /**
     * Get documents for a given term/word
     * @param string $name The term/word
     * @return array Array of document records
     */
    public function getDocuments($name);
    
    /**
     * Clear the entire index
     * @return void
     */
    public function clearIndex();
    
    /**
     * Validate a document record
     * @param array $document Document record to validate
     * @return bool Validation result
     */
    public function validateDocument(array $document);
}
?>