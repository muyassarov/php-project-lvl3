<?php

namespace App\Services\Domain;

use DiDom\Document;
use Illuminate\Support\Facades\Log;

class SeoAnalyzer
{

    public function analyze($htmlBody)
    {
        $seoInfo = [
            'h1'          => null,
            'description' => null,
            'keywords'    => null,
        ];

        try {
            $htmlDocument       = new Document($htmlBody);
            $h1Element          = $htmlDocument->first('h1');
            $descriptionElement = $htmlDocument->first('meta[name="description"]');
            $keywordsElement    = $htmlDocument->first('meta[name="keywords"]');
            if ($descriptionElement) {
                $seoInfo['description'] = $descriptionElement->getAttribute('content');
            }
            if ($keywordsElement) {
                $seoInfo['keywords'] = $keywordsElement->getAttribute('content');
            }
            if ($h1Element) {
                $seoInfo['h1'] = $h1Element->text();
            }
        } catch (\Exception $e) {
            Log::debug("Error occurred while parsing HTML document. Error: {$e->getMessage()}");
        }

        return $seoInfo;
    }
}
