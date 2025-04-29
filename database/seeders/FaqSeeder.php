<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            // Delivery FAQs
            [
                'question' => 'When will I receive my order?',
                'answer' => 'Standard delivery typically takes 2-3 business days within Dhaka and 3-5 business days outside Dhaka.',
                'section' => 'delivery',
                'order' => 1
            ],
            [
                'question' => 'Do you offer same-day delivery?',
                'answer' => 'Yes, we offer same-day delivery within Dhaka city for orders placed before 2 PM.',
                'section' => 'delivery',
                'order' => 2
            ],
            
            // Payments FAQs
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept cash on delivery, bKash, Nagad, credit/debit cards, and bank transfers.',
                'section' => 'payments',
                'order' => 1
            ],
            [
                'question' => 'Is online payment secure?',
                'answer' => 'Yes, all our online payments are processed through secure payment gateways with SSL encryption.',
                'section' => 'payments',
                'order' => 2
            ],
            
            // Referrals FAQs
            [
                'question' => 'How does the referral program work?',
                'answer' => 'Share your referral code with friends. When they make their first purchase, they get 10% off and you earn 100 points.',
                'section' => 'referrals',
                'order' => 1
            ],
            [
                'question' => 'How can I find my referral code?',
                'answer' => 'Your referral code can be found in your account dashboard under the "Referrals" section.',
                'section' => 'referrals',
                'order' => 2
            ],
            
            // Promotions FAQs
            [
                'question' => 'How do I use a promo code?',
                'answer' => 'Enter your promo code in the designated field during checkout before completing your purchase.',
                'section' => 'promotions',
                'order' => 1
            ],
            [
                'question' => 'Can I use multiple promo codes?',
                'answer' => 'No, only one promo code can be used per order.',
                'section' => 'promotions',
                'order' => 2
            ],
            
            // Return & Refund FAQs
            [
                'question' => 'What is your return policy?',
                'answer' => 'We accept returns within 7 days of delivery if the product is unused and in its original packaging.',
                'section' => 'return_refund',
                'order' => 1
            ],
            [
                'question' => 'How long do refunds take?',
                'answer' => 'Refunds are processed within 5-7 business days after we receive and verify the returned item.',
                'section' => 'return_refund',
                'order' => 2
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
