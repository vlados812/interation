<?php

class MetaTagIterator implements Iterator {
    private $tags = [];
    private $position = 0;

    public function __construct(string $htmlPath) {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile($htmlPath);
        
        $this->extractTags($dom);
    }

    private function extractTags(DOMDocument $dom) {
        $titleNodes = $dom->getElementsByTagName('title');
        if ($titleNodes->length > 0) {
            $this->tags['title'] = $titleNodes->item(0)->nodeValue;
        }
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $meta) {
            if ($meta instanceof DOMElement) {
                $name = strtolower($meta->getAttribute('name'));
                if (in_array($name, ['description', 'keywords'])) {
                    $this->tags[$name] = $meta->getAttribute('content');
                }
            }
        }
        

        $this->keys = array_keys($this->tags);
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function current(): mixed {
        $key = $this->keys[$this->position];
        return $this->tags[$key];
    }

    public function key(): mixed {
        return $this->keys[$this->position];
    }

    public function next(): void {
        ++$this->position;
    }

    public function valid(): bool {
        return isset($this->keys[$this->position]);
    }
}

$metaIterator = new MetaTagIterator('index.html');

foreach ($metaIterator as $name => $content) {
    echo "<b>$name</b>: $content <br>";
}
