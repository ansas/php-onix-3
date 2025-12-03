<?php

namespace Ribal\Onix;

use Ribal\Onix\Message\Message;
use Ribal\Onix\Normalizer\CodeListNormalizer;
use Ribal\Onix\Normalizer\DateNormalizer;
use Ribal\Onix\Normalizer\ShortTagNameConverter;
use Ribal\Onix\Normalizer\TextNormalizer;
use Ribal\Onix\Product\Product;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use SimpleXMLElement;

class Parser
{
    private XmlEncoder $encoder;
    private array      $normalizers = [];
    private Serializer $serializer;

    public function __construct(string $language = 'en')
    {
        $supportedLanguages = ['en', 'es', 'de', 'fr', 'it', 'nb', 'tr'];

        if (!in_array($language, $supportedLanguages)) {
            throw new \InvalidArgumentException('Language must be one of ' . join(', ', $supportedLanguages));
        }

        $this->encoder = new ONIXEncoder();

        $this->normalizers = [
            new ArrayDenormalizer(),
            new CodeListNormalizer($language),
            new DateNormalizer(),
            new TextNormalizer(),
            new ObjectNormalizer(
                null,
                new ShortTagNameConverter(),
                null,
                new ReflectionExtractor()
            ),
        ];

        $this->serializer = new Serializer(
            $this->normalizers,
            [$this->encoder]
        );
    }

    public function parseString(string $xml): Message
    {
        return $this->serializer->deserialize($xml, Message::class, 'xml');
    }

    public function parseStringProduct(string $xml): Product
    {
        return $this->serializer->deserialize($xml, Product::class, 'xml');
    }
}
