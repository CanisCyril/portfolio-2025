<script setup lang="ts">
import { Head, router} from '@inertiajs/vue3';
import { ref } from 'vue';

import type { Inventory } from '@/types/inventory';
import axios from 'axios';

const selectedLevel = ref<number | null>(null);

const props = defineProps<{ 
    wallet: any, 
    ores: any, 
    inventory: Inventory[],
}>();

const inventory = ref([...props.inventory]);

const mine = async () => {
  if (selectedLevel.value === null) return;

  try {
    const { data } = await axios.post('/mine', {
      oreID: selectedLevel.value,
    });

    const updatedOre = data.updated_ore as Inventory;

    // Find and update the ore in inventory
    const ore = inventory.value.find(i => i.ore_id === updatedOre.ore_id);
    if (ore) {
      ore.amount = updatedOre.amount;
    }

    console.log('✅ Inventory updated:', inventory.value);
  } catch (error) {
    console.error('❌ Mining failed:', error);
  }
};

const selectLevel = (oreID: number) => {
  selectedLevel.value = oreID;
  console.log(`Selected level: ${oreID}`);
}

</script>

<template>
        <Head>
            <title>Mining Game</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        </Head>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-white text-center py-4">
            ⛏️  Virtual Miner
        </h1>
        <div 
                v-if="!selectedLevel"
        class=" 
            bg-green-500
            w-full
            h-dvh
            p-4
            overflow-y-auto

            flex flex-col space-y-4
            md:grid md:grid-cols-2 md:gap-4
            lg:grid-cols-3
           
            ">
            <div
                v-for="(ore, index) in ores"
                :key="index"
                class="bg-white text-red-800 min-h-[220px] p-4 rounded shadow"
                @click="selectLevel(ore.id)"
                >
                {{ ore.display_name }}
            </div>
        </div>


        <div class="controls-container
        bg-green-500
            w-full
            h-dvh
            p-4
            overflow-y-auto

            flex flex-col space-y-4
            md:grid md:grid-cols-2 md:gap-4
            lg:grid-cols-3
           
            " v-if="selectedLevel">
            <div class="flex items-center justify-center-safe ">
                Virtual Wallet: {{ wallet['balance'] }}
            </div>
            <div class="flex items-center justify-center-safe ">
                <button
                    @click="mine"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                        Mine
                </button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Sell
                </button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Menu
                </button>
            </div>
             <ul>
                <li v-for="(item, index) in inventory"
                    :key="index">{{ item.ore.display_name + ': ' +  item.amount}}</li>
            </ul>
           
        </div>
     

        <!-- Loading icon??? -->
        <!-- https://lottiefiles.com/animation/uranium-8695235 -->

</template>
