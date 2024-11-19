<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

readonly final class Address
{
    public CountryCode $countryCode;

    public function __construct(
        public string $name,
        public string $street,
        public string $city,
        public string $postalCode,
        string|CountryCode $countryCode,
    ) {
        if (is_string($countryCode)) {
            $this->countryCode = CountryCode::from(strtoupper($countryCode));
        } else {
            $this->countryCode = $countryCode;
        }
    }

    /** @param array{
     *     name: string,
     *     street: string,
     *     city: string,
     *     postalCode: string,
     *     countryCode: string,
     *  } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            street: $data['street'],
            city: $data['city'],
            postalCode: $data['postalCode'],
            countryCode: $data['countryCode'],
        );
    }

    /**
     * @return array{
     *     name: string,
     *     street: string,
     *     city: string,
     *     postalCode: string,
     *     countryCode: string,
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'street' => $this->street,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'countryCode' => $this->countryCode->value,
        ];
    }
}
