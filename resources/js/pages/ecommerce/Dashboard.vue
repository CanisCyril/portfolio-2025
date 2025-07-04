<script setup lang="ts">
import { Head } from '@inertiajs/vue3';


</script>

<template>
        <Head>
            <title>E-Commerce Dashboard</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        </Head>
        <div class="flex items-center justify-center-safe ">
            <div class="bg-white rounded shadow sm:m-6 m-4 w-[95dvw] h-[95dvh] sm:h-[95dvh] md:h-[40dvh]">
                <h2 class="text-lg font-semibold mb-2">Simple Box</h2>
                <p class="text-gray-600">This is a simple box using Tailwind CSS.</p>
            </div>
        </div>
          <div class="flex items-center justify-center-safe">
                <button class="bg-gradient-to-r mr-3 from-blue-500 to-green-300 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:from-green-300 hover:to-blue-500 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                  Buy Now $1
                </button>
                 <button class="bg-gradient-to-r from-blue-500 to-green-300 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:from-green-300 hover:to-blue-500 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                  More Info
                </button>
            </div>

</template>
