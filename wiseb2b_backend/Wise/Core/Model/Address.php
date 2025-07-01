<?php

declare(strict_types=1);

namespace Wise\Core\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class Address extends AbstractModel
{
    #[OA\Property(
        description: 'Nazwa adresu',
        example: 'Domowy',
    )]
    protected ?string $name;

    #[OA\Property(
        description: 'Ulica przy której znajduje się siedziba klienta',
        example: 'Powstańców Wielkopolskich',
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    protected ?string $street;

    #[OA\Property(
        description: 'Numer budynku',
        example: '15',
    )]
    #[Assert\NotBlank]
    protected ?string $houseNumber;

    #[OA\Property(
        description: 'Numer lokalu',
        example: '26',
    )]
    protected ?string $apartmentNumber;

    #[OA\Property(
        description: 'Miasto',
        example: 'Wrocław',
    )]
    #[Assert\NotBlank]
    protected ?string $city;

    #[OA\Property(
        description: 'Kod pocztowy',
        example: '16-569',
    )]
    #[Assert\NotBlank]
    protected ?string $postalCode;

    #[OA\Property(
        description: 'Kod Kraju',
        example: 'pl',
    )]
    #[Assert\NotBlank]
    protected ?string $countryCode;

    #[OA\Property(
        description: 'Stan',
        example: 'example',
    )]
    protected ?string $state;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Sprawdzamy, czy adres jest w pełni poprawny.
     * Adres jest poprawny, jeśli wszystkie wymagane pola są wypełnione.
     * Możemy dodać dodatkowe walidacje, jeśli zajdzie taka potrzeba.
     */
    public function isFullyValid(): bool
    {
        if (empty($this->name) ||
            empty($this->street) ||
            empty($this->houseNumber) ||
            empty($this->city) ||
            empty($this->postalCode) ||
            empty($this->countryCode)) {
            return false;
        }

        return true;
    }


    public function toArray(): array
    {
        return [
            'name' => $this->isInitialized('name') ? $this->getName() : null,
            'street' => $this->isInitialized('street') ? $this->getStreet() : null,
            'houseNumber' => $this->isInitialized('houseNumber') ? $this->getHouseNumber() : null,
            'apartmentNumber' => $this->isInitialized('apartmentNumber') ? $this->getApartmentNumber() : null,
            'city' => $this->isInitialized('city') ? $this->getCity() : null,
            'postalCode' => $this->isInitialized('postalCode') ? $this->getPostalCode() : null,
            'countryCode' => $this->isInitialized('countryCode') ? $this->getCountryCode() : null,
            'state' => $this->isInitialized('state') ? $this->getState() : null,
        ];
    }

    public static function fromArray(mixed $param)
    {
        $address = new self();
        $address->setName($param['name'] ?? null);
        $address->setStreet($param['street'] ?? null);
        $address->setHouseNumber($param['houseNumber'] ?? null);
        $address->setApartmentNumber($param['apartmentNumber'] ?? null);
        $address->setCity($param['city'] ?? null);
        $address->setPostalCode($param['postalCode'] ?? null);
        $address->setCountryCode($param['countryCode'] ?? null);
        $address->setState($param['state'] ?? null);
        return $address;
    }

}
