<?php


namespace App\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonProvider
{
    private $encoders;
    private $logger;

    /**
     * Helpers constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->encoders = new JsonEncoder();
        $this->logger = $logger;
    }

    public function provideJsonFromContext($data, array $context, array $fieldsToNormalize)
    {
        $callback = static function ($innerObject) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ATOM) : '';
        };
        $fields = array();
        foreach ($fieldsToNormalize as $iValue) {
            $fields[$iValue] = $callback;
        }
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => $fields,
        ];
        try {
            $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);
        } catch (\Exception $e) {
            $this->logger->error('Normalizer exception: '.$e->getMessage());
            return [];
        }
        try {
            $serializer = new Serializer([$normalizer], [$this->encoders]);
        } catch (\Exception $e) {
            $this->logger->error('Serializer exception: '.$e->getMessage());
            return [];
        }
        try {
            $result = $serializer->normalize($data, null, $context);
        } catch (\Exception $e) {
            $this->logger->error('Normalizer result exception: '.$e->getMessage());
            return [];
        } catch (ExceptionInterface $e) {
            $this->logger->error('Normalizer result interface exception: '.$e->getMessage());
            return [];
        }
        return $result;
    }
}