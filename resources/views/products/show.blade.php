<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row -mx-4">
                        <div class="md:flex-1 px-4">
                            <div class="h-[460px] rounded-lg bg-gray-300 dark:bg-gray-700 mb-4">
                                <img class="w-full h-full object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </div>
                            <div class="flex items-center -mx-2 mb-4">
                                <div class="w-1/2 px-2">
                                    <a href="{{ route('products.edit', $product) }}" class="w-full bg-gray-900 dark:bg-blue-600 text-white py-2.5 px-24 rounded-full font-bold hover:bg-blue-800 dark:hover:bg-blue-700">{{ __('Edit') }}</a>
                                </div>
                                <form class="w-1/2 px-2" method="post" action="{{ route('products.destroy', $product) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="w-full bg-gray-200 dark:bg-red-700 text-red-800 dark:text-white py-2 px-4 rounded-full font-bold hover:bg-red-300 dark:hover:bg-red-600">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                        <div class="md:flex-1 px-4">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h2>
                            <div class="mb-6">
                                <div class="mb-4">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Category') }}:</span>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $product->category->name }}</span>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Price') }}:</span>
                                    <span class="text-gray-600 dark:text-gray-300">R$ {{ $product->price }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Product Description') }}:</span>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
