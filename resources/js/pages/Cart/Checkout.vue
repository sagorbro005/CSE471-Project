<template>
  <NavBar />
  <div class="min-h-screen bg-gray-50 flex flex-col items-center py-10">
    <div class="w-full max-w-7xl flex flex-col md:flex-row gap-10">
      <!-- Payment Section (wider) -->
      <div class="flex-1 bg-white rounded-xl shadow p-10 md:max-w-4xl">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
          <span class="block sm:inline">{{ errorMessage }}</span>
        </div>
        <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
          <span class="block sm:inline">{{ successMessage }}</span>
        </div>
        <form>
          <!-- District Selection -->
          <div class="mb-6">
            <label for="district" class="block text-sm font-medium text-gray-700 mb-2">Select District</label>
            <select id="district" v-model="district" @change="updateDeliveryCharge" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
              <option value="">Select a district</option>
              <option value="dhaka">Dhaka</option>
              <option value="other">Other District</option>
            </select>
          </div>

          <!-- Address Fields -->
          <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <input type="text" id="address" v-model="address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="House/Road/Area" required />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
              <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
              <input type="text" id="city" v-model="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="City" required />
            </div>
            <div>
              <label for="zipCode" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
              <input type="text" id="zipCode" v-model="zipCode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Zip Code" required />
            </div>
          </div>

          <!-- Payment Method Tabs -->
          <div class="mb-6">
            <div class="flex space-x-4 mb-6">
              <button type="button" @click="activeTab = 'card'" :class="activeTab === 'card' ? 'bg-blue-50 text-blue-600' : 'text-gray-500'" class="flex-1 py-2 px-4 rounded-md font-medium">Card Payment</button>
              <button type="button" @click="activeTab = 'mobile'" :class="activeTab === 'mobile' ? 'bg-blue-50 text-blue-600' : 'text-gray-500'" class="flex-1 py-2 px-4 rounded-md font-medium">Mobile Payment</button>
              <button type="button" @click="activeTab = 'cod'" :class="activeTab === 'cod' ? 'bg-blue-50 text-blue-600' : 'text-gray-500'" class="flex-1 py-2 px-4 rounded-md font-medium">Cash on Delivery</button>
            </div>

            <!-- Card Payment Fields -->
            <div v-if="activeTab === 'card'" class="space-y-6">
              <div class="grid grid-cols-3 gap-4 mb-6">
                <label v-for="type in cardTypes" :key="type.value" class="relative border rounded-lg p-4 cursor-pointer hover:border-blue-500 flex flex-col items-center justify-center" :class="cardForm.card_type === type.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                  <input type="radio" v-model="cardForm.card_type" :value="type.value" class="absolute top-2 right-2" required />
                  <img :src="type.logo" :alt="type.label" class="h-12 mx-auto mb-2" />
                  <div class="text-center mt-2 text-sm font-medium">{{ type.label }}</div>
                </label>
              </div>
              <div>
                <input type="text" v-model="cardForm.card_number" @input="onCardNumberInput" placeholder="Card Number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-4" required />
              </div>
              <div class="grid grid-cols-2 gap-4">
                <input type="text" v-model="cardForm.expiry" placeholder="MM/YY" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required />
                <input type="text" v-model="cardForm.cvv" @input="onCVVInput" placeholder="CVV" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required />
              </div>
              <button type="submit" @click="submitCard" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-base" :disabled="isSubmitting">
                <span v-if="isSubmitting">
                  <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                </span>
                <span v-else>
                  Pay ৳{{ formatPrice(finalTotal) }}
                </span>
              </button>
            </div>

            <!-- Mobile Payment Fields -->
            <div v-if="activeTab === 'mobile'" class="space-y-6">
              <div class="grid grid-cols-3 gap-4 mb-6">
                <label v-for="type in mobileTypes" :key="type.value" class="relative border rounded-lg p-4 cursor-pointer hover:border-blue-500 flex flex-col items-center justify-center" :class="mobileForm.mobile_payment === type.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                  <input type="radio" v-model="mobileForm.mobile_payment" :value="type.value" class="absolute top-2 right-2" required />
                  <img :src="type.logo" :alt="type.label" class="h-12 mx-auto mb-2" />
                  <div class="text-center mt-2 text-sm font-medium">{{ type.label }}</div>
                </label>
              </div>
              <div>
                <input type="text" v-model="mobileForm.phone" @input="onMobilePhoneInput" placeholder="Mobile Number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-4" required />
              </div>
              <button type="submit" @click="submitMobile" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-base" :disabled="isSubmitting">
                <span v-if="isSubmitting">
                  <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                </span>
                <span v-else>
                  Pay ৳{{ formatPrice(finalTotal) }}
                </span>
              </button>
            </div>

            <!-- Cash on Delivery Message -->
            <div v-if="activeTab === 'cod'" class="space-y-6">
              <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                <p class="font-medium">Cash on Delivery</p>
                <p class="text-sm mt-1">You will pay ৳{{ formatPrice(finalTotal) }} when your order is delivered.</p>
              </div>
              <button type="submit" @click="submitCOD" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-base" :disabled="isSubmitting">
                <span v-if="isSubmitting">
                  <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                </span>
                <span v-else>
                  Place Order ৳{{ formatPrice(finalTotal) }}
                </span>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Order Summary (narrower) -->
      <div class="w-full md:w-1/3 max-w-md bg-white rounded-xl shadow p-8 self-start">
        <h3 class="text-xl font-semibold mb-4">Order Summary</h3>
        <!-- Coupon Field -->
        <div class="mb-6">
          <label for="coupon" class="block text-sm font-medium text-gray-700 mb-2">Apply Coupon</label>
          <div class="flex gap-2">
            <input id="coupon" v-model="couponCode" placeholder="Enter coupon code" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
            <button @click="applyCoupon" type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">Apply</button>
          </div>
          <div v-if="couponSuccess" class="text-green-600 text-sm mt-2">Coupon applied! 10% discount.</div>
          <div v-if="couponError" class="text-red-600 text-sm mt-2">{{ couponError }}</div>
        </div>
        <div class="space-y-4 mb-6">
          <div v-for="item in cartItems" :key="item.id" class="flex justify-between pb-4 border-b border-gray-100">
            <div class="flex items-start">
              <div class="flex-shrink-0 w-12 h-12 rounded overflow-hidden mr-3">
                <img
                  :src="item.product.image ? getImageUrl(item.product.image) : '/images/placeholder.png'"
                  :alt="item.product.name"
                  class="w-full h-full object-cover"
                >
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">{{ item.product.name }}</p>
                <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
              </div>
            </div>
            <span class="text-sm font-medium text-gray-900">৳{{ formatPrice(item.product.price * item.quantity) }}</span>
          </div>
        </div>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Subtotal</span>
            <span class="text-sm font-medium text-gray-900">৳{{ formatPrice(subtotal) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Delivery Charge</span>
            <span class="text-sm font-medium text-gray-900">৳{{ formatPrice(deliveryCharge) }}</span>
          </div>
          <div v-if="discount > 0" class="flex justify-between">
            <span class="text-sm text-green-600">Discount (10%)</span>
            <span class="text-sm font-medium text-green-600">-৳{{ formatPrice(discount) }}</span>
          </div>
          <div class="flex justify-between pt-3 border-t border-gray-200">
            <span class="text-base font-bold text-gray-900">Total</span>
            <span class="text-base font-bold text-gray-900">৳{{ formatPrice(finalTotal) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import { imageHelper } from '@/mixins/ImageHelper.js';

const props = defineProps({
  cartItems: Array,
  subtotal: Number
});

const district = ref('');
const address = ref('');
const city = ref('');
const zipCode = ref('');
const activeTab = ref('card');
const isSubmitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const deliveryCharge = ref(0);
const couponCode = ref('');
const couponSuccess = ref(false);
const couponError = ref('');
const couponApplied = ref(false);
const discount = ref(0);

const total = computed(() => (props.subtotal || 0) + deliveryCharge.value);
const finalTotal = computed(() => total.value - discount.value);

// Card form state
const cardForm = ref({
  card_type: '',
  card_number: '',
  expiry: '',
  cvv: ''
});
// Mobile form state
const mobileForm = ref({
  mobile_payment: '',
  phone: ''
});

function updateDeliveryCharge() {
  if (district.value === 'dhaka') deliveryCharge.value = 30;
  else if (district.value === 'other') deliveryCharge.value = 60;
  else deliveryCharge.value = 0;
}
watch(district, updateDeliveryCharge);

function formatCardNumber(val, type) {
  let value = val.replace(/\D/g, '');
  let formatted = '';
  if (type === 'amex') {
    for (let i = 0; i < value.length; i++) {
      if (i === 4 || i === 10) formatted += ' ';
      formatted += value[i];
    }
  } else {
    for (let i = 0; i < value.length; i++) {
      if (i > 0 && i % 4 === 0) formatted += ' ';
      formatted += value[i];
    }
  }
  return formatted;
}

function onCardNumberInput(e) {
  let value = e.target.value.replace(/\D/g, '');
  value = value.slice(0, 16);
  cardForm.value.card_number = formatCardNumber(value, cardForm.value.card_type);
}

function onCVVInput(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (cardForm.value.card_type === 'amex') value = value.slice(0, 4);
  else value = value.slice(0, 3);
  cardForm.value.cvv = value;
}

function onMobilePhoneInput(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);
  mobileForm.value.phone = value;
}

function submitCard(e) {
  e.preventDefault();
  errorMessage.value = '';
  if (!district.value || !address.value || !city.value || !zipCode.value || !cardForm.value.card_type || !cardForm.value.card_number || !cardForm.value.expiry || !cardForm.value.cvv) {
    errorMessage.value = 'Please fill in all required fields.';
    return;
  }
  const payload = {
    payment_type: 'card',
    district: district.value,
    address: address.value,
    city: city.value,
    zip_code: zipCode.value,
    card_type: cardForm.value.card_type,
    card_number: cardForm.value.card_number.replace(/\s/g, ''),
    expiry: cardForm.value.expiry,
    cvv: cardForm.value.cvv,
    cart: props.cartItems,
    subtotal: props.subtotal,
    delivery_charge: deliveryCharge.value,
    total: finalTotal.value
  };
  isSubmitting.value = true;
  router.post(route('checkout.process'), payload, {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Order placed successfully!';
      setTimeout(() => {
        router.visit(route('orders.index'));
      }, 1000);
    },
    onError: (errors) => {
      errorMessage.value = 'Failed to place order. Please check your input.';
      isSubmitting.value = false;
    },
    onFinish: () => {
      if (errorMessage.value) {
        isSubmitting.value = false;
      }
    }
  });
}

function submitMobile(e) {
  e.preventDefault();
  errorMessage.value = '';
  if (!district.value || !address.value || !city.value || !zipCode.value || !mobileForm.value.mobile_payment || !mobileForm.value.phone) {
    errorMessage.value = 'Please fill in all required fields.';
    return;
  }
  const payload = {
    payment_type: 'mobile',
    district: district.value,
    address: address.value,
    city: city.value,
    zip_code: zipCode.value,
    mobile_payment: mobileForm.value.mobile_payment,
    phone: mobileForm.value.phone,
    cart: props.cartItems,
    subtotal: props.subtotal,
    delivery_charge: deliveryCharge.value,
    total: finalTotal.value
  };
  isSubmitting.value = true;
  router.post(route('checkout.process'), payload, {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Order placed successfully!';
      setTimeout(() => {
        router.visit(route('orders.index'));
      }, 1000);
    },
    onError: (errors) => {
      errorMessage.value = 'Failed to place order. Please check your input.';
      isSubmitting.value = false;
    },
    onFinish: () => {
      if (errorMessage.value) {
        isSubmitting.value = false;
      }
    }
  });
}

function submitCOD(e) {
  e.preventDefault();
  errorMessage.value = '';
  if (!district.value || !address.value || !city.value || !zipCode.value) {
    errorMessage.value = 'Please fill in all required address fields.';
    return;
  }
  const payload = {
    payment_type: 'cod',
    district: district.value,
    address: address.value,
    city: city.value,
    zip_code: zipCode.value,
    cart: props.cartItems,
    subtotal: props.subtotal,
    delivery_charge: deliveryCharge.value,
    total: finalTotal.value
  };
  isSubmitting.value = true;
  router.post(route('checkout.process'), payload, {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Order placed successfully!';
      setTimeout(() => {
        router.visit(route('orders.index'));
      }, 1000);
    },
    onError: (errors) => {
      errorMessage.value = 'Failed to place order. Please check your input.';
      isSubmitting.value = false;
    },
    onFinish: () => {
      if (errorMessage.value) {
        isSubmitting.value = false;
      }
    }
  });
}

