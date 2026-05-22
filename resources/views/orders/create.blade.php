<x-app-layout>
    @section('title', 'Create Sales Order')

    <div class="max-w-6xl mx-auto space-y-6" x-data="orderForm()">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Create New Sales Order</h2>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Header Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-3">
                            <i class="fa-solid fa-file-contract text-brand w-6"></i> Order Header
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 ml-1">Customer</label>
                                <select name="customer_id" x-model="selectedCustomer" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" data-account="{{ $c->account_id }}">{{ $c->name }} ({{ $c->account->name }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 ml-1">Order Date</label>
                                <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 ml-1">Payment Term (TOP)</label>
                                <select name="payment_term" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="Net 14">Net 14 Days</option>
                                    <option value="Net 30">Net 30 Days</option>
                                    <option value="Net 60">Net 60 Days</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 ml-1">Currency</label>
                                <select name="currency" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="IDR">IDR - Rupiah</option>
                                    <option value="USD">USD - US Dollar</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fa-solid fa-boxes-stacked text-brand w-6"></i> Order Items
                            </h3>
                            <button type="button" @click="addItem()" class="text-xs font-bold text-brand hover:bg-brand/10 px-4 py-2 rounded-xl border border-brand/20 transition-all">
                                <i class="fa-solid fa-plus mr-2"></i> Add Item
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 relative group" x-data="{ open: false, search: '' }">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <!-- Product -->
                                        <div class="md:col-span-2 relative">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Product</label>
                                            <div @click="open = !open" class="cursor-pointer flex items-center justify-between w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand/20">
                                                <span x-text="getItemName(item.product_id)" class="text-gray-700 font-medium"></span>
                                                <i class="fa-solid fa-chevron-down text-gray-300 text-xs"></i>
                                            </div>
                                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto" x-cloak>
                                                <input type="text" x-model="search" placeholder="Search product..." class="w-full text-xs border-gray-100 rounded-lg mb-2">
                                                <template x-for="product in filteredProducts(search)" :key="product.id">
                                                    <div @click="item.product_id = product.id; open = false; search = ''" class="px-3 py-2 rounded-lg hover:bg-brand hover:text-white cursor-pointer transition-colors text-sm font-bold" x-text="product.name"></div>
                                                </template>
                                            </div>
                                            <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                                        </div>

                                        <!-- Qty -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Qty</label>
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="1" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                        </div>

                                        <!-- Subtotal Display -->
                                        <div class="text-right">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Subtotal</label>
                                            <div class="py-3 text-sm font-black text-gray-900" x-text="'Rp ' + new Intl.NumberFormat().format(calculateSubtotal(item))"></div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="absolute -top-2 -right-2 bg-red-100 text-red-500 rounded-full w-8 h-8 flex items-center justify-center border-2 border-white shadow-sm hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Summary & Totals -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gray-900 rounded-2xl shadow-xl p-8 text-white sticky top-24">
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Financial Summary</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400 font-bold">Subtotal</span>
                                <span class="font-black" x-text="'Rp ' + new Intl.NumberFormat().format(calculateTotal())"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400 font-bold">DP / Paid Amount</span>
                                <input type="number" name="dp_amount" value="0" class="block w-32 bg-white/10 border-white/20 rounded-lg px-3 py-2 text-right text-xs focus:ring-brand focus:border-brand text-white">
                            </div>
                            <div class="pt-6 border-t border-white/10 mt-6 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black text-brand uppercase tracking-widest">Grand Total</p>
                                    <p class="text-2xl font-black tracking-tighter" x-text="'Rp ' + new Intl.NumberFormat().format(calculateTotal())"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 space-y-3">
                            <button type="submit" class="w-full py-4 bg-brand text-white rounded-2xl font-black uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-lg shadow-brand/20">
                                Confirm Sales Order
                            </button>
                            <a href="{{ route('orders.index') }}" class="block w-full py-3 text-center text-gray-500 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">
                                Save as Draft
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function orderForm() {
            return {
                selectedCustomer: '',
                selectedAccount: '',
                allProducts: {!! json_encode($productsByAccount) !!},
                items: [],

                init() {
                    this.$watch('selectedCustomer', (val) => {
                        const el = document.querySelector(`option[value="${val}"]`);
                        if (el) this.selectedAccount = el.dataset.account;
                    });
                },

                addItem() {
                    this.items.push({ product_id: '', quantity: 1, discount: 0, tax_percent: 11 });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                getAvailableProducts() {
                    return this.allProducts[this.selectedAccount] || [];
                },

                filteredProducts(search) {
                    const products = this.getAvailableProducts();
                    if (!search) return products;
                    const s = search.toLowerCase();
                    return products.filter(p => p.name.toLowerCase().includes(s));
                },

                getItemName(id) {
                    const product = this.getAvailableProducts().find(p => p.id === id);
                    return product ? product.name : '-- Select Product --';
                },

                calculateSubtotal(item) {
                    const product = this.getAvailableProducts().find(p => p.id === item.product_id);
                    return product ? product.price * item.quantity : 0;
                },

                calculateTotal() {
                    return this.items.reduce((total, item) => total + this.calculateSubtotal(item), 0);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
