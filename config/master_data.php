<?php

return [
    'sub_classifications' => [
        'label' => 'Sub Klasifikasi',
        'model' => \App\Models\SubClassification::class,
        'icon' => 'fas fa-list-ul',
        'group' => 'Umum',
        'fields' => [
            'asset_category_code' => [
                'label' => 'Kategori Aset',
                'type' => 'select',
                'options' => [
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
                'required' => true,
            ],
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'opd_owners' => [
        'label' => 'OPD / Pemilik Aset',
        'model' => \App\Models\OpdOwner::class,
        'icon' => 'fas fa-building',
        'group' => 'Umum',
        'fields' => [
            'name' => ['label' => 'Nama OPD', 'type' => 'text', 'required' => true],
            'code' => ['label' => 'Kode OPD', 'type' => 'text'],
            'address' => ['label' => 'Alamat', 'type' => 'textarea'],
            'phone' => ['label' => 'Telepon', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'email'],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'asset_statuses' => [
        'label' => 'Status Aset',
        'model' => \App\Models\AssetStatus::class,
        'icon' => 'fas fa-info-circle',
        'group' => 'Aset',
        'fields' => [
            'name' => ['label' => 'Nama Status', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'color' => [
                'label' => 'Warna Badge',
                'type' => 'select',
                'options' => [
                    'gray' => 'Abu-abu',
                    'green' => 'Hijau',
                    'yellow' => 'Kuning',
                    'red' => 'Merah',
                    'blue' => 'Biru',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'asset_conditions' => [
        'label' => 'Kondisi Aset',
        'model' => \App\Models\AssetCondition::class,
        'icon' => 'fas fa-tools',
        'group' => 'Aset',
        'fields' => [
            'name' => ['label' => 'Nama Kondisi', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'color' => [
                'label' => 'Warna Badge',
                'type' => 'select',
                'options' => [
                    'green' => 'Hijau',
                    'yellow' => 'Kuning',
                    'red' => 'Merah',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'confidentiality_levels' => [
        'label' => 'Tingkat Kerahasiaan',
        'model' => \App\Models\ConfidentialityLevel::class,
        'icon' => 'fas fa-lock',
        'group' => 'Keamanan',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'code' => ['label' => 'Kode', 'type' => 'text'],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'integrity_levels' => [
        'label' => 'Tingkat Integritas',
        'model' => \App\Models\IntegrityLevel::class,
        'icon' => 'fas fa-shield-alt',
        'group' => 'Keamanan',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'code' => ['label' => 'Kode', 'type' => 'text'],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'availability_levels' => [
        'label' => 'Tingkat Ketersediaan',
        'model' => \App\Models\AvailabilityLevel::class,
        'icon' => 'fas fa-clock',
        'group' => 'Keamanan',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'code' => ['label' => 'Kode', 'type' => 'text'],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'criticality_levels' => [
        'label' => 'Tingkat Kritikalitas',
        'model' => \App\Models\CriticalityLevel::class,
        'icon' => 'fas fa-exclamation-triangle',
        'group' => 'Keamanan',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'code' => ['label' => 'Kode', 'type' => 'text'],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'platforms' => [
        'label' => 'Platform',
        'model' => \App\Models\Platform::class,
        'icon' => 'fas fa-desktop',
        'group' => 'Teknologi',
        'fields' => [
            'name' => ['label' => 'Nama Platform', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'icon' => ['label' => 'Icon', 'type' => 'text'],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'ip_types' => [
        'label' => 'Tipe IP',
        'model' => \App\Models\IpType::class,
        'icon' => 'fas fa-network-wired',
        'group' => 'Teknologi',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'se_categories' => [
        'label' => 'Kategori Sistem Elektronik',
        'model' => \App\Models\SeCategory::class,
        'icon' => 'fas fa-server',
        'group' => 'Teknologi',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'data_centers' => [
        'label' => 'Data Center',
        'model' => \App\Models\DataCenter::class,
        'icon' => 'fas fa-database',
        'group' => 'Teknologi',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'code' => ['label' => 'Kode', 'type' => 'text'],
            'address' => ['label' => 'Alamat', 'type' => 'textarea'],
            'city' => ['label' => 'Kota', 'type' => 'text'],
            'provider' => ['label' => 'Provider', 'type' => 'text'],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'asset_type_categories' => [
        'label' => 'Kategori Tipe Aset',
        'model' => \App\Models\AssetTypeCategory::class,
        'icon' => 'fas fa-tags',
        'group' => 'Kategori',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset',
                'type' => 'select',
                'options' => [
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                ],
                'required' => true,
            ],
            'color' => [
                'label' => 'Warna',
                'type' => 'select',
                'options' => ['green' => 'Hijau', 'yellow' => 'Kuning', 'red' => 'Merah'],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'personnel_categories' => [
        'label' => 'Kategori Personil',
        'model' => \App\Models\PersonnelCategory::class,
        'icon' => 'fas fa-users',
        'group' => 'SDM',
        'fields' => [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'personnel_functions' => [
        'label' => 'Fungsi Personil',
        'model' => \App\Models\PersonnelFunction::class,
        'icon' => 'fas fa-user-cog',
        'group' => 'SDM',
        'fields' => [
            'name' => ['label' => 'Nama Fungsi', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],

    'storage_formats' => [
        'label' => 'Format Penyimpanan',
        'model' => \App\Models\StorageFormat::class,
        'icon' => 'fas fa-file-alt',
        'group' => 'Lainnya',
        'fields' => [
            'name' => ['label' => 'Nama Format', 'type' => 'text', 'required' => true],
            'asset_category_code' => [
                'label' => 'Kategori Aset (opsional)',
                'type' => 'select',
                'options' => [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ],
            ],
            'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
            'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
            'is_active' => ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ],
    ],
];