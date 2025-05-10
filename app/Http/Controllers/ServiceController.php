<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    // Main Service Page
    public function index()
    {
        return Inertia::render('Services/Index', [
            'ambulanceHotline' => '+880-123-456-789',
            'supportPhone' => '+88-01711008229',
            'supportEmail' => 'support@medimart.com'
        ]);
    }

    // Hospital Information Page
    public function hospitalInfo()
    {
        $hospitals = [
            [
                'id' => 1,
                'name' => 'Evercare Hospital',
                'address' => 'Bashundhara, Dhaka',
                'hotline' => '10678',
                'doctors' => [
                    [
                        'id' => 1,
                        'name' => 'Dr. Shams Munwar',
                        'designation' => 'MBBS, MRCP (UK), D.Card (London)',
                        'department' => 'Cardiology',
                        'time' => '10:00 AM - 1:00 PM',
                        'profileLink' => 'https://www.evercarebd.com/profile/?cid=69'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Prof. ATM Mowladad Chowdhury',
                        'designation' => 'MBBS, MS (Urology), FCPS (Surgery)',
                        'department' => 'Urology',
                        'time' => '12:30 PM - 1:30 PM',
                        'profileLink' => 'https://evercarebd.com/profile/?cid=506'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Prof. (Dr.) Motiur Rahman Molla',
                        'designation' => 'BDS, FCPS (Hons), PhD (KMS-Japan), FDS, RCPS (Glasgow, UK)',
                        'department' => 'Dental & Maxillofacial Surgery',
                        'time' => '11:00 PM - 12:30 PM',
                        'profileLink' => 'https://www.evercarebd.com/profile/?cid=112'
                    ]
                ]
            ],
            [
                'id' => 2,
                'name' => 'Square Hospital',
                'address' => 'Panthapath, Dhaka',
                'hotline' => '10666',
                'doctors' => [
                    [
                        'id' => 4,
                        'name' => 'Brig. Gen. Prof. Dr. Moklesur Rahman',
                        'designation' => 'MBBS (DMC), MS (Orthopaedics), FACS (USA), Fellow Hand, Upper Limb & Microsurgery (Thailand), Fellow Hip & Knee Joint Replacement Surgery (India)',
                        'department' => 'Orthopaedics',
                        'time' => '10:00 AM - 1:00 PM',
                        'profileLink' => 'https://www.squarehospital.com/doctors/242',
                    ],
                    [
                        'id' => 5,
                        'name' => 'Prof. Dr. Saban Horo',
                        'designation' => 'MBBS, MS (Ophthalmology) Fellow in Cataract & Microsurgery Fellow in Medical Retina & Surgical Retina',
                        'department' => 'Ophthalmology',
                        'time' => '5:00 PM - 7:00 PM',
                        'profileLink' => 'https://www.squarehospital.com/doctors/271',
                    ],
                    [
                        'id' => 6,
                        'name' => 'Dr. Khondoker Asaduzzaman',
                        'designation' => 'MBBS, DTCD (Chest), MD (Cardiology) Fellowship Training in Interventional Cardiology from Sri Lanka, Japan and Korea.',
                        'department' => 'Interventional Cardiology',
                        'time' => '2:00 PM - 5:00 PM',
                        'profileLink' => 'https://www.squarehospital.com/doctors/267',
                    ]
                ]
            ]
        ];

        return Inertia::render('Services/Hospital', [
            'hospitals' => $hospitals
        ]);
    }

    // Telemedicine Page
    public function telemedicine()
    {
        $telemedicineDoctors = [
            [
                'id' => 1,
                'name' => 'Dr. Shafiul Quadry',
                'department' => 'Nephrology',
                'time' => '11:00 AM - 3:00 PM',
                'fee' => 650,
                'profileLink' => 'https://doctime.com.bd/doctors/dr-shafiul-quadry-84292',
                'designation' => 'Online Consultant'
            ],
            [
                'id' => 2,
                'name' => 'Dr. Shahana Parveen',
                'department' => 'Paediatrics',
                'time' => '2:00 PM - 6:00 PM',
                'fee' => 630,
                'profileLink' => 'https://doctime.com.bd/doctors/dr-shahana-parveen-131243',
                'designation' => 'Online Consultant'
            ],
            [
                'id' => 3,
                'name' => 'Asst. Prof. Dr. Z. Wadud',
                'department' => 'Psychiatry',
                'time' => '2:00 PM - 6:00 PM',
                'fee' => 550,
                'profileLink' => 'https://doctime.com.bd/doctors/asst-prof-dr-z-wadud-3059901',
                'designation' => 'Online Consultant'
            ],
            [
                'id' => 4,
                'name' => 'Dr. MD. ASADUZZAMAN RAJIB',
                'department' => 'Nephrology',
                'time' => '2:00 PM - 6:00 PM',
                'fee' => 500,
                'profileLink' => 'https://doctime.com.bd/doctors/dr-md-asaduzzaman-rajib-113586',
                'designation' => 'Online Consultant'
            ],
            [
                'id' => 5,
                'name' => 'Nazmin Nahar',
                'department' => 'Respiratory Medicine',
                'time' => '2:00 PM - 6:00 PM',
                'fee' => 380,
                'profileLink' => 'https://doctime.com.bd/doctors/nazmin-nahar-1687301',
                'designation' => 'Online Consultant'
            ],
            [
                'id' => 6,
                'name' => 'Dr. Rajib Dutta',
                'department' => 'Respiratory Medicine',
                'time' => '7:00 PM - 10:00 PM',
                'fee' => 500,
                'profileLink' => 'https://doctime.com.bd/doctors/dr-rajib-dutta-141971',
                'designation' => 'Online Consultant'
            ]

        ];

        return Inertia::render('Services/Telemedicine', [
            'telemedicineDoctors' => $telemedicineDoctors,
            'telemedicineHotline' => '+880-987-654-321'
        ]);
    }
}
