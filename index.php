<?php

$htmlContent = file_get_contents('index.html');


$dom = new DOMDocument();
@$dom->loadHTML($htmlContent);

$data = [];


$titleTags = $dom->getElementsByTagName('title');
if ($titleTags->length > 0) {
    $data['title'] = $titleTags->item(0)->nodeValue;
}


$metaTags = $dom->getElementsByTagName('meta');

foreach ($metaTags as $tag) {
 
    if ($tag instanceof DOMElement) {
        $name = strtolower($tag->getAttribute('name'));
        
        if ($name == 'description' || $name == 'keywords') {
            $data[$name] = $tag->getAttribute('content');
        }
    }
}


print_r($data);
?>