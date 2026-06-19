<?php

/**
 * Site metadata container with description generation.
 *
 * Stores basic site information and provides methods to generate
 * a short descriptive text based on the stored data.
 */

class SiteMeta
{
    private string $name;
    private string $baseUrl;
    private array $keywords;
    private string $locale;
    private string $description;

    public function __construct(
        string $name = 'Example Site',
        string $baseUrl = 'https://cn-cnweb-hth.com',
        array $keywords = ['hth', 'web', 'meta'],
        string $locale = 'zh_CN',
        string $description = ''
    ) {
        $this->name = $name;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->keywords = $keywords;
        $this->locale = $locale;
        $this->description = $description !== '' ? $description : $this->generateDefaultDescription();
    }

    /**
     * Generate a short description text from the stored metadata.
     *
     * @param int $maxLength Maximum length of the description (default 160).
     * @return string
     */
    public function generateShortDescription(int $maxLength = 160): string
    {
        $base = sprintf(
            '%s: %s | %s | %s',
            $this->name,
            $this->description,
            implode(', ', $this->keywords),
            $this->locale
        );

        if (mb_strlen($base) <= $maxLength) {
            return $base;
        }

        return mb_substr($base, 0, $maxLength - 3) . '...';
    }

    /**
     * Get the full site URL.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get site name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get keywords array.
     *
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * Generate a default description when none is provided.
     *
     * @return string
     */
    private function generateDefaultDescription(): string
    {
        return sprintf(
            'A metadata-driven site focused on %s at %s.',
            implode(' and ', array_slice($this->keywords, 0, 2)),
            $this->baseUrl
        );
    }

    /**
     * Output a simple HTML meta tag block (safe for embedding).
     *
     * @return string
     */
    public function toHtmlMetaTags(): string
    {
        $escapedName = htmlspecialchars($this->name, ENT_QUOTES, 'UTF-8');
        $escapedDesc = htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8');
        $escapedUrl  = htmlspecialchars($this->baseUrl, ENT_QUOTES, 'UTF-8');
        $keywordsStr = htmlspecialchars(implode(', ', $this->keywords), ENT_QUOTES, 'UTF-8');

        return <<<HTML
<meta name="description" content="{$escapedDesc}" />
<meta name="keywords" content="{$keywordsStr}" />
<meta property="og:title" content="{$escapedName}" />
<meta property="og:url" content="{$escapedUrl}" />
HTML;
    }
}

// Example usage (data based on context)
$meta = new SiteMeta(
    name: 'HTH Resource Hub',
    baseUrl: 'https://cn-cnweb-hth.com',
    keywords: ['hth', 'web', 'resource', 'guide'],
    locale: 'zh_CN'
);

echo $meta->generateShortDescription(120) . PHP_EOL;