<div>
    <main class="container flex-grow p-4 sm:p-6" x-data="handler()">
        <!-- Page Title Starts -->
        <div class="mb-6 flex flex-col justify-between gap-y-1 sm:flex-row sm:gap-y-0">
            <h5>Invoice List</h5>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a wire:navigate href="{{ route('user.prod') }}">Invoice List</a>
                </li>
            </ol>
        </div>
        <!-- Page Title Ends -->


        <!-- Product List Starts -->
        <div class="space-y-4">
            <!-- Product Header Starts -->
            <div class="flex flex-col items-center justify-between gap-y-4 md:flex-row md:gap-y-0">
                <!-- Customer Search Starts -->
                @if ($count == 1)
                <form
                    class="group flex h-10 w-full items-center rounded-primary border border-transparent bg-white shadow-sm focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-inset focus-within:ring-primary-500 dark:border-transparent dark:bg-slate-800 dark:focus-within:border-primary-500 md:w-72">
                    <div class="flex h-full items-center px-2">
                        <i class="h-4 text-slate-400 group-focus-within:text-primary-500" data-feather="search"></i>
                    </div>
                    <input
                        class="h-full w-full border-transparent bg-transparent px-0 text-sm placeholder-slate-400 placeholder:text-sm focus:border-transparent focus:outline-none focus:ring-0"
                        type="text" placeholder="Search" />
                </form>
                @endif
                <!-- Customer Search Ends -->

                <!-- Customer Action Starts -->
                <div class="flex w-full items-center justify-between gap-x-4 md:w-auto">
                    @if ($count == 1)
                    <div class="flex items-center gap-x-4">
                        <div class="dropdown" data-placement="bottom-end">
                            <div class="dropdown-toggle">
                                <button type="button" class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                                    <iconify-icon icon="solar:filter-line-duotone" class="text-2xl"></iconify-icon>
                                    <span class="hidden sm:inline-block">Filter</span>
                                    <iconify-icon icon="solar:alt-arrow-down-line-duotone" class="text-2xl">
                                    </iconify-icon>
                                </button>
                            </div>
                            <div class="dropdown-content w-72 !overflow-visible">
                                <ul class="dropdown-list space-y-4 p-4">
                                    <li class="dropdown-list-item">
                                        <h2 class="my-1 text-sm font-medium">Category</h2>
                                        <select class="tom-select w-full" autocomplete="off">
                                            <option value="">Select a Category</option>
                                            <option value="1">Electronics</option>
                                            <option value="2">Fashion</option>
                                            <option value="3">Accessories</option>
                                        </select>
                                    </li>

                                    <li class="dropdown-list-item">
                                        <h2 class="my-1 text-sm font-medium">Status</h2>
                                        <select class="select">
                                            <option value="">Select to Status</option>
                                            <option value="1">Available</option>
                                            <option value="2">Disabled</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <button class="btn bg-white font-medium shadow-sm dark:bg-slate-800">
                            <iconify-icon icon="solar:export-line-duotone" class="text-2xl"></iconify-icon>
                            <span class="hidden sm:inline-block">Export</span>
                        </button>
                    </div>

                    <a class="btn btn-primary" wire:click='addinvo' role="button">
                        <iconify-icon icon="solar:cart-plus-line-duotone" class="text-2xl"></iconify-icon>
                        <span class="hidden sm:inline-block">Create Invoice</span>
                    </a>
                    @else
                    <a class="btn btn-primary" wire:click='back' role="button">
                        <iconify-icon icon="solar:arrow-left-bold-duotone" class="text-2xl"></iconify-icon>
                        <span class="hidden sm:inline-block">Back</span>
                    </a>
                    @endif
                </div>
                <!-- Customer Action Ends -->
            </div>
            <!-- Product Header Ends -->
            @if ($count == 1)
            <!-- Product Table Starts -->
            <div class="table-responsive whitespace-nowrap rounded-primary">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-[5%]">
                                <input class="checkbox" type="checkbox" data-check-all
                                    data-check-all-target=".product-checkbox" />
                            </th>
                            <th class="w-[10%] uppercase">Invoice</th>
                            <th class="w-[10%] uppercase">Amount</th>
                            <th class="w-[10%] uppercase">Create Date</th>
                            <th class="w-[10%] uppercase">Customer</th>
                            <th class="w-[10%] uppercase">Due Date</th>
                            <th class="w-[10%] uppercase">Status</th>
                            <th class="w-[5%] !text-right uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:poll>
                        @foreach ($invoice as $invo)
                        <tr>
                            <td>
                                <input class="checkbox product-checkbox" type="checkbox" />
                            </td>
                            <td>{{ $invo->id }}</td>
                            <td>$ {{ $invo->mtoal }}</td>
                            <td>{{ $invo->created_at->format('d M y') }}</td>
                            <td>{{ $invo->user->name }}</td>
                            <td>{{ $invo->created_at->format('d M y') }}</td>
                            <td>
                                <div class="badge badge-soft-success">
                                    Active
                            </td>

                            <td>
                                <div class="flex justify-end gap-x-2">
                                    <div class="dropdown" data-placement="bottom-start">
                                        <div wire:click='delete({{ $invo->id }})' class="dropdown-toggle">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-line-duotone"
                                                class="text-2xl text-danger-500">
                                            </iconify-icon>
                                        </div>
                                    </div>
                                    <div class="dropdown" data-placement="bottom-start">
                                        <div wire:click='download({{ $invo->id }})' class="dropdown-toggle">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-line-duotone"
                                                class="text-2xl text-teal-300">
                                            </iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Product Table Ends -->

            <!-- Product Pagination Starts -->
            {{ $prodli->links() }}
            <!-- Product Pagination Ends -->
        </div>
        <!-- Product List Ends -->
        @elseif ($count == 2)
        <form wire:poll>
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
                <!--Left Side Div Starts -->
                <div class="col-span-1 xl:col-span-3">
                    <div class="card">
                        <div class="card-body space-y-6">
                            <!-- Invoice Header Starts -->
                            <div class="flex flex-col justify-between p-1 md:flex-row">
                                <!-- Logo Starts -->
                                <div class="flex items-center justify-center md:justify-start">
                                    <div class="flex h-16 w-full items-center gap-4 pr-4">
                                        <span class="inline-block flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="text-4xl w-12 h-12 text-[#8b5cf6]" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M15 16.69V13h1.5v2.82l2.44 1.41l-.75 1.3zm-4.42 3.73L9 22l-1.5-1.5L6 22l-1.5-1.5L3 22V2l1.5 1.5L6 2l1.5 1.5L9 2l1.5 1.5L12 2l1.5 1.5L15 2l1.5 1.5L18 2l1.5 1.5L21 2v9.1a7.001 7.001 0 0 1-9.95 9.85a4.69 4.69 0 0 1-.47-.53m-.86-1.33c-.32-.66-.54-1.36-.65-2.09H6v-2h3.07c.1-.71.31-1.38.61-2H6v-2h5.1c1.27-1.24 3-2 4.9-2H6V7h12v2h-2c1.05 0 2.07.24 3 .68V4.91H5v14.18zM20.85 16c0-2.68-2.18-4.85-4.85-4.85c-1.29 0-2.5.51-3.43 1.42c-.91.93-1.42 2.14-1.42 3.43c0 2.68 2.17 4.85 4.85 4.85c.64 0 1.27-.12 1.86-.35c.58-.26 1.14-.62 1.57-1.07c.45-.43.81-.99 1.07-1.57c.23-.59.35-1.22.35-1.86" />
                                            </svg>
                                        </span>

                                        <div>
                                            <h1 class="flex text-xl">
                                                <span class="font-bold text-slate-800 dark:text-slate-200">
                                                    Inva
                                                </span>
                                                <span class="font-semibold text-primary-500">
                                                    tser
                                                </span>
                                            </h1>
                                            <p class="whitespace-nowrap text-xs text-slate-400">
                                                Simple &amp;
                                                Customizable
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Logo Ends -->

                                <!-- Title and Date Start -->
                                <div class="flex flex-col items-start gap-3 md:items-end">
                                    <h4>Invoice #1001</h4>
                                    <div class="flex w-full flex-col items-start gap-2 sm:items-center md:flex-row">
                                        <label for="invoice-date"
                                            class="label w-full font-medium md:w-1/4 md:text-right">Date:</label>
                                        <input id="invoice-date" class="input input-date bg-white dark:bg-slate-800"
                                            type="text" x-mask="99-99-9999" placeholder="DD-MM-YYYY" />
                                    </div>
                                    <div class="flex w-full flex-col items-start gap-2 sm:items-center md:flex-row">
                                        <label for="invoice-due-date"
                                            class="label w-full font-medium md:w-1/4 md:text-right">Due
                                            Date:</label>
                                        <input id="invoice-due-date" class="input input-date bg-white dark:bg-slate-800"
                                            type="text" x-mask="99-99-9999" placeholder="DD-MM-YYYY" />
                                    </div>
                                </div>
                                <!-- Title and Date End -->
                            </div>
                            <!-- Invoice Header Ends -->

                            <!-- Invoice Info Starts  -->
                            <div class="flex flex-col justify-between space-y-6 p-1 md:flex-row md:space-y-0">
                                <div
                                    class="flex w-full flex-col items-start justify-center md:mb-0 md:w-2/3 md:justify-center">
                                    <p class="text-xs font-medium uppercase text-slate-400">
                                        BILLED FROM
                                    </p>
                                    <h6 class="my-1">
                                        Admin Toolkit
                                    </h6>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        206 Yonge St, Toronto - M4S
                                        2A3
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        Tel No: (317) 745-1499
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        Email: info@admintoolkit.com
                                    </p>
                                </div>

                                <div class="flex w-full flex-col items-start justify-center md:w-1/3 md:items-end">
                                    <p class="text-xs font-medium uppercase text-slate-400">
                                        BILLED TO
                                    </p>
                                    @if ($cltdat != null)

                                    @php
                                    $options = json_decode($cltdat->add_data, true)
                                    @endphp

                                    <h6 class="my-1">{{$cltdat->name}}</h6>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        {{$options['address']}}, {{$options['city']}}
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        {{$options['state']}}, {{$options['country']}}
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        Tel No: {{$cltdat->phone}}
                                    </p>
                                    @else
                                    <h6 class="my-1">John Doe</h6>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        206 Yonge St, Toronto - M4S
                                        2A3
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        Tel No: (317) 745-1499
                                    </p>
                                    <p class="whitespace-nowrap text-sm font-normal text-slate-600 dark:text-slate-300">
                                        Email: johndoe@example.com
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <!-- Invoice Info Ends  -->

                            <!-- Product Table Starts -->
                            <div class="w-full overflow-auto p-1">
                                <div class="min-w-[42rem]">
                                    <div
                                        class="table-responsive whitespace-nowrap rounded-primary border border-slate-200 dark:border-slate-600">
                                        <table id="table-products" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="45%">
                                                        Product Name
                                                    </th>
                                                    <th width="20%">
                                                        Price
                                                    </th>
                                                    <th width="15%">
                                                        Quantity
                                                    </th>
                                                    <th class="!text-right">
                                                        Total Price
                                                    </th>
                                                    <th class="w-8 !px-0 !py-0 !pr-2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(field, index) in fields" :key="index">
                                                    <tr>
                                                        <td>
                                                            <select x-model="field.txt3" data-rules='["required"]'
                                                                name="txt3" class="select" autocomplete="off"
                                                                @change="setprice($event)">
                                                                <option>Add Item</option>
                                                                @foreach ($prodli as $cate)
                                                                <option value="{{ $cate->name }}"
                                                                    data-price="{{ $cate->price }}">
                                                                    {{ $cate->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" data-rules='["required"]'
                                                                x-model="field.txt1" name="txt1" placeholder="0.00"
                                                                @input.debounce.500ms="sumofvalue()" class="input"
                                                                :value="field.price" />
                                                        </td>
                                                        <td>
                                                            <input type="text" data-rules='["required"]'
                                                                x-model="field.txt2"
                                                                @input.debounce.500ms="sumofvalue()" name="txt2"
                                                                placeholder="1" class="input" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total" placeholder="1"
                                                                class="input" disabled="" :value="field.total" />
                                                        </td>
                                                        <td class="!px-0 !py-0 !pr-2">
                                                            <div class="flex items-center justify-center">
                                                                <button type="button" @click="removeField(index)"
                                                                    class="btn-remove-item cursor-pointer rounded-full p-1 font-medium focus:bg-slate-300 focus:bg-opacity-50 focus:text-slate-600">
                                                                    <iconify-icon
                                                                        icon="solar:trash-bin-minimalistic-line-duotone"
                                                                        class="text-2xl text-danger-500"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <div class="flex w-2/5 flex-col items-start gap-y-4">
                                            <div class="w-full">
                                                <button id="btn-add-item" @click="addNewField()" type="button"
                                                    class="inline-flex items-center gap-x-2 text-sm text-primary-500 hover:text-primary-700 focus:text-primary-700 dark:hover:text-primary-600 dark:focus:text-primary-600">
                                                    <i class="h-4" data-feather="plus"></i>
                                                    New Item
                                                </button>
                                            </div>
                                            <div class="w-full">
                                                <textarea class="textarea" rows="3"
                                                    placeholder="Write some note"></textarea>
                                            </div>
                                        </div>

                                        <div>
                                            <ul class="mr-8 space-y-3">
                                                <li class="flex items-center gap-x-2">
                                                    <span
                                                        class="inline-block w-1/2 text-right text-sm font-medium text-slate-400">Subtotal:</span>
                                                    <span x-text="subtotal"
                                                        class="inline-block w-1/2 pr-6 text-right text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                        $00.00
                                                    </span>
                                                </li>
                                                {{-- <li class="flex items-center gap-x-2">
                                                    <span
                                                        class="inline-block w-1/2 text-right text-sm font-medium text-slate-400">Tax:</span>
                                                    <span
                                                        class="inline-block w-1/2 pr-6 text-right text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                        $00.00
                                                    </span>
                                                </li> --}}
                                                <li class="flex items-center gap-x-2">
                                                    <span
                                                        class="inline-block w-1/2 text-right text-sm font-medium text-slate-400">Balance:</span>
                                                    <input type="text" x-model="balance"
                                                        @input.debounce.500ms="balanceof()" placeholder="$00.00"
                                                        class="input w-1/2 text-right" />
                                                </li>
                                            </ul>
                                            <hr class="mb-1 mt-5 border-slate-200 dark:border-slate-600" />
                                            <div class="mr-8 flex items-center gap-x-2">
                                                <span
                                                    class="inline-block w-1/2 text-right text-sm font-medium text-slate-400">Total:</span>
                                                <span x-text="mtotal"
                                                    class="inline-block w-1/2 pr-6 text-right text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                    $00.00
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Product Table Ends -->

                            <p class="py-2 text-center text-sm">
                                Thanks for your Business
                            </p>
                        </div>
                    </div>
                </div>
                <!--Left Side Div Ends -->

                <!-- Right Side Div Starts-->
                <div class="sticky top-20 col-span-1 h-max">
                    <div class="card">
                        <div class="card-body flex flex-col gap-4">

                            <div class="w-full">
                                <select wire:model.live='customer' class="select">
                                    <option>Select Customer</option>
                                    @foreach ($clntli as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Method  -->
                            <div class="w-full">
                                <select class="select">
                                    <option value="">Payment Method</option>
                                    <option value="1">Bank Account</option>
                                    <option value="2">Paypal</option>
                                    <option value="2">Credit/Debit Card</option>
                                    <option value="2">UPI Transfer</option>
                                </select>
                            </div>
                            <!-- Send Email  -->
                            <div class="flex w-full justify-between">
                                <span class="label text-sm font-medium text-slate-400">Send What's App</span>
                                <label for="send-email" class="toggle">
                                    <input class="toggle-input peer sr-only" id="send-email" type="checkbox"
                                        checked="true" name="send-email" />
                                    <div class="toggle-body"></div>
                                </label>
                            </div>

                            <!-- Charge Fee  -->
                            <div class="flex w-full justify-between">
                                <span class="label text-sm font-medium text-slate-400">Charge Late Fee</span>
                                <label for="charge-late-fee" class="toggle">
                                    <input class="toggle-input peer sr-only" id="charge-late-fee" type="checkbox"
                                        name="charge-late-fee" />
                                    <div class="toggle-body"></div>
                                </label>
                            </div>
                            <!-- Save And Print Invoice  -->
                            <div class="flex flex-col gap-4 2xl:flex-row">
                                <button @click="send()" type="button"
                                    class="btn btn-primary bg-[#8b5cf6] w-full font-medium">
                                    <i class="h-4" data-feather="save"></i>
                                    <span>Save</span>
                                </button>

                                <button class="btn btn-success w-full font-medium">
                                    <i class="h-4" data-feather="printer"></i>
                                    <span>Print</span>
                                </button>
                            </div>

                            <!-- Preview Invoice  -->
                            <div class="w-full">
                                <button class="btn btn-outline-primary w-full font-medium">
                                    <i class="h-4" data-feather="eye"></i>
                                    <span>Preview</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Right Side Div Ends -->
            </div>
        </form>
        @endif
    </main>

    @push('js')
    <script>
        function handler() {
                return {
                    fields: [],
                    subtotal: 0,
                    balance: 0,
                    mtotal: 0,

                    addNewField() {
                        this.fields.push({
                            txt1: '',
                            txt2: '',
                            txt3: '',
                            total: '',
                            price: '',
                        });
                    },
                    removeField(index) {
                        this.fields.splice(index, 1);
                    },
                    sumofvalue() {
                        let totalSum = 0;
                        this.fields.forEach((field, index) => {
                            const txt1Value = parseFloat(field.txt1) || 0;
                            const txt2Value = parseFloat(field.txt2) || 0;
                            totalSum = txt1Value * txt2Value;
                            this.fields[index].total = totalSum;
                            this.subtotal += totalSum;
                        });
                        this.mtotal = this.subtotal
                    },
                    setprice(event) {
                        const selectedOption = event.target.options[event.target.selectedIndex];
                        const price = selectedOption.dataset.price;
                        this.fields.forEach((field, index) => {
                            if (this.fields[index].txt3 === selectedOption.value) {
                                this.fields[index].txt1 = price;
                            }
                        });
                    },
                    balanceof() {
                        const txtbal = parseFloat(this.balance) || 0;
                        const txtsub = parseFloat(this.subtotal) || 0;
                        this.mtotal = txtbal + txtsub;
                    },
                    send() {
                        if (this.fields.length == 0) {
                            console.log('its working');
                            this.$dispatch('warning', {
                                title: 'Nothing To Calculate!'
                            });
                        } else {
                            this.$wire.invosav(this.fields, this.subtotal, this.balance, this.mtotal);
                        }

                    }
                }
            }
    </script>
    @endpush
</div>