// Import ImageHelper methods
const { getImageUrl } = imageHelper.methods;

// Card and mobile types
const cardTypes = [
  { value: 'visa', label: 'Visa', logo: '/images/Visa.png' },
  { value: 'mastercard', label: 'Mastercard', logo: '/images/Mastercard.png' },
  { value: 'amex', label: 'Amex', logo: '/images/Amex.png' }
];
const mobileTypes = [
  { value: 'bkash', label: 'bKash', logo: '/images/bKash.png' },
  { value: 'nagad', label: 'Nagad', logo: '/images/Nagad.png' },
  { value: 'rocket', label: 'Rocket', logo: '/images/Rocket.png' }
];

function formatPrice(price) {
  return Number(price).toFixed(2);
}

function applyCoupon() {
  couponError.value = '';
  couponSuccess.value = false;
  if (couponApplied.value) {
    couponError.value = 'Coupon already applied.';
    return;
  }
  if (couponCode.value.trim().toLowerCase() === 'medimart10') {
    discount.value = Math.round(props.subtotal * 0.10);
    couponSuccess.value = true;
    couponApplied.value = true;
    couponError.value = '';
  } else {
    couponError.value = 'Invalid coupon code.';
    couponSuccess.value = false;
    discount.value = 0;
  }
}

// Update discount if subtotal or delivery changes (e.g., user changes district)
watch([total], () => {
  if (couponApplied.value) {
    discount.value = Math.round(props.subtotal * 0.10);
  }
});

// Initialize delivery charge
updateDeliveryCharge();
</script>
