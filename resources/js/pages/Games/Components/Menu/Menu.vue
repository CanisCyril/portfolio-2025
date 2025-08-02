<script setup lang="ts">


import { ref } from 'vue'

import PickaxeSelection from './PickaxeSelection.vue';

const options = ref<string[]>(['Pickaxes', 'Ores', 'Inventory', 'Settings', 'Help']);
const selectedOption = ref<string | null>(null);
const emit = defineEmits(['pickaxeSelected', 'goBack']);

const emitPickaxe = (name: string) => {
  emit('pickaxeSelected', name)
  selectedOption.value = null;
}

const emitGoBack = () => {
  selectedOption.value = null;
}
</script>

<template>
  <PickaxeSelection v-if="selectedOption" @pickaxe-selected="emitPickaxe" @go-back="emitGoBack"/>

  <div v-if="!selectedOption" class="space-y-4 flex flex-col items-center">
    <h2 class="text-4xl font-semibold ">Menu</h2>

    <div class="flex flex-wrap w-full gap-4 ">
      <div
        v-for="option in options"
        :key="option"
        class=" text-white flex-[0_0_30%] max-w-[30%]"
      >
        <div @click="selectedOption = option"
            class="flex items-center justify-center card bg-stone-500 
            cursor-pointer hover:bg-blue-500 transition-colors duration-200
            w-full shadow-sm text-center py-4  min-h-[150px]
            text-xl font-bold tracking-wide drop-shadow-lg">
          {{ option }}
        </div>
      </div>
    </div>
    
  </div>
</template>