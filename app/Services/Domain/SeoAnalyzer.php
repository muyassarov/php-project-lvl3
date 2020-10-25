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

        $document               = new Document($htmlBody);
        $seoInfo['h1']          = optional($document->first('h1'))->text();
        $seoInfo['description'] = optional($document->first('meta[name="description"]'))
            ->getAttribute('content');
        $seoInfo['keywords']    = optional($document->first('meta[name="keywords"]'))
            ->getAttribute('content');

        return $seoInfo;
    }
}
