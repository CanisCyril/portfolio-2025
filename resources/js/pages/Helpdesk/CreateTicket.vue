<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue'

import { TicketIcon } from '@heroicons/vue/24/solid'

import { Field, Form, ErrorMessage, configure } from 'vee-validate';
import * as yup from 'yup';

import { PaperClipIcon, TrashIcon
} from '@heroicons/vue/24/outline'

// Default values
configure({
    // validateOnBlur: true, // controls if `blur` events should trigger validation with `handleChange` handler
    validateOnChange: true, // controls if `change` events should trigger validation with `handleChange` handler
    //   validateOnInput: false, // controls if `input` events should trigger validation with `handleChange` handler
    //   validateOnModelUpdate: true, // controls if `update:modelValue` events should trigger validation with `handleChange` handler
});

const schema = yup.object({
    title: yup.string().required(),
    description: yup.string().required(),
    category_id: yup.number().required().label('Category'),
    priority_id: yup.number().required().label('Priority'),
    file: yup.mixed(), //must add proper file validation later
});

const onFileChange = (e: Event, values, setFieldValue) => {
  const files = Array.from(e.target?.files)

  setFieldValue('attachments', [
    ...(values.attachments || []),
    ...files,
  ])
}

function onSubmit(values) {
    router.post(route('helpdesk.tickets.store'), values, {
        replace: true,
        ...(values.attachments.length ? { forceFormData: true } : {}),
        onSuccess: () => {
            // reset vee-validate via resetForm() if you’re using the <Form v-slot>
        },
    })
}




const priorities = ref([
    { id: 1, name: 'Low' },
    { id: 2, name: 'Medium' },
    { id: 3, name: 'High' },
    { id: 4, name: 'Business Critical' },
]);

const categories = ref([
    { id: 1, name: 'Internet' },
    { id: 2, name: 'Email' },
    { id: 3, name: 'Software' },
    { id: 4, name: 'Hardware' },
    { id: 4, name: 'Other' },
]);

</script>

<template>

    <Head>
        <title>Helpdesk - Create Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>
    <div class="min-h-screen bg-neutral-200 dark:bg-zinc-900">
        <main class="md:pt-8 container mx-auto max-w-4xl">
            <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm">
                <div class="card-body p-0">
                    <div class="flex flex-row items-center border-b-1 ">
                        <TicketIcon class="size-8 ml-4" />
                        <h2 class="card-title p-4 mb-1">Create Ticket</h2>
                    </div>
                    <div class="flex flex-col gap-8 p-4">
                        <Form :validation-schema="schema" @submit="onSubmit" v-slot="{ errors, values, setFieldValue }">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Title</legend>
                                <Field name="title" v-slot="{ field, meta }">
                                    <input type="text" v-bind="field"
                                        class="input focus:outline-none focus:border-2 focus:border-sky-900 dark:bg-zinc-800 border-1 w-full"
                                        :class="{
                                            'input-error': errors.title,
                                            'input-success': !errors.title && meta.dirty
                                        }" placeholder="Type here" :aria-invalid="!errors.title"
                                        :aria-describedby="errors.title ? 'title-error' : undefined" />

                                </Field>
                                <ErrorMessage name="title" class="first-letter:uppercase text-error p-2 text-sm"
                                    aria-live="polite" />
                            </fieldset>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Description</legend>
                                <Field name="description" v-slot="{ field, meta }">
                                    <textarea v-bind="field"
                                        class="textarea focus:outline-none focus:border-2 focus:border-sky-900 dark:bg-zinc-800 border-1 h-42 w-full"
                                        :class="{
                                            'input-error': errors.description,
                                            'input-success': !errors.description && meta.dirty
                                        }" placeholder="Describe the issue" :aria-invalid="!errors.description"
                                        :aria-describedby="errors.description ? 'description-error' : undefined">
                                    </textarea>
                                </Field>
                                <ErrorMessage name="description"
                                    class="first-letter:uppercase text-error p-2 text-sm" />
                            </fieldset>
                            <div class="flex md:flex-row flex-col gap-4 w-full">
                                <fieldset class="fieldset w-full">
                                    <legend class="fieldset-legend">Category</legend>
                                    <Field name="category_id" v-slot="{ field, meta }">
                                        <select v-bind="field"
                                            class="select focus:outline-none focus:border-2 focus:border-sky-900 dark:bg-zinc-800 border-1 w-full"
                                            :class="{
                                                'input-error': errors.category_id,
                                                'input-success': !errors.category_id && meta.dirty
                                            }">
                                            <option disabled selected>Category</option>
                                            <option v-for="category in categories" :key="category.id"
                                                :value="category.id" :aria-invalid="!errors.category_id"
                                                :aria-describedby="errors.category_id ? 'category-error' : undefined">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                    </Field>
                                    <ErrorMessage name="category_id"
                                        class="first-letter:uppercase text-error p-2 text-sm" />
                                </fieldset>
                                <fieldset class="fieldset w-full">
                                    <legend class="fieldset-legend">Priority</legend>
                                    <Field name="priority_id" v-slot="{ field, meta }">
                                        <select v-bind="field" class="select focus:outline-none focus:border-2 focus:border-sky-900
                                            dark:bg-zinc-800 border-1 w-full" :class="{
                                                'input-error': errors.priority_id,
                                                'input-success': !errors.priority_id && meta.dirty
                                            }">
                                            <option disabled selected>Priority</option>
                                            <option v-for="priority in priorities" :key="priority.id"
                                                :value="priority.id">
                                                {{ priority.name }}
                                            </option>
                                        </select>
                                    </Field>
                                    <ErrorMessage name="priority_id"
                                        class="first-letter:uppercase text-error p-2 text-sm" />
                                </fieldset>
                            </div>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Pick a file</legend>
                                <Field name="file" @change="(e) => onFileChange(e, values, setFieldValue)"
                                    type="file" multiple class="file-input file:h-full focus:outline-none focus:border-2 focus:border-sky-900
                                            dark:bg-zinc-800 border-1 w-full md:w-[50%]" />
                                <label class="label p-1">Optional</label>
                                <label class="label">Max size 2MB</label>
                                <ErrorMessage name="file" class="first-letter:uppercase text-error p-2 text-sm" />
                            </fieldset>
                            <div v-if="values.attachments" v-for="(file, index) in values.attachments"
                                class="flex flex-row justify-between items-center border w-full sm:w-75 gap-4 mt-4 p-4 rounded-lg bg-gray-200 dark:bg-zinc-800">
                                <div class="flex flex-row items-center gap-2">
                                    <PaperClipIcon class="size-5" />
                                    <div class="flex flex-col">
                                        <span>{{ file.name }}</span>
                                        <span class="text-xs text-zinc-500">{{ Math.round(file.size / 1024) }} KB</span>
                                    </div>
                                </div>
                                <span class="bg-zinc-300 dark:bg-zinc-900 hover:bg-zinc-400 hover:dark:bg-zinc-950 p-2 rounded-md cursor-pointer">
                                    <TrashIcon class="size-5" />
                                </span>
                            </div>
                            <div class="card-actions justify-end">
                                <button
                                    class="btn btn-neutral dark:border-zinc-300 dark:text-white dark:hover:bg-zinc-300 dark:hover:text-zinc-900 btn-outline mt-6">Submit
                                    Ticket
                                </button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>