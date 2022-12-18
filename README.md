# 💸 İnteraktif Vergi Dairesi

GİB İnteraktif Vergi Dairesi üzerinden şifresiz/şifreli işlemlere olanak tanır.

-   https://ivd.gib.gov.tr

## Kurulum

🛠️ Paketi composer ile projenize dahil edin;

```bash
composer require mlevent/ivd
```

## Kullanım

```php
use Mlevent\Ivd\IvdException;
use Mlevent\Ivd\IvdService;

try {

    // Şifresiz Giriş
    $ivd = (new IvdService)->login();

    // Vergi Numarası Doğrulama
    $result = $ivd->taxIdVerification(
        trId      : '11111111111',
        province  : '016',
        taxOffice : '016252'
    );

    print_r($result);

    // Oturumu Sonlandır
    $ivd->logout();

} catch(IvdException $e){

    print_r($e->getMessage());
    print_r($e->getResponse());
    print_r($e->getRequest());
}
```

### Gerçek Kullanıcı

Kullanıcı bilgilerinizi `setCredentials` ya da `login` metoduyla tanımlayabilirsiniz.

```php
use Mlevent\Ivd\IvdService;

// Kullanıcı Bilgileriyle Giriş
$ivd = (new IvdService)->login('TC Kimlik No', 'Parola');

// Şirketlerdeki Ortaklık ve Yöneticilik Bilgileri
print_r($ivd->getPartnerships());
```

> Not: Token değerini herhangi bir yerde kullanmanız gerekmeyecek.

## Şifresiz İşlemler

İnteraktif Vergi Dairesi üzerindeki bazı servisler şifresiz/giriş yapmadan kullanılabilir;

#### Vergi Kimlik Numarası Sorgulama

```php
$result = $ivd->taxIdInquiry(
    name        : 'Mert',    // Zorunlu · Ad
    lastName    : 'Levent',  // Zorunlu · Soyad
    fatherName  : 'Walter',  // Zorunlu · Baba Adı
    province    : '016',     // Zorunlu · İl
    dateOfBirth : '19890511' // Zorunlu · Doğum Tarihi
);

print_r($result);
```

#### Yabancılar İçin Vergi Kimlik Numarasından Sorgulama

```php
$result = $ivd->taxIdInquiryForForeigners(
    taxId : '1234567890' // Zorunlu · Vergi Numarası
);

print_r($result);
```

#### Vergi Kimlik Numarası Doğrulama

```php
$result = $ivd->taxIdVerification(
    //taxId   : '1234567890',  // Opsiyonel · Vergi Numarası
    trId      : '11111111111', // Opsiyonel · TcKN
    province  : '016',         // Zorunlu   · İl
    taxOffice : '016252'       // Zorunlu   · Vergi Dairesi
);

print_r($result);
```

#### Diğer Metodlar

```php
print_r($ivd->getTaxOffices());            // Vergi Daireleri
print_r($ivd->getTaxList());               // Vergiler
print_r($ivd->getCountries());             // Ülkeler
print_r($ivd->getProvinces());             // İller
print_r($ivd->getProvincesAndDistricts()); // İller ve İlçeler
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
