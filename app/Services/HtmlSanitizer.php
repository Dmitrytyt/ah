<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'em', 'b', 'i', 'u',
        'ul', 'ol', 'li', 'blockquote',
        'h2', 'h3', 'h4', 'pre', 'code',
        'a', 'img',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title'],
    ];

    private const DANGEROUS_TAGS = ['script', 'style', 'iframe', 'object', 'embed'];

    public function sanitize(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previousUseInternalErrors = libxml_use_internal_errors(true);

        $wrappedHtml = '<?xml encoding="UTF-8"><div id="sanitizer-root">' . $html . '</div>';
        $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $root = $dom->getElementById('sanitizer-root');
        if (!$root instanceof DOMElement) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
            return '';
        }

        $this->sanitizeTree($root);

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $dom->saveHTML($child);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $clean;
    }

    private function sanitizeTree(DOMNode $node): void
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $this->sanitizeElement($child);
                continue;
            }

            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
            }
        }
    }

    private function sanitizeElement(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);

        if (in_array($tag, self::DANGEROUS_TAGS, true)) {
            $element->parentNode?->removeChild($element);
            return;
        }

        if (!in_array($tag, self::ALLOWED_TAGS, true)) {
            $this->unwrapElement($element);
            return;
        }

        $this->sanitizeAttributes($element, $tag);
        $this->sanitizeTree($element);
    }

    private function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];
        $toRemove = [];

        foreach ($element->attributes as $attribute) {
            $name = strtolower($attribute->name);

            if (!in_array($name, $allowed, true)) {
                $toRemove[] = $name;
                continue;
            }

            if (in_array($name, ['href', 'src'], true) && !$this->isSafeUrl($attribute->value)) {
                $toRemove[] = $name;
            }
        }

        foreach ($toRemove as $name) {
            $element->removeAttribute($name);
        }

        if ($tag === 'a') {
            $target = strtolower($element->getAttribute('target'));
            if ($target === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private function unwrapElement(DOMElement $element): void
    {
        $parent = $element->parentNode;
        if (!$parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private function isSafeUrl(string $url): bool
    {
        $trimmed = trim($url);

        if ($trimmed === '' || str_starts_with($trimmed, '#') || str_starts_with($trimmed, '/')) {
            return true;
        }

        if (preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:/', $trimmed) !== 1) {
            return true;
        }

        $scheme = strtolower((string) parse_url($trimmed, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto'], true);
    }
}
