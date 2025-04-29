<template>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
                <p class="text-lg text-gray-600">Find answers to common questions about our services</p>
            </div>

            <!-- FAQ Navigation -->
            <div class="mb-8 flex flex-wrap justify-center gap-4">
                <a v-for="(name, key) in sections"
                   :key="key"
                   :href="'#' + key"
                   class="px-6 py-3 rounded-full bg-white border-2 transition-all duration-300 shadow-sm hover:shadow-md"
                   :class="[
                       `border-${sectionColors[key]}`,
                       `text-${sectionColors[key]}`,
                       `hover:bg-${sectionColors[key]}`,
                       'hover:text-white'
                   ]">
                    {{ name }}
                </a>
            </div>

            <!-- FAQ Sections -->
            <div class="space-y-8">
                <div v-for="(name, key) in sections"
                     :key="key"
                     :id="key"
                     class="bg-white overflow-hidden shadow-lg sm:rounded-2xl"
                     :class="[`border-t-4 border-${sectionColors[key]}`]">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-6 flex items-center"
                            :class="`text-${sectionColors[key]}`">
                            <i :class="['mr-3', sectionIcons[key]]"></i>
                            {{ name }}
                        </h2>

                        <div class="space-y-4">
                            <div v-for="faq in getFaqsBySection(key)"
                                 :key="faq.id"
                                 class="border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-300">
                                <button @click="toggleFaq(faq)"
                                        class="flex justify-between items-center w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors duration-200">
                                    <span class="font-medium text-gray-900">{{ faq.question }}</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200"
                                         :class="[
                                             `text-${sectionColors[key]}`,
                                             { 'rotate-180': faq.isOpen }
                                         ]"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div v-show="faq.isOpen"
                                     class="px-6 pb-4 text-gray-600"
                                     :class="{ 'animate-fade-in-down': faq.isOpen }">
                                    <p class="leading-relaxed">{{ faq.answer }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sections: {
                delivery: 'Delivery',
                payments: 'Payments',
                referrals: 'Referrals',
                promotions: 'Promotions',
                return_refund: 'Return & Refund'
            },
            sectionColors: {
                delivery: 'blue-500',
                payments: 'purple-500',
                referrals: 'green-500',
                promotions: 'orange-500',
                return_refund: 'red-500'
            },
            sectionIcons: {
                delivery: 'fas fa-truck',
                payments: 'fas fa-credit-card',
                referrals: 'fas fa-users',
                promotions: 'fas fa-gift',
                return_refund: 'fas fa-undo'
            },
            faqs: [
                {
                    id: 1,
                    question: 'When will I receive my order?',
                    answer: 'Standard delivery typically takes 2-3 business days within Dhaka and 3-5 business days outside Dhaka.',
                    section: 'delivery',
                    isOpen: false
                },
                {
                    id: 2,
                    question: 'Do you offer same-day delivery?',
                    answer: 'Yes, we offer same-day delivery within Dhaka city for orders placed before 2 PM.',
                    section: 'delivery',
                    isOpen: false
                },
                {
                    id: 3,
                    question: 'What payment methods do you accept?',
                    answer: 'We accept cash on delivery, bKash, Nagad, credit/debit cards, and bank transfers.',
                    section: 'payments',
                    isOpen: false
                },
                {
                    id: 4,
                    question: 'Is online payment secure?',
                    answer: 'Yes, all our online payments are processed through secure payment gateways with SSL encryption.',
                    section: 'payments',
                    isOpen: false
                },
                {
                    id: 5,
                    question: 'How does the referral program work?',
                    answer: 'Share your referral code with friends. When they make their first purchase, they get 10% off and you earn 100 points.',
                    section: 'referrals',
                    isOpen: false
                },
                {
                    id: 6,
                    question: 'How can I find my referral code?',
                    answer: 'Your referral code can be found in your account dashboard under the "Referrals" section.',
                    section: 'referrals',
                    isOpen: false
                },
                {
                    id: 7,
                    question: 'How do I use a promo code?',
                    answer: 'Enter your promo code in the designated field during checkout before completing your purchase.',
                    section: 'promotions',
                    isOpen: false
                },
                {
                    id: 8,
                    question: 'Can I use multiple promo codes?',
                    answer: 'No, only one promo code can be used per order.',
                    section: 'promotions',
                    isOpen: false
                },
                {
                    id: 9,
                    question: 'What is your return policy?',
                    answer: 'We accept returns within 7 days of delivery if the product is unused and in its original packaging.',
                    section: 'return_refund',
                    isOpen: false
                },
                {
                    id: 10,
                    question: 'How long do refunds take?',
                    answer: 'Refunds are processed within 5-7 business days after we receive and verify the returned item.',
                    section: 'return_refund',
                    isOpen: false
                }
            ]
        }
    },
    methods: {
        getFaqsBySection(section) {
            return this.faqs.filter(faq => faq.section === section)
        },
        toggleFaq(faq) {
            faq.isOpen = !faq.isOpen
        }
    }
}
</script>

<style>
.animate-fade-in-down {
    animation: fadeInDown 0.2s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-0.5rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

html {
    scroll-behavior: smooth;
}

:target {
    scroll-margin-top: 2rem;
}
</style>
