<script setup lang="ts">
import { Head, router} from '@inertiajs/vue3';
import { ref } from 'vue';

import axios from 'axios';
import { toast } from 'vue3-toastify';

import type { Inventory } from '@/types/inventory';
import { Experience } from '@/types/experience';

import Menu from './Components/Menu/Menu.vue';
import Third from './Third.vue';


// ThreeJS imports

const modalRef = ref<HTMLDialogElement | null>(null);
const selectedLevel = ref<number | null>(null);
const threeContainer = ref(null)

const props = defineProps<{ 
    wallet: any, 
    ores: any, 
    inventory: Inventory[],
    equippedPickaxeName: string,
    level: number,
    totalXp: number,
    userGold: number,
}>();

const inventory = ref([...props.inventory]);
const experience = ref<Experience | null>(null);
const equippedPickaxeName = ref(props.equippedPickaxeName);
const userGold = ref(props.userGold);
// const totalXp = ref(props.totalXp);
// const level = ref(props.level);


// Emits

const handleEquip = async (name: string) => {
  equippedPickaxeName.value = name;
  // await api call if needed

modalRef.value?.close(); // ✅ Close the modal programmatically
}

// Functions

const mine = async () => {
    if (selectedLevel.value === null) return;
    
    try {
        const { data } = await axios.post('/mine', {
            oreID: selectedLevel.value,
        });
        
        const updatedData = data.ore as Inventory;
        
        
        // Find and update the ore in inventory
        const ore = inventory.value.find(i => i.ore_id === updatedData.ore_id);
        if (ore) {
            ore.amount = updatedData.amount;
        }
        
        const updatedXp = data.experience as Experience;
        experience.value = updatedXp;
        console.log(updatedXp);
        
        console.log('✅ Inventory updated:', inventory.value);
        } catch (error) {
            console.error('❌ Mining failed:', error);
        }
};

const sellAll = async () => {
    try {
        const { data } = await axios.post('/api/gold/sell-all', {
            inventory: inventory.value,
        });
        
        // Update the inventory with the response data
        inventory.value = data.inventory;
        userGold.value = data.totalGold;
    
        toast.success(data.message);
    } catch (error) {
        console.error('❌ Failed to sell ores:', error);
    }
};

const selectLevel = (oreID: number) => {
    selectedLevel.value = oreID;
    console.log(`Selected level: ${oreID}`);
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/storage/games/mining/ores/default-ore.svg';
}

</script>

<template>
    <Head>
        <title>Mining Game</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>
    
    <div class="header-container flex flex-row w-full">
        <div class="w-[65%]">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white text-center py-4">
                    ⛏️  Virtual Miner
            </h1>
        </div>
        <div class="header-info flex flex-row w-[35%] gap-6">
            <span class="flex flex-row items-center">
                <img
                    src="/storage/games/mining/pickaxes/default-pickaxe.svg"
                    alt="coins"
                    class="w-8 h-8 m-2"
                    @error="handleImageError($event)"
                />
                <p>{{ equippedPickaxeName }}</p>
            </span>
            <span class="flex flex-row items-center">
                <img
                    src="/storage/games/mining/ores/coins.svg"
                    alt="coins"
                    class="w-8 h-8 m-2"
                    @error="handleImageError($event)"
                />
                <p>{{ userGold }}</p>
            </span>
            <span class="flex flex-row items-center gap-6">
                <p>Level: {{ level }}</p>
                <p>XP: {{ totalXp }}</p>
            </span>
        </div>
    </div>
    <div v-if="!selectedLevel"
        class=" 
              bg-neutral-900
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

    <div v-if="selectedLevel" class="mx-auto flex flex-row w-full h-[660px] gap-6">
        <div class="three-js-container">
            <Third />
        </div>

        <!-- <div class="controls flex flex-wrap flex-col bg-stone-900 gap-6 basis-[60%] h-[40%]">
            <button
                @click="mine"
                class=" w-[1/4] bg-blue-500 flex-1 text-white font-semibold gap-6 rounded">
                Mine
            </button>
            <button @click="sellAll" class=" w-[1/4] bg-blue-500 flex-1 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                Sell
            </button>
            <button 
            onclick="my_modal_3.showModal()"
            class=" w-[1/4] bg-blue-500 flex-1 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                Menu
            </button>
        </div> -->
        <div class="inventory bg-stone-900 basis-[40%] p-2 h-full">
            <div class="ore-container">
                <ul class="gap-6">
                    <li
                        v-for="(item, index) in inventory"
                        :key="index"
                        class="flex items-center rounded-lg overflow-hidden shadow-lg bg-stone-800 space-x-2 mb-2"
                    >
                        <!-- ICON REFERENCE: https://game-icons.net/1x1/faithtoken/ore.html -->
                        <img
                        :src="`/storage/games/mining/ores/${item.ore.name + '-ore.svg'}`"
                        :alt="item.ore.display_name"
                        class="w-8 h-8 m-2"
                        @error="handleImageError($event)"
                        />
                            {{ item.ore.display_name + ': '}} 
                        <transition name="fade-slide" mode="out-in">
                            <span :key="item.amount" class="transition-all duration-500 ease-out">
                                {{item.amount }}
                            </span>
                        </transition>
                    </li>
                </ul>
            </div>
        </div>
    </div>

<dialog id="my_modal_3" ref="modalRef" class="modal">
  <div class="modal-box h-[600px] min-w-[900px]">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <!-- <h3 class="text-lg font-bold">Hello!</h3> -->
    <!-- <p class="py-4">Press ESC key or click on ✕ button to close</p> -->
    <Menu @pickaxeSelected="handleEquip"></Menu>
  </div>
</dialog>

    <!-- Open the modal using ID.showModal() method -->
<!-- You can open the modal using ID.showModal() method -->



<!-- <div class="controls-container
bg-red-500
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
<ul v-if="experience">
    <li>gainedXp: {{ experience.gainedXp }}</li>
    <li>gainedXp: {{ experience.totalCurrentXp }}</li>
</ul>

</div> -->


<!-- Loading icon??? -->
<!-- https://lottiefiles.com/animation/uranium-8695235 -->

</template>


<style>
.fade-slide-enter-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-0.1rem);
}
.fade-slide-leave-active {
  transition: opacity 0.1s ease;
}
.fade-slide-leave-to {
  opacity: 0;
}

</style>
