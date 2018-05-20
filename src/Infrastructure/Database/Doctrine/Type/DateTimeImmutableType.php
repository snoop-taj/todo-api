<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Infrastructure\Database\Doctrine\Types;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class DateTimeImmutableType extends DateTimeType
{
    const NAME = 'datetime_immutable';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|DateTimeImmutable
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeImmutable
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }

        $dateTime = DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value);

        if (! $dateTime instanceof DateTimeImmutable) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }
        if ($value instanceof DateTimeInterface) {
            return $value->format($platform->getDateTimeFormatString());
        }
        if (! is_scalar($value)) {
            $value = sprintf('of type %s', gettype($value));
        }
        throw ConversionException::conversionFailed($value, $this->getName());
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
