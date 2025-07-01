<?php

declare(strict_types=1);

namespace Wise\GPSR\Service\GpsrSupplier\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplier;
use Wise\GPSR\Domain\GpsrSupplier\GpsrSupplierRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.gpsr_supplier')]
class SupplierQualityProvider extends AbstractAdditionalFieldProvider implements SupplierProviderInterface
{
    private const TRUSTED_EMAIL_DOMAINS = [
        'example.com',
        'my-company.eu',
        'wiseb2b.eu',
        // Add more trusted domains as needed
    ];

    public const FIELD = 'quality';

    public function __construct(
        private readonly GpsrSupplierRepositoryInterface $gpsrSupplierRepository,
    ) {}

    /**
     * Zwraca wartość pola quality dla dostawcy GPSR
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        if ($cacheData['userAgreementId'] === null) {
            return null;
        }

        /** @var GpsrSupplier $gpsrSupplier */
        $gpsrSupplier = $this->gpsrSupplierRepository->find($entityId);

        if ($gpsrSupplier === null) {
            return null; // or throw an exception if you prefer
        }

        $quality = $this->getQualityFromGpsrSupplier($gpsrSupplier);

        if ($quality >= 35) {
            return 'High Quality';
        }

        if ($quality >= 20) {
            return 'Medium Quality';
        }

        return 'Low Quality';
    }

    /**
     * Wyliczamy pole quality na podstawie wprowadzonych danych
     *
     * @return int
     */
    private function getQualityFromGpsrSupplier(GpsrSupplier $gpsrSupplier): int
    {
        $sum = 0;

        if (filter_var($gpsrSupplier->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $sum += 10;
        }

        if (in_array($gpsrSupplier->getEmail(), self::TRUSTED_EMAIL_DOMAINS, false)) {
            $sum += 5;
        }

        $address = $gpsrSupplier->getAddress();

        if ($address !== null && $address->isFullyValid()) {
            $sum += 10;
        }

        if ($address !== null && $address->getCity() === 'Warszawa') {
            $sum += 5;
        }

        $phone = $gpsrSupplier->getPhone();

        if ($phone !== null && preg_match('/^\+48\d{9}$/', $phone)) {
            $sum += 5;
        }

        if ($gpsrSupplier->getRegisteredTradeName() !== null) {
            $sum += 5;
        }

        $taxNumber = $gpsrSupplier->getTaxNumber();

        if ($taxNumber !== null && preg_match('/^\d{10}$/', $taxNumber)) {
            $sum += 10;
        }

        return $sum;
    }
}
