# traitsforatkdata

[![codecov](https://codecov.io/gh/PhilippGrashoff/traitsforatkdata/branch/master/graph/badge.svg)](https://codecov.io/gh/PhilippGrashoff/traitsforatkdata)


A small collection of traits which I find helpful:

CachedModelTrait: Usually added to App to cache model records which are often used in read-only mode.

CryptIdTrait: Generate a cryptic ID for a model field like adfsdfkj2-f23ref-rwe in a custom format of your choice

UniqueFieldTrait: Check if a field of a model has a unique value across all model records.

EncryptedFieldTrait: Store the field value encrypted in Database.