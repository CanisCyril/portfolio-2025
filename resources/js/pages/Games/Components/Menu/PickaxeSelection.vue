<script setup lang="ts">

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { toast } from 'vue3-toastify';

const emit = defineEmits(['pickaxeSelected', 'goBack']);

const pickaxes = ref<any[]>([]);
const nextUnlock = ref<any>({});
const lockedPickaxes = ref<any[]>([]);

// import { ref } from 'vue'
const getPickaxes = async () => {
    try {
        const response = await axios.get('/api/pickaxes');

        pickaxes.value = response.data.unlockedPickaxes;
        nextUnlock.value = response.data.nextLockedPickaxe;
        lockedPickaxes.value = response.data.lockedPickaxes;

        // return response.data;
    } catch (error) {
        console.error('Error fetching pickaxes:', error);
        return [];
    }
};

const selectPickaxe = (pickaxeID: number) => {
    const response = confirm('Are you sure you want to select this pickaxe?');
    if (response) {
        axios.post('/api/pickaxes/equip', { pickaxe_id: pickaxeID })
            .then((response) => {

                emit('pickaxeSelected', response.data); //Returns pickaxe name

                toast.success(`${response.data} equipped`);
            })
            .catch(error => {

                toast.error(`Failed to equip, please try again.`);
            });
    }
};

const goBack = () => {
    emit('goBack');
};

onMounted(() => {
    getPickaxes()
})

</script>

<template>
    <div class="space-y-4 flex flex-col items-center">
        <h2 class="text-4xl font-semibold">Pickaxe Selection</h2>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded place-self-start"
            @click="goBack">Go Back</button>
        <div class="flex flex-wrap w-full gap-12 justify-center pt-4">
            <!-- Unlocked  -->
            <div v-for="pickaxe in pickaxes" :key="pickaxe.id"
                 :class="[
                            'group text-white flex flex-col items-center basis-1/5 h-[140px]',
                            pickaxe.equipped ? 'border-4 border-green-500' : ''
                        ]"
                @click="selectPickaxe(pickaxe.id)">
                <div class="flex items-center justify-center card 
                    opacity-70 hover:opacity-100 transition-opacity duration-200
                    transition-colors duration-200
                    bg-stone-500  hover:bg-blue-500 cursor-pointer 
                    w-full shadow-sm text-center py-4 min-h-[140px] hover:min-h-[145px] hover:min-w-[180px]
                    text-xl font-bold tracking-wide drop-shadow-lg">
                    <img :src="`/storage/games/mining/pickaxes/${'default-pickaxe.svg'}`" :alt="pickaxe.display_name"
                        class="h-12 m-2" />
                </div>
                <span class="p-2 opacity-15 transition-opacity duration-200 group-hover:opacity-100">
                    {{ pickaxe.display_name }} {{ pickaxe.equipped  }}
                </span>
            </div>
            <!-- Next Unlock -->

            <div
                class="group text-white flex flex-col items-center basis-1/5 h-[140px]">
                <div class="flex items-center justify-center card 
                    opacity-70 hover:opacity-100 transition-opacity duration-200
                    transition-colors duration-200
                    bg-stone-500  hover:bg-stone-600 cursor-pointer 
                    w-full shadow-sm text-center py-4 min-h-[140px] hover:min-h-[145px] hover:min-w-[180px]
                    text-xl font-bold tracking-wide drop-shadow-lg">
                    <img :src="`/storage/games/mining/pickaxes/${'default-pickaxe.svg'}`" :alt="nextUnlock.display_name"
                        class="h-12 m-2 opacity-20" />
                </div>
                <span class="p-2 opacity-15 transition-opacity duration-200 group-hover:opacity-100">
                    {{ 'Unlocked at level: ' + nextUnlock.level_requirement }}
                </span>
            </div>

            <!-- Locked Pickaxes -->
        
            <div v-for="pickaxe in lockedPickaxes" :key="pickaxe.id"
                class="group text-white opacity-90 flex flex-col items-center basis-1/5 h-[140px]">
                <div class="flex items-center justify-center card 
                    opacity-50 pointer-events-none 
                    bg-stone-500  hover:bg-blue-500 
                    w-full shadow-sm text-center py-4 min-h-[140px] hover:min-h-[145px] hover:min-w-[180px]
                    text-xl font-bold tracking-wide drop-shadow-lg">
                    <img :src="`/storage/games/mining/locked-item.svg`" alt="Locked Pickaxe"
                        class="m-2 size-[62px]" />
                </div>
                <span class="p-2 opacity-15 transition-opacity duration-200 group-hover:opacity-100">
                    {{ pickaxe.display_name }}
                </span>
            </div>
        </div>
    </div>
</template>