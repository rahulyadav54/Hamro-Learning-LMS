class PurchaseItem {
  PurchaseItem({
    required this.title,
    required this.amount,
    required this.discount,
    required this.paymentMethod,
    required this.status,
    required this.date,
    required this.billingName,
  });

  final String title;
  final double amount;
  final double discount;
  final String? paymentMethod;
  final String status;
  final String date;
  final String billingName;

  factory PurchaseItem.fromJson(Map<String, dynamic> json) {
    return PurchaseItem(
      title: json['title']?.toString() ?? 'Payment',
      amount: (json['amount'] as num?)?.toDouble() ?? 0,
      discount: (json['discount'] as num?)?.toDouble() ?? 0,
      paymentMethod: json['payment_method']?.toString(),
      status: json['status']?.toString() ?? 'Pending',
      date: json['date']?.toString() ?? '',
      billingName: json['billing_name']?.toString() ?? '',
    );
  }
}
