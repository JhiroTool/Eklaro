<?php
namespace Eklaro;

class FactCheckAPI {
    private $apiKey;
    private $baseUrl = 'https://factchecktools.googleapis.com/v1alpha1/claims:search';
    private $db;
    
    public function __construct() {
        $this->apiKey = GOOGLE_FACT_CHECK_API_KEY;
        $this->db = Database::getInstance();
    }
    
    public function searchClaims($query, $languageCode = 'en') {
        if (empty($this->apiKey)) {
            return ['error' => 'API key not configured'];
        }
        
        $params = [
            'query' => $query,
            'languageCode' => $languageCode,
            'key' => $this->apiKey
        ];
        
        $url = $this->baseUrl . '?' . http_build_query($params);
        
        $startTime = microtime(true);
        $response = $this->makeRequest($url);
        $responseTime = (microtime(true) - $startTime) * 1000;
        
        // Log API usage
        $this->logAPIUsage('Google Fact Check', $url, $params, $response, $responseTime);
        
        return $response;
    }
    
    public function extractAndCheckClaims($text) {
        $claims = $this->extractClaims($text);
        $results = [];
        
        foreach ($claims as $claim) {
            $response = $this->searchClaims($claim);
            
            if (isset($response['claims']) && !empty($response['claims'])) {
                $results[] = [
                    'claim' => $claim,
                    'matches' => $this->parseClaimResults($response['claims'])
                ];
            }
        }
        
        return $results;
    }
    
    private function extractClaims($text) {
        // Split text into sentences
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $claims = [];
        
        // Look for sentences that make factual claims
        $claimIndicators = [
            'is', 'are', 'was', 'were', 'has', 'have', 'will',
            'study', 'research', 'report', 'according', 'said',
            'announced', 'discovered', 'found', 'revealed'
        ];
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            $lowerSentence = strtolower($sentence);
            
            // Check if sentence contains claim indicators
            foreach ($claimIndicators as $indicator) {
                if (strpos($lowerSentence, $indicator) !== false) {
                    // Only add sentences with reasonable length
                    if (strlen($sentence) > 20 && strlen($sentence) < 200) {
                        $claims[] = $sentence;
                        break;
                    }
                }
            }
        }
        
        // Limit to top 5 claims to avoid API quota issues
        return array_slice($claims, 0, 5);
    }
    
    private function parseClaimResults($claims) {
        $parsed = [];
        
        foreach ($claims as $claim) {
            $claimData = [
                'text' => $claim['text'] ?? '',
                'claimant' => $claim['claimant'] ?? 'Unknown',
                'claimDate' => $claim['claimDate'] ?? null,
                'reviews' => []
            ];
            
            if (isset($claim['claimReview']) && is_array($claim['claimReview'])) {
                foreach ($claim['claimReview'] as $review) {
                    $claimData['reviews'][] = [
                        'publisher' => $review['publisher']['name'] ?? 'Unknown',
                        'url' => $review['url'] ?? '',
                        'title' => $review['title'] ?? '',
                        'rating' => $review['textualRating'] ?? 'Unrated',
                        'reviewDate' => $review['reviewDate'] ?? null
                    ];
                }
            }
            
            $parsed[] = $claimData;
        }
        
        return $parsed;
    }
    
    private function makeRequest($url) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            return ['error' => $error, 'http_code' => $httpCode];
        }
        
        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Invalid JSON response', 'http_code' => $httpCode];
        }
        
        return array_merge($decoded, ['http_code' => $httpCode]);
    }
    
    private function logAPIUsage($apiName, $endpoint, $requestData, $response, $responseTime) {
        $responseStatus = $response['http_code'] ?? null;
        $errorMessage = $response['error'] ?? null;

        // Prevent oversized endpoint strings from breaking DB inserts
        if (strlen($endpoint) > 255) {
            $endpoint = substr($endpoint, 0, 255);
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO api_usage_logs (api_name, endpoint, request_data, response_status, response_time_ms, error_message) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        $requestJson = json_encode($requestData);
        $responseTimeInt = (int)$responseTime;
        
        $stmt->bind_param("sssiss", $apiName, $endpoint, $requestJson, $responseStatus, $responseTimeInt, $errorMessage);
        $stmt->execute();
    }
}
