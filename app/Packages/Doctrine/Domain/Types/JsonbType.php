<?php


namespace App\Packages\Doctrine\Domain\Types;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonbType extends Type
{
    const NAME = 'jsonb'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform instanceof PostgreSQL100Platform) {
            return self::NAME;  // return the SQL used to create your column type. To create a portable column type, use the $platform.
        }
        return 'text';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        return json_decode($value, true);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        return json_encode($value);
    }

    public function getName()
    {
        return self::NAME; // modify to match your constant name
    }
}
