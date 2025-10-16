<?php
namespace Eklaro;

class ArticleValidator {
    private $db;
    private $nlpAnalyzer;
    private $factCheckAPI;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->nlpAnalyzer = new NLPAnalyzer();
        $this->factCheckAPI = new FactCheckAPI();
    }
    
    public function validateArticle($articleId) {
        // Get article
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $article = $stmt->get_result()->fetch_assoc();
        
        if (!$article) {
            return ['success' => false, 'message' => 'Article not found'];
        }
        
        // Update status to processing
        $this->updateArticleStatus($articleId, 'processing');
        
        try {
            // Perform NLP analysis
            $nlpAnalysis = $this->nlpAnalyzer->analyze($article['content']);
            
            // Perform fact checking
            $factCheckResults = $this->factCheckAPI->extractAndCheckClaims($article['content']);
            
            // Calculate credibility score
            $credibilityScore = $this->nlpAnalyzer->calculateCredibilityScore($nlpAnalysis, $factCheckResults);
            $credibilityLabel = $this->nlpAnalyzer->getCredibilityLabel($credibilityScore);
            
            // Generate explanation
            $explanation = $this->generateExplanation($nlpAnalysis, $factCheckResults, $credibilityScore);
            
            // Save validation results
            $resultId = $this->saveValidationResults(
                $articleId,
                $credibilityScore,
                $credibilityLabel,
                $nlpAnalysis,
                $factCheckResults,
                $explanation
            );
            
            // Save suspicious claims
            $this->saveSuspiciousClaims($articleId, $nlpAnalysis['suspicious_claims']);
            
            // Save fact check sources
            $this->saveFactCheckSources($articleId, $factCheckResults);
            
            // Update article with final score and status
            $stmt = $this->db->prepare(
                "UPDATE articles SET credibility_score = ?, validation_status = 'completed' WHERE id = ?"
            );
            $stmt->bind_param("di", $credibilityScore, $articleId);
            $stmt->execute();
            
            return [
                'success' => true,
                'result_id' => $resultId,
                'credibility_score' => $credibilityScore,
                'credibility_label' => $credibilityLabel
            ];
            
        } catch (\Exception $e) {
            $this->updateArticleStatus($articleId, 'failed');
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function saveValidationResults($articleId, $score, $label, $nlpAnalysis, $factCheckResults, $explanation) {
        $stmt = $this->db->prepare(
            "INSERT INTO validation_results 
             (article_id, credibility_score, credibility_label, nlp_analysis, fact_check_matches, 
              linguistic_features, metadata_features, explanation) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $nlpJson = json_encode($nlpAnalysis, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $factCheckJson = json_encode($factCheckResults, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $linguisticJson = json_encode($nlpAnalysis['linguistic_features'] ?? [], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $metadataJson = json_encode(['credibility_indicators' => $nlpAnalysis['credibility_indicators'] ?? []], JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        
        // Validate JSON encoding
        if ($nlpJson === false) {
            throw new \Exception('Failed to encode NLP analysis: ' . json_last_error_msg());
        }
        if ($factCheckJson === false) {
            throw new \Exception('Failed to encode fact check results: ' . json_last_error_msg());
        }
        if ($linguisticJson === false) {
            throw new \Exception('Failed to encode linguistic features: ' . json_last_error_msg());
        }
        if ($metadataJson === false) {
            throw new \Exception('Failed to encode metadata: ' . json_last_error_msg());
        }
        
        $stmt->bind_param(
            "idssssss",
            $articleId,
            $score,
            $label,
            $nlpJson,
            $factCheckJson,
            $linguisticJson,
            $metadataJson,
            $explanation
        );
        
        if (!$stmt->execute()) {
            throw new \Exception('Database error: ' . $stmt->error);
        }
        
        return $this->db->lastInsertId();
    }
    
    private function saveSuspiciousClaims($articleId, $claims) {
        if (empty($claims)) return;
        
        $stmt = $this->db->prepare(
            "INSERT INTO suspicious_claims 
             (article_id, claim_text, confidence_score, claim_type, position_start, position_end) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        foreach ($claims as $claim) {
            // Clean the text to remove problematic UTF-8 characters
            $claimText = mb_convert_encoding($claim['context'], 'UTF-8', 'UTF-8');
            $claimText = preg_replace('/[\x00-\x1F\x7F]/u', '', $claimText); // Remove control characters
            
            $confidenceScore = 75.0; // Default confidence
            $claimType = $claim['type'];
            $positionStart = $claim['position'];
            $positionEnd = $claim['position'] + strlen($claim['text']);
            
            $stmt->bind_param(
                "isdsii",
                $articleId,
                $claimText,
                $confidenceScore,
                $claimType,
                $positionStart,
                $positionEnd
            );
            
            $stmt->execute();
        }
    }
    
    private function saveFactCheckSources($articleId, $factCheckResults) {
        if (empty($factCheckResults)) return;
        
        $stmt = $this->db->prepare(
            "INSERT INTO fact_check_sources 
             (article_id, claim, rating, source_name, source_url, publisher, review_date) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        
        foreach ($factCheckResults as $result) {
            if (empty($result['matches'])) continue;
            
            foreach ($result['matches'] as $match) {
                if (empty($match['reviews'])) continue;
                
                foreach ($match['reviews'] as $review) {
                    $claim = $result['claim'];
                    $rating = $review['rating'];
                    if (!empty($rating) && strlen($rating) > 100) {
                        $rating = substr($rating, 0, 100);
                    }
                    $sourceName = $review['title'];
                    $sourceUrl = $review['url'];
                    $publisher = $review['publisher'];
                    $reviewDate = $review['reviewDate'];
                    if (!empty($reviewDate)) {
                        $timestamp = strtotime($reviewDate);
                        $reviewDate = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
                    } else {
                        $reviewDate = null;
                    }
                    
                    $stmt->bind_param(
                        "issssss",
                        $articleId,
                        $claim,
                        $rating,
                        $sourceName,
                        $sourceUrl,
                        $publisher,
                        $reviewDate
                    );
                    
                    $stmt->execute();
                }
            }
        }
    }
    
    private function generateExplanation($nlpAnalysis, $factCheckResults, $score) {
        $explanation = "Credibility Analysis:\n\n";
        
        // Score interpretation
        if ($score >= SCORE_VALID_MIN) {
            $explanation .= "This article shows strong credibility indicators. ";
        } elseif ($score >= SCORE_PARTIAL_MIN) {
            $explanation .= "This article shows mixed credibility signals. ";
        } else {
            $explanation .= "This article shows concerning credibility issues. ";
        }
        
        // Suspicious claims
        $suspiciousCount = count($nlpAnalysis['suspicious_claims']);
        if ($suspiciousCount > 0) {
            $explanation .= "\n\n• Found {$suspiciousCount} suspicious linguistic pattern(s) that may indicate bias, exaggeration, or unverified claims.";
        }
        
        // Credibility indicators
        $indicators = $nlpAnalysis['credibility_indicators'];
        $explanation .= "\n• Credible sources/citations: {$indicators['positive_count']}";
        $explanation .= "\n• Sensational language patterns: {$indicators['negative_count']}";
        
        // Readability
        $readability = $nlpAnalysis['readability_score'];
        $explanation .= "\n• Readability score: " . round($readability, 1) . "/100";
        
        // Fact check results
        if (!empty($factCheckResults)) {
            $explanation .= "\n\n• Found " . count($factCheckResults) . " claim(s) with fact-check verification available.";
        }
        
        $explanation .= "\n\nNote: This analysis uses NLP techniques and external fact-checking databases. Always verify important information from multiple reliable sources.";
        
        return $explanation;
    }
    
    private function updateArticleStatus($articleId, $status) {
        $stmt = $this->db->prepare("UPDATE articles SET validation_status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $articleId);
        $stmt->execute();
    }
    
    public function getValidationResult($articleId) {
        $stmt = $this->db->prepare(
            "SELECT vr.*, a.title, a.content, a.source_url 
             FROM validation_results vr 
             JOIN articles a ON vr.article_id = a.id 
             WHERE vr.article_id = ? 
             ORDER BY vr.created_at DESC 
             LIMIT 1"
        );
        
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if (!$result) {
            return null;
        }
        
        // Decode JSON fields
        $result['nlp_analysis'] = json_decode($result['nlp_analysis'], true);
        $result['fact_check_matches'] = json_decode($result['fact_check_matches'], true);
        $result['linguistic_features'] = json_decode($result['linguistic_features'], true);
        $result['metadata_features'] = json_decode($result['metadata_features'], true);
        
        // Get suspicious claims
        $stmt = $this->db->prepare(
            "SELECT * FROM suspicious_claims WHERE article_id = ? ORDER BY confidence_score DESC"
        );
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result['suspicious_claims'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get fact check sources
        $stmt = $this->db->prepare(
            "SELECT * FROM fact_check_sources WHERE article_id = ? ORDER BY review_date DESC"
        );
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result['fact_check_sources'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return $result;
    }
}
