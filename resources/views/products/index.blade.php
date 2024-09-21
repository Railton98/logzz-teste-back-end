<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                {{ __('New') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                <div class="w-full">
                                    <form method="get" action="{{ route('products.index') }}" class="flex items-center justify-between">
                                        <div>
                                            <x-input-label for="id" :value="__('ID')" />
                                            <x-text-input id="id" name="id" type="number" class="mt-1 block w-full" :value="old('id', request()->query('id'))" />
                                        </div>

                                        <div>
                                            <x-input-label for="name" :value="__('Name')" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Product or Category" :value="old('name', request()->query('name'))" />
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <input id="has_image" name="has_image" type="checkbox" class="mr-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" @checked(old('has_image', request()->query('has_image')))>
                                            <x-input-label for="has_image" :value="__('Product has image')" />
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <input id="no_has_image" name="no_has_image" type="checkbox" class="mr-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" @checked(old('no_has_image', request()->query('no_has_image')))>
                                            <x-input-label for="no_has_image" :value="__('Product no has image')" />
                                        </div>

                                        <div>
                                            <x-input-label for="category_id" :value="__('Category')" />
                                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                <option disabled selected>{{ __('Choose a Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" @selected(old('category_id', request()->query('category_id')) == $category->id)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <x-primary-button>{{ __('Search') }}</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('ID') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Category') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Price') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        #{{ $product->id }}
                                    </th>
                                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        @if($product->image_url)
                                            <img class="w-8 h-8 rounded-full" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        @endif
                                        <div class="ps-3">
                                            <div class="text-base font-semibold">{{ str($product->name)->limit(60) }}</div>
                                        </div>
                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $product->category->name }}
                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $product->price }}
                                    </th>
                                    <td class="flex px-6 py-4">
                                        <a href="{{ route('products.show', $product) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                            {{ __('Show')  }}
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="ml-2 font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                            {{ __('Edit')  }}
                                        </a>
                                        <form method="post" action="{{ route('products.destroy', $product) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="ml-2 font-medium text-red-600 dark:text-red-500 hover:underline">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td class="px-6 py-4" colspan="4">
                                        {{ __('Products not found!') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="p-1">
                            {{ $products->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
