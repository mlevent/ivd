# 💸 İnteraktif Vergi Dairesi

Bu Paket ile GİB İnteraktif Vergi Dairesi üzerinden bazı şifresiz/şifreli işlemleri gerçekleştirebilirsiniz.

-   https://ivd.gib.gov.tr

## Kurulum

🛠️ Paketi composer ile projenize dahil edin;

```bash
composer require mlevent/ivd
```

## Örnek Kullanım

```php
use Mlevent\Ivd\IvdService;

// Şifresiz Giriş
$ivd = (new IvdService)->login();

// Vergi Numarası Doğrulama
$result = $ivd->taxIdVerification(
    taxId     : '1234567890',
    province  : '016',
    taxOffice : '016252'
);

print_r($result);
```

### Gerçek Kullanıcı

Kullanıcı bilgilerinizi `setCredentials` ya da `login` metoduyla tanımlayabilirsiniz.

```php
use Mlevent\Ivd\IvdService;

// Kullanıcı Bilgileriyle Giriş
$ivd = (new IvdService)->login('TC Kimlik No', 'Parola');
```

> Not: Token değerini herhangi bir yerde kullanmanız gerekmeyecek.

## Şifresiz İşlemler

İnteraktif Vergi Dairesi üzerindeki bazı servisler şifresiz/giriş yapmadan kullanılabilir;

```php
/**
 * Vergi Kimlik Numarası Sorgulama
 * @description Kimlik bilgileriyle Vergi Kimlik numarası sorgulama. Tüm alanların gönderilmesi zorunludur. 
 *
 * @param  string $name        · İsim
 * @param  string $lastName    · Soyisim
 * @param  string $fatherName  · Baba Adı
 * @param  string $province    · İl
 * @param  string $dateOfBirth · Doğum Tarihi
 * @return array
 */
$result = $ivd->taxIdInquiry(
    name        : 'Mert',
    lastName    : 'Levent',
    fatherName  : 'Walter',
    province    : '016',
    dateOfBirth : '19890511'
);

/**
 * Yabancılar İçin Vergi Kimlik Numarasından Sorgulama
 *
 * @param  string $taxId · Vergi Numarası
 * @return array
 */
$result = $ivd->taxIdInquiryForForeigners(
    taxId : '1234567890'
);

/**
 * Vergi Kimlik Numarası Doğrulama
 * @description Sorgulanacak kişi ya da kurumun Vergi Kimlik ya da T.C. Kimlik numarasından sadece birini giriniz.
 *
 * @param  string $taxId     · Vergi Numarası
 * @param  string $trId      · TcKN
 * @param  string $province  · İl
 * @param  string $taxOffice · Vergi Dairesi
 * @return array
 */
$result = $ivd->taxIdVerification(
    //taxId   : '1234567890',
    trId      : '11111111111',
    province  : '016',
    taxOffice : '016252'
);

/**
 * Vergi Dairelerine ait liste çıktısını verir.
 *
 * @return array
 */
$ivd->getTaxOffices();

/**
 * Vergileri ve vergi kodlarına ait liste çıktısını verir.
 *
 * @return array
 */
$ivd->getTaxList();

/**
 * Ülkelere ait liste çıktısını verir.
 *
 * @return array
 */
$ivd->getCountries();

/**
 * Türkiye'deki illere ait liste çıktısını verir.
 *
 * @return array
 */
$ivd->getProvinces();

/**
 * Türkiye'deki iller ve ilçelere ait liste çıktısını verir.
 *
 * @return array
 */
$ivd->getProvincesAndDistricts();
```

## Şifreli İşlemler

İnteraktif Vergi Dairesinde kayıtlı TcKN ve şifre bilgileriyle oturum açılarak kullanılabilecek metodlar;

#### Sicil Kaydı

```php
$ivd->getRegistry();
```

#### Kimlik Bilgileri

```php
$ivd->getIdInformation();
```

#### Şirketlerdeki Ortaklık ve Yöneticilik Bilgileri

```php
$ivd->getPartnerships();
```

#### Borç Durumu

```php
$ivd->getDebtStatus();
```

#### KYK Borç Durumu

```php
$ivd->getKYKDebtStatus();
```

#### Banka Hesaplarına Uygulanan Elektronik Hacizler

```php
$ivd->getGarnishmentsAppliedToBankAccounts();
```

#### Araçlara Uygulanan Elektronik Hacizler

```php
$ivd->getGarnishmentsAppliedToVehicles();
```

#### Mevcut Araç Bilgileri

```php
$ivd->getCurrentVehicles();
```

#### Geçmiş Araç Bilgileri

```php
$ivd->getPreviousVehicles();
```

#### Vergi Ceza İhbarname Bilgileri

```php
$ivd->getTaxPenaltyNoticeInformation();
```

#### Sanal Pos Ödemeleri

```php
$ivd->getVirtualPosPayments(
    year: 2018 // Zorunlu · Yıl
);
```

#### E-Devlet Ödemeleri

```php
$ivd->getEDevletPayments(
    year: 2018 // Zorunlu · Yıl
);
```

#### Diğer Ödemeler

```php
$ivd->getOtherPayments(
    year: 2018 // Zorunlu · Yıl
);
```

#### Servis Mesajları

```php
$ivd->getServiceMessages();
```

## 📧İletişim

İletişim için ghergedan@gmail.com adresine e-posta gönderin.
