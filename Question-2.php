<?php
/**
 *Write a function that takes a phone number in any form and formats it using a delimiter supplied by the developer. The delimiter is optional; if one is not supplied,
 * use a dash (-). Your function should accept a phone number in any format
 * (e.g. 123-456-7890, (123) 456-7890, 1234567890, etc) and format it according
 * to the 3-3-4 US block standard, using the delimiter specified. Assume foreign phone
 * numbers and country codes are out of scope.
 *
 * Note: This question CAN be solved using a regular expression, but one is not REQUIRED as a solution.
 * Focus instead on cleanliness and effectiveness of the code, and take into
 * account phone numbers that may not pass a sanity check.
 *
 */
function formatPhoneNumber(string $phone, string $delimiter = '-'): string
{
    $cleanedNumber = cleanPhoneNumber($phone);

    return sprintf(
        '%s%s%s%s%s',
        substr($cleanedNumber, 0, 3),
        $delimiter,
        substr($cleanedNumber, 3, 3),
        $delimiter,
        substr($cleanedNumber, 6, 4)
    );
}
function cleanPhoneNumber(string $phone): string
{
    $cleanedNumber = preg_replace('/\D+/', '', $phone);
    $expectedLength = 10;

    if (strlen($cleanedNumber) !== $expectedLength) {
        throw new InvalidArgumentException(sprintf(
            'Expected exactly %d digits for a US phone number, got %d from input "%s".',
            $expectedLength,
            strlen($cleanedNumber),
            $phone
        ));
    }

    return $cleanedNumber;
}

echo formatPhoneNumber('123-456-7890');
echo "\n";
echo "\n";
echo "\n";
echo formatPhoneNumber('(123) 456-7890');
echo "\n";
echo "\n";
echo "\n";
echo formatPhoneNumber('1234567890');