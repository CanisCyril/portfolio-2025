<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

const form = useForm({ role: '' })

function login(role: 'user' | 'admin') {
    form.role = role
    form.post(route('helpdesk.demo.auth'), {
        preserveScroll: true,
        onError: () => (form.role = ''), // optional
    })
}


onMounted(() => {

})

</script>

<template>

    <Head>
        <title>Helpdesk Demo Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>
    <main class="bg-neutral-200 dark:bg-zinc-900">
        <div class="h-screen container mx-auto flex justify-center items-center">
            <div class="card w-[28rem] sm:w-[34rem] rem] mx-4">
                <div class="flex flex-col gap-4 card-body bg-zinc-100 dark:bg-zinc-950 items-center text-center rounded-lg shadow-sm">
                    <h2 class="card-title text-3xl text-shadow-2xs">Hello!</h2>
                    <div class="mt-2">
                        <p class="text-lg text-shadow-2xs">Welcome to the helpdesk demo.</p>
                        <p class="text-md mt-2 text-shadow-sm">Please select a role.</p>
                    </div>
                    <div class="mt-16 flex flex-col sm:flex-row gap-4">
                        <button class="btn btn-neutral dark:border-zinc-300 dark:text-white dark:hover:bg-zinc-300 dark:hover:text-zinc-900 btn-outline h-12 w-36"
                            :disabled="form.processing" @click="login('user')">User
                        </button>
                        <button class="btn btn-neutral dark:btn-primary btn-outline h-12 w-36" :disabled="form.processing"
                            @click="login('admin')">Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>