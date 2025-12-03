<?php

namespace Ribal\Onix\Normalizer;

use Ribal\Onix\CodeList\CodeList;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CodeListNormalizer implements NormalizerInterface, DenormalizerInterface
{

	/**
	 * Language to get code files for
	 *
	 * @var string
	 */
	private $language = 'en';

	/**
	 * Constructor function
	 *
	 * @param string
	 * @return void
	 */
	public function __construct(string $language = 'en')
	{
		$this->language = $language;
	}

    /**
     * Check if the current object is a CodeList to be normalized
     *
     * @param mixed $data
     * @param string $format
     * @param array $context
     * @return boolean
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CodeList;
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $data->getCode();
    }

    /**
     * Checks if the current element is a CodeList that needs to be deserialized
     *
     * @param mixed $data
     * @param mixed $type
     * @param string $format
     * @param array $context
     * @return boolean
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_subclass_of($type, CodeList::class);
    }

    /**
     * Denormalize a given CodeList
     * $data represents a Code
     *
     * @param string $data
     * @param CodeList $type
     * @param string $format
     * @param array $context
     * @return CodeListList
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return $type::resolve($data, $this->language);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}
