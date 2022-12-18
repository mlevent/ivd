<?php

declare(strict_types=1);

namespace Mlevent\Ivd;

use Mlevent\Ivd\Request;

class IvdService
{    
    protected const ApiAuth     = 'https://ivd.gib.gov.tr/tvd_server/assos-login';
    protected const ApiDispatch = 'https://ivd.gib.gov.tr/tvd_server/dispatch';

    /**
     * @var string|null
     */
    protected ?string $username = null,
                      $password = null,
                      $token    = null;

    /**
     * setCredentials
     */
    public function setCredentials(string $username = null, string $password = null): self
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }
    
    /**
     * getCredentials
     */
    public function getCredentials(): array
    {
        return ['username' => $this->username, 'password' => $this->password];
    }
    
    /**
     * setToken
     */
    public function setToken(string $token = null): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * getToken
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * guestLogin
     */
    public function guestLogin(): self
    {
        $response = new Request(self::ApiAuth, [
            'assoscmd' => 'cfsession',
            'rtype'    => 'json',
            'fskey'    => 'intvrg.fix.session',
            'fuserid'  => 'INTVRG_FIX',
        ]);

        $this->setToken($response->get('token'));
        return $this;
    }
        
    /**
     * login
     */
    public function login(string $username = null, string $password = null): self
    {
        if ($username && $password) {
            $this->setCredentials($username, $password);
        }

        if (!$this->username || !$this->password) {
            return $this->guestLogin();
        }

        $response = new Request(self::ApiAuth, [
            'assoscmd'       => 'multilogin',
            'rtype'          => 'json',
            'userid'         => $this->username,
            'sifre'          => $this->password,
            'parola'         => 'maliye',
            'controlCaptcha' => 'false',
            'dk'             => '',
            'imageID'        => '',
        ]);

        $this->setToken($response->get('token'));
        return $this;
    }

    /**
     * logout
     */
    public function logout(): bool
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['kullaniciBilgileriService_logout', 'PG_MAIN_DYNAMIC', 'cbf1b5447b6cc-44'])
        );

        $this->setCredentials();
        $this->setToken();

        return $response->object('data')->logout == 1;
    }

    /**
     * Vergi Numarası Sorgulama
     */
    public function taxIdInquiry(string $name, string $lastName, string $fatherName, string $province, string $dateOfBirth)
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['vergiNoIslemleri_vergiNoSorgulaSorgu', 'P_INTVRG_INTVD_VKN_SORGULA_SORGU', '64864a0c5ef08-14'], [
                'isim'      => $name,
                'soyisim'   => $lastName,
                'babaAdi'   => $fatherName,
                'il'        => $province,
                'dogumYili' => $dateOfBirth,
            ])
        );
        return $response->get('data');
    }

    /**
     * Yabancılar İçin Vergi Kimlik Numarasından Sorgulama
     */
    public function taxIdInquiryForForeigners(string $taxId)
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['vergiNoService_yabanciVKNSorguSonuc', 'P_INTVRG_INTVD_YABANCI_VKN_SORGULA', '64864a0c5ef08-16'], [
                'eVknWithValidationTx' => $taxId,
            ])
        );
        return $response->get('data');
    }

    /**
     * Vergi Kimlik Numarası Doğrulama
     */
    public function taxIdVerification(string $province, string $taxOffice, string $taxId = '', string $trId = '')
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['vergiNoIslemleri_vergiNumarasiSorgulama', 'R_INTVRG_INTVD_VERGINO_DOGRULAMA', '64864a0c5ef08-15'], [
                'dogrulama' => [
                    'vkn1'           => $taxId, 
                    'tckn1'          => $trId,
                    'iller'          => $province,
                    'vergidaireleri' => $taxOffice,
                ]
            ])
        );
        return $response->get('data');
    }
    
    /**
     * Sicil Kaydı
     */
    public function getRegistry()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['sicilIslemleri_evdoYsicilTckimliknoVknCevrimiMernisAdsoyad', 'RG_SICIL', '151218977cdcc-29'])
        );
        return $response->get('data');
    }

    /**
     * Kimlik Bilgileri
     */
    public function getIdInformation()
    {
        if ($registry = $this->getRegistry()) {
            return $registry['kimlikBilgileri'];
        }
    }

    /**
     * Şirketlerdeki Ortaklık ve Yöneticilik Bilgileri
     */
    public function getPartnerships()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['yoneticiOrtaklikIslemleri_sirketOrtaklikYoneticilikSorgula', 'P_MEVCUT_SIRKET_ORTAKLIK_YONETICILIK', '0861bf80b5f42-27'], [
                'bilgi' => 1
            ])
        );
        return $response->get('data');
    }

    /**
     * Borç Durumu
     */
    public function getDebtStatus()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['tvdBorcIslemleri_borcGetirYeni', 'P_DASHBOARD', 'e95931c2f24ce-145'])
        );
        return $response->get('data');
    }

    /**
     * KYK Borç Durumu
     */
    public function getKYKDebtStatus()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['kykBorcDurum_borcDurumGetir', 'PG_KYK_BORC_DURUMU', 'cbf1b5447b6cc-32'])
        );
        return $response->get('data');
    }

    /**
     * Banka Hesaplarına Uygulanan Elektronik Hacizler
     */
    public function getGarnishmentsAppliedToBankAccounts()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['ehacizSorgulamaService_EhacizSorgulamaSonuc', 'P_INTVRG_INTVD_EHACIZ_ARAC_SRG', 'e95931c2f24ce-119'], [
                'secim' => 2
            ])
        );
        return $response->get('data');
    }

    /**
     * Araçlara Uygulanan Elektronik Hacizler
     */
    public function getGarnishmentsAppliedToVehicles()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['ehacizSorgulamaService_EhacizSorgulamaSonuc', 'P_INTVRG_INTVD_EHACIZ_ARAC_SRG', 'e95931c2f24ce-146'], [
                'secim' => 2
            ])
        );
        return $response->get('data');
    }

    /**
     * Mevcut Araç Bilgileri
     */
    public function getCurrentVehicles()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['aracBilgileriService_aracBilgileriGetir', 'P_MEVCUT_ARAC_BILGILERI', 'cbf1b5447b6cc-43'], [
                'sorgulamaTip' => 1
            ])
        );
        return $response->get('data');
    }

    /**
     * Geçmiş Araç Bilgileri
     */
    public function getPreviousVehicles()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['aracBilgileriService_aracBilgileriGetir', 'P_GECMIS_ARAC_BILGILERI', '3be8a89c47b6c-31'], [
                'sorgulamaTip' => 2
            ])
        );
        return $response->get('data');
    }

    /**
     * Vergi Ceza İhbarname Bilgileri
     */
    public function getTaxPenaltyNoticeInformation()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['cezaIhbarnameleriService_cezaIhbarnameleriGetir', 'P_CEZA_IHBARNAMELERI', '0861bf80b5f42-37'])
        );
        return $response->get('data');
    }

    /**
     * Sanal Pos Ödemeleri
     */
    public function getVirtualPosPayments(int $year)
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['borcIslemleri_odemeSorgulaSpos', 'P_INTVRG_INTVD_ODEME_SORGULAMA', '0884d5a31205c-38'], [
                'year' => $year
            ])
        );
        return $response->get('data');
    }

    /**
     * E-Devlet Ödemeleri
     */
    public function getEDevletPayments(int $year)
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['tahsilatIslemleri_edevletOdemeleriSorgula', 'P_EDEVLET_ODEMELERIM', 'f3c28c5d3009f-35'], [
                'tarih' => $year
            ])
        );
        return $response->get('data');
    }

    /**
     * Diğer Ödemeler
     */
    public function getOtherPayments(int $year)
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['tahsilatIslemleri_mukellefeAitdemeleriSorgula', 'P_MUKELLEF_ODEME_SORGULAMA', '57a00a6bf5f92-38'], [
                'yil' => $year
            ])
        );
        return $response->get('data');
    }

    /**
     * Servis Mesajları
     */
    public function getServiceMessages()
    {
        $response = new Request(self::ApiDispatch, 
            $this->setParams(['kullaniciMesajlariService_kullaniciMesajlariGetir', 'PG_MAIN_DYNAMIC', 'e61d39920010c-61'])
        );
        return $response->get('data');
    }

    /**
     * __call
     */
    public function __call($name, $arguments)
    {
        $keysAndCommands = [
            'getTaxOffices'            => 'RF_VERGI_DAIRELERI',
            'getTaxList'               => 'RF_FILTRELI_VERGIKODLARI',
            'getCountries'             => 'RF_EVDO_ULKELER',
            'getProvinces'             => 'RF_INTVRG_INTVD_ILLER',
            'getProvincesAndDistricts' => 'RF_SICIL_DOGUMYERI_ILILCELER',
        ];

        if (array_key_exists($name, $keysAndCommands)) {
            $response = new Request(self::ApiDispatch, 
                $this->setParams(['referenceDataService_getCacheableRfDataInfo', 'undefined', 'e61d39920010c-56'], [
                    'status' => [['rf' => $keysAndCommands[$name]]]
                ])
            );
            return $response->get('data')[0]['values'];
        }
    }

    /**
     * setParams
     */
    public function setParams(array $command, array $payload = []): array
    {
        list($cmd, $pageName, $callId) = $command;

        return [
            'callid'   => $callId,
            'cmd'      => $cmd,
            'pageName' => $pageName,
            'token'    => $this->token,
            'jp'       => json_encode($payload ?: (object) $payload),
        ];
    }
}