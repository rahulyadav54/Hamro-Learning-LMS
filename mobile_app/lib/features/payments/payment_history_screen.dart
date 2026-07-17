import 'package:flutter/material.dart';

import '../../core/network/lms_api_service.dart';
import '../../models/purchase_item.dart';
import '../../widgets/section_header.dart';

class PaymentHistoryScreen extends StatefulWidget {
  const PaymentHistoryScreen({super.key});

  @override
  State<PaymentHistoryScreen> createState() => _PaymentHistoryScreenState();
}

class _PaymentHistoryScreenState extends State<PaymentHistoryScreen> {
  late Future<List<PurchaseItem>> _futurePurchases;

  @override
  void initState() {
    super.initState();
    _futurePurchases = LmsApiService.instance.fetchPurchases();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<PurchaseItem>>(
      future: _futurePurchases,
      builder: (context, snapshot) {
        final purchases = snapshot.data ?? const <PurchaseItem>[];
        final paidTotal = purchases.fold<double>(
          0,
          (sum, item) => sum + item.amount,
        );
        final dueTotal = purchases
            .where((item) => item.status.toLowerCase() != 'paid')
            .fold<double>(0, (sum, item) => sum + item.amount);

        return ListView(
          padding: const EdgeInsets.all(20),
          children: [
            const SectionHeader(
              title: 'Payment History',
              subtitle: 'See your purchases, deposits, and invoices',
            ),
            const SizedBox(height: 16),
            if (snapshot.connectionState == ConnectionState.waiting)
              const Center(child: CircularProgressIndicator())
            else if (snapshot.hasError)
              Text('Failed to load payments: ${snapshot.error}')
            else ...[
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      Expanded(
                        child: _SummaryItem(
                          label: 'Paid',
                          value: 'INR ${paidTotal.toStringAsFixed(0)}',
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _SummaryItem(
                          label: 'Due',
                          value: 'INR ${dueTotal.toStringAsFixed(0)}',
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              if (purchases.isEmpty)
                const Text('No payment history found.')
              else
                ...purchases.map(
                  (purchase) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Card(
                      child: ListTile(
                        contentPadding: const EdgeInsets.all(16),
                        title: Text(
                          purchase.title,
                          style: const TextStyle(fontWeight: FontWeight.w700),
                        ),
                        subtitle: Padding(
                          padding: const EdgeInsets.only(top: 6),
                          child: Text(
                            '${purchase.status} • ${purchase.date}',
                            style: TextStyle(
                              color: purchase.status.toLowerCase() == 'paid'
                                  ? Colors.green
                                  : Colors.orange,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                        trailing: Text(
                          'INR ${purchase.amount.toStringAsFixed(0)}',
                          style: const TextStyle(fontWeight: FontWeight.w700),
                        ),
                      ),
                    ),
                  ),
                ),
            ],
          ],
        );
      },
    );
  }
}

class _SummaryItem extends StatelessWidget {
  const _SummaryItem({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(color: Colors.black54)),
        const SizedBox(height: 6),
        Text(
          value,
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w700),
        ),
      ],
    );
  }
}
