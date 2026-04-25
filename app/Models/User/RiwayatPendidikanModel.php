<?php

namespace App\Models\User;

use App\Models\BaseModel;

class RiwayatPendidikanModel extends BaseModel
{
    protected $table            = 'riwayat_pendidikan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid',
        'user_id',
        'jenjang_pendidikan',
        'program_studi',
        'institusi',
        'tahun_masuk',
        'tahun_lulus',
        'ipk',
        'dokumen_ijazah',
        'dokumen_tipe',
    ];

    // Validation
    protected $validationRules    = [
        'jenjang_pendidikan' => 'required|string|max_length[50]',
        'program_studi'      => 'required|string|max_length[255]',
        'institusi'          => 'required|string|max_length[255]',
        'tahun_masuk'        => 'required|integer|greater_than[1900]|less_than_equal_to[2100]',
        'tahun_lulus'        => 'required|integer|greater_than[1900]|less_than_equal_to[2100]',
        'ipk'                => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[4]',
        'dokumen_ijazah'     => 'permit_empty|string|max_length[500]',
        'dokumen_tipe'       => 'permit_empty|in_list[url,file]',
    ];

    protected $validationMessages = [
        'jenjang_pendidikan' => [
            'required'    => 'Jenjang pendidikan harus diisi.',
            'max_length'  => 'Jenjang pendidikan maksimal 50 karakter.',
        ],
        'program_studi' => [
            'required'    => 'Program studi harus diisi.',
            'max_length'  => 'Program studi maksimal 255 karakter.',
        ],
        'institusi' => [
            'required'    => 'Institusi harus diisi.',
            'max_length'  => 'Institusi maksimal 255 karakter.',
        ],
        'tahun_masuk' => [
            'required'    => 'Tahun masuk harus diisi.',
            'integer'     => 'Tahun masuk harus berupa angka.',
            'greater_than' => 'Tahun masuk tidak boleh kurang dari 1900.',
            'less_than_equal_to' => 'Tahun masuk tidak boleh lebih dari 2100.',
        ],
        'tahun_lulus' => [
            'required'    => 'Tahun lulus harus diisi.',
            'integer'     => 'Tahun lulus harus berupa angka.',
            'greater_than' => 'Tahun lulus tidak boleh kurang dari 1900.',
            'less_than_equal_to' => 'Tahun lulus tidak boleh lebih dari 2100.',
        ],
        'ipk' => [
            'decimal'     => 'IPK harus berupa desimal (contoh: 3.45).',
            'greater_than' => 'IPK tidak boleh kurang dari 0.',
            'less_than_equal_to' => 'IPK tidak boleh lebih dari 4.',
        ],
        'dokumen_tipe' => [
            'in_list'     => 'Jenis dokumen hanya boleh url atau file.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
