define([], function () {
    'use strict';

    return {
        unMask: (value) => value.replace(/[^\d]+/g, ''),
        taxvat: {
            isValidCPF: (value) => {
                const invalidCPFs = [
                    '00000000000',
                    '11111111111',
                    '22222222222',
                    '33333333333',
                    '44444444444',
                    '55555555555',
                    '66666666666',
                    '77777777777',
                    '88888888888',
                    '99999999999',
                ]

                const isValidDigit = (value, digitPos) => {
                    let add = 0;

                    for (let i = 0; i < digitPos; i += 1) {
                        add += Number.parseInt(value.charAt(i), 10) * (digitPos + 1 - i);
                    }

                    let digit = 11 - (add % 11);

                    if (digit === 10 || digit === 11) {
                        digit = 0;
                    }

                    return digit === Number.parseInt(value.charAt(digitPos), 10);
                };

                const unMaskedValue = this.unMask(value);

                return unMaskedValue.length === 11
                    && invalidCPFs.includes(unMaskedValue) === false
                    && isValidDigit(unMaskedValue, 9)   // First check digit
                    && isValidDigit(unMaskedValue, 10); // Second check digit
            },
            isValidCNPJ: (value) => {
                const invalidCNPJs = [
                    '00000000000000',
                    '11111111111111',
                    '22222222222222',
                    '33333333333333',
                    '44444444444444',
                    '55555555555555',
                    '66666666666666',
                    '77777777777777',
                    '88888888888888',
                    '99999999999999',
                ];

                const isValidDigit = (value, size) => {
                    const numbers = value.substring(0, size);

                    let sum = 0;
                    let pos = size - 7;

                    for (let i = size; i >= 1; i -= 1) {
                        sum += numbers.charAt(size - i) * pos;

                        pos -= 1;

                        if (pos < 2) {
                            pos = 9;
                        }
                    }

                    let digit = 0;

                    const rest = sum % 11;

                    if (rest >= 2) {
                        digit = 11 - rest;
                    }

                    return digit === Number.parseInt(value.charAt(size), 10);
                };

                const unMaskedValue = this.unMask(value);

                return unMaskedValue.length === 14
                    && invalidCNPJs.includes(unMaskedValue) === false
                    && isValidDigit(unMaskedValue, 12)
                    && isValidDigit(unMaskedValue, 13);
            },
            isValidTaxvat: (value) => {
                this.isValidCPF(value)
                || this.isValidCPF(value)
            },
        }
    };
});
