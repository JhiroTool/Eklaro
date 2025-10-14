<?php
namespace Eklaro;

class NLPAnalyzer {
    private $suspiciousPatterns = [
        'exaggeration' => [
            '/\b(always|never|everyone|nobody|all|none)\b/i',
            '/\b(100%|completely|totally|absolutely|definitely)\b/i',
            '/\b(shocking|unbelievable|incredible|amazing|miracle)\b/i'
        ],
        'bias' => [
            '/\b(obviously|clearly|undoubtedly|certainly)\b/i',
            '/\b(they say|some people|experts|sources)\b/i'
        ],
        'unverified' => [
            '/\b(allegedly|reportedly|rumored|claimed|supposedly)\b/i',
            '/\b(may|might|could|possibly|perhaps)\b/i'
        ],
        'misleading' => [
            '/\b(secret|hidden|they don\'t want you to know|truth)\b/i',
            '/\b(conspiracy|cover-up|exposed)\b/i'
        ]
    ];
    
    private $credibilityIndicators = [
        'positive' => [
            '/\b(study|research|according to|data shows|statistics)\b/i',
            '/\b(professor|doctor|scientist|expert|researcher)\b/i',
            '/\b(published|peer-reviewed|journal|university)\b/i',
            '/\d{4}/' // Years
        ],
        'negative' => [
            '/!!!+/',
            '/\?{2,}/',
            '/[A-Z]{5,}/', // ALL CAPS
            '/\b(click here|share now|act now)\b/i'
        ]
    ];
    
    public function analyze($text) {
        $analysis = [
            'suspicious_claims' => $this->detectSuspiciousClaims($text),
            'credibility_indicators' => $this->analyzeCredibilityIndicators($text),
            'linguistic_features' => $this->extractLinguisticFeatures($text),
            'readability_score' => $this->calculateReadability($text),
            'sentiment' => $this->analyzeSentiment($text)
        ];
        
        return $analysis;
    }
    
    private function detectSuspiciousClaims($text) {
        $claims = [];
        
        foreach ($this->suspiciousPatterns as $type => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        $claims[] = [
                            'type' => $type,
                            'text' => $match[0],
                            'position' => $match[1],
                            'context' => $this->getContext($text, $match[1], 50)
                        ];
                    }
                }
            }
        }
        
        return $claims;
    }
    
    private function analyzeCredibilityIndicators($text) {
        $positive = 0;
        $negative = 0;
        
        foreach ($this->credibilityIndicators['positive'] as $pattern) {
            $positive += preg_match_all($pattern, $text);
        }
        
        foreach ($this->credibilityIndicators['negative'] as $pattern) {
            $negative += preg_match_all($pattern, $text);
        }
        
        return [
            'positive_count' => $positive,
            'negative_count' => $negative,
            'ratio' => $positive > 0 ? $positive / ($positive + $negative) : 0
        ];
    }
    
    private function extractLinguisticFeatures($text) {
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($text, 1);
        
        return [
            'word_count' => count($words),
            'sentence_count' => count($sentences),
            'avg_sentence_length' => count($sentences) > 0 ? count($words) / count($sentences) : 0,
            'avg_word_length' => count($words) > 0 ? array_sum(array_map('strlen', $words)) / count($words) : 0,
            'exclamation_count' => substr_count($text, '!'),
            'question_count' => substr_count($text, '?'),
            'uppercase_ratio' => $this->calculateUppercaseRatio($text)
        ];
    }
    
    private function calculateReadability($text) {
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($text, 1);
        $syllables = $this->countSyllables($text);
        
        if (count($sentences) === 0 || count($words) === 0) {
            return 0;
        }
        
        // Flesch Reading Ease Score
        $score = 206.835 - 1.015 * (count($words) / count($sentences)) - 84.6 * ($syllables / count($words));
        
        return max(0, min(100, $score));
    }
    
    private function analyzeSentiment($text) {
        $positiveWords = ['good', 'great', 'excellent', 'positive', 'beneficial', 'success', 'improve', 'better'];
        $negativeWords = ['bad', 'terrible', 'negative', 'harmful', 'failure', 'worse', 'decline', 'crisis'];
        
        $text = strtolower($text);
        $positive = 0;
        $negative = 0;
        
        foreach ($positiveWords as $word) {
            $positive += substr_count($text, $word);
        }
        
        foreach ($negativeWords as $word) {
            $negative += substr_count($text, $word);
        }
        
        $total = $positive + $negative;
        
        return [
            'positive_count' => $positive,
            'negative_count' => $negative,
            'polarity' => $total > 0 ? ($positive - $negative) / $total : 0
        ];
    }
    
    private function getContext($text, $position, $length = 50) {
        $start = max(0, $position - $length);
        $end = min(strlen($text), $position + $length);
        return substr($text, $start, $end - $start);
    }
    
    private function calculateUppercaseRatio($text) {
        $letters = preg_replace('/[^a-zA-Z]/', '', $text);
        if (strlen($letters) === 0) return 0;
        
        $uppercase = preg_replace('/[^A-Z]/', '', $letters);
        return strlen($uppercase) / strlen($letters);
    }
    
    private function countSyllables($text) {
        $words = str_word_count(strtolower($text), 1);
        $syllables = 0;
        
        foreach ($words as $word) {
            $syllables += $this->countWordSyllables($word);
        }
        
        return $syllables;
    }
    
    private function countWordSyllables($word) {
        $word = strtolower($word);
        $count = preg_match_all('/[aeiouy]+/', $word);
        
        // Adjust for silent e
        if (substr($word, -1) === 'e') {
            $count--;
        }
        
        return max(1, $count);
    }
    
    public function calculateCredibilityScore($analysis, $factCheckMatches = []) {
        $score = 50; // Base score
        
        // Adjust based on suspicious claims
        $suspiciousCount = count($analysis['suspicious_claims']);
        $score -= min(30, $suspiciousCount * 3);
        
        // Adjust based on credibility indicators
        $indicators = $analysis['credibility_indicators'];
        $score += min(20, $indicators['positive_count'] * 2);
        $score -= min(20, $indicators['negative_count'] * 3);
        
        // Adjust based on readability
        $readability = $analysis['readability_score'];
        if ($readability >= 60 && $readability <= 80) {
            $score += 10;
        } elseif ($readability < 30 || $readability > 90) {
            $score -= 10;
        }
        
        // Adjust based on linguistic features
        $features = $analysis['linguistic_features'];
        if ($features['exclamation_count'] > 5) {
            $score -= 5;
        }
        if ($features['uppercase_ratio'] > 0.1) {
            $score -= 10;
        }
        
        // Adjust based on fact check matches
        if (!empty($factCheckMatches)) {
            foreach ($factCheckMatches as $match) {
                if (isset($match['rating'])) {
                    $rating = strtolower($match['rating']);
                    if (strpos($rating, 'true') !== false || strpos($rating, 'correct') !== false) {
                        $score += 10;
                    } elseif (strpos($rating, 'false') !== false || strpos($rating, 'incorrect') !== false) {
                        $score -= 15;
                    }
                }
            }
        }
        
        return max(0, min(100, $score));
    }
    
    public function getCredibilityLabel($score) {
        if ($score >= SCORE_VALID_MIN) {
            return 'Valid';
        } elseif ($score >= SCORE_PARTIAL_MIN) {
            return 'Partially Valid';
        } else {
            return 'Invalid';
        }
    }
}
