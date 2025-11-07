<script setup lang="ts">

import { ref, watch, onMounted } from 'vue';

// REPLACE WITH SWEET ALERT
const modalRef = ref<HTMLDialogElement | null>(null);

const openModal = () => {
  modalRef.value?.showModal();
}
const props = defineProps<{
    isOpen: boolean,
    message: string,
}>();

defineExpose({ openModal })

// watch(
//   () => props.isOpen,
//   (open) => {
//     console.log('open', open)
//   },
//   { immediate: true }
// );


</script>

<template>
    <!-- <button class="btn" >open modal</button> -->
        <dialog ref="modalRef" id="my_modal_5" class="modal modal-bottom sm:modal-middle">
            <div class="flex flex-col gap-8 items-center modal-box w-full">
                <h1 class="text-4xl font-bold">Confirmation</h1>
                <p>{{ props.message }}</p>
                <div class="modal-action">
                    <form method="dialog">
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <button class="btn btn-lg btn-success">Confirm</button>
                            <button class="btn btn-lg btn-ghost">Close</button>
                        </div>
                    </form>
                </div>
            </div>
    </dialog>
</template>