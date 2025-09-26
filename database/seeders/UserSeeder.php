<?php

namespace Database\Seeders;

use App\Models\Addresse;
use App\Models\Department;
use App\Models\Phone;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Roberto',
                'email' => 'roberto@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Administracion',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '09638252',
                    'full_name' => 'ROBERTO RICARDO ROJAS LUQUE',
                    'date_of_birth' => '1970-01-01',
                    'gender' => 'M',
                    'civil_status' => 'married',
                    'education_level' => 'bachelor_degree',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'Diagonal Angamos',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '902538882',
                    ],
                ],
            ],

            [
                'name' => 'Niel',
                'email' => 'niel@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Sistemas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '73639659',
                    'full_name' => 'NIEL EMERSON VITORINO ARONI',
                    'date_of_birth' => '2003-02-13',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080105',
                        'address' => 'Apv. Camino Blanco San Sebastián',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '935790035',
                    ],
                ],
            ],

            [
                'name' => 'Leyni',
                'email' => 'leyni@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Administracion',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '47252700',
                    'full_name' => 'LEYNI CABALLERO ZUNIGA',
                    'date_of_birth' => '1984-09-23',
                    'gender' => 'F',
                    'civil_status' => 'married',
                    'education_level' => 'bachelor_degree',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'Diagonal Angamos',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '974293649',
                    ],
                ],
            ],

            [
                'name' => 'Yanina',
                'email' => 'yanina@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Administracion',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '41663170',
                    'full_name' => 'YANINA ELIZABETH CHAMBILLA CLEMENTE',
                    'date_of_birth' => '1982-12-23',
                    'gender' => 'F',
                    'civil_status' => 'married',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'Urbanización La Florida H-7',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '946548640',
                    ],
                ],
            ],

            [
                'name' => 'Johan',
                'email' => 'johan@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Ventas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '76755067',
                    'full_name' => 'JOHAN BORDA SALAZAR',
                    'date_of_birth' => '2002-12-01',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'secondary',
                    'blood_type' => 'B-',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'Av. Occhuyo alto',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '914606037',
                    ],
                ],
            ],

            [
                'name' => 'Maria',
                'email' => 'maria@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Administracion',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '72352560',
                    'full_name' => 'MARIA JOSEFINA OROSCO CUSIHUAMAN',
                    'date_of_birth' => '1998-07-16',
                    'gender' => 'F',
                    'civil_status' => 'single',
                    'education_level' => 'bachelor_degree',
                    'blood_type' => 'O+',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'Urb. Santa Ana A-8',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '982381336',
                    ],
                ],
            ],

            [
                'name' => 'Jesus',
                'email' => 'jesus@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Ventas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '44706247',
                    'full_name' => 'JESUS YONATAN RAMIRES MENDOZA',
                    'date_of_birth' => '1987-04-12',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => 'A+',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Calle San Martín S/N Surite Anta',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '972222949',
                    ],
                ],
            ],

            [
                'name' => 'Miguel',
                'email' => 'miguel@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Contabilidad',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '76356195',
                    'full_name' => 'MIGUEL ANGEL CHERO PANTI',
                    'date_of_birth' => '2005-05-22',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080108',
                        'address' => 'C.C. San Marcos',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '983134174',
                    ],
                ],
            ],

            [
                'name' => 'Jordy',
                'email' => 'jordy@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Contabilidad',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '71688571',
                    'full_name' => 'JORDY DAVID QUISPE WARTHON',
                    'date_of_birth' => '2001-03-19',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => 'O+',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Luis Vallejos Santoni M-6',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '906999917',
                    ],
                ],
            ],

            [
                'name' => 'Cinthya',
                'email' => 'cinthya@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Contabilidad',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '45424205',
                    'full_name' => 'CINTHYA GAMBOA VILLAVICENCIO',
                    'date_of_birth' => '1995-10-12',
                    'gender' => 'F',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'San Sebastián',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '952412761',
                    ],
                ],
            ],

            [
                'name' => 'Aracely',
                'email' => 'aracely@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Contabilidad',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '72559594',
                    'full_name' => 'ARACELY LISSET HUAMAN AVILES',
                    'date_of_birth' => '2000-08-31',
                    'gender' => 'F',
                    'civil_status' => 'single',
                    'education_level' => 'bachelor_degree',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Plaza de Armas',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '991752235',
                    ],
                ],
            ],

            [
                'name' => 'James',
                'email' => 'james@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Tecnología y Soporte',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '74464389',
                    'full_name' => 'JHERSON JAMES CARREON PARIGUANA',
                    'date_of_birth' => '2005-07-20',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080105',
                        'address' => 'Calle Inti Raymi APV Villa Allpa Orccona Cusco-San Sebastián',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '929890608',
                    ],
                ],
            ],

            [
                'name' => 'Moises',
                'email' => 'moises@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Sistemas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '60016526',
                    'full_name' => 'MOISES CONDORI RAMOS SISTEMAS',
                    'date_of_birth' => '2006-07-08',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Santiago Pje Los Olivos A-10',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '935578757',
                    ],
                ],
            ],

            [
                'name' => 'Christian',
                'email' => 'christian@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Sistemas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '73208731',
                    'full_name' => 'CHRISTIAN ALDAYR GIANCARLOS ARQQUE ESPINOSA',
                    'date_of_birth' => '2003-10-28',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Paradero Callejón, Nro 516 San Sebastián',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '912516762',
                    ],
                ],
            ],

            [
                'name' => 'Gonzalo',
                'email' => 'gonzalo@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'Staff',
                'departament' => 'Sistemas',
                'type_user' => 'Staff',
                'profile' => [
                    'identification_type_id' => 2,
                    'document_number' => '73899824',
                    'full_name' => 'GONZALO MARTIN SEDANO CONDORI',
                    'date_of_birth' => '1994-11-14',
                    'gender' => 'M',
                    'civil_status' => 'single',
                    'education_level' => 'technical',
                    'blood_type' => '',
                    'characteristics' => [],
                    'is_active' => true,
                ],
                'addresses' => [
                    [
                        'ubigeo_cod' => '080101',
                        'address' => 'Apv. Señor de Animas MZ. 12 LT. A',
                        'reference' => 'S/N',
                    ],
                ],
                'phones' => [
                    [
                        'phone_number' => '937640994',
                    ],
                ],
            ],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'type_user' => $data['type_user'],
            ]);

            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }

            $profile = new Profile([
                'profileable_id' => $user->id,
                'profileable_type' => User::class,
                'identification_type_id' => $data['profile']['identification_type_id'],
                'document_number' => $data['profile']['document_number'],
                'full_name' => $data['profile']['full_name'],
                'gender' => $data['profile']['gender'],
                'date_of_birth' => $data['profile']['date_of_birth'],
                'civil_status' => $data['profile']['civil_status'],
                'education_level' => $data['profile']['education_level'],
                'blood_type' => $data['profile']['blood_type'],
                'characteristics' => $data['profile']['characteristics'],
                'is_active' => $data['profile']['is_active'],
            ]);
            $user->profile()->save($profile);

            $managersByName = [
                'Roberto' => 'Administracion',
                'Niel' => 'Sistemas',
                'Jesus' => 'Ventas',
                'Aracely' => 'Contabilidad',
                'James' => 'Tecnología y Soporte',
            ];

            foreach ($managersByName as $name => $departmentName) {
                $manager = User::where('name', $name)->first();
                $department = Department::where('name', $departmentName)->first();
                if ($manager && $department) {
                    $department->update(['manager_id' => $manager->id]);
                    $manager->departments()->syncWithoutDetaching([$department->id => ['type' => 'manager']]);
                }
            }

            if (isset($data['departament'])) {
                $department = Department::where('name', $data['departament'])->first();
                if ($department) {
                    $user->departments()->syncWithoutDetaching([$department->id => [
                        'type' => 'staff',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]]);
                }
            }

            foreach ($data['phones'] as $phoneData) {
                $phone = new Phone([
                    'phoneable_id' => $user->id,
                    'phoneable_type' => User::class,
                    'phone_type' => $phoneData['phone_type'] ?? 'Personal',
                    'phone_number' => $phoneData['phone_number'],
                    'country_code' => $phoneData['country_code'] ?? '+51',
                ]);
                $user->phones()->save($phone);
            }

            foreach ($data['addresses'] as $addressData) {
                $address = new Addresse([
                    'addressable_id' => $user->id,
                    'addressable_type' => User::class,
                    'ubigeo_cod' => $addressData['ubigeo_cod'],
                    'address' => $addressData['address'],
                    'reference' => $addressData['reference'],
                ]);
                $user->addresses()->save($address);
            }
        }
    }
}
