// Utility for mapping payment method/type to icon/text (for use in Orders pages)
export function getPaymentIcon(order) {
  if (order.payment_method === 'card') {
    switch (order.card_type) {
      case 'visa': return 'fab fa-cc-visa';
      case 'mastercard': return 'fab fa-cc-mastercard';
      case 'amex': return 'fab fa-cc-amex';
      default: return 'fas fa-credit-card';
    }
  }
  if (order.payment_method === 'mobile') {
    switch (order.mobile_payment_type) {
      case 'bkash': return 'fas fa-mobile-alt text-pink-400';
      case 'nagad': return 'fas fa-mobile-alt text-orange-400';
      case 'rocket': return 'fas fa-mobile-alt text-purple-400';
      default: return 'fas fa-mobile-alt';
    }
  }
  return 'fas fa-money-bill';
}
export function getPaymentText(order) {
  if (order.payment_method === 'card') {
    return order.card_type ? order.card_type.charAt(0).toUpperCase() + order.card_type.slice(1) : 'Card';
  }
  if (order.payment_method === 'mobile') {
    return order.mobile_payment_type ? order.mobile_payment_type.charAt(0).toUpperCase() + order.mobile_payment_type.slice(1) : 'Mobile';
  }
  return order.payment_method ? order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1) : 'Cash';
}